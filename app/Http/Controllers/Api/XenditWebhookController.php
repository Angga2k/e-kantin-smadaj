<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryPenarikan;
use App\Models\Dompet;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    /**
     * Handle Callback dari Xendit (Disbursement / Penarikan Saldo)
     */
    public function handleDisbursement(Request $request)
    {
        // 1. Verifikasi Token Callback (Security Check)
        // Pastikan XENDIT_CALLBACK_TOKEN sudah ada di .env
        $callbackToken = $request->header('x-callback-token');
        if ($callbackToken !== env('XENDIT_CALLBACK_TOKEN')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 2. Ambil Data
        $data = $request->all();
        $externalId = $data['external_id'];
        $status = $data['status']; // 'COMPLETED' atau 'FAILED'

        Log::info('Webhook Disbursement Masuk:', $data); // Log untuk debugging

        // 3. Cari Data Penarikan
        $transaksi = HistoryPenarikan::where('external_id', $externalId)->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cek apakah status sudah final, jika ya stop.
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
                    // Otomatis didekripsi, ditambah, lalu dienkripsi lagi saat save
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
     * Handle Callback dari Xendit (Invoice / Pembayaran Siswa)
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

        // Log::info('Webhook Invoice Masuk:', $data); // Log untuk debugging
        // Log::info('statuss:', $status); // Log untuk debugging

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
                $paymentMethod = $data['payment_channel'] ?? $data['payment_method'] ?? 'XENDIT';
                $transaksi->update([
                    'status_pembayaran' => 'success',
                    'metode_pembayaran' => $paymentMethod,
                    // 'waktu_pembayaran' => now(), // Uncomment jika punya kolom ini
                ]);
                // Log::info("tessss: " . $transaksi);
                // Log::info("=== SELESAI PROSES TOPUP ===");
                    // ---------------------

                if (str_starts_with($transaksi->detail_pengambilan, 'TOPUP_')) {
                    
                    // --- KASUS A: TOP UP SALDO ---
                    
                    // Ambil nominal murni dari string (contoh: TOPUP_10000 -> 10000)
                    $parts = explode('_', $transaksi->detail_pengambilan);
                    $nominalTopUp = isset($parts[1]) ? (float)$parts[1] : $transaksi->total_harga;

                    $dompetSiswa = Dompet::firstOrCreate(
                        ['id_user' => $transaksi->id_user_pembeli],
                        ['saldo' => 0]
                    );

                    // Tambah Saldo sesuai Nominal Murni
                    $dompetSiswa->saldo = $dompetSiswa->saldo + $nominalTopUp;
                    $dompetSiswa->save();

                    Log::info("Topup Berhasil: {$transaksi->kode_transaksi}, User: {$transaksi->id_user_pembeli}, Masuk Saldo: {$nominalTopUp}");

                } else {
                    // --- KASUS B: BELANJA BARANG (Masuk ke Dompet PENJUAL) ---
                    
                    $details = DetailTransaksi::where('id_transaksi', $transaksi->id_transaksi)->get();

                    foreach ($details as $detail) {
                        $barang = Barang::find($detail->id_barang);

                        if ($barang) {
                            // Masukkan Saldo ke Penjual
                            $dompetPenjual = Dompet::firstOrCreate(
                                ['id_user' => $barang->id_user_penjual],
                                ['saldo' => 0]
                            );

                            $pendapatan = $detail->harga_saat_transaksi * $detail->jumlah;
                            $dompetPenjual->saldo = $dompetPenjual->saldo + $pendapatan;
                            $dompetPenjual->save();
                        }
                    }
                }

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
