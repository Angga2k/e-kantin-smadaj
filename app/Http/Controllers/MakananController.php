<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class MakananController extends Controller
{
    /**
     * Display a listing of makanan (food items).
     */
    public function index()
    {
        $barang = Barang::whereIn('jenis_barang', ['Makanan Berat', 'Makanan Ringan'])
            ->with(['penjual', 'ratingUlasan'])
            ->get();

        return view('buyer.makanan.index', compact('barang'));
    }
}

