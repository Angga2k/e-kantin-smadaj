@extends('layouts.Buyer')

@section('title', 'Beranda')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item text-muted">Beranda /</li>
    </ol>
</nav>

<h1 class="fw-bold">Beranda</h1>

<div class="text-center my-4">
    <h2 class="fw-bold">Apa yang enak di Kantin Sekolah?</h2>
    <p class="text-muted">Temukan aneka menu favorit, pilihan sehat, dan penawaran terbaik langsung dari kantin sekolahmu.</p>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

    <div class="col">
        <div class="card h-100">
            <div style="position: relative;">
                <img src="{{ asset('tes/ayam.png') }}" class="card-img-top" alt="Ayam Goreng" style="height: 200px; object-fit: cover; background-color: #eee;">
                <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
            </div>
            <div class="card-body">
                <p class="stall-name mb-1">Stand A</p>
                <h5 class="card-title">Ayam Goreng</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="price mb-0">Rp. 12.000</p>
                    <a href="#" class="add-button">+</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100">
                <div style="position: relative;">
                <img src="{{ asset('tes/naspad.png') }}" class="card-img-top" alt="Nasi Padang" style="height: 200px; object-fit: cover; background-color: #eee;">
                <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
            </div>
            <div class="card-body">
                <p class="stall-name mb-1">Stand B</p>
                <h5 class="card-title">Nasi Padang</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="price mb-0">Rp. 12.000</p>
                    <a href="#" class="add-button">+</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
            <div class="card h-100">
                <div style="position: relative;">
                <img src="{{ asset('tes/ayam.png') }}" class="card-img-top" alt="Ayam Goreng" style="height: 200px; object-fit: cover; background-color: #eee;">
                <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
            </div>
            <div class="card-body">
                <p class="stall-name mb-1">Stand A</p>
                <h5 class="card-title">Ayam Goreng</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="price mb-0">Rp. 12.000</p>
                    <a href="#" class="add-button">+</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100">
                <div style="position: relative;">
                <img src="{{ asset('tes/naspad.png') }}" class="card-img-top" alt="Nasi Padang" style="height: 200px; object-fit: cover; background-color: #eee;">
                <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
            </div>
            <div class="card-body">
                <p class="stall-name mb-1">Stand B</p>
                <h5 class="card-title">Nasi Padang</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="price mb-0">Rp. 12.000</p>
                    <a href="#" class="add-button">+</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100">
                <div style="position: relative;">
                <img src="{{ asset('tes/esteler.png') }}" class="card-img-top" alt="Es Buah" style="height: 200px; object-fit: cover; background-color: #eee;">
                <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
            </div>
            <div class="card-body">
                <p class="stall-name mb-1">Stand C</p>
                <h5 class="card-title">Es Teler</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="price mb-0">Rp. 8.000</p>
                    <a href="#" class="add-button">+</a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
