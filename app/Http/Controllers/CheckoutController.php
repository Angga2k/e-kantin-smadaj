<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    /**
     * Memproses data keranjang dari Local Storage dan menyimpannya sebagai Transaksi.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function process(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_items' => 'required|array|min:1',
            'cart_items.*.id_barang' => 'required|uuid',
            'cart_items.*.kuantitas' => 'required|integer|min:1',
            'cart_items.*.harga_satuan' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'waktu_pengambilan' => 'required|string',
            'detail_pengambilan' => 'required|string',
        ]);

        $userIdPembeli = '09a75a40-da09-4438-964a-313b6b737248'; //ID Pembeli testing

        $transactionId = (string) Str::uuid();
        $timestamp = now()->timestamp;
        $kodeTransaksi = 'INV-' . $timestamp;

        try {
            $waktuPengambilan = Carbon::createFromFormat('d/m/Y H:i:s', $validated['waktu_pengambilan'] . ' 00:00:00');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Format tanggal/waktu pengambilan tidak valid.'], 422);
        }

        DB::beginTransaction();

        try {
            $transaksi = [
                'id_transaksi' => $transactionId,
                'kode_transaksi' => $kodeTransaksi,
                'id_user_pembeli' => $userIdPembeli,
                'total_harga' => $validated['total_harga'],
                'id_order_gateway' => 'INV-'.$timestamp,
                'metode_pembayaran' => 'TES_CHECKOUT', // TES
                'status_pembayaran' => 'pending', // Default pending
                'waktu_transaksi' => now(),
                'waktu_pengambilan' => $waktuPengambilan,
                'detail_pengambilan' => $validated['detail_pengambilan'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('transaksi')->insert($transaksi);

            $detailItems = [];
            foreach ($validated['cart_items'] as $item) {
                $detailItems[] = [
                    'id_transaksi' => $transactionId,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['kuantitas'],
                    'harga_saat_transaksi' => $item['harga_satuan'],
                    'status_barang' => 'baru',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('detail_transaksi')->insert($detailItems);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat.',
                'kode_transaksi' => $kodeTransaksi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Checkout Failed for user {$userIdPembeli}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi ke database. Coba lagi.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }
}
