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
        $barang = Barang::with(['penjual', 'ratingUlasan'])
            ->get();

        $barang = $barang->sortBy('jenis_barang')->values();

        return view('buyer.beranda.index', compact('barang'));
    }

    public function makanan()
    {
        $barang = Barang::whereIn('jenis_barang', ['Makanan'])
            ->with(['penjual', 'ratingUlasan'])
            ->get();

        return view('buyer.makanan.index', compact('barang'));
    }

    public function minuman()
    {
        $barang = Barang::whereIn('jenis_barang', ['Minuman'])
            ->with(['penjual', 'ratingUlasan'])
            ->get();

        return view('buyer.minuman.index', compact('barang'));
    }

    public function camilan()
    {
        $barang = Barang::whereIn('jenis_barang', ['Snack'])
            ->with(['penjual', 'ratingUlasan'])
            ->get();

        return view('buyer.camilan.index', compact('barang'));
    }

    public function show(Barang $barang)
    {
        $barang->load(['penjual', 'ratingUlasan']); // Data dimuat
        return view('buyer.detail.index', compact('barang')); // Data dikirim
    }
}

