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
        // 1. Validasi Input
        $request->validate([
            'amount' => 'required|numeric|min:10000', // Minimal top up 10rb
            'payment_method' => 'required|in:BANK_TRANSFER,E_WALLET',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $tipePembayaran = $request->payment_method;

        DB::beginTransaction();
        try {
            // 2. [INTI PERMINTAAN ANDA] Cek/Buat Dompet Siswa
            // Jika baris dompet belum ada untuk user ini, buat baru dengan saldo 0
            $dompet = Dompet::firstOrCreate(
                ['id_user' => $user->id_user],
                ['saldo' => 0]
            );

            // 3. Hitung Biaya Admin (Opsional, tergantung kebijakan)
            // Kita gunakan helper yang sudah ada di service
            $totalBayar = $this->xenditService->calculateTotalWithFee($amount, $tipePembayaran);

            // 4. Generate Kode Transaksi
            $dateCode = date('Ymd');
            $prefix = "TOPUP/$dateCode/";

            // Hitung jumlah transaksi hari ini yang berawalan prefix tersebut untuk menentukan urutan
            $countToday = Transaksi::where('kode_transaksi', 'like', $prefix . '%')->count();
            
            // Format urutan menjadi 4 digit (misal: 1 -> 0001)
            $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
            
            $kodeTransaksi = $prefix . $sequence; 
            $externalId = 'TOPUP-' . time() . '-' . Str::random(5);

            // 5. Filter Metode Pembayaran Xendit
            $allowedPaymentMethods = [];
            if ($tipePembayaran === 'E_WALLET') {
                $allowedPaymentMethods = ['OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY', 'QRIS'];
            } elseif ($tipePembayaran === 'BANK_TRANSFER') {
                $allowedPaymentMethods = ['BCA', 'BNI', 'BRI', 'MANDIRI', 'PERMATA', 'CIMB', 'BSI'];
            }

            // 6. Buat Invoice Xendit
            $description = "Top Up Saldo E-Kantin #" . $kodeTransaksi;
            $xenditInvoice = $this->xenditService->createInvoice(
                $externalId,
                $totalBayar,
                $user->email ?? 'siswa@kantin.com',
                $description,
                $allowedPaymentMethods
            );

            // 7. Simpan Riwayat ke Tabel Transaksi
            // Kita gunakan tabel 'transaksi' yang ada, tapi beri tanda khusus
            // Misal: detail_pengambilan diisi 'TOPUP_SALDO' agar webhook tahu ini bukan beli barang
            $transaksi = new Transaksi();
            $transaksi->id_transaksi = Str::uuid();
            $transaksi->kode_transaksi = $kodeTransaksi;
            $transaksi->id_user_pembeli = $user->id_user;
            $transaksi->external_id = $externalId;
            $transaksi->id_order_gateway = $xenditInvoice['id'];
            $transaksi->payment_link = $xenditInvoice['invoice_url'];
            
            // Simpan nominal murni (amount) di database agar nanti masuk ke saldo sesuai input
            // Atau simpan total_bayar jika ingin mencatat gross. 
            // Disini saya simpan total_bayar agar sinkron dengan invoice, 
            // Nanti di webhook kita harus pintar memilah mana admin fee mana saldo masuk.
            // *Saran: Untuk Topup, sebaiknya yang masuk ke saldo adalah 'amount' awal.
            // Kita bisa simpan 'amount' di kolom lain jika ada, atau parsing dari logika nanti.
            $transaksi->total_harga = $totalBayar; 
            
            $transaksi->metode_pembayaran = $tipePembayaran;
            $transaksi->status_pembayaran = 'pending';
            $transaksi->waktu_transaksi = now();
            
            // FLAGGING PENTING: Menandakan ini Top Up
            $transaksi->detail_pengambilan = 'TOPUP_SALDO'; 
            $transaksi->waktu_pengambilan = now(); // Dummy date

            $transaksi->save();

            DB::commit();

            // 8. Redirect ke Xendit
            return redirect($xenditInvoice['invoice_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses Top Up: ' . $e->getMessage());
        }
    }
}
