<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Ayam Goreng - Kantin SMA Negeri 2 Jember</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        /* Style yang sudah ada dari halaman sebelumnya */
        .navbar-brand img { height: 40px; }
        .navbar-nav .nav-link { font-weight: 500; color: #6c757d; }
        .navbar-nav .nav-link.active { color: #000; font-weight: 600; }
        .search-box { position: relative; }
        .search-box .form-control { padding-left: 2.5rem; border-radius: 20px; background-color: #f3f4f6; border: none; }
        .search-box .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6c757d; }
        .btn-keranjang { background-color: #e53935; color: white; border-radius: 10px; font-weight: 500; }
        .btn-keranjang:hover { background-color: #c62828; color: white; }
        .breadcrumb a { text-decoration: none; color: #6c757d; }
        .breadcrumb a:hover { color: #0d6efd; }

        /* BARU: Style untuk halaman detail produk */
        .product-image {
            width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .rating-stars .bi-star-fill {
            color: #ffc107;
        }
        .product-price {
            color: #0d6efd;
        }
        .nutrition-info .badge {
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            color: #212529;
            background-color: transparent;
            font-weight: 500;
            text-align: center;
        }
        .nutrition-info .badge span {
            display: block;
            font-size: 0.8em;
            color: #6c757d;
        }
        .variant-options .btn-check:checked+.btn {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .quantity-selector {
            display: flex;
            align-items: center;
        }
        .quantity-selector .btn {
            border-radius: 50%;
            width: 38px;
            height: 38px;
            font-weight: 600;
        }
        .quantity-selector .form-control {
            width: 50px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 1.2rem;
        }
        .quantity-selector .form-control:focus {
            box-shadow: none;
        }
        .btn-add-to-cart {
            background-color: #00897b; /* Warna hijau toska */
            color: white;
            font-weight: 600;
        }
        .btn-add-to-cart:hover {
            background-color: #00695c;
            color: white;
        }
    </style>
</head>
<body>

    <header class="bg-white shadow-sm sticky-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo SMA 2 Jember" class="me-2">
                    <span class="fw-bold">SMA NEGERI 2 JEMBER</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">Makanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Minuman</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Camilan</a></li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <div class="search-box me-3">
                           <i class="bi bi-search search-icon"></i>
                           <input class="form-control" type="search" placeholder="Cari...">
                        </div>
                        <a href="#" class="btn btn-keranjang"><i class="bi bi-cart-fill me-2"></i>Keranjang</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item"><a href="#">Makanan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ayam Goreng</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-6">
                <img src="" alt="Ayam Goreng Lengkuas" class="product-image" style="background-color: #eee;">
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
                    <div class="d-flex gap-3 nutrition-info">
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
                    <h6 class="fw-bold mb-3">Catatan</h6>
                    <input type="text" class="form-control" placeholder="Tulis disini">
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
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
