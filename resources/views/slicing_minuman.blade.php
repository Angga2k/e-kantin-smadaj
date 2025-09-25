<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minuman - Kantin SMA Negeri 2 Jember</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS ini sama dengan kode sebelumnya, tidak ada perubahan */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            color: #6c757d;
        }
        .navbar-nav .nav-link.active {
            color: #000;
            font-weight: 600;
        }
        .search-box {
            position: relative;
        }
        .search-box .form-control {
            padding-left: 2.5rem;
            border-radius: 20px;
            background-color: #f3f4f6;
            border: none;
        }
        .search-box .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .btn-keranjang {
            background-color: #e53935;
            color: white;
            border-radius: 10px;
            font-weight: 500;
        }
        .btn-keranjang:hover {
            background-color: #c62828;
            color: white;
        }
        .hero-banner {
            background-color: #ff9800;
            padding: 2rem 1rem;
            text-align: center;
        }
        .hero-banner img {
            max-width: 250px;
            height: auto;
        }
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .rating-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ffc107;
            color: #000;
            font-weight: 600;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.9em;
            display: flex;
            align-items: center;
        }
        .rating-badge .bi-star-fill {
            margin-right: 5px;
        }
        .card .card-title {
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.25rem;
        }
        .card .stall-name {
            font-size: 0.85em;
            color: #6c757d;
        }
        .card .price {
            font-weight: 700;
            color: #0d6efd;
            font-size: 1.1em;
        }
        .add-button {
            background-color: #0d6efd;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            text-decoration: none;
        }
        .add-button:hover {
            background-color: #0a58ca;
            color: white;
        }
        .breadcrumb a {
            text-decoration: none;
            color: #6c757d;
        }
        .breadcrumb a:hover {
            color: #0d6efd;
        }
        /* Style untuk Offcanvas Keranjang */
        .offcanvas {
            display: flex;
            flex-direction: column;
        }
        .offcanvas-body {
            flex-grow: 1;
            overflow-y: auto;
        }
        .cart-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 2px dashed #e0e0e0;
            border-radius: 15px;
            margin-bottom: 1rem;
        }
        .cart-item img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 1rem;
        }
        .cart-item-details {
            flex-grow: 1;
        }
        .cart-item-details h6 {
            margin-bottom: 0;
            font-weight: 600;
        }
        .cart-item-details .text-muted {
            font-size: 0.9em;
        }
        .cart-item-price {
            color: #0d6efd;
            font-weight: 600;
            margin-top: 0.25rem;
        }
        .cart-quantity-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .cart-quantity-selector .btn {
            width: 28px;
            height: 28px;
            padding: 0;
            border-radius: 50%;
            background-color: #f3f4f6;
            border: none;
            color: #000;
        }
        .schedule-section .input-group-text {
            background-color: transparent;
            border-right: 0;
        }
        .schedule-section .form-control,
        .schedule-section .form-select {
            border-left: 0;
        }
        .payment-method {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .payment-method .icon-bg {
            background-color: #e0e0e0;
            padding: 0.5rem;
            border-radius: 8px;
            margin-right: 1rem;
        }
        .payment-method .text-danger {
            font-size: 0.9em;
        }
        .offcanvas-footer {
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            background-color: #fff;
        }
        .btn-confirm {
            background-color: #e53935;
            color: white;
            font-weight: 600;
            padding: 0.75rem;
        }
        .btn-confirm:hover {
            background-color: #c62828;
            color: white;
        }
    </style>
</head>
<body>

    <header class="bg-white shadow-sm sticky-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo" class="me-2">
                    <span class="fw-bold d-none d-md-inline">SMA NEGERI 2 JEMBER</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/makanan">Makanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/minuman">Minuman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/camilan">Camilan</a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <div class="search-box me-3">
                           <i class="bi bi-search search-icon"></i>
                           <input class="form-control" type="search" placeholder="Cari...">
                        </div>
                        {{-- <button class="btn btn-keranjang" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                            <i class="bi bi-cart-fill me-2"></i>Keranjang
                        </button> --}}
                    </div>
                </div>
                <button class="btn btn-keranjang" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                            <i class="bi bi-cart-fill me-2"></i>Keranjang
                        </button>
            </div>
        </nav>
    </header>

    <section class="hero-banner">
        <img src="https://i.ibb.co/6PqjXfG/eating-illustration.png" alt="Ilustrasi orang makan">
    </section>

    <main class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Minuman</li>
            </ol>
        </nav>

        <h1 class="fw-bold">Minuman</h1>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-3">

            <div class="col">
                <div class="card h-100">
                    <div style="position: relative;">
                        <img src="" class="card-img-top" alt="Es Teler" style="height: 200px; object-fit: cover; background-color: #eee;">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
                    </div>
                    <div class="card-body">
                        <p class="stall-name mb-1">Stand C</p>
                        <h5 class="card-title">Es Teler</h5>
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
                        <img src="" class="card-img-top" alt="Es Teler" style="height: 200px; object-fit: cover; background-color: #eee;">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
                    </div>
                    <div class="card-body">
                        <p class="stall-name mb-1">Stand C</p>
                        <h5 class="card-title">Es Teler</h5>
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
                        <img src="" class="card-img-top" alt="Es Teler" style="height: 200px; object-fit: cover; background-color: #eee;">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
                    </div>
                    <div class="card-body">
                        <p class="stall-name mb-1">Stand C</p>
                        <h5 class="card-title">Es Teler</h5>
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
                        <img src="" class="card-img-top" alt="Es Teler" style="height: 200px; object-fit: cover; background-color: #eee;">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
                    </div>
                    <div class="card-body">
                        <p class="stall-name mb-1">Stand C</p>
                        <h5 class="card-title">Es Teler</h5>
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
                        <img src="" class="card-img-top" alt="Es Teler" style="height: 200px; object-fit: cover; background-color: #eee;">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
                    </div>
                    <div class="card-body">
                        <p class="stall-name mb-1">Stand C</p>
                        <h5 class="card-title">Es Teler</h5>
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
                        <img src="" class="card-img-top" alt="Es Teler" style="height: 200px; object-fit: cover; background-color: #eee;">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
                    </div>
                    <div class="card-body">
                        <p class="stall-name mb-1">Stand C</p>
                        <h5 class="card-title">Es Teler</h5>
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
                        <img src="" class="card-img-top" alt="Es Teler" style="height: 200px; object-fit: cover; background-color: #eee;">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
                    </div>
                    <div class="card-body">
                        <p class="stall-name mb-1">Stand C</p>
                        <h5 class="card-title">Es Teler</h5>
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
                        <img src="" class="card-img-top" alt="Es Teler" style="height: 200px; object-fit: cover; background-color: #eee;">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i>5.0</div>
                    </div>
                    <div class="card-body">
                        <p class="stall-name mb-1">Stand C</p>
                        <h5 class="card-title">Es Teler</h5>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="price mb-0">Rp. 12.000</p>
                            <a href="#" class="add-button">+</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
      <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="cartOffcanvasLabel">Keranjang</h5>
        <span class="text-muted">3 barang</span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>

      <div class="offcanvas-body">

        <div class="cart-item">
            <img src="" alt="Ayam Goreng" style="background-color: #eee;">
            <div class="cart-item-details">
                <h6>Ayam Goreng</h6>
                <p class="text-muted mb-1">380gr</p>
                <p class="cart-item-price">Rp. 12.000</p>
            </div>
            <div class="cart-quantity-selector">
                <button class="btn btn-light btn-sm">+</button>
                <span class="fw-bold">1</span>
                <button class="btn btn-light btn-sm">-</button>
            </div>
        </div>

        <div class="cart-item">
            <img src="" alt="Ayam Goreng" style="background-color: #eee;">
            <div class="cart-item-details">
                <h6>Ayam Goreng</h6>
                <p class="text-muted mb-1">380gr</p>
                <p class="cart-item-price">Rp. 12.000</p>
            </div>
            <div class="cart-quantity-selector">
                <button class="btn btn-light btn-sm">+</button>
                <span class="fw-bold">1</span>
                <button class="btn btn-light btn-sm">-</button>
            </div>
        </div>

        <div class="cart-item">
            <img src="" alt="Ayam Goreng" style="background-color: #eee;">
            <div class="cart-item-details">
                <h6>Ayam Goreng</h6>
                <p class="text-muted mb-1">380gr</p>
                <p class="cart-item-price">Rp. 12.000</p>
            </div>
            <div class="cart-quantity-selector">
                <button class="btn btn-light btn-sm">+</button>
                <span class="fw-bold">1</span>
                <button class="btn btn-light btn-sm">-</button>
            </div>
        </div>

        <div class="mt-4 schedule-section">
            <h6 class="fw-bold">JADWAL PENGAMBILAN</h6>
            <div class="input-group my-3">
                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                <input type="text" class="form-control" placeholder="dd/mm/yyyy">
            </div>
            <div class="input-group mb-3">
                 <span class="input-group-text"><i class="bi bi-clock"></i></span>
                <select class="form-select">
                    <option selected>Istirahat 1</option>
                    <option value="1">Istirahat 2</option>
                    <option value="2">Pulang Sekolah</option>
                </select>
            </div>
        </div>
      </div>

      <div class="offcanvas-footer">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted mb-0">TOTAL</h6>
            <h5 class="fw-bold mb-0">Rp. 36.000</h5>
        </div>
        <div class="payment-method mb-3">
            <div class="icon-bg"><i class="bi bi-three-dots"></i></div>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold small">Metode Pembayaran</h6>
                <p class="text-danger mb-0">Pilih Metode Pembayaran</p>
            </div>
            <i class="bi bi-chevron-right"></i>
        </div>
        <button class="btn btn-confirm w-100">Konfirmasi Pembelian</button>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
