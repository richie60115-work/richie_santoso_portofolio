<?php

namespace App\Services;

use App\Models\RawMaterialItemDelivery as RawMaterial;
use App\Models\RawMaterialType;
use App\Models\RawMaterialUsage;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * StockService
 * ─────────────
 * Menangani:
 *  1. Kalkulasi stok total per jenis (FIFO-aware)
 *  2. Estimasi habis dengan kalender kerja pabrik:
 *       Senin–Jumat  = 1 hari penuh
 *       Sabtu        = 0.5 hari
 *       Minggu       = 0 (libur)
 *  3. FIFO deduction — potongan stok dimulai dari batch
 *     yang paling lama (tanggal_pesan terlama)
 *  4. Skenario "jika ada pesanan X sekarang"
 */
class StockService
{
    // ── Kalender Kerja ───────────────────────────────────────────

    /**
     * Bobot hari kerja per hari dalam seminggu.
     * 0=Minggu, 1=Senin ... 6=Sabtu
     */
    const WORK_WEIGHTS = [
        0 => 0,    // Minggu - libur
        1 => 1,    // Senin
        2 => 1,    // Selasa
        3 => 1,    // Rabu
        4 => 1,    // Kamis
        5 => 1,    // Jumat
        6 => 0.5,  // Sabtu - setengah hari
    ];

    /**
     * Hitung "hari kerja efektif" antara 2 tanggal.
     * Hasilnya bisa berupa float (misal 3.5 hari = 3 hari + sabtu).
     */
    public static function effectiveWorkDays(Carbon $from, Carbon $to): float
    {
        if ($to->lte($from)) return 0;

        $days  = 0.0;
        $cursor = $from->copy()->startOfDay();
        $end    = $to->copy()->startOfDay();

        while ($cursor->lt($end)) {
            $days += self::WORK_WEIGHTS[$cursor->dayOfWeek] ?? 1;
            $cursor->addDay();
        }

        return $days;
    }

    /**
     * Dari sekarang, hitung kapan N hari kerja efektif akan tercapai.
     * Digunakan untuk proyeksikan tanggal estimasi habis.
     */
    public static function addWorkDays(Carbon $from, float $effectiveDays): Carbon
    {
        $cursor   = $from->copy()->startOfDay()->addDay(); // mulai besok
        $remaining = $effectiveDays;

        while ($remaining > 0) {
            $weight    = self::WORK_WEIGHTS[$cursor->dayOfWeek] ?? 1;
            $remaining -= $weight;
            if ($remaining > 0) $cursor->addDay();
        }

        return $cursor;
    }

    // ── Stok per Jenis (FIFO view) ───────────────────────────────

    /**
     * Kembalikan ringkasan stok untuk 1 jenis bahan baku.
     * Termasuk breakdown per batch urut FIFO.
     *
     * @return array{
     *   total_stok: float,
     *   total_ditunggu: float,
     *   batches: Collection,
     *   satuan: string,
     * }
     */
    public static function stockSummary(RawMaterialType $type): array
    {
        $batches = RawMaterial::with(['deliveries', 'usages'])
            ->where('raw_material_type_id', $type->id)
            ->whereIn('status', ['tersedia','sebagian','diterima','dipesan'])
            ->orderBy('tanggal_pesan', 'asc') // FIFO order
            ->get();

        $totalStok    = 0.0;
        $totalDitunggu = 0.0;

        foreach ($batches as $b) {
            $stok = (float)$b->jumlah_diterima - (float)$b->jumlah_terpakai;
            $totalStok += $stok;

            if (in_array($b->status, ['dipesan','sebagian'])) {
                $totalDitunggu += (float)$b->jumlah_dipesan - (float)$b->jumlah_diterima;
            }
        }

        return [
            'total_stok'     => round($totalStok, 2),
            'total_ditunggu' => round($totalDitunggu, 2),
            'batches'        => $batches,
            'satuan'         => $type->satuan,
        ];
    }

    // ── Estimasi Habis (Kalender Kerja) ──────────────────────────

    /**
     * Hitung estimasi kapan stok jenis bahan ini akan habis,
     * berdasarkan rata-rata pemakaian HARIAN EFEKTIF (dengan bobot hari kerja).
     *
     * Formula:
     *   rata_per_hari_efektif = total_terpakai / total_hari_kerja_efektif_sejak_pertama_tiba
     *   hari_efektif_tersisa  = stok_tersedia  / rata_per_hari_efektif
     *   tanggal_habis         = sekarang + hari_efektif_tersisa (dalam kalender kerja)
     *
     * @return array{
     *   rata_per_hari: float|null,
     *   hari_kerja_tersisa: float|null,
     *   tanggal_estimasi: string|null,
     *   catatan: string,
     * }
     */
    public static function estimasiHabis(RawMaterialType $type): array
    {
        // Ambil semua batch yang pernah aktif, FIFO
        $batches = RawMaterial::where('raw_material_type_id', $type->id)
            ->whereNotNull('tanggal_pertama_tiba')
            ->where('jumlah_terpakai', '>', 0)
            ->orderBy('tanggal_pesan')
            ->get();

        if ($batches->isEmpty()) {
            return [
                'rata_per_hari'     => null,
                'hari_kerja_tersisa'=> null,
                'tanggal_estimasi'  => null,
                'catatan'           => 'Belum ada data pemakaian untuk menghitung estimasi.',
            ];
        }

        // Tanggal pertama bahan pernah tiba
        $tanggalMulai = $batches->min('tanggal_pertama_tiba');
        $hariEfektif  = self::effectiveWorkDays(
            Carbon::parse($tanggalMulai),
            Carbon::now()
        );

        if ($hariEfektif < 1) {
            return [
                'rata_per_hari'     => null,
                'hari_kerja_tersisa'=> null,
                'tanggal_estimasi'  => null,
                'catatan'           => 'Data terlalu baru (< 1 hari kerja).',
            ];
        }

        // Total pemakaian semua batch jenis ini
        $totalTerpakai = RawMaterial::where('raw_material_type_id', $type->id)
            ->sum('jumlah_terpakai');

        $rata = $totalTerpakai / $hariEfektif;

        if ($rata <= 0) {
            return [
                'rata_per_hari'     => 0,
                'hari_kerja_tersisa'=> null,
                'tanggal_estimasi'  => null,
                'catatan'           => 'Pemakaian = 0, tidak dapat menghitung estimasi.',
            ];
        }

        // Stok tersedia saat ini (bisa negatif = backlog)
        $stokNow = RawMaterial::where('raw_material_type_id', $type->id)
            ->whereIn('status', ['tersedia','sebagian','diterima'])
            ->sum(DB::raw('jumlah_diterima - jumlah_terpakai'));

        $hariTersisa = $stokNow / $rata;  // bisa negatif kalau stok < 0

        $catatan = '';
        $tglEst  = null;

        if ($stokNow < 0) {
            $catatan = '⚠️ Stok defisit! Pabrik sedang pakai stok buffer/pre-order.';
            $tglEst  = null;
        } elseif ($hariTersisa <= 0) {
            $catatan = '⚠️ Stok habis secara teoritis.';
            $tglEst  = null;
        } else {
            $tglObj  = self::addWorkDays(Carbon::now(), $hariTersisa);
            $tglEst  = $tglObj->format('d M Y');
            // Tambahkan keterangan Sabtu/Minggu
            $catatan = sprintf(
                'Estimasi berdasarkan %.2f %s/hari kerja (Sabtu=½hari, Minggu=libur).',
                $rata,
                $type->satuan
            );
        }

        return [
            'rata_per_hari'      => round($rata, 2),
            'hari_kerja_tersisa' => round($hariTersisa, 1),
            'tanggal_estimasi'   => $tglEst,
            'stok_now'           => round($stokNow, 2),
            'catatan'            => $catatan,
        ];
    }

    // ── Skenario Pesanan ─────────────────────────────────────────

    /**
     * Simulasi "jika ada pesanan produksi sebesar X sekarang":
     *   - Kurangi dari stok secara FIFO (tidak benar-benar simpan)
     *   - Hitung sisa stok setelah pesanan
     *   - Estimasi ulang habis setelah pesanan
     *
     * @return array{
     *   fifo_plan: array,   // batch mana kena berapa
     *   sisa_stok: float,
     *   estimasi_setelah: string|null,
     *   hari_setelah: float|null,
     *   cukup: bool,
     * }
     */
    public static function skenarioPesanan(RawMaterialType $type, float $jumlahPesanan): array
    {
        // FIFO plan: list batch dan berapa yg dipakai dari masing-masing
        $batches = RawMaterial::where('raw_material_type_id', $type->id)
            ->whereIn('status', ['tersedia','sebagian','diterima'])
            ->orderBy('tanggal_pesan', 'asc') // FIFO
            ->get();

        $remaining  = $jumlahPesanan;
        $fifoPlan   = [];
        $totalStok  = 0;

        foreach ($batches as $b) {
            $stok = (float)$b->jumlah_diterima - (float)$b->jumlah_terpakai;
            $totalStok += $stok;

            if ($remaining <= 0) break;

            $ambil = min($stok, $remaining);
            if ($stok > 0) {
                $fifoPlan[] = [
                    'id'             => $b->id,
                    'provider'       => $b->provider,
                    'tanggal_pesan'  => $b->tanggal_pesan->format('d M Y'),
                    'stok_sebelum'   => round($stok, 2),
                    'dipakai'        => round($ambil, 2),
                    'sisa'           => round($stok - $ambil, 2),
                    'satuan'         => $type->satuan,
                ];
                $remaining -= $ambil;
            }
        }

        $sisaStok = $totalStok - $jumlahPesanan; // bisa negatif
        $cukup    = $sisaStok >= 0;

        // Estimasi habis setelah pesanan (pakai rata yang sama)
        $estBase = self::estimasiHabis($type);
        $rata    = $estBase['rata_per_hari'] ?? 0;

        $hariSetelah = null;
        $tglSetelah  = null;

        if ($rata > 0 && $sisaStok > 0) {
            $hariSetelah = round($sisaStok / $rata, 1);
            $tglSetelah  = self::addWorkDays(Carbon::now(), $hariSetelah)->format('d M Y');
        }

        return [
            'fifo_plan'       => $fifoPlan,
            'sisa_stok'       => round($sisaStok, 2),
            'estimasi_setelah'=> $tglSetelah,
            'hari_setelah'    => $hariSetelah,
            'cukup'           => $cukup,
            'kekurangan'      => $cukup ? 0 : round(abs($sisaStok), 2),
            'satuan'          => $type->satuan,
        ];
    }

    // ── FIFO Deduction (Real — simpan ke DB) ─────────────────────

    /**
     * Kurangi stok secara FIFO untuk pesanan produksi.
     * Dipanggil saat pesanan divalidasi/diproses.
     *
     * Proses:
     *   1. Ambil batch tersedia urut tanggal_pesan ASC
     *   2. Kurangi dari batch terlama dulu
     *   3. Kalau batch itu habis, lanjut ke batch berikutnya
     *   4. Kalau semua stok habis tapi masih ada sisa, tetap catat
     *      (stok jadi negatif = backlog/pabrik hutang stok)
     *   5. Buat record di raw_material_usages untuk audit trail
     *
     * @return array{ success: bool, fifo_log: array, defisit: float }
     */
    public static function deductFifo(
        RawMaterialType $type,
        float $jumlahPakai,
        ?int $orderId = null,
        ?int $userId  = null,
        string $keterangan = ''
    ): array {
        $batches = RawMaterial::where('raw_material_type_id', $type->id)
            ->whereIn('status', ['tersedia','sebagian','diterima'])
            ->orderBy('tanggal_pesan', 'asc')
            ->lockForUpdate()
            ->get();

        $remaining = $jumlahPakai;
        $log       = [];

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $stokBatch = (float)$batch->jumlah_diterima - (float)$batch->jumlah_terpakai;
            $pakai     = min(max($stokBatch, 0), $remaining);

            // Kalau stok batch ini 0 atau negatif, skip
            if ($stokBatch <= 0) continue;

            $batch->jumlah_terpakai += $pakai;

            // Update status batch
            if ($batch->jumlah_terpakai >= $batch->jumlah_diterima) {
                $batch->status = 'habis';
            }

            $batch->updated_by = $userId;
            $batch->save();

            // Catat usage
            RawMaterialUsage::create([
                'raw_material_id' => $batch->id,
                'order_id'        => $orderId,
                'jumlah_pakai'    => $pakai,
                'keterangan'      => $keterangan ?: "FIFO deduct untuk order #{$orderId}",
                'created_by'      => $userId ?? 1,
            ]);

            $log[] = [
                'batch_id'      => $batch->id,
                'provider'      => $batch->provider,
                'tanggal_pesan' => $batch->tanggal_pesan->format('d M Y'),
                'jumlah_pakai'  => $pakai,
                'sisa_batch'    => (float)$batch->jumlah_diterima - (float)$batch->jumlah_terpakai,
            ];

            $remaining -= $pakai;
        }

        // Kalau masih ada sisa (semua batch habis tapi pesanan belum terpenuhi)
        // → catat sebagai defisit di batch terakhir (backlog)
        if ($remaining > 0) {
            $lastBatch = $batches->last();
            if ($lastBatch) {
                $lastBatch->jumlah_terpakai += $remaining;
                $lastBatch->updated_by = $userId;
                $lastBatch->save();

                RawMaterialUsage::create([
                    'raw_material_id' => $lastBatch->id,
                    'order_id'        => $orderId,
                    'jumlah_pakai'    => $remaining,
                    'keterangan'      => "DEFISIT - stok tidak cukup, backlog {$remaining} {$type->satuan}",
                    'created_by'      => $userId ?? 1,
                ]);

                $log[] = [
                    'batch_id'      => $lastBatch->id,
                    'provider'      => $lastBatch->provider,
                    'tanggal_pesan' => $lastBatch->tanggal_pesan->format('d M Y'),
                    'jumlah_pakai'  => $remaining,
                    'sisa_batch'    => (float)$lastBatch->jumlah_diterima - (float)$lastBatch->jumlah_terpakai,
                    'is_defisit'    => true,
                ];
            }
        }

        return [
            'success'  => true,
            'fifo_log' => $log,
            'defisit'  => max(0, $remaining),
        ];
    }
}
