<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Dompet;
use App\Models\Transaksi;
use App\Services\XenditService;

class DompetController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Menampilkan Halaman Input Top Up
     */
    public function index()
    {
        $user = Auth::user();
        
        // Cek/Buat Dompet untuk sekadar menampilkan saldo saat ini
        $dompet = Dompet::firstOrCreate(
            ['id_user' => $user->id_user],
            ['saldo' => 0] // Saldo awal 0 (otomatis terenkripsi oleh Model)
        );

        return view('buyer.dana.index', compact('dompet'));
    }

    /**
     * Memproses Request Top Up
     */
    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|in:BANK_TRANSFER,E_WALLET',
        ]);

        $user = Auth::user();
        $amount = $request->amount; // Nominal Murni (misal: 10000)
        $tipePembayaran = $request->payment_method;

        DB::beginTransaction();
        try {
            $dompet = Dompet::firstOrCreate(
                ['id_user' => $user->id_user],
                ['saldo' => 0]
            );

            // Hitung Total Bayar (Nominal + Admin)
            $totalBayar = $this->xenditService->calculateTotalWithFee($amount, $tipePembayaran);

            $dateCode = date('Ymd');
            $prefix = "TOPUP/$dateCode/";
            $countToday = Transaksi::where('kode_transaksi', 'like', $prefix . '%')->count();
            $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
            
            $kodeTransaksi = $prefix . $sequence; 
            $externalId = 'TOPUP-' . time() . '-' . Str::random(5);

            $allowedPaymentMethods = [];
            if ($tipePembayaran === 'E_WALLET') {
                $allowedPaymentMethods = ['OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY', 'QRIS'];
            } elseif ($tipePembayaran === 'BANK_TRANSFER') {
                $allowedPaymentMethods = ['BCA', 'BNI', 'BRI', 'MANDIRI', 'PERMATA', 'CIMB', 'BSI'];
            }

            $description = "Top Up Saldo E-Kantin #" . $kodeTransaksi;
            $xenditInvoice = $this->xenditService->createInvoice(
                $externalId,
                $totalBayar, 
                $user->email ?? 'siswa@kantin.com',
                $description,
                $allowedPaymentMethods
            );

            $transaksi = new Transaksi();
            $transaksi->id_transaksi = Str::uuid();
            $transaksi->kode_transaksi = $kodeTransaksi;
            $transaksi->id_user_pembeli = $user->id_user;
            $transaksi->external_id = $externalId;
            $transaksi->id_order_gateway = $xenditInvoice['id'];
            $transaksi->payment_link = $xenditInvoice['invoice_url'];
            
            $transaksi->total_harga = $totalBayar; // Simpan harga kotor (gross) untuk invoice
            
            $transaksi->metode_pembayaran = $tipePembayaran;
            $transaksi->status_pembayaran = 'pending';
            $transaksi->waktu_transaksi = now();
            
            // --- UPDATE DI SINI ---
            // Simpan format "TOPUP_NOMINAL" (contoh: TOPUP_10000)
            // Agar nanti webhook tahu nominal murni yang harus dimasukkan ke saldo
            $transaksi->detail_pengambilan = 'TOPUP_' . $amount; 
            // ----------------------

            $transaksi->waktu_pengambilan = now(); 

            $transaksi->save();

            DB::commit();

            return redirect($xenditInvoice['invoice_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses Top Up: ' . $e->getMessage());
        }
    }

    public function tesss()
    {;
        $dompetSiswa = Dompet::firstOrCreate(
            ['id_user' =>Auth::id()],
            ['saldo' => 0]
        );

        // Tambah Saldo sesuai Nominal Murni
        $dompetSiswa->saldo = 245000;
        $dompetSiswa->save();
    }
}
