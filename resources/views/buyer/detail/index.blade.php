@extends('layouts.Buyer')

@section('title', 'Detail')

@section('content')

{{-- Tambahkan dd($barang) di sini jika ingin melihat data mentah: {{ dd($barang) }} --}}

<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            {{-- Breadcrumb navigasi --}}
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
            {{-- Asumsi ada route untuk filter jenis barang --}}
            <li class="breadcrumb-item"><a href="{{ route('makanan.index') }}">{{ $barang->jenis_barang }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $barang->nama_barang }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        {{-- BAGIAN KIRI: GAMBAR PRODUK --}}
        <div class="col-lg-6">
            @if($barang->foto_barang)
                {{-- <img src="{{ asset('tes/ayam.png') }}" alt="Ayam Goreng Lengkuas" class="product-image"> --}}
                <img src="{{ asset($barang->foto_barang) }}" alt="{{ $barang->nama_barang }}" class="product-image">
            @else
                {{-- Fallback: Gunakan placeholder yang sudah diperbaiki --}}
                <img src="{{ asset('icon\\'. $barang->jenis_barang . '.png') }}" alt="{{ $barang->nama_barang }}" class="product-image" style="object-fit: contain; background-color: #f8f9fa;">
            @endif
        </div>

        {{-- BAGIAN KANAN: DETAIL, HARGA, TAMBAH KERANJANG --}}
        <div class="col-lg-6">
            {{-- Informasi Penjual --}}
            <p class="text-muted mb-1">{{ $barang->penjual->nama_toko ?? 'Stand Tidak Ditemukan' }}</p>

            {{-- Nama dan Deskripsi Barang --}}
            <h1 class="display-5 fw-bold">{{ $barang->nama_barang }}</h1>
            <p class="mt-3">{{ $barang->deskripsi_barang ?? 'Deskripsi tidak tersedia.' }}</p>

            @php
                // Hitung rating dan total ulasan
                $avgRating = $barang->ratingUlasan->avg('rating') ?? 0;
                $totalReviews = $barang->ratingUlasan->count();
                $roundedRating = floor($avgRating); // Pembulatan ke bawah sesuai permintaan terakhir
            @endphp

            {{-- Tampilan Rating --}}
            <div class="d-flex align-items-center my-3 rating-stars">
                @for ($i = 1; $i <= 5; $i++)
                    {{-- Menggunakan floor() untuk pembulatan ke bawah --}}
                    <i class="bi bi-star-{{ $i <= $roundedRating ? 'fill' : 'empty' }}"></i>
                @endfor
                <span class="ms-2 fw-bold">{{ number_format($avgRating, 1) }}</span>
                <span class="ms-1 text-muted">({{ $totalReviews }})</span>
            </div>

            {{-- Harga --}}
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

            {{-- VARIAN (LOGIKA STATIS) --}}
            <div class="my-4">
                <h6 class="fw-bold mb-3">Varian</h6>
                <div class="variant-options">
                    {{-- Asumsi Varian dipilih di sini. ID Varian harus unik. --}}
                    <input type="radio" class="btn-check" name="varian" id="varian-pedas" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary rounded-pill" for="varian-pedas">Pedas</label>

                    <input type="radio" class="btn-check" name="varian" id="varian-tidak-pedas" autocomplete="off">
                    <label class="btn btn-outline-secondary rounded-pill" for="varian-tidak-pedas">Tidak Pedas</label>
                </div>
            </div>
            <hr>

            {{-- QUANTITY SELECTOR DAN TOMBOL TAMBAH KERANJANG --}}
            <div class="d-flex align-items-center gap-4 mt-4">
                <div class="quantity-selector border rounded-pill p-1">
                    <button class="btn btn-sm btn-outline-secondary" id="decreaseQty">-</button>
                    <input type="text" class="form-control border-0 text-center" value="1" id="inputQty" readonly style="width: 50px;">
                    <button class="btn btn-sm btn-outline-secondary" id="increaseQty">+</button>
                </div>

                <button
                    class="btn btn-add-to-cart btn-lg flex-grow-1"
                    id="addToCartButton"
                    data-id="{{ $barang->id_barang }}"
                    data-name="{{ $barang->nama_barang }}"
                    data-price="{{ $barang->harga }}"
                    data-jenis="{{ $barang->jenis_barang }}"
                    data-photo="{{ asset($barang->foto_barang) ?: asset('icon/' . $barang->jenis_barang . '.png') }}"
                    >
                    Tambah Keranjang
                </button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT LOKAL UNTUK QUANTITY DAN TAMBAH KERANJANG --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gunakan querySelectorAll untuk menangani potensi DOM yang dimuat ulang
    const inputQty = document.getElementById('inputQty');
    const increaseBtn = document.getElementById('increaseQty');
    const decreaseBtn = document.getElementById('decreaseQty');
    const addToCartBtn = document.getElementById('addToCartButton');

    // 1. Logika tombol kuantitas
    if (increaseBtn && decreaseBtn && inputQty) {
        increaseBtn.addEventListener('click', () => {
            let qty = parseInt(inputQty.value) || 1;
            inputQty.value = qty + 1;
        });

        decreaseBtn.addEventListener('click', () => {
            let qty = parseInt(inputQty.value) || 1;
            if (qty > 1) {
                inputQty.value = qty - 1;
            }
        });
    }
});
</script>
@endsection
