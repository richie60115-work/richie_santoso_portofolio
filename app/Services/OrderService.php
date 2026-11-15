<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderNote;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\DB;

class OrderService
{
    // ─── Buat catatan otomatis saat status berubah ─────────────────
    public static function catatPerubahanStatus(
        Order  $order,
        int    $userId,
        string $statusOrderBaru,
        string $statusBayarBaru,
        string $pesanTambahan = ''
    ): void {
        $statusOrderLama = $order->status_order;
        $statusBayarLama = $order->status_bayar;

        $parts = [];

        // Perubahan status order
        if ($statusOrderBaru !== $statusOrderLama) {
            $labelLama = Order::STATUS_ORDER[$statusOrderLama]['label'] ?? $statusOrderLama;
            $labelBaru  = Order::STATUS_ORDER[$statusOrderBaru]['label'] ?? $statusOrderBaru;
            $parts[] = "Status pesanan berubah dari **{$labelLama}** menjadi **{$labelBaru}**.";
        }

        // Perubahan status bayar
        if ($statusBayarBaru !== $statusBayarLama) {
            $labelLama = Order::STATUS_BAYAR[$statusBayarLama]['label'] ?? $statusBayarLama;
            $labelBaru  = Order::STATUS_BAYAR[$statusBayarBaru]['label'] ?? $statusBayarBaru;
            $parts[] = "Status pembayaran berubah dari **{$labelLama}** menjadi **{$labelBaru}**.";
        }

        if ($pesanTambahan) {
            $parts[] = $pesanTambahan;
        }

        if (empty($parts)) return;

        $order->addNote(
            userId:            $userId,
            isi:               implode(' ', $parts),
            tipe:              'auto',
            statusOrderBefore: $statusOrderLama,
            statusOrderAfter:  $statusOrderBaru !== $statusOrderLama ? $statusOrderBaru : null,
            statusBayarBefore: $statusBayarLama !== $statusBayarBaru ? $statusBayarLama : null,
            statusBayarAfter:  $statusBayarBaru !== $statusBayarLama ? $statusBayarBaru : null,
        );
    }

    // ─── Update status order + otomatis catat ─────────────────────
    public static function updateStatus(
        Order  $order,
        int    $userId,
        string $statusOrderBaru,
        string $statusBayarBaru,
        string $catatan = '',
        ?string $estimasiSelesai = null
    ): void {
        DB::transaction(function () use ($order, $userId, $statusOrderBaru, $statusBayarBaru, $catatan, $estimasiSelesai) {
            // Catat perubahan otomatis
            self::catatPerubahanStatus($order, $userId, $statusOrderBaru, $statusBayarBaru, '');

            // Update order
            $updateData = [
                'status_order' => $statusOrderBaru,
                'status_bayar' => $statusBayarBaru,
                'handled_by'   => $userId,
            ];

            if ($estimasiSelesai) {
                $updateData['estimasi_selesai'] = $estimasiSelesai;
            }

            if ($statusOrderBaru === 'selesai') {
                $updateData['tanggal_selesai'] = now()->toDateString();
            }

            $order->update($updateData);

            // Catatan manual tambahan (dari operator)
            if ($catatan) {
                $order->addNote(
                    userId: $userId,
                    isi:    $catatan,
                    tipe:   'manual',
                );
            }
        });
    }

    // ─── Validasi pembayaran ───────────────────────────────────────
    public static function validasiPembayaran(
        Order        $order,
        OrderPayment $payment,
        int          $userId,
        string       $statusBaru,      // 'valid' atau 'ditolak'
        float        $jumlahValid,
        string       $alasan = ''
    ): void {
        DB::transaction(function () use ($order, $payment, $userId, $statusBaru, $jumlahValid, $alasan) {
            $payment->update([
                'status'          => $statusBaru,
                'jumlah_valid'    => $jumlahValid,
                'divalidasi_oleh' => $userId,
                'divalidasi_at'   => now(),
                'alasan_tolak'    => $statusBaru === 'ditolak' ? $alasan : null,
            ]);

            if ($statusBaru === 'valid') {
                // Recalc total dibayar
                $totalValid = $order->payments()->where('status', 'valid')->sum('jumlah_valid');
                $order->update(['total_dibayar' => $totalValid]);

                // Tentukan status bayar baru
                $grand = (float) $order->grand_total;
                $pct   = $grand > 0 ? ($totalValid / $grand) * 100 : 0;

                $statusBayarBaru = match(true) {
                    $pct >= 100     => 'lunas',
                    $pct >= 75      => 'hampir_lunas',
                    $pct >= 50      => 'separuh',
                    $pct > 0        => 'uang_muka',
                    default         => 'belum_bayar',
                };

                $order->update(['status_bayar' => $statusBayarBaru]);

                $note = sprintf(
                    'Pembayaran Rp %s divalidasi. Total terbayar: Rp %s (%.0f%% dari grand total).',
                    number_format($jumlahValid, 0, ',', '.'),
                    number_format($totalValid, 0, ',', '.'),
                    $pct
                );

                $order->addNote(
                    userId:           $userId,
                    isi:              $note,
                    tipe:             'bayar',
                    statusBayarBefore: $order->getOriginal('status_bayar') ?? 'ada_upload',
                    statusBayarAfter:  $statusBayarBaru,
                );
            } else {
                // Tolak — kembalikan status bayar ke sebelumnya
                $pernah = $order->payments()->where('status', 'valid')->exists();
                $order->update(['status_bayar' => $pernah ? 'uang_muka' : 'belum_bayar']);

                $order->addNote(
                    userId: $userId,
                    isi:    "Upload pembayaran ditolak. Alasan: {$alasan}",
                    tipe:   'bayar',
                );
            }
        });
    }
}
