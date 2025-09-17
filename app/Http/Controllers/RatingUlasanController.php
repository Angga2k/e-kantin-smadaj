<?php

namespace App\Http\Controllers;

use App\Models\RatingUlasan;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RatingUlasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ratingUlasan = RatingUlasan::with(['barang', 'siswa', 'detailTransaksi'])->get();
        return response()->json($ratingUlasan);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_detail_transaksi' => 'required|integer|exists:detail_transaksi,id_detail|unique:rating_ulasan',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string',
        ]);

        // Get detail transaksi to validate ownership and get required data
        $detailTransaksi = DetailTransaksi::with(['transaksi', 'barang'])->findOrFail($validated['id_detail_transaksi']);
        
        // Validate that the item has been taken
        if ($detailTransaksi->status_barang !== 'sudah_diambil') {
            return response()->json(['error' => 'Rating hanya bisa diberikan untuk barang yang sudah diambil'], 400);
        }

        // Validate that the transaction is successful
        if ($detailTransaksi->transaksi->status_pembayaran !== 'success') {
            return response()->json(['error' => 'Rating hanya bisa diberikan untuk transaksi yang berhasil'], 400);
        }

        $ratingUlasan = RatingUlasan::create([
            'id_rating' => Str::uuid(),
            'id_barang' => $detailTransaksi->id_barang,
            'id_user_siswa' => $detailTransaksi->transaksi->id_user_pembeli,
            'id_detail_transaksi' => $validated['id_detail_transaksi'],
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan'],
        ]);

        return response()->json($ratingUlasan->load(['barang', 'siswa', 'detailTransaksi']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ratingUlasan = RatingUlasan::with(['barang', 'siswa', 'detailTransaksi'])->findOrFail($id);
        return response()->json($ratingUlasan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ratingUlasan = RatingUlasan::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'integer|min:1|max:5',
            'ulasan' => 'nullable|string',
        ]);

        $ratingUlasan->update($validated);

        return response()->json($ratingUlasan->load(['barang', 'siswa', 'detailTransaksi']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ratingUlasan = RatingUlasan::findOrFail($id);
        $ratingUlasan->delete();

        return response()->json(['message' => 'Rating ulasan deleted successfully']);
    }

    /**
     * Get rating ulasan by barang
     */
    public function getByBarang(string $idBarang)
    {
        $ratingUlasan = RatingUlasan::where('id_barang', $idBarang)
            ->with(['siswa', 'detailTransaksi'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($ratingUlasan);
    }

    /**
     * Get rating ulasan by user
     */
    public function getByUser(string $idUser)
    {
        $ratingUlasan = RatingUlasan::where('id_user_siswa', $idUser)
            ->with(['barang', 'detailTransaksi'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($ratingUlasan);
    }

    /**
     * Get average rating for a barang
     */
    public function getAverageRating(string $idBarang)
    {
        $avgRating = RatingUlasan::where('id_barang', $idBarang)->avg('rating');
        $totalReviews = RatingUlasan::where('id_barang', $idBarang)->count();
        
        return response()->json([
            'average_rating' => round($avgRating, 2),
            'total_reviews' => $totalReviews
        ]);
    }
}