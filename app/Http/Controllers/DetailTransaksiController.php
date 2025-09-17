<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class DetailTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detailTransaksi = DetailTransaksi::with(['transaksi', 'barang'])->get();
        return response()->json($detailTransaksi);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detailTransaksi = DetailTransaksi::with(['transaksi', 'barang', 'ratingUlasan'])->findOrFail($id);
        return response()->json($detailTransaksi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $detailTransaksi = DetailTransaksi::findOrFail($id);

        $validated = $request->validate([
            'status_barang' => 'required|in:sudah_diambil,belum_diambil',
        ]);

        $detailTransaksi->update($validated);

        return response()->json($detailTransaksi->load(['transaksi', 'barang']));
    }

    /**
     * Get detail transaksi by transaksi
     */
    public function getByTransaksi(string $idTransaksi)
    {
        $detailTransaksi = DetailTransaksi::where('id_transaksi', $idTransaksi)
            ->with(['barang', 'ratingUlasan'])
            ->get();
        
        return response()->json($detailTransaksi);
    }

    /**
     * Mark item as taken
     */
    public function markAsTaken(string $id)
    {
        $detailTransaksi = DetailTransaksi::findOrFail($id);
        $detailTransaksi->update(['status_barang' => 'sudah_diambil']);

        return response()->json($detailTransaksi->load(['transaksi', 'barang']));
    }

    /**
     * Mark item as not taken
     */
    public function markAsNotTaken(string $id)
    {
        $detailTransaksi = DetailTransaksi::findOrFail($id);
        $detailTransaksi->update(['status_barang' => 'belum_diambil']);

        return response()->json($detailTransaksi->load(['transaksi', 'barang']));
    }
}