<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Services\XenditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request, XenditService $xenditService)
    {
        // 1. Validasi Input
        $request->validate([
            // 'total_bayar' => 'required|numeric|min:1000', // Gunakan hitung ulang di backend agar aman
            'payment_method' => 'required|string', // Contoh: BCA_VA, BRI_VA, QRIS
            // Pastikan frontend mengirim array items
            // 'items'       => 'required|array|min:1',
        ]);

        $user = Auth::user();

        // Hardcode dummy items untuk tes jika frontend belum kirim (Hapus ini nanti)
        // Asumsi total bayar dihitung ulang di server demi keamanan
        $totalBayar = 22000;

        // Generate ID
        $externalId = 'TRX-' . time() . '-' . Str::random(5);
        $kodeTransaksi = 'INV/' . date('Ymd') . '/' . strtoupper(Str::random(6));

        DB::beginTransaction();
        try {
            // 2. Simpan Transaksi Utama
            $transaksi = new Transaksi();
            $transaksi->id_transaksi = Str::uuid();
            $transaksi->kode_transaksi = $kodeTransaksi;
            $transaksi->id_user_pembeli = $user->id_user ?? 1; // Fallback id 1
            $transaksi->external_id = $externalId;
            $transaksi->id_order_gateway = $externalId;
            $transaksi->total_harga = $totalBayar;
            $transaksi->status_pembayaran = 'pending';
            $transaksi->waktu_transaksi = now();

            // Simpan metode pembayaran yang dipilih user
            $transaksi->payment_channel = $request->payment_method;

            $transaksi->save();

            // 3. Simpan Detail Barang (Lewati dulu untuk fokus ke Payment)
            // ... (Kode simpan detail barang tetap sama) ...

            // 4. INTEGRASI XENDIT DIRECT API (CUSTOM UI)
            $responseXendit = null;
            $paymentMethod = $request->payment_method; // ex: BCA_VA, QRIS

            if ($paymentMethod == 'QRIS') {
                // --- A. JIKA PILIH QRIS ---
                $responseXendit = $xenditService->createQRCode(
                    $externalId,
                    $totalBayar
                );

                // Simpan String QR Code ke database (agar bisa ditampilkan)
                // Pastikan tabel transaksi punya kolom 'payment_code' atau 'qr_string'
                $transaksi->payment_code = $responseXendit['qr_string'];
                $transaksi->save();

            } else {
                // --- B. JIKA PILIH VIRTUAL ACCOUNT (BCA_VA, BRI_VA, dll) ---
                // Pecah string "BCA_VA" menjadi "BCA"
                $bankCode = explode('_', $paymentMethod)[0];

                $responseXendit = $xenditService->createVirtualAccount(
                    $externalId,
                    $bankCode,
                    $user->nama ?? 'Pembeli Kantin',
                    $totalBayar
                );

                // Simpan Nomor VA ke database
                $transaksi->payment_code = $responseXendit['account_number'];
                $transaksi->save();
            }

            DB::commit();

            // 5. REDIRECT KE HALAMAN DETAIL (BUKAN KE XENDIT)
            // Kita arahkan ke halaman internal aplikasi yang menampilkan QR/VA
            return redirect()->route('payment.show', $transaksi->kode_transaksi);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function show($kodeTransaksi)
    {
        // Cari transaksi berdasarkan kode
        $transaksi = Transaksi::where('kode_transaksi', $kodeTransaksi)->firstOrFail();

        // Tampilkan view detail pembayaran (Custom UI Sukses)
        return view('payment.show', compact('transaksi'));
    }
}
