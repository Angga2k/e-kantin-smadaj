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
use Carbon\Carbon;

class CheckoutController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    public function process(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'items'             => 'required|array|min:1',
            // 'total_bayar'    => 'required', // Tidak perlu validasi total dari luar, kita hitung sendiri
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.jumlah'    => 'required|integer|min:1',
            'tanggal_pengambilan' => 'required',
            'detail_pengambilan'  => 'required|string',
            'payment_method'      => 'required|in:BANK_TRANSFER,E_WALLET', // Update: Validasi metode
        ]);

        $user = Auth::user();

        // Generate ID Unik
        $externalId = 'TRX-' . time() . '-' . Str::random(5);
        $kodeTransaksi = 'INV/' . date('Ymd') . '/' . strtoupper(Str::random(6));

        DB::beginTransaction();
        try {
            $itemsToInsert = [];
            $totalBelanjaMurni = 0;

            // 2. Loop Items untuk Cek Stok & Hitung Total Real
            foreach ($request->items as $item) {
                $barang = Barang::lockForUpdate()->find($item['id_barang']); // Lock baris agar aman saat race condition

                if (!$barang) continue;

                // Cek Stok
                if ($barang->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$barang->nama_barang} habis atau tidak cukup.");
                }

                $subtotal = $barang->harga * $item['jumlah'];
                $totalBelanjaMurni += $subtotal;

                $itemsToInsert[] = [
                    'barang' => $barang,
                    'jumlah' => $item['jumlah'],
                    'harga'  => $barang->harga
                ];
            }

            // 3. Hitung Biaya Admin & Tentukan Metode Pembayaran Xendit
            $tipePembayaran = $request->payment_method;
            
            // Hitung total akhir (+ Admin Fee) menggunakan Service
            $totalBayarAkhir = $this->xenditService->calculateTotalWithFee($totalBelanjaMurni, $tipePembayaran);

            // Tentukan metode yang diizinkan (Filter Invoice Xendit)
            $allowedPaymentMethods = [];
            if ($tipePembayaran === 'E_WALLET') {
                $allowedPaymentMethods = ['OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY', 'QRIS'];
            } elseif ($tipePembayaran === 'BANK_TRANSFER') {
                $allowedPaymentMethods = ['BCA', 'BNI', 'BRI', 'MANDIRI', 'PERMATA', 'CIMB', 'BSI'];
            }

            // --- 4. Request Invoice ke Xendit ---
            $payerEmail = $user->email ?? 'pembeli@kantin.com';
            $description = "Pembayaran Kantin #" . $kodeTransaksi;

            // Panggil Service dengan parameter tambahan $allowedPaymentMethods
            $xenditInvoice = $this->xenditService->createInvoice(
                $externalId,
                $totalBayarAkhir, // Gunakan total yang sudah + admin
                $payerEmail,
                $description,
                $allowedPaymentMethods // <-- Update: Kunci metode pembayaran
            );

            // --- 5. Simpan Transaksi ke Database ---
            $transaksi = new Transaksi();
            $transaksi->id_transaksi = Str::uuid();
            $transaksi->kode_transaksi = $kodeTransaksi;
            $transaksi->id_user_pembeli = $user->id_user;
            $transaksi->external_id = $externalId;
            $transaksi->id_order_gateway = $xenditInvoice['id'];
            $transaksi->payment_link = $xenditInvoice['invoice_url'];
            
            $transaksi->total_harga = $totalBayarAkhir; // Simpan total akhir
            $transaksi->metode_pembayaran = $tipePembayaran; // Simpan jenis pembayaran
            
            $transaksi->status_pembayaran = 'pending';
            $transaksi->waktu_transaksi = now();
            $transaksi->detail_pengambilan = $request->detail_pengambilan;
            
            // Format Tanggal (Pastikan format input d/m/Y konsisten)
            try {
                $transaksi->waktu_pengambilan = Carbon::createFromFormat('d/m/Y', $request->tanggal_pengambilan)->format('Y-m-d');
            } catch (\Exception $e) {
                // Fallback jika format tanggal berbeda (misal Y-m-d)
                $transaksi->waktu_pengambilan = Carbon::parse($request->tanggal_pengambilan)->format('Y-m-d');
            }

            $transaksi->save();

            // --- 6. Simpan Detail Barang & Kurangi Stok ---
            foreach ($itemsToInsert as $data) {
                DetailTransaksi::create([
                    'id_detail' => Str::uuid(),
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_barang' => $data['barang']->id_barang,
                    'jumlah' => $data['jumlah'],
                    'harga_saat_transaksi' => $data['harga'],
                    'status_barang' => 'baru'
                ]);

                // Kurangi Stok (Opsional: Aktifkan jika ingin langsung potong)
                // $data['barang']->decrement('stok', $data['jumlah']);
            }

            DB::commit();

            // --- 7. Redirect User ---
            return redirect($xenditInvoice['invoice_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi Kesalahan: ' . $e->getMessage());
        }
    }

    public function repayOrder(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required',
            'payment_method' => 'required|in:BANK_TRANSFER,E_WALLET'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            
            $transaksi = Transaksi::where('id_transaksi', $request->id_transaksi)
                ->where('id_user_pembeli', $user->id_user)
                ->where('status_pembayaran', 'pending')
                ->firstOrFail();

            // 1. Simpan ID Invoice lama ke variabel sementara
            $oldInvoiceId = $transaksi->id_order_gateway ?? null;

            // 2. Hitung Ulang Total (Barang Murni)
            $totalBelanjaMurni = 0;
            foreach ($transaksi->detailTransaksi as $detail) {
                $totalBelanjaMurni += $detail->harga_saat_transaksi * $detail->jumlah;
            }

            // 3. Hitung Total Baru (+ Admin)
            $tipePembayaran = $request->payment_method;
            $totalBayarAkhir = $this->xenditService->calculateTotalWithFee($totalBelanjaMurni, $tipePembayaran);

            // 4. Generate ID Baru
            $newExternalId = 'TRX-' . time() . '-' . Str::random(5);

            $allowedPaymentMethods = [];
            if ($tipePembayaran === 'E_WALLET') {
                $allowedPaymentMethods = ['OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY', 'QRIS'];
            } elseif ($tipePembayaran === 'BANK_TRANSFER') {
                $allowedPaymentMethods = ['BCA', 'BNI', 'BRI', 'MANDIRI', 'PERMATA', 'CIMB', 'BSI'];
            }

            $description = "Pembayaran Kantin (Revisi) #" . $transaksi->kode_transaksi;
            
            // 5. Buat Invoice Baru di Xendit (RESET WAKTU JADI 30 MENIT)
            $xenditInvoice = $this->xenditService->createInvoice(
                $newExternalId,
                $totalBayarAkhir,
                $user->email ?? 'pembeli@kantin.com',
                $description,
                $allowedPaymentMethods,
                1800 // <-- Kirim 1800 detik (30 menit) agar waktu Xendit reset
            );

            // 6. Update Data Transaksi (RESET WAKTU DB JUGA)
            $transaksi->update([
                'external_id' => $newExternalId,
                'total_harga' => $totalBayarAkhir,
                'metode_pembayaran' => $tipePembayaran,
                'id_order_gateway' => $xenditInvoice['id'],
                'payment_link' => $xenditInvoice['invoice_url'],
                'waktu_transaksi' => now(), // <-- RESET WAKTU agar UI Aplikasi juga hitung 30 menit lagi
            ]);

            // 7. Matikan Invoice Lama (Jika ada)
            if ($oldInvoiceId) {
                $this->xenditService->expireInvoice($oldInvoiceId);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'new_link' => $xenditInvoice['invoice_url']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error', 
                'message' => 'Gagal mengganti metode: ' . $e->getMessage()
            ], 500);
        }
    }


    public function show($kodeTransaksi)
    {
        // Cari transaksi berdasarkan kode
        $transaksi = Transaksi::where('kode_transaksi', $kodeTransaksi)->firstOrFail();

        // Tampilkan view detail pembayaran (Custom UI Sukses)
        return view('payment.show', compact('transaksi'));
    }

    public function paymentStatus(Request $request)
    {
        // Ambil parameter 'status' dari URL, default 'failed'
        $status = $request->query('status', 'failed');

        // Return ke view errors/payment.blade.php
        return view('errors.payment', compact('status'));
    }
}
