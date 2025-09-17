<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::with(['penjual', 'ratingUlasan'])->get();
        return response()->json($barang);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user_penjual' => 'required|uuid|exists:users,id_user',
            'nama_barang' => 'required|string|max:255',
            'deskripsi_barang' => 'nullable|string',
            'jenis_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto_barang' => 'nullable|string|max:255',
            'kalori_kkal' => 'nullable|integer|min:0',
            'protein_g' => 'nullable|numeric|min:0',
            'lemak_g' => 'nullable|numeric|min:0',
            'karbohidrat_g' => 'nullable|numeric|min:0',
            'serat_g' => 'nullable|numeric|min:0',
            'gula_g' => 'nullable|numeric|min:0',
        ]);

        $barang = Barang::create([
            'id_barang' => Str::uuid(),
            ...$validated
        ]);

        return response()->json($barang->load('penjual'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = Barang::with(['penjual', 'ratingUlasan', 'detailTransaksi'])->findOrFail($id);
        return response()->json($barang);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'nama_barang' => 'string|max:255',
            'deskripsi_barang' => 'nullable|string',
            'jenis_barang' => 'string|max:255',
            'harga' => 'numeric|min:0',
            'stok' => 'integer|min:0',
            'foto_barang' => 'nullable|string|max:255',
            'kalori_kkal' => 'nullable|integer|min:0',
            'protein_g' => 'nullable|numeric|min:0',
            'lemak_g' => 'nullable|numeric|min:0',
            'karbohidrat_g' => 'nullable|numeric|min:0',
            'serat_g' => 'nullable|numeric|min:0',
            'gula_g' => 'nullable|numeric|min:0',
        ]);

        $barang->update($validated);

        return response()->json($barang->load('penjual'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json(['message' => 'Barang deleted successfully']);
    }

    /**
     * Get barang by penjual
     */
    public function getByPenjual(string $idUserPenjual)
    {
        $barang = Barang::where('id_user_penjual', $idUserPenjual)
            ->with(['ratingUlasan'])
            ->get();
        
        return response()->json($barang);
    }

    /**
     * Update stock
     */
    public function updateStock(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'stok' => 'required|integer|min:0',
        ]);

        $barang->update(['stok' => $validated['stok']]);

        return response()->json($barang);
    }
}