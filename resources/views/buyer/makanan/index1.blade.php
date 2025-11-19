@extends('layouts.Buyer')

@section('title', 'Makanan')

@section('content')
<main class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Makanan</li>
            </ol>
        </nav>

        <h1 class="fw-bold">Makanan</h1>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-3">

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
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>


@endsection
