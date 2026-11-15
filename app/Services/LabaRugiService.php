<?php
// FILE: app/Services/LabaRugiService.php

namespace App\Services;


use App\Models\MaterialLedger;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * LabaRugiService
 *
 * Menghitung laba rugi dari dua sumber:
 *   Revenue  = grand_total order yang sudah selesai
 *   COGS     = material_ledger (tipe: pakai + hutang/penuhi_hutang)
 *
 * Hutang yang belum settled = COGS estimasi (belum final).
 * Report menunjukkan flag 'ada_hutang_pending' agar operator tahu
 * angka belum 100% akurat.
 */
class LabaRugiService
{
    // ─────────────────────────────────────────────────────────────
    // PER ORDER
    // ─────────────────────────────────────────────────────────────

    /**
     * Laba rugi untuk satu order.
     *
     * @return array{
     *   order_id: int,
     *   nomor_order: string,
     *   status_order: string,
     *   revenue: float,           grand_total order
     *   total_dibayar: float,
     *   cogs_settled: float,      COGS dari bahan yang stoknya ada
     *   cogs_hutang: float,       COGS estimasi dari hutang (belum settle)
     *   cogs_hutang_final: float, COGS hutang yang sudah di-settle (harga aktual)
     *   cogs_total: float,        cogs_settled + max(cogs_hutang, cogs_hutang_final)
     *   gross_profit: float,      revenue - cogs_total
     *   gross_margin_pct: float,
     *   ada_hutang_pending: bool,
     *   is_final: bool,           true = tidak ada hutang pending
     *   detail_bahan: array,      breakdown per type bahan
     * }
     */
    public function perOrder(Order $order): array
    {
        $ledgers = MaterialLedger::where('order_id', $order->id)
            ->with('rawMaterialType')
            ->get();

        $cogsSettled     = 0.0;  // pakai (stok ada)
        $cogsHutang      = 0.0;  // hutang estimasi (belum settle)
        $cogsHutangFinal = 0.0;  // hutang yang sudah disettle (harga aktual)
        $detailBahan     = [];

        foreach ($ledgers as $l) {
            $typeId   = $l->raw_material_type_id;
            $typeName = $l->rawMaterialType?->nama ?? "Type #{$typeId}";

            if (!isset($detailBahan[$typeId])) {
                $detailBahan[$typeId] = [
                    'raw_material_type_id' => $typeId,
                    'nama'                 => $typeName,
                    'satuan'               => $l->rawMaterialType?->satuan ?? '-',
                    'jumlah_pakai'         => 0.0,
                    'jumlah_hutang'        => 0.0,
                    'jumlah_hutang_settled'=> 0.0,
                    'cogs'                 => 0.0,
                    'ada_hutang_pending'   => false,
                ];
            }

            if ($l->tipe === 'pakai') {
                $cogsSettled += (float) $l->nilai_cogs;
                $detailBahan[$typeId]['jumlah_pakai'] += (float) $l->jumlah;
                $detailBahan[$typeId]['cogs']         += (float) $l->nilai_cogs;

            } elseif ($l->tipe === 'hutang') {
                if (!$l->is_settled) {
                    // Masih estimasi
                    $cogsHutang += (float) $l->nilai_cogs;
                    $detailBahan[$typeId]['jumlah_hutang']      += (float) $l->jumlah;
                    $detailBahan[$typeId]['ada_hutang_pending']  = true;
                    $detailBahan[$typeId]['cogs']               += (float) $l->nilai_cogs;
                }
                // Jika sudah settled, nilai_cogs di baris hutang sudah diupdate ke harga aktual
                // dan ada baris penuhi_hutang yang di-link ke order ini

            } elseif ($l->tipe === 'penuhi_hutang') {
                // Hutang yang sudah diselesaikan — ini COGS final
                $cogsHutangFinal += (float) $l->nilai_cogs;
                $detailBahan[$typeId]['jumlah_hutang_settled'] += (float) $l->jumlah;
                $detailBahan[$typeId]['cogs']                  += (float) $l->nilai_cogs;
            }
        }

        $revenue    = (float) $order->grand_total;
        $cogsTotal  = $cogsSettled + $cogsHutang + $cogsHutangFinal;
        $grossProfit = $revenue - $cogsTotal;
        $margin      = $revenue > 0 ? round($grossProfit / $revenue * 100, 2) : 0;

        $adaHutangPending = $cogsHutang > 0;

        return [
            'order_id'            => $order->id,
            'nomor_order'         => $order->nomor_order,
            'status_order'        => $order->status_order,
            'tanggal'             => $order->created_at?->toDateString(),
            'tanggal_selesai'     => $order->tanggal_selesai?->toDateString(),
            'revenue'             => $revenue,
            'total_dibayar'       => (float) $order->total_dibayar,
            'cogs_settled'        => round($cogsSettled, 2),
            'cogs_hutang'         => round($cogsHutang, 2),       // estimasi
            'cogs_hutang_final'   => round($cogsHutangFinal, 2),  // aktual settled
            'cogs_total'          => round($cogsTotal, 2),
            'gross_profit'        => round($grossProfit, 2),
            'gross_margin_pct'    => $margin,
            'ada_hutang_pending'  => $adaHutangPending,
            'is_final'            => !$adaHutangPending,
            'detail_bahan'        => array_values($detailBahan),
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // PER PERIODE
    // ─────────────────────────────────────────────────────────────

    /**
     * Laba rugi per periode (bulan atau minggu).
     *
     * @param Carbon $dari
     * @param Carbon $sampai
     * @param string $granularitas  'bulan' | 'minggu' | 'hari'
     * @param array  $statusOrder   Order status yang dihitung (default: selesai)
     *
     * @return array{
     *   periode: string,
     *   dari: string,
     *   sampai: string,
     *   total_order: int,
     *   order_final: int,         tanpa hutang pending
     *   order_estimasi: int,      masih ada hutang pending
     *   revenue: float,
     *   cogs_total: float,
     *   cogs_hutang_pending: float,
     *   gross_profit: float,
     *   gross_margin_pct: float,
     *   is_final: bool,
     *   breakdown: array,         per bucket waktu
     * }
     */
    public function perPeriode(
        Carbon $dari,
        Carbon $sampai,
        string $granularitas = 'bulan',
        array  $statusOrder  = ['selesai']
    ): array {
        $orders = Order::whereBetween('created_at', [$dari->startOfDay(), $sampai->endOfDay()])
            ->whereIn('status_order', $statusOrder)
            ->get();

        if ($orders->isEmpty()) {
            return $this->emptyPeriode($dari, $sampai);
        }

        // Ambil semua ledger untuk order-order ini sekaligus (efisien)
        $orderIds = $orders->pluck('id')->toArray();

        $ledgers = MaterialLedger::whereIn('order_id', $orderIds)->get();

        // Group ledger per order
        $ledgerByOrder = $ledgers->groupBy('order_id');

        // Hitung per order lalu aggregate
        $totalRevenue         = 0.0;
        $totalCogsSettled     = 0.0;
        $totalCogsHutang      = 0.0;
        $totalCogsHutangFinal = 0.0;
        $orderFinal           = 0;
        $orderEstimasi        = 0;

        // Bucket per granularitas
        $buckets = [];

        foreach ($orders as $order) {
            $orderLedgers = $ledgerByOrder->get($order->id, collect());
            $cogs         = $this->hitungCogsFromLedgers($orderLedgers);

            $totalRevenue         += (float) $order->grand_total;
            $totalCogsSettled     += $cogs['settled'];
            $totalCogsHutang      += $cogs['hutang'];
            $totalCogsHutangFinal += $cogs['hutang_final'];

            if ($cogs['hutang'] > 0) {
                $orderEstimasi++;
            } else {
                $orderFinal++;
            }

            // Tentukan bucket
            $bucketKey = $this->bucketKey($order->created_at, $granularitas);
            if (!isset($buckets[$bucketKey])) {
                $buckets[$bucketKey] = [
                    'periode'       => $bucketKey,
                    'label'         => $this->bucketLabel($order->created_at, $granularitas),
                    'total_order'   => 0,
                    'revenue'       => 0.0,
                    'cogs'          => 0.0,
                    'gross_profit'  => 0.0,
                    'ada_estimasi'  => false,
                ];
            }
            $b = &$buckets[$bucketKey];
            $b['total_order']++;
            $b['revenue']      += (float) $order->grand_total;
            $b['cogs']         += $cogs['settled'] + $cogs['hutang'] + $cogs['hutang_final'];
            $b['gross_profit']  = $b['revenue'] - $b['cogs'];
            if ($cogs['hutang'] > 0) $b['ada_estimasi'] = true;
        }

        ksort($buckets);

        $cogsTotal   = $totalCogsSettled + $totalCogsHutang + $totalCogsHutangFinal;
        $grossProfit = $totalRevenue - $cogsTotal;
        $margin      = $totalRevenue > 0 ? round($grossProfit / $totalRevenue * 100, 2) : 0;

        return [
            'periode'              => $granularitas,
            'dari'                 => $dari->toDateString(),
            'sampai'               => $sampai->toDateString(),
            'total_order'          => $orders->count(),
            'order_final'          => $orderFinal,
            'order_estimasi'       => $orderEstimasi,
            'revenue'              => round($totalRevenue, 2),
            'cogs_settled'         => round($totalCogsSettled, 2),
            'cogs_hutang_pending'  => round($totalCogsHutang, 2),
            'cogs_hutang_final'    => round($totalCogsHutangFinal, 2),
            'cogs_total'           => round($cogsTotal, 2),
            'gross_profit'         => round($grossProfit, 2),
            'gross_margin_pct'     => $margin,
            'is_final'             => $totalCogsHutang == 0,
            'catatan_estimasi'     => $totalCogsHutang > 0
                ? "⚠ Ada {$orderEstimasi} order dengan hutang bahan baku belum dipenuhi. COGS sebesar Rp " . number_format($totalCogsHutang, 0, ',', '.') . " masih estimasi."
                : null,
            'breakdown'            => array_values($buckets),
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // RINGKASAN HUTANG BAHAN PENDING (untuk halaman bahan baku)
    // ─────────────────────────────────────────────────────────────

    /**
     * Daftar semua hutang bahan yang belum dipenuhi,
     * dikelompokkan per jenis bahan.
     */
    public function ringkasanHutangPending(): Collection
    {
        return MaterialLedger::where('tipe', 'hutang')
            ->where('is_settled', false)
            ->with(['rawMaterialType', 'order'])
            ->select(
                'raw_material_type_id',
                DB::raw('SUM(jumlah - jumlah_settled) as total_hutang'),
                DB::raw('SUM(nilai_cogs)              as total_cogs_estimasi'),
                DB::raw('COUNT(*)                     as jumlah_transaksi')
            )
            ->groupBy('raw_material_type_id')
            ->get()
            ->map(fn($row) => [
                'raw_material_type_id' => $row->raw_material_type_id,
                'nama'                 => $row->rawMaterialType?->nama ?? '-',
                'satuan'               => $row->rawMaterialType?->satuan ?? '-',
                'total_hutang'         => (float) $row->total_hutang,
                'total_cogs_estimasi'  => (float) $row->total_cogs_estimasi,
                'jumlah_transaksi'     => (int) $row->jumlah_transaksi,
            ]);
    }

    /**
     * Detail hutang per baris per order (untuk drill-down di halaman bahan baku).
     */
    public function detailHutangPending(int $rawMaterialTypeId): Collection
    {
        return MaterialLedger::where('raw_material_type_id', $rawMaterialTypeId)
            ->where('tipe', 'hutang')
            ->where('is_settled', false)
            ->with(['order', 'rawMaterialType'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($l) => [
                'ledger_id'         => $l->id,
                'order_id'          => $l->order_id,
                'nomor_order'       => $l->order?->nomor_order,
                'status_order'      => $l->order?->status_order,
                'jumlah_hutang'     => (float) $l->jumlah,
                'jumlah_settled'    => (float) $l->jumlah_settled,
                'sisa_hutang'       => (float) $l->jumlah - (float) $l->jumlah_settled,
                'harga_estimasi'    => (float) $l->harga_per_satuan,
                'cogs_estimasi'     => (float) $l->nilai_cogs,
                'tanggal'           => $l->created_at?->toDateTimeString(),
                'keterangan'        => $l->keterangan,
            ]);
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────

    private function hitungCogsFromLedgers(Collection $ledgers): array
    {
        $settled     = 0.0;
        $hutang      = 0.0;
        $hutangFinal = 0.0;

        foreach ($ledgers as $l) {
            if ($l->tipe === 'pakai') {
                $settled += (float) $l->nilai_cogs;
            } elseif ($l->tipe === 'hutang' && !$l->is_settled) {
                $hutang += (float) $l->nilai_cogs;
            } elseif ($l->tipe === 'penuhi_hutang') {
                $hutangFinal += (float) $l->nilai_cogs;
            }
        }

        return ['settled' => $settled, 'hutang' => $hutang, 'hutang_final' => $hutangFinal];
    }

    private function bucketKey(Carbon|\DateTimeInterface|null $dt, string $granularitas): string
    {
        $c = $dt instanceof Carbon ? $dt : Carbon::instance($dt);
        return match ($granularitas) {
            'minggu' => $c->format('Y-W'),
            'hari'   => $c->format('Y-m-d'),
            default  => $c->format('Y-m'),  // bulan
        };
    }

    private function bucketLabel(Carbon|\DateTimeInterface|null $dt, string $granularitas): string
    {
        $c = $dt instanceof Carbon ? $dt : Carbon::instance($dt);
        return match ($granularitas) {
            'minggu' => 'Minggu ' . $c->weekOfYear . ' ' . $c->year,
            'hari'   => $c->translatedFormat('d M Y'),
            default  => $c->translatedFormat('F Y'),
        };
    }

    private function emptyPeriode(Carbon $dari, Carbon $sampai): array
    {
        return [
            'periode'             => '-',
            'dari'                => $dari->toDateString(),
            'sampai'              => $sampai->toDateString(),
            'total_order'         => 0,
            'order_final'         => 0,
            'order_estimasi'      => 0,
            'revenue'             => 0,
            'cogs_settled'        => 0,
            'cogs_hutang_pending' => 0,
            'cogs_hutang_final'   => 0,
            'cogs_total'          => 0,
            'gross_profit'        => 0,
            'gross_margin_pct'    => 0,
            'is_final'            => true,
            'catatan_estimasi'    => null,
            'breakdown'           => [],
        ];
    }
}