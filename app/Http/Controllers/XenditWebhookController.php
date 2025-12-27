<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryPenarikan;
use App\Models\Dompet;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    private function verifyToken(Request $request)
    {
        $callbackToken = $request->header('x-callback-token');
        if ($callbackToken !== env('XENDIT_CALLBACK_TOKEN')) {
            abort(401, 'Unauthorized');
        }
    }

    public function createQRCode($externalId, $amount)
    {
        // Dokumentasi: https://developers.xendit.co/api-reference/#create-qr-code
        $response = Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
            ->post('https://api.xendit.co/qr_codes', [
                'external_id' => $externalId,
                'type' => 'DYNAMIC',
                'callback_url' => env('XENDIT_QR_CALLBACK_URL'), // Pastikan di set di .env
                'amount' => (int)$amount,
            ]);

        if ($response->failed()) {
            throw new \Exception('Gagal buat QR: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Membuat Virtual Account (Closed VA)
     * Closed VA: Nominal sudah ditentukan, user tidak perlu input nominal lagi.
     */
    public function createVirtualAccount($externalId, $bankCode, $name, $amount)
    {
        // Dokumentasi: https://developers.xendit.co/api-reference/#create-virtual-account
        $response = Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
            ->post('https://api.xendit.co/callback_virtual_accounts', [
                'external_id' => $externalId,
                'bank_code' => $bankCode,
                'name' => $name,
                'is_closed' => true, // Agar nominal terkunci
                'is_single_use' => true, // Agar VA hangus setelah dibayar
                'expected_amount' => (int)$amount,
                'expiration_date' => now()->addDay()->toISOString(), // Expired 24 jam
            ]);

        if ($response->failed()) {
            throw new \Exception('Gagal buat VA: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * 1. Callback untuk Virtual Account (FVA Paid)
     * Atur di Dashboard Xendit: Virtual Accounts > Created/Paid
     */
    public function handleVirtualAccount(Request $request)
    {
        $this->verifyToken($request);
        $data = $request->all();

        // Debug log
        Log::info('Xendit VA Callback:', $data);

        // Format Payload VA dari Xendit biasanya:
        // { "external_id": "...", "account_number": "...", "amount": 10000, ... }
        // TIDAK ADA field 'status'. Keberadaan callback ini menandakan SUKSES.

        $externalId = $data['external_id'];

        // Cari Transaksi
        $transaksi = Transaksi::where('external_id', $externalId)->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaksi->status_pembayaran == 'success') {
            return response()->json(['message' => 'Already paid']);
        }

        // Cek apakah nominal pas (Opsional tapi disarankan)
        // if ((int)$transaksi->total_harga !== (int)$data['amount']) { ... }

        DB::beginTransaction();
        try {
            $transaksi->update([
                'status_pembayaran' => 'success', // Langsung sukses karena ini callback VA Paid
                'waktu_pembayaran' => now(),
            ]);

            // Tambahkan saldo ke dompet penjual, kurangi stok, dll disini
            // ...

            DB::commit();
            return response()->json(['message' => 'Payment Received']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VA Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * 2. Callback untuk QR Code (QR Paid)
     * Atur di Dashboard Xendit: QR Codes > Paid
     */
    public function handleQRCode(Request $request)
    {
        $this->verifyToken($request);
        $data = $request->all();

        Log::info('Xendit QR Callback:', $data);

        $externalId = $data['external_id'];
        $status = $data['status']; // Biasanya 'COMPLETED' atau 'SUCCEEDED'

        $transaksi = Transaksi::where('external_id', $externalId)->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaksi->status_pembayaran == 'success') {
            return response()->json(['message' => 'Already paid']);
        }

        DB::beginTransaction();
        try {
            if ($status == 'COMPLETED' || $status == 'SUCCEEDED') {
                $transaksi->update([
                    'status_pembayaran' => 'success',
                    'waktu_pembayaran' => now(),
                ]);
            } else {
                // Handle status failed jika ada
                $transaksi->update(['status_pembayaran' => 'failed']);
            }

            DB::commit();
            return response()->json(['message' => 'QR Status Updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * 3. Handle Callback Disbursement (Penarikan Saldo)
     * Atur di Dashboard Xendit: Disbursements > Sent
     */
    public function handleDisbursement(Request $request)
    {
        $this->verifyToken($request);
        $data = $request->all();

        Log::info('Xendit Disb Callback:', $data);

        $externalId = $data['external_id'];
        $status = $data['status']; // 'COMPLETED' atau 'FAILED'

        $transaksi = HistoryPenarikan::where('external_id', $externalId)->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaksi->status == 'berhasil' || $transaksi->status == 'gagal') {
            return response()->json(['message' => 'Already updated']);
        }

        DB::beginTransaction();
        try {
            if ($status == 'COMPLETED') {
                $transaksi->update(['status' => 'berhasil']);
            } elseif ($status == 'FAILED') {
                $transaksi->update([
                    'status' => 'gagal',
                    'failure_code' => $data['failure_code'] ?? 'UNKNOWN'
                ]);

                // Refund Saldo
                $dompet = Dompet::find($transaksi->id_dompet);
                if ($dompet) {
                    $dompet->increment('saldo', $transaksi->jumlah);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Disbursement processed']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Disb Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing'], 500);
        }
    }
}
