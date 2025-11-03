@extends('layouts.Buyer')

@section('title', 'Makanan')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Makanan</li>
    </ol>
</nav>

<h1 class="fw-bold">Makanan</h1>

@if(count($barang) == 0)
    <div id="notFoundMessage" class="text-center my-5">
        <p class="lead text-muted">Tidak ada makanan yang tersedia saat ini...</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-3">
        @foreach($barang as $item)
            <div class="col">
                <div class="card card1 h-100">
                    <div style="position: relative;">
                        @if($item->foto_barang)
                            <img src="{{ asset($item->foto_barang) }}" class="card-img-top" alt="{{ $item->nama_barang }}" style="height: 200px; object-fit: cover; background-color: #eee;">
                        @else
                            <img src="{{ asset('tes/ayam.png') }}" class="card-img-top" alt="{{ $item->nama_barang }}" style="height: 200px; object-fit: cover; background-color: #eee;">
                        @endif

                        @php
                            $avgRating = $item->ratingUlasan->avg('rating') ?? 0;
                        @endphp

                        @if($avgRating > 0)
                            <div class="rating-badge"><i class="bi bi-star-fill"></i>{{ number_format($avgRating, 1) }}</div>
                        @endif
                    </div>
                    <div class="card-body">
                        <p class="stall-name mb-1">{{ $item->penjual->nama_toko ?? 'Stand' }}</p>
                        <h5 class="card-title">{{ $item->nama_barang }}</h5>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="price mb-0">Rp. {{ number_format($item->harga, 0, ',', '.') }}</p>
                            {{-- <a href="#" class="add-button">+</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif


@endsection
