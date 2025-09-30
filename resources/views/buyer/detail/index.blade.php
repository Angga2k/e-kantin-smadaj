@extends('layouts.Buyer')

@section('title', 'Detail')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item"><a href="#">Makanan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ayam Goreng</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-lg-6">
            <img src="{{ asset('tes/ayam.png') }}" alt="Ayam Goreng Lengkuas" class="product-image">
        </div>

        <div class="col-lg-6">
            <p class="text-muted mb-1">Stand A</p>
            <h1 class="display-5 fw-bold">Ayam Goreng</h1>
            <p class="mt-3">Ayam goreng berbumbu lengkuas khas, gurih, renyah, dan makin nikmat dengan sambal merah yang menggugah selera.</p>

            <div class="d-flex align-items-center my-3 rating-stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <span class="ms-2 fw-bold">5.0</span>
                <span class="ms-1 text-muted">(25)</span>
            </div>

            <p class="display-4 fw-bold product-price">Rp. 12.000</p>

            <hr>

            <div class="my-4">
                <h6 class="fw-bold mb-3">Kandungan</h6>
                <div class="d-flex flex-wrap gap-3 nutrition-info">
                    <div class="badge">230<span>kcal</span></div>
                    <div class="badge">45<span>carbo</span></div>
                    <div class="badge">21<span>protein</span></div>
                    <div class="badge">230<span>kcal</span></div>
                    <div class="badge">45<span>carbo</span></div>
                    <div class="badge">21<span>protein</span></div>
                </div>
            </div>

            <div class="my-4">
                <h6 class="fw-bold mb-3">Varian</h6>
                <div class="variant-options">
                    <input type="radio" class="btn-check" name="varian" id="varian-pedas" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary rounded-pill" for="varian-pedas">Pedas</label>

                    <input type="radio" class="btn-check" name="varian" id="varian-tidak-pedas" autocomplete="off">
                    <label class="btn btn-outline-secondary rounded-pill" for="varian-tidak-pedas">Tidak Pedas</label>
                </div>
            </div>

            <div class="my-4">
                <label for="catatan" class="form-label fw-bold mb-2">Catatan</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-pencil-square"></i> </span>
                    <textarea class="form-control" id="catatan" rows="3" placeholder="Tulis catatan untuk penjual (opsional)"></textarea>
                </div>
            </div>

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
