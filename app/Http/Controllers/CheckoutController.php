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
    public function process(Request $request, XenditService $xenditService)
    {
        // 1. Validasi Input
        $request->validate([
            'total_bayar' => 'required|numeric|min:1000',
            // Pastikan frontend mengirim array items: [{id_barang: '...', jumlah: 2}, ...]
            'items'       => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.jumlah'    => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        // Generate ID Unik
        // external_id: Untuk Xendit (TRX-TIMESTAMP-RANDOM)
        $externalId = 'TRX-' . time() . '-' . Str::random(5);
        // kode_transaksi: Untuk Tampilan User (INV/YYYYMMDD/RANDOM)
        $kodeTransaksi = 'INV/' . date('Ymd') . '/' . strtoupper(Str::random(6));

        // dd($request);
        DB::beginTransaction();
        try {
            // 2. Simpan Transaksi Utama
            $transaksi = new Transaksi();
            $transaksi->id_transaksi = Str::uuid();
            $transaksi->kode_transaksi = $kodeTransaksi;
            $transaksi->id_user_pembeli = $user->id_user;

            // Kolom Integrasi Xendit
            $transaksi->external_id = $externalId;
            $transaksi->id_order_gateway = $externalId;

            $transaksi->total_harga = $request->total_bayar;
            $transaksi->status_pembayaran = 'pending';
            // $transaksi->status_barang = 'baru';
            $transaksi->waktu_transaksi = now();
            
            // Default Pengambilan (Bisa dibuat dinamis jika ada input form)
            // $transaksi->waktu_pengambilan = $request->tanggal_pengambilan;
            $transaksi->waktu_pengambilan = Carbon::createFromFormat('d/m/Y', $request->tanggal_pengambilan)->format('Y-m-d H:i:s');
            $transaksi->detail_pengambilan = $request->detail_pengambilan;
            
            $transaksi->save();

            // 3. Simpan Detail Barang (Item Belanja)
            foreach($request->items as $item) {
                $barang = Barang::find($item['id_barang']);

                // Cek stok (Opsional, tapi disarankan)
                if($barang->stok < $item['jumlah']) {
                    throw new \Exception("Stok barang {$barang->nama_barang} tidak mencukupi.");
                }

                DetailTransaksi::create([
                    'id_detail' => Str::uuid(),
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga_saat_transaksi' => $barang->harga, // Rekam harga saat beli (antisipasi perubahan harga)
                    'status_barang' => 'baru'
                ]);

                // Opsional: Kurangi stok langsung (atau kurangi saat webhook success)
                // $barang->decrement('stok', $item['jumlah']);
            }

            // 4. Buat Invoice Xendit
            $invoice = $xenditService->createInvoice(
                $externalId,
                $request->total_bayar,
                $user->email ?? 'pembeli@ekantin.com', // Fallback email
                "Pembayaran Kantin - " . $user->username
            );

            // Simpan Link Pembayaran (Agar user bisa bayar nanti)
            $transaksi->payment_link = $invoice['invoice_url'];
            $transaksi->save();

            DB::commit();

            // 5. Redirect User ke Halaman Pembayaran Xendit
            return redirect($invoice['invoice_url']);

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function paymentStatus(Request $request)
    {
        // Ambil parameter 'status' dari URL, default 'failed'
        $status = $request->query('status', 'failed');

        // Return ke view errors/payment.blade.php
        return view('errors.payment', compact('status'));
    }
}
