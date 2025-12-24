<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryPenarikan;
use App\Models\Dompet;
use App\Models\Transaksi; // TAMBAHAN: Model untuk transaksi pembelian siswa
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    /**
     * Handle Callback dari Xendit (Disbursement / Penarikan Saldo)
     */
    public function handleDisbursement(Request $request)
    {
        // 1. Verifikasi Token Callback
        $callbackToken = $request->header('x-callback-token');
        if ($callbackToken !== env('XENDIT_CALLBACK_TOKEN')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 2. Ambil Data
        $data = $request->all();
        $externalId = $data['external_id'];
        $status = $data['status']; // 'COMPLETED' atau 'FAILED'

        // 3. Cari Data Penarikan
        $transaksi = HistoryPenarikan::where('external_id', $externalId)->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cek apakah status sudah final
        if ($transaksi->status == 'berhasil' || $transaksi->status == 'gagal') {
            return response()->json(['message' => 'Already updated']);
        }

        DB::beginTransaction();
        try {
            if ($status == 'COMPLETED') {
                // SUKSES: Update status jadi berhasil
                $transaksi->update(['status' => 'berhasil']);

            } elseif ($status == 'FAILED') {
                // GAGAL: Update status jadi gagal & KEMBALIKAN SALDO (Refund)
                $transaksi->update([
                    'status' => 'gagal',
                    'failure_code' => $data['failure_code'] ?? 'UNKNOWN_ERROR'
                ]);

                // Refund saldo ke dompet penjual
                $dompet = Dompet::find($transaksi->id_dompet);
                if ($dompet) {
                    $dompet->saldo = $dompet->saldo + $transaksi->jumlah;
                    $dompet->save();
                }
            }

            DB::commit();
            return response()->json(['message' => 'Success']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Disbursement Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing'], 500);
        }
    }

    /**
     * Handle Callback dari Xendit (Invoice / Pembayaran Masuk)
     * Dipanggil saat siswa berhasil membayar via VA/QRIS/E-Wallet
     */
    public function handleInvoice(Request $request)
    {
        // 1. Verifikasi Token
        $callbackToken = $request->header('x-callback-token');
        if ($callbackToken !== env('XENDIT_CALLBACK_TOKEN')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 2. Ambil Data
        $data = $request->all();
        $externalId = $data['external_id'];
        $status = $data['status']; // 'PAID' atau 'EXPIRED'

        // 3. Cari Transaksi Pembelian Siswa
        $transaksi = Transaksi::where('external_id', $externalId)->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cek jika sudah dibayar sebelumnya
        if ($transaksi->status_pembayaran == 'success') {
            return response()->json(['message' => 'Already paid']);
        }

        DB::beginTransaction();
        try {
            if ($status == 'PAID') {
                // SUKSES BAYAR: Ubah status jadi success
                $transaksi->update([
                    'status_pembayaran' => 'success',
                    'waktu_pembayaran' => now(), // Opsional: jika ada kolom ini
                ]);

                // (Opsional) Disini bisa tambah logika:
                // - Kurangi stok barang real
                // - Kirim notifikasi ke penjual

            } else if ($status == 'EXPIRED') {
                // KADALUARSA: Ubah status jadi failed/expired
                $transaksi->update(['status_pembayaran' => 'failed']);
            }

            DB::commit();
            return response()->json(['message' => 'Success']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Invoice Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing'], 500);
        }
    }
}
