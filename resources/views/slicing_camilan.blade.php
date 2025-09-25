<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camilan - Kantin SMA Negeri 2 Jember</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS ini sama dengan kode sebelumnya */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .dashboard-bar {
            background-color: #424242;
            color: white;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.9em;
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
                        <li class="nav-item">
                            <a class="nav-link" href="/">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/makanan">Makanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/minuman">Minuman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/camilan">Camilan</a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <div class="search-box me-3">
                           <i class="bi bi-search search-icon"></i>
                           <input class="form-control" type="search" placeholder="Cari...">
                        </div>
                        <a href="#" class="btn btn-keranjang">
                            <i class="bi bi-cart-fill me-2"></i>Keranjang
                        </a>
                    </div>
                </div>
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
                <li class="breadcrumb-item active" aria-current="page">Camilan</li>
            </ol>
        </nav>

        <h1 class="fw-bold">Camilan</h1>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-3">

            <div class="col">
                <div class="card h-100">
                    <div style="position: relative;">
                        <img src="" class="card-img-top" alt="Camilan A" style="height: 200px; object-fit: cover; background-color: #eee;">
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
                        <img src="" class="card-img-top" alt="Camilan B" style="height: 200px; object-fit: cover; background-color: #eee;">
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
                        <img src="" class="card-img-top" alt="Camilan C" style="height: 200px; object-fit: cover; background-color: #eee;">
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
                        <img src="" class="card-img-top" alt="Camilan D" style="height: 200px; object-fit: cover; background-color: #eee;">
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
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
