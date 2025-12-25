<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi; // Model Transaksi (Diasumsikan ada)
use App\Models\DetailTransaksi;
use App\Models\RatingUlasan;
class BuyerOrderController extends Controller
{
    /**
     * Menampilkan halaman riwayat pesanan.
     */
    public function index()
    {
        // Mengambil data transaksi milik user yang login
        // REVISI: Menggunakan 'id_user_pembeli' sesuai model Transaksi
        $orders = Transaksi::where('id_user_pembeli', Auth::id())
                    ->with([
                        // Memuat detail transaksi beserta barangnya
                        'detailTransaksi.barang', 
                        // Memuat rating ulasan jika sudah ada
                        'detailTransaksi.ratingUlasan'
                    ])
                    ->orderBy('waktu_transaksi', 'desc') // Menggunakan waktu_transaksi
                    ->get();

        return view('buyer.pesanan.index', compact('orders'));
    }

    /**
     * Menyimpan atau Mengupdate ulasan (Rating) dari User.
     */
    public function storeRating(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_detail' => 'required|exists:detail_transaksi,id_detail',
            'rating'    => 'required|integer|min:1|max:5',
            'ulasan'    => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // 1. Ambil data detail transaksi
            // REVISI: Cek keamanan menggunakan 'id_user_pembeli'
            $detail = DetailTransaksi::where('id_detail', $request->id_detail)
                ->whereHas('transaksi', function($query) {
                    $query->where('id_user_pembeli', Auth::id());
                })
                ->firstOrFail();

            // 2. Simpan atau Update RatingUlasan
            // Menggunakan updateOrCreate agar user bisa edit rating
            RatingUlasan::updateOrCreate(
                [
                    // Kondisi pencarian: 1 detail transaksi = 1 ulasan
                    'id_detail_transaksi' => $detail->id_detail
                ],
                [
                    // Data yang diupdate/disimpan
                    // Catatan: Model RatingUlasan Anda sebelumnya menggunakan 'id_user_siswa', 
                    // jadi di sini tetap 'id_user_siswa' (sesuaikan jika di DB namanya id_user_pembeli)
                    'id_barang'     => $detail->id_barang,
                    'id_user_siswa' => Auth::id(), 
                    'rating'        => $request->rating,
                    'ulasan'        => $request->ulasan
                ]
            );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Ulasan berhasil disimpan!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}