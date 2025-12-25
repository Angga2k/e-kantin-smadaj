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
                    $cardClass = '';
                    if ($item->jenis_barang === 'Makanan') {
                        $cardClass = 'card1';
                    } elseif ($item->jenis_barang === 'Minuman') {
                        $cardClass = 'card2';
                    } elseif ($item->jenis_barang === 'Camilan') {
                        $cardClass = 'card3';
                    }
                @endphp
                <a href="{{ route('detail.index', $item->id_barang) }}" class="text-decoration-none text-dark">
                    <div class="card {{ $cardClass }} h-100 border-0 shadow-sm">
                        {{-- Bagian Gambar Barang --}}
                        <div style="position: relative;">
                            @if($item->foto_barang)
                                <img src="{{ asset($item->foto_barang) }}" class="card-img-top" alt="{{ $item->nama_barang }}" style="height: 200px; object-fit: cover; background-color: #eee;">
                            @else
                                <img src="{{ asset('icon/'. $item->jenis_barang . '.png') }}" class="card-img-top" alt="{{ $item->nama_barang }}" style="height: 200px; object-fit: contain; background-color: #eee;">
                            @endif

                            {{-- Badge Rating --}}
                            @php
                                $avgRating = $item->ratingUlasan->avg('rating') ?? 0;
                            @endphp
                            @if($avgRating > 0)
                                <div class="rating-badge"><i class="bi bi-star-fill"></i>{{ number_format($avgRating, 1) }}</div>
                            @else
                                <div class="rating-badge"><i class="bi bi-star-fill"></i>0</div>
                            @endif
                        </div>

                        {{-- Bagian Body Card --}}
                        <div class="card-body d-flex flex-column">
                            {{-- Container Foto Profil Toko + Teks --}}
                            <div class="d-flex align-items-center mb-2">
                                {{-- Foto Profil Toko --}}
                                <div class="flex-shrink-0 me-2">
                                    <img src="{{ asset($item->penjual->foto_profile ?? 'icon/toko.png') }}" 
                                         alt="Toko" 
                                         class="rounded-circle border" 
                                         style="width: 40px; height: 40px; object-fit: cover;"
                                         onerror="this.src='{{ asset('asset/default-profile.png') }}'">
                                </div>
                                
                                {{-- Nama Toko & Nama Barang --}}
                                <div class="overflow-hidden">
                                    <p class="stall-name mb-0 text-muted small text-truncate" style="line-height: 1.2;">
                                        {{ $item->penjual->nama_toko ?? 'Stand' }}
                                    </p>
                                    <h6 class="card-title mb-0 text-truncate fw-bold" style="font-size: 1rem;">
                                        {{ $item->nama_barang }}
                                    </h6>
                                </div>
                            </div>

                            {{-- Harga (ditaruh di bawah dengan margin-top auto agar rapi rata bawah) --}}
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                <p class="price mb-0 fw-bold text-primary">Rp. {{ number_format($item->harga, 0, ',', '.') }}</p>
                                {{-- Tombol Add (Opsional) --}}
                                {{-- <button class="btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-plus"></i></button> --}}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif


@endsection
