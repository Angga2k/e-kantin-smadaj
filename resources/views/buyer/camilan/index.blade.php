@extends('layouts.Buyer')

@section('title', 'Camilan')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Camilan</li>
    </ol>
</nav>

<h1 class="fw-bold">Camilan</h1>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-3">

    <div class="col">
        <div class="card h-100">
            <div style="position: relative;">
                <img src="{{ asset('tes/kripik.jpg') }}" class="card-img-top" alt="Camilan A" style="height: 200px; object-fit: cover; background-color: #eee;">
                <div class="rating-badge"><i class="bi bi-star-fill"></i>4.9</div>
            </div>
            <div class="card-body">
                <p class="stall-name mb-1">Stand D</p>
                <h5 class="card-title">Keripik Pedas</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="price mb-0">Rp. 5.000</p>
                    <a href="#" class="add-button">+</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100">
                <div style="position: relative;">
                <img src="{{ asset('tes/gorengan.jpg') }}" class="card-img-top" alt="Camilan B" style="height: 200px; object-fit: cover; background-color: #eee;">
                <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
            </div>
            <div class="card-body">
                <p class="stall-name mb-1">Stand E</p>
                <h5 class="card-title">Gorengan</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="price mb-0">Rp. 2.000</p>
                    <a href="#" class="add-button">+</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
            <div class="card h-100">
                <div style="position: relative;">
                <img src="{{ asset('tes/somay.jpg') }}" class="card-img-top" alt="Camilan C" style="height: 200px; object-fit: cover; background-color: #eee;">
                <div class="rating-badge"><i class="bi bi-star-fill"></i>4.8</div>
            </div>
            <div class="card-body">
                <p class="stall-name mb-1">Stand D</p>
                <h5 class="card-title">Siomay</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="price mb-0">Rp. 10.000</p>
                    <a href="#" class="add-button">+</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100">
                <div style="position: relative;">
                <img src="{{ asset('tes/batagor.jpg') }}" class="card-img-top" alt="Camilan D" style="height: 200px; object-fit: cover; background-color: #eee;">
                <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
            </div>
            <div class="card-body">
                <p class="stall-name mb-1">Stand E</p>
                <h5 class="card-title">Batagor</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="price mb-0">Rp. 10.000</p>
                    <a href="#" class="add-button">+</a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
