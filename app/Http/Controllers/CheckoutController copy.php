<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi; // Pastikan model ini ada
use App\Models\DetailTransaksi; // Pastikan model ini ada
use App\Services\XenditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request, XenditService $xenditService)
    {
        // 1. Validasi Keranjang (Logic keranjang Anda, disederhanakan disini)
        // Misal data dikirim via request: total_harga, items[]
        $request->validate([
            'total_bayar' => 'required|numeric|min:1000',
            // 'items' => 'required|array', // Data barang yang dibeli
        ]);

        $user = Auth::user();
        $externalId = 'TRX-' . time() . '-' . Str::random(5);

        DB::beginTransaction();
        try {
            // 2. Simpan Transaksi ke Database Lokal (Status: PENDING)
            $transaksi = new Transaksi();
            $transaksi->id_transaksi = Str::uuid();
            $transaksi->id_user_pembeli = $user->id_user; // Sesuaikan nama kolom
            $transaksi->external_id = $externalId; // Penting untuk Webhook
            $transaksi->total_harga = $request->total_bayar;
            $transaksi->status_pembayaran = 'pending';
            $transaksi->status_barang = 'baru'; // Barang belum diambil
            $transaksi->waktu_transaksi = now();
            $transaksi->save();

            // 3. Simpan Detail Barang (Logic looping items keranjang Anda disini)
            // foreach($request->items as $item) { ... DetailTransaksi::create(...) ... }

            // 4. Buat Invoice Xendit
            $invoice = $xenditService->createInvoice(
                $externalId,
                $request->total_bayar,
                $user->email ?? 'pembeli@contoh.com', // Email pembeli
                "Pembayaran Kantin - " . $user->username
            );

            DB::commit();

            // 5. Redirect User ke Halaman Pembayaran Xendit
            // invoice_url adalah link hosted checkout dari Xendit
            return redirect($invoice['invoice_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}
