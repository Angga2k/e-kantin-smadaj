@extends('layouts.Buyer')

@section('title', 'Detail')

@section('content')
{{-- DEBUGGING: Tampilkan isi variabel $barang secara mentah --}}
{{-- {{ dd($barang) }} --}}
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            {{-- Link Beranda --}}
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
            <li class="breadcrumb-item">
                <a href="{{ url(strtolower('/' . $barang->jenis_barang)) }}">{{ $barang->jenis_barang }}</a>
            </li>
            
            {{-- Nama Barang Aktif --}}
            <li class="breadcrumb-item active" aria-current="page">{{ $barang->nama_barang }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-lg-6">
            {{-- FOTO BARANG --}}
            @if($barang->foto_barang)
                {{-- <img src="{{ asset('tes/ayam.png') }}" alt="Ayam Goreng Lengkuas" class="product-image"> --}}
                <img src="{{ asset($barang->foto_barang) }}" alt="{{ $barang->nama_barang }}" class="product-image">
            @else
                {{-- Fallback: Gunakan placeholder yang sudah diperbaiki --}}
                <img src="{{ asset('icon\\'. $barang->jenis_barang . '.png') }}" alt="{{ $barang->nama_barang }}" class="product-image" style="object-fit: contain; background-color: #f8f9fa;">
            @endif
        </div>

        <div class="col-lg-6">
            {{-- INFORMASI PENJUAL --}}
            <p class="text-muted mb-1">
                @if($barang->penjual)
                    {{ $barang->penjual->nama_toko }}
                @else
                    Stand Tidak Ditemukan
                @endif
            </p> 
            
            {{-- NAMA & DESKRIPSI --}}
            <h1 class="display-5 fw-bold">{{ $barang->nama_barang }}</h1> 
            <p class="mt-3">{{ $barang->deskripsi_barang ?? 'Deskripsi tidak tersedia.' }}</p> 

            @php
                // Hitung rata-rata rating dan total ulasan
                $avgRating = $barang->ratingUlasan->avg('rating') ?? 5.0;
                $totalReviews = $barang->ratingUlasan->count();
            @endphp
            
            {{-- RATING DAN ULASAN --}}
            <div class="d-flex align-items-center my-3 rating-stars">
                @for ($i = 1; $i <= 5; $i++)
                <i class="bi bi-star{{ $i <= floor($avgRating) ? '-fill' : '' }}"></i>
                @endfor
                <span class="ms-2 fw-bold">{{ number_format($avgRating, 1) }}</span>
                <span class="ms-1 text-muted">({{ $totalReviews }})</span>
            </div>

            {{-- HARGA --}}
            <p class="display-4 fw-bold product-price">Rp. {{ number_format($barang->harga, 0, ',', '.') }}</p> 

            <hr>

            {{-- KANDUNGAN NUTRISI --}}
            <div class="my-4">
                <h6 class="fw-bold mb-3">Kandungan Nutrisi per Sajian</h6>
                <div class="d-flex flex-wrap gap-3 nutrition-info">
                    @if($barang->kalori_kkal)
                        <div class="badge">{{ number_format($barang->kalori_kkal, 0) }}<span>kkal</span></div>
                    @endif
                    @if($barang->karbohidrat_g)
                        <div class="badge">{{ number_format($barang->karbohidrat_g, 1) }}<span>karbo</span></div>
                    @endif
                    @if($barang->protein_g)
                        <div class="badge">{{ number_format($barang->protein_g, 1) }}<span>protein</span></div>
                    @endif
                    @if($barang->lemak_g)
                        <div class="badge">{{ number_format($barang->lemak_g, 1) }}<span>lemak</span></div>
                    @endif
                </div>
            </div>
            
            {{-- VARIAN (Statis) --}}
            <div class="my-4">
                <h6 class="fw-bold mb-3">Varian</h6>
                <div class="variant-options">
                    <input type="radio" class="btn-check" name="varian" id="varian-pedas" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary rounded-pill" for="varian-pedas">Pedas</label>

                    <input type="radio" class="btn-check" name="varian" id="varian-tidak-pedas" autocomplete="off">
                    <label class="btn btn-outline-secondary rounded-pill" for="varian-tidak-pedas">Tidak Pedas</label>
                </div>
            </div>
            <hr>

            {{-- KUANTITAS DAN TOMBOL --}}
            <div class="d-flex align-items-center gap-4 mt-4">
                <div class="quantity-selector border rounded-pill p-1">
                    <button class="btn btn-sm btn-outline-secondary">-</button>
                    <input type="text" class="form-control border-0" value="1">
                    <button class="btn btn-sm btn-outline-secondary">+</button>
                </div>
                <button class="btn btn-add-to-cart btn-lg flex-grow-1">Tambah Keranjang</button>
            </div>
        </div>
    </div>
</div>
@endsection