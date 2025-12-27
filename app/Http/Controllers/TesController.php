<?php

namespace App\Http\Controllers;

use App\Models\Barang; 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TesController
{
    public function index()
    {
        // $barang = Barang::whereIn('jenis_barang', ['Makanan'])
        //     ->with(['penjual', 'ratingUlasan'])
        //     ->get();

        // ===============================================
        
        $barang = Barang::with(['penjual', 'ratingUlasan'])
            ->get();
        
        $barang = $barang->sortBy('jenis_barang')->values();
        
        return response()->json($barang);
    }
    public function index2(Barang $barang)
    {
        $barang->load(['penjual', 'ratingUlasan']);
        return response()->json($barang);
    }
}
