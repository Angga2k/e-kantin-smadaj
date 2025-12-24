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
    // public function process(Request $request, XenditService $xenditService)
    // {
    //     // 1. Validasi Input

    //     $request->validate([

    //         'total_bayar' => 'required|numeric|min:1000',
    //         // Pastikan frontend mengirim array items: [{id_barang: '...', jumlah: 2}, ...]
    //         'tanggal_pengambilan'       => 'required',
    //         'detail_pengambilan'       => 'required',
    //         'items'       => 'required|array|min:1',
    //         'items.*.id_barang' => 'required|exists:barang,id_barang',
    //         'items.*.jumlah'    => 'required|integer|min:1',
    //         // 'total_bayar' => 'required|numeric|min:1000', // Gunakan hitung ulang di backend agar aman
    //         // 'payment_method' => 'required|string', // Contoh: BCA_VA, BRI_VA, QRIS
    //         // Pastikan frontend mengirim array items
    //         // 'items'       => 'required|array|min:1',
    //     ]);

    //     $user = Auth::user();

    //     // Hardcode dummy items untuk tes jika frontend belum kirim (Hapus ini nanti)
    //     // Asumsi total bayar dihitung ulang di server demi keamanan
    //     // $totalBayar = 22000;

    //     // Generate ID
    //     $externalId = 'TRX-' . time() . '-' . Str::random(5);
    //     $kodeTransaksi = 'INV/' . date('Ymd') . '/' . strtoupper(Str::random(6));
    //     // dd($request);


    //     DB::beginTransaction();
    //     try {
    //         // 2. Simpan Transaksi Utama
    //         $transaksi = new Transaksi();
    //         $transaksi->id_transaksi = Str::uuid();
    //         $transaksi->kode_transaksi = $kodeTransaksi;
    //         $transaksi->id_user_pembeli = $user->id_user ?? 1; // Fallback id 1
    //         $transaksi->external_id = $externalId;
    //         $transaksi->id_order_gateway = $externalId;
    //         $transaksi->total_harga = $request->total_bayar;
    //         $transaksi->detail_pengambilan = $request->tanggal_pengambilan;
    //         $transaksi->total_harga = $request->total_bayar;
    //         $transaksi->status_pembayaran = 'pending';
    //         $transaksi->waktu_transaksi = now();

    //         // Simpan metode pembayaran yang dipilih user
    //         // $transaksi->payment_channel = $request->payment_method;
    //         $transaksi->save();

    //         // 3. Simpan Detail Barang (Lewati dulu untuk fokus ke Payment)
    //         // ... (Kode simpan detail barang tetap sama) ...

    //         // 4. INTEGRASI XENDIT DIRECT API (CUSTOM UI)
    //         $responseXendit = null;
    //         $paymentMethod = $request->payment_method; // ex: BCA_VA, QRIS

    //         if ($paymentMethod == 'QRIS') {
    //             // --- A. JIKA PILIH QRIS ---
    //             $responseXendit = $xenditService->createQRCode(
    //                 $externalId,
    //                 $totalBayar
    //             );

    //             // Simpan String QR Code ke database (agar bisa ditampilkan)
    //             // Pastikan tabel transaksi punya kolom 'payment_code' atau 'qr_string'
    //             $transaksi->payment_code = $responseXendit['qr_string'];
    //             $transaksi->save();

    //         } else {
    //             // --- B. JIKA PILIH VIRTUAL ACCOUNT (BCA_VA, BRI_VA, dll) ---
    //             // Pecah string "BCA_VA" menjadi "BCA"
    //             $bankCode = explode('_', $paymentMethod)[0];

    //             $responseXendit = $xenditService->createVirtualAccount(
    //                 $externalId,
    //                 $bankCode,
    //                 $user->nama ?? 'Pembeli Kantin',
    //                 $totalBayar
    //             );

    //             // Simpan Nomor VA ke database
    //             $transaksi->payment_code = $responseXendit['account_number'];
    //             $transaksi->save();
    //         }

    //         DB::commit();

    //         // 5. REDIRECT KE HALAMAN DETAIL (BUKAN KE XENDIT)
    //         // Kita arahkan ke halaman internal aplikasi yang menampilkan QR/VA
    //         return redirect()->route('payment.show', $transaksi->kode_transaksi);

    //     } catch (\Exception $e) {
    //         dd($e->getMessage());
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
    //     }
    // }

    public function process(Request $request, XenditService $xenditService)
    {
        // 1. Validasi
        $request->validate([
            'items'                 => 'required|array|min:1',
            'total_bayar' => 'required|numeric|min:1000',
            'items.*.id_barang'     => 'required|exists:barang,id_barang',
            'items.*.jumlah'        => 'required|integer|min:1',
            'tanggal_pengambilan'   => 'required', // string tgl
            'detail_pengambilan'    => 'required|string',
            // 'payment_method'     => 'nullable', // Tidak wajib lagi karena user pilih di Xendit nanti
        ]);

        $user = Auth::user();
        // dd($request);

        // Generate ID
        $externalId = 'TRX-' . time() . '-' . Str::random(5);
        $kodeTransaksi = 'INV/' . date('Ymd') . '/' . strtoupper(Str::random(6));

        DB::beginTransaction();
        try {
            $itemsToInsert = [];

            foreach ($request->items as $item) {
                $barang = Barang::find($item['id_barang']);

                // Cek Stok
                if ($barang->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$barang->nama_barang} habis/kurang.");
                }
                $itemsToInsert[] = [
                    'barang' => $barang,
                    'jumlah' => $item['jumlah'],
                    'harga'  => $barang->harga
                ];
            }


            // --- B. Simpan Transaksi ---
            $transaksi = new Transaksi();
            $transaksi->id_transaksi = Str::uuid();
            $transaksi->kode_transaksi = $kodeTransaksi;
            $transaksi->id_user_pembeli = $user->id_user ?? 1; // Fallback jika user null
            $transaksi->external_id = $externalId;
            $transaksi->id_order_gateway = $externalId; // Nanti diisi ID dari Xendit
            $transaksi->total_harga = $request->total_bayar;
            $transaksi->status_pembayaran = 'pending';
            $transaksi->waktu_transaksi = now();
            $transaksi->detail_pengambilan = $request->detail_pengambilan;
            $transaksi->waktu_pengambilan = $transaksi->waktu_pengambilan = Carbon::createFromFormat('d/m/Y', $request->tanggal_pengambilan)->format('Y-m-d');
            // dd('masukkkkkkkkk');

            $transaksi->detail_pengambilan = $request->detail_pengambilan;
            $transaksi->save();

            // --- C. Simpan Detail Barang ---
            foreach ($itemsToInsert as $data) {
                DetailTransaksi::create([
                    'id_detail' => Str::uuid(),
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_barang' => $data['barang']->id_barang,
                    'jumlah' => $data['jumlah'],
                    'harga_saat_transaksi' => $data['harga'],
                    'status_barang' => 'baru'
                ]);

                // Kurangi Stok (Opsional, aktifkan jika mau potong langsung)
                // $data['barang']->decrement('stok', $data['jumlah']);
            }

            // --- D. REQUEST KE XENDIT (INVOICE) ---
            // Kita pakai createInvoice sesuai service yang Anda punya
            $payerEmail = $user->email ?? 'pembeli@kantin.com';
            $description = "Pembayaran Kantin #" . $kodeTransaksi;

            // Panggil Service
            $xenditInvoice = $xenditService->createInvoice(
                $externalId,
                $request->total_bayar,
                $payerEmail,
                $description
            );

            // SDK Xendit mengembalikan Object, bukan Array.
            // Cara akses datanya pakai getter atau property
            $paymentLink = $xenditInvoice['invoice_url']; // Link pembayaran
            $gatewayId = $xenditInvoice['id']; // ID Invoice Xendit

            // Update Transaksi dengan Link Pembayaran
            $transaksi->payment_link = $paymentLink; // Simpan link ini
            $transaksi->id_order_gateway = $gatewayId;
            $transaksi->save();

            DB::commit();

            // --- E. REDIRECT USER ---
            // Arahkan user langsung ke halaman pembayaran Xendit
            // Nanti setelah bayar, Xendit akan redirect balik ke 'success_redirect_url' yang ada di Service
            return redirect($paymentLink);

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return back()->with('error', 'Terjadi Kesalahan: ' . $e->getMessage());
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
