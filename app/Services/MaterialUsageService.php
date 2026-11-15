<?php
// FILE: app/Services/MaterialUsageService.php
// ============================================================
// PERBAIKAN DARI VERSI SEBELUMNYA:
//
// BUG 1 — Rumus berat SALAH:
//   Kode lama: $beratPcs = $variant->berat_per_pcs (langsung)
//   BENAR: pakai variant->hitungBeratOtomatis() yang sudah ada
//          yaitu P(cm) × L(cm) × T(mm)/10 × density(0.95)
//          Kalau berat_otomatis=false, pakai berat_per_pcs manual.
//
// BUG 2 — Warna TIDAK terbaca:
//   Kode lama: parse regex dari $item->catatan / custom_keterangan
//   BENAR: warna ada di $item->variant->product->colors (pivot is_default=false
//          berarti warna pilihan pelanggan). OrderItem tidak punya kolom warna,
//          jadi harus ambil dari relasi Product → ProductColor via nama_varian
//          yang disimpan saat checkout.
//   Solusi: cari nama warna di ProductColor yang namanya cocok dengan
//           kata-kata dalam nama_varian order item.
//
// BUG 3 — Service TIDAK PERNAH dipanggil:
//   Tidak ada panggilan proses() di OrderController saat status → antri.
//   Fix ada di OrderController (file terpisah).
//
// BUG 4 — Stok query salah:
//   Perlu join raw_material_orders untuk FIFO by tanggal_pesan.
// ============================================================

namespace App\Services;

use App\Models\MaterialLedger;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductColor;
use App\Models\ProductVariant;
use App\Models\RawMaterialOrderItem;
use App\Models\RawMaterialType;
use App\Models\RawMaterialUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialUsageService
{
    // Persentase pewarna dari total berat produk
    const PEWARNA_PCT = 3.0;

    // ─────────────────────────────────────────────────────────────
    // ENTRY POINT — dipanggil saat order → 'antri'
    // ─────────────────────────────────────────────────────────────

    public function proses(Order $order): array
    {
        // Load semua relasi yang dibutuhkan sekaligus (hindari N+1)
        $order->load([
            'items.product.defaultFormula.items.rawMaterialType',
            'items.product.colors',          // untuk cari warna
            'items.variant.formula.items.rawMaterialType',
        ]);

        $kebutuhan = $this->hitungKebutuhan($order);

        if (empty($kebutuhan)) {
            Log::warning("[MaterialUsage] Order #{$order->id}: tidak ada kebutuhan bahan. " .
                "Periksa apakah variant memiliki dimensi atau berat_per_pcs, dan produk memiliki formula.");
            return [
                'berhasil'   => true,
                'kebutuhan'  => [],
                'kekurangan' => [],
                'alokasi'    => [],
            ];
        }

        return DB::transaction(function () use ($order, $kebutuhan) {
            $alokasi    = [];
            $kekurangan = [];
            $aktorId    = $order->handled_by ?? $order->user_id;

            foreach ($kebutuhan as $entry) {
                $typeId     = $entry['raw_material_type_id'];
                $jumlahNeed = (float) $entry['jumlah'];
                if ($jumlahNeed <= 0.0001) continue;

                [$entries, $sisaKurang] = $this->alokasiUntukBahan(
                    order:      $order,
                    typeId:     $typeId,
                    typeName:   $entry['nama'],
                    satuan:     $entry['satuan'],
                    jumlahNeed: $jumlahNeed,
                    aktorId:    $aktorId,
                );

                $alokasi = array_merge($alokasi, $entries);

                if ($sisaKurang > 0.0001) {
                    $kekurangan[] = [
                        'raw_material_type_id' => $typeId,
                        'nama'                 => $entry['nama'],
                        'jumlah_kurang'        => round($sisaKurang, 4),
                        'satuan'               => $entry['satuan'],
                    ];
                }
            }

            return [
                'berhasil'   => empty($kekurangan),
                'kebutuhan'  => $kebutuhan,
                'kekurangan' => $kekurangan,
                'alokasi'    => $alokasi,
            ];
        });
    }

    // ─────────────────────────────────────────────────────────────
    // SETTLE HUTANG — dipanggil saat delivery bahan baku diterima
    // ─────────────────────────────────────────────────────────────

    public function settleHutang(RawMaterialOrderItem $item, float $jumlahBaru): void
    {
        if ($jumlahBaru <= 0) return;

        $typeId  = $item->raw_material_type_id;
        $harga   = (float) $item->harga_per_satuan;
        $aktorId = $item->order?->updated_by ?? $item->order?->created_by ?? 1;

        // Catat ledger MASUK
        MaterialLedger::create([
            'raw_material_type_id'        => $typeId,
            'raw_material_order_item_id'  => $item->id,
            'order_id'                    => null,
            'tipe'                        => 'masuk',
            'arah'                        => +1,
            'jumlah'                      => $jumlahBaru,
            'harga_per_satuan'            => $harga,
            'nilai_cogs'                  => 0,
            'is_settled'                  => true,
            'keterangan'                  => "Bahan masuk: PO {$item->order?->nomor_po}",
            'created_by'                  => $aktorId,
        ]);

        // Selesaikan hutang pending secara FIFO
        $hutangList = MaterialLedger::where('raw_material_type_id', $typeId)
            ->where('tipe', 'hutang')
            ->where('is_settled', false)
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->get();

        $sisaMasuk = $jumlahBaru;

        foreach ($hutangList as $hutang) {
            if ($sisaMasuk <= 0.0001) break;

            $sisaHutang = (float)$hutang->jumlah - (float)$hutang->jumlah_settled;
            if ($sisaHutang <= 0.0001) continue;

            $dipenuhi  = min($sisaHutang, $sisaMasuk);
            $nilaiCogs = round($dipenuhi * $harga, 2);

            MaterialLedger::create([
                'raw_material_type_id'        => $typeId,
                'raw_material_order_item_id'  => $item->id,
                'order_id'                    => $hutang->order_id,
                'tipe'                        => 'penuhi_hutang',
                'arah'                        => +1,
                'jumlah'                      => $dipenuhi,
                'harga_per_satuan'            => $harga,
                'nilai_cogs'                  => $nilaiCogs,
                'is_settled'                  => true,
                'ref_hutang_id'               => $hutang->id,
                'keterangan'                  => "Penuhi hutang order #{$hutang->order?->nomor_order}",
                'created_by'                  => $aktorId,
            ]);

            $hutang->jumlah_settled   = (float)$hutang->jumlah_settled + $dipenuhi;
            $hutang->harga_per_satuan = $harga;
            $hutang->nilai_cogs       = round((float)$hutang->jumlah * $harga, 2);
            if (abs((float)$hutang->jumlah - (float)$hutang->jumlah_settled) < 0.0001) {
                $hutang->is_settled = true;
            }
            $hutang->save();

            $sisaMasuk -= $dipenuhi;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE: Akumulasi kebutuhan semua item dalam 1 order
    // ─────────────────────────────────────────────────────────────

    private function hitungKebutuhan(Order $order): array
    {
        $acc = [];
        foreach ($order->items as $item) {
            foreach ($this->hitungKebutuhanItem($item) as $entry) {
                $tid = $entry['raw_material_type_id'];
                if (!isset($acc[$tid])) {
                    $acc[$tid] = [
                        'raw_material_type_id' => $tid,
                        'nama'   => $entry['nama'],
                        'satuan' => $entry['satuan'],
                        'jumlah' => 0.0,
                    ];
                }
                $acc[$tid]['jumlah'] += $entry['jumlah'];
            }
        }
        return array_values($acc);
    }

    private function hitungKebutuhanItem(OrderItem $item): array
    {
        $variant = $item->variant;
        $product = $item->product;

        // ── 1. Cari formula ──────────────────────────────────────
        $formula = null;

        // Prioritas: formula spesifik di variant
        if ($variant?->product_formula_id) {
            $formula = $variant->formula?->load('items.rawMaterialType');
        }

        // Fallback: formula default produk
        if (!$formula) {
            $formula = $product->formulas()
                ->where('is_default', true)
                ->with('items.rawMaterialType')
                ->first();
        }

        if (!$formula || $formula->items->isEmpty()) {
            Log::warning("[MaterialUsage] Item #{$item->id} ({$item->nama_produk}): tidak ada formula. Skip.");
            return [];
        }

        // ── 2. Hitung berat total (PERBAIKAN BUG 1) ──────────────
        $totalBeratGram = $this->hitungBeratTotal($item, $variant);

        if ($totalBeratGram <= 0) {
            Log::warning("[MaterialUsage] Item #{$item->id} ({$item->nama_produk}): berat = 0. " .
                "Variant panjang={$variant?->panjang}, lebar={$variant?->lebar}, tebal={$variant?->tebal}, " .
                "berat_per_pcs={$variant?->berat_per_pcs}. Set dimensi atau berat_per_pcs di master produk.");
            return [];
        }

        // ── 3. Cari nama warna (PERBAIKAN BUG 2) ─────────────────
        $namaWarna = $this->ambilNamaWarna($item);

        Log::debug("[MaterialUsage] Item #{$item->id}: berat={$totalBeratGram}g, warna=" . ($namaWarna ?? 'null'));

        return $this->hitungKomposisi($formula, $totalBeratGram, $namaWarna);
    }

    // ─────────────────────────────────────────────────────────────
    // Hitung berat total item dalam gram — FULLY AUTOMATIC
    //
    // Urutan prioritas (tidak perlu set flag berat_otomatis):
    //   1. Dimensi lengkap (P + L + T semua > 0) → P×L×T×density
    //   2. Dimensi tidak lengkap, berat_per_pcs ada → pakai manual
    //   3. Keduanya 0 → return 0, log warning
    //
    // Rumus final: berat_per_pcs × SATUAN_MULTIPLIER[satuan] × qty
    //   pack=50 pcs, lusin=12 pcs, sisanya=1
    // ─────────────────────────────────────────────────────────────
    private function hitungBeratTotal(OrderItem $item, ?ProductVariant $variant): float
    {
        if (!$variant) {
            Log::warning("[MaterialUsage] Item #{$item->id}: tidak ada variant, skip.");
            return 0.0;
        }

        $satuan     = $item->satuan ?? $item->product?->satuan ?? 'pcs';
        $multiplier = ProductVariant::SATUAN_MULTIPLIER[$satuan] ?? 1;
        $qty        = (float) $item->qty;

        // Pakai accessor berat_efektif dari ProductVariant
        // Sudah handle: dimensi dulu → fallback berat_per_pcs
        $beratPerPcs = $variant->berat_efektif;

        if ($beratPerPcs <= 0) {
            Log::warning("[MaterialUsage] Item #{$item->id} ({$item->nama_produk}): berat = 0. " .
                "Isi panjang+lebar+tebal, atau berat_per_pcs di master varian. " .
                "P={$variant->panjang}, L={$variant->lebar}, T={$variant->tebal}, " .
                "berat_per_pcs={$variant->berat_per_pcs}");
            return 0.0;
        }

        $total = round($beratPerPcs * $multiplier * $qty, 4);

        Log::debug("[MaterialUsage] Item #{$item->id}: {$beratPerPcs}g/pcs × {$multiplier}({$satuan}) × {$qty}qty = {$total}g");

        return $total;
    }

    // ─────────────────────────────────────────────────────────────
    // PERBAIKAN BUG 2: Ambil nama warna dari relasi yang benar
    //
    // Skenario warna:
    //   - Produk punya warna-warna via product_color_options
    //   - Pelanggan pilih warna saat order (disimpan di nama_varian OrderItem)
    //   - Contoh nama_varian: "Biru - 20x30cm" atau "Merah"
    //
    // Strategi lookup (urutan prioritas):
    //   1. Cek kata-kata di nama_varian OrderItem terhadap semua ProductColor aktif
    //   2. Cek catatan / custom_keterangan (fallback)
    //   3. Return null jika tidak ketemu (tidak pakai pewarna)
    // ─────────────────────────────────────────────────────────────
    private function ambilNamaWarna(OrderItem $item): ?string
    {
        // Cache semua nama warna aktif (hindari query berulang)
        static $namaWarnaCache = null;
        if ($namaWarnaCache === null) {
            $namaWarnaCache = ProductColor::where('is_active', true)
                ->pluck('nama')
                ->toArray();
        }

        if (empty($namaWarnaCache)) return null;

        // 1. Cari di nama_varian (misal "Biru - 20x30cm")
        if ($item->nama_varian) {
            foreach ($namaWarnaCache as $namaWarna) {
                if (stripos($item->nama_varian, $namaWarna) !== false) {
                    return $namaWarna;
                }
            }
        }

        // 2. Cari di catatan item
        if ($item->catatan) {
            foreach ($namaWarnaCache as $namaWarna) {
                if (stripos($item->catatan, $namaWarna) !== false) {
                    return $namaWarna;
                }
            }
            // Juga coba format "warna: xxx"
            if (preg_match('/warna\s*[:\-=]\s*([a-zA-Z\s]+)/iu', $item->catatan, $m)) {
                $namaFromCatatan = trim($m[1]);
                foreach ($namaWarnaCache as $namaWarna) {
                    if (stripos($namaWarna, $namaFromCatatan) !== false ||
                        stripos($namaFromCatatan, $namaWarna) !== false) {
                        return $namaWarna;
                    }
                }
            }
        }

        // 3. Cari di custom_keterangan
        if ($item->custom_keterangan) {
            foreach ($namaWarnaCache as $namaWarna) {
                if (stripos($item->custom_keterangan, $namaWarna) !== false) {
                    return $namaWarna;
                }
            }
        }

        return null;
    }

    // ─────────────────────────────────────────────────────────────
    // Hitung komposisi bahan dari formula + pewarna
    // ─────────────────────────────────────────────────────────────

    private function hitungKomposisi(
        \App\Models\ProductFormula $formula,
        float $totalBeratGram,
        ?string $namaWarna
    ): array {
        $hasil = [];

        if ($namaWarna) {
            // Ada pewarna → formula bahan utama hanya 97%, pewarna 3%
            $sisaPct  = 100.0 - self::PEWARNA_PCT;

            // Cari tipe bahan pewarna di database
            // Nama harus persis "Pewarna {Warna}" contoh "Pewarna Biru"
            $pewarnaNama = 'Pewarna ' . ucwords(strtolower($namaWarna));
            $pewarnaTipe = RawMaterialType::where('is_active', true)
                ->whereRaw('LOWER(nama) = ?', [strtolower($pewarnaNama)])
                ->first();

            if ($pewarnaTipe) {
                $jumlahPewarna = round($totalBeratGram * self::PEWARNA_PCT / 100, 4);
                $hasil[] = [
                    'raw_material_type_id' => $pewarnaTipe->id,
                    'nama'   => $pewarnaTipe->nama,
                    'satuan' => $pewarnaTipe->satuan,
                    'jumlah' => $jumlahPewarna,
                ];
            } else {
                Log::warning("[MaterialUsage] Tipe bahan '{$pewarnaNama}' tidak ditemukan. " .
                    "Buat bahan baku dengan nama persis '{$pewarnaNama}' di master bahan baku.");
            }

            // Bahan utama (97% dari total berat)
            foreach ($formula->items as $fi) {
                $type = $fi->rawMaterialType;
                if (!$type) continue;
                // Persentase relatif terhadap 97% (bukan 100%)
                $jumlah = round($totalBeratGram * ($fi->persentase / 100) * ($sisaPct / 100), 4);
                if ($jumlah <= 0) continue;
                $hasil[] = [
                    'raw_material_type_id' => $type->id,
                    'nama'   => $type->nama,
                    'satuan' => $type->satuan,
                    'jumlah' => $jumlah,
                ];
            }
        } else {
            // Tidak ada pewarna → 100% bahan utama dari formula
            foreach ($formula->items as $fi) {
                $type = $fi->rawMaterialType;
                if (!$type) continue;
                $jumlah = round($totalBeratGram * $fi->persentase / 100, 4);
                if ($jumlah <= 0) continue;
                $hasil[] = [
                    'raw_material_type_id' => $type->id,
                    'nama'   => $type->nama,
                    'satuan' => $type->satuan,
                    'jumlah' => $jumlah,
                ];
            }
        }

        return $hasil;
    }

    // ─────────────────────────────────────────────────────────────
    // Alokasi FIFO dari stok yang ada → tulis ke material_ledger
    // ─────────────────────────────────────────────────────────────

    private function alokasiUntukBahan(
        Order  $order,
        int    $typeId,
        string $typeName,
        string $satuan,
        float  $jumlahNeed,
        int    $aktorId,
    ): array {
        $alokasi = [];
        $sisa    = $jumlahNeed;

        // Ambil stok FIFO: dari PO terlama, item yang masih ada stok
        // Status 'tersedia', 'sebagian', atau 'diterima' = bisa dipakai
        $stocks = RawMaterialOrderItem::where('raw_material_type_id', $typeId)
            ->whereIn('status', ['tersedia', 'sebagian', 'diterima'])
            ->whereRaw('(jumlah_diterima - jumlah_terpakai) > 0.001')
            ->join(
                'raw_material_orders',
                'raw_material_orders.id',
                '=',
                'raw_material_order_items.raw_material_order_id'
            )
            ->whereNull('raw_material_orders.deleted_at')
            ->whereNotIn('raw_material_orders.status', ['dibatalkan'])
            ->orderBy('raw_material_orders.tanggal_pesan', 'asc')
            ->orderBy('raw_material_order_items.id', 'asc')
            ->select('raw_material_order_items.*')
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $stockItem) {
            if ($sisa <= 0.0001) break;

            $tersedia = (float)$stockItem->jumlah_diterima - (float)$stockItem->jumlah_terpakai;
            if ($tersedia <= 0) continue;

            $diambil   = min($tersedia, $sisa);
            $harga     = (float) $stockItem->harga_per_satuan;
            $nilaiCogs = round($diambil * $harga, 2);

            // Tulis ledger PAKAI
            $ledger = MaterialLedger::create([
                'raw_material_type_id'        => $typeId,
                'raw_material_order_item_id'  => $stockItem->id,
                'order_id'                    => $order->id,
                'tipe'                        => 'pakai',
                'arah'                        => -1,
                'jumlah'                      => $diambil,
                'harga_per_satuan'            => $harga,
                'nilai_cogs'                  => $nilaiCogs,
                'is_settled'                  => true,
                'keterangan'                  => "Produksi order #{$order->nomor_order} ({$typeName})",
                'created_by'                  => $aktorId,
            ]);

            // Catat di raw_material_usages (backward compat)
            RawMaterialUsage::create([
                'raw_material_id'            => null,
                'raw_material_order_item_id' => $stockItem->id,
                'order_id'                   => $order->id,
                'jumlah_pakai'               => $diambil,
                'keterangan'                 => "Auto-alokasi order #{$order->nomor_order} ({$typeName})",
                'created_by'                 => $aktorId,
            ]);

            // Kurangi stok item PO
            $stockItem->increment('jumlah_terpakai', $diambil);
            $stockItem->refresh();

            // Update status item (tersedia → sebagian → habis)
            $statusBaru = $stockItem->hitungStatus();
            if ($statusBaru !== $stockItem->status) {
                $stockItem->update(['status' => $statusBaru]);
                // Update juga status PO header
                $stockItem->order?->recalcStatus();
            }

            $alokasi[] = [
                'tipe'                       => 'pakai',
                'raw_material_order_item_id' => $stockItem->id,
                'nomor_po'                   => $stockItem->order?->nomor_po,
                'raw_material_type_id'       => $typeId,
                'nama'                       => $typeName,
                'jumlah'                     => $diambil,
                'harga_per_satuan'           => $harga,
                'nilai_cogs'                 => $nilaiCogs,
                'ledger_id'                  => $ledger->id,
            ];

            $sisa -= $diambil;
        }

        // Stok tidak cukup → catat HUTANG
        if ($sisa > 0.0001) {
            $hargaEst  = MaterialLedger::hargaTerakhir($typeId);
            $nilaiCogs = round($sisa * $hargaEst, 2);

            $ledger = MaterialLedger::create([
                'raw_material_type_id'        => $typeId,
                'raw_material_order_item_id'  => null,
                'order_id'                    => $order->id,
                'tipe'                        => 'hutang',
                'arah'                        => -1,
                'jumlah'                      => $sisa,
                'jumlah_settled'              => 0,
                'harga_per_satuan'            => $hargaEst,
                'nilai_cogs'                  => $nilaiCogs,
                'is_settled'                  => false,
                'keterangan'                  => "HUTANG: {$typeName} kurang " .
                    round($sisa, 3) . " {$satuan} (order #{$order->nomor_order})",
                'created_by'                  => $aktorId,
            ]);

            RawMaterialUsage::create([
                'raw_material_id'            => null,
                'raw_material_order_item_id' => null,
                'order_id'                   => $order->id,
                'jumlah_pakai'               => $sisa,
                'keterangan'                 => "HUTANG: {$typeName} {$sisa} {$satuan}",
                'created_by'                 => $aktorId,
            ]);

            $alokasi[] = [
                'tipe'                       => 'hutang',
                'raw_material_order_item_id' => null,
                'nomor_po'                   => null,
                'raw_material_type_id'       => $typeId,
                'nama'                       => $typeName,
                'jumlah'                     => $sisa,
                'harga_per_satuan'           => $hargaEst,
                'nilai_cogs'                 => $nilaiCogs,
                'ledger_id'                  => $ledger->id,
                'is_hutang'                  => true,
            ];
        }

        return [$alokasi, max(0.0, $sisa)];
    }
}