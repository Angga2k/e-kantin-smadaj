@extends('layouts.Buyer')

@section('title', 'Beranda')

@section('content')
<style>
    @media (max-width: 575.98px) {
        .card-img-top {
            height: 140px !important;
        }

        .card-body {
            padding: 0.75rem !important;
        }

        .card .card-title {
            font-size: 0.95rem;
            margin-bottom: 0.15rem;
        }

        .card .stall-name {
            font-size: 0.75rem;
        }

        .card .price {
            font-size: 0.95rem;
        }

        .rating-badge {
            padding: 2px 8px !important;
            font-size: 0.75rem !important;
        }
    }
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item text-muted">Beranda /</li>
    </ol>
</nav>

<h1 class="fw-bold">Beranda</h1>

<div id="notFoundMessage" class="text-center my-5 d-none">
    <p class="lead text-muted">Tidak ada nama item yang sesuai...</p>
</div>

<div class="text-center my-4 early">
    <h2 class="fw-bold">Apa yang enak di Kantin Sekolah?</h2>
    <p class="text-muted">Temukan aneka menu favorit, pilihan sehat, dan penawaran terbaik langsung dari kantin sekolahmu.</p>
</div>

@if(count($barang) == 0)
    <div id="notFoundMessage" class="text-center my-5">
        <p class="lead text-muted">Tidak ada makanan yang tersedia saat ini...</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-3 mb-5">
        @foreach($barang as $item)
            <div class="col">
                @php
                        if ($item->jenis_barang === 'Makanan') {
                            $cardClass = 'card1';
                        } elseif ($item->jenis_barang === 'Minuman') {
                            $cardClass = 'card2';
                        } elseif ($item->jenis_barang === 'Camilan') {
                            $cardClass = 'card3';
                        }
                @endphp
                <a href="{{ route('detail.index', $item->id_barang) }}" class="text-decoration-none text-dark">
                    <div class="card {{ $cardClass }} h-100">
                        <div style="position: relative;">
                            @if($item->foto_barang)
                                <img src="{{ asset($item->foto_barang) }}" class="card-img-top" alt="{{ $item->nama_barang }}" style="height: 200px; object-fit: cover; background-color: #eee;">
                            @else
                                <img src="{{ asset('icon\\'. $item->jenis_barang . '.png') }}" class="card-img-top" alt="{{ $item->nama_barang }}" style="height: 200px; object-fit: contain; background-color: #eee;">
                            @endif

                            @php
                                $avgRating = $item->ratingUlasan->avg('rating') ?? 0;
                            @endphp
                            @if($avgRating > 0)
                                <div class="rating-badge"><i class="bi bi-star-fill"></i>{{ number_format($avgRating, 1) }}</div>
                                @else
                                <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
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
                </a>
            </div>
        @endforeach
    </div>
@endif


@endsection
