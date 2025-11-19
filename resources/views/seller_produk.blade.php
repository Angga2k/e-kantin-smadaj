<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Saya - Kantin Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-green: #00897b;
            --primary-red: #d32f2f;
            --btn-grey: #757575;
            --light-gray-bg: #f0f2f5;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray-bg);
        }

        /* Navbar */
        .navbar { background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,.05); }
        .navbar-brand img { height: 35px; }
        .navbar .nav-link { font-weight: 500; color: #333; }
        .navbar .nav-link.active { font-weight: 600; color: var(--primary-green); }
        .avatar { width: 40px; height: 40px; border-radius: 50%; background-color: #5d4037; }

        /* Product Card */
        .product-card {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }

        .card-img-wrapper {
            position: relative;
            height: 180px;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .rating-badge .bi-star-fill { color: #ffc107; }

        .card-body { padding: 1rem; }
        .product-title { font-weight: 600; font-size: 1rem; margin-bottom: 0.25rem; color: #212529; }
        .product-price { color: #0d6efd; font-weight: 700; font-size: 1.1rem; }

        /* Buttons */
        .btn-add-new {
            background-color: var(--primary-red);
            color: white;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            border: none;
            text-decoration: none; /* Penting agar tidak ada underline */
            display: inline-flex;
            align-items: center;
        }
        .btn-add-new:hover { background-color: #b71c1c; color: white; }

        .btn-action-edit {
            background-color: var(--btn-grey);
            color: white;
            border: none;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .btn-action-edit:hover { background-color: #616161; color: white; }

        .btn-action-delete {
            background-color: var(--primary-red);
            color: white;
            border: none;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .btn-action-delete:hover { background-color: #b71c1c; color: white; }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="{{ asset('logo/smanda.png') }}" alt="Logo" class="me-2"><span class="fw-bold d-none d-lg-inline">SMA NEGERI 2 JEMBER</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">Produk</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Tarik Dana</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">History</a></li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <span class="fw-bold text-success me-3">Rp 240.000</span>
                    <div class="avatar"></div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-4 pb-5">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <div class="mb-3 mb-md-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Produk</li>
                    </ol>
                </nav>
                <h2 class="fw-bold mb-0">Produk</h2>
            </div>

            <a href="/a" class="btn btn-add-new shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
            </a>
        </div>

        <div class="row g-4">

            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="card product-card">
                    <div class="card-img-wrapper">
                        <img src="{{ asset('icon/Makanan.png') }}" class="card-img-top" alt="Ayam Goreng">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i> 5.0</div>
                    </div>
                    <div class="card-body">
                        <h5 class="product-title text-truncate">Ayam Goreng Lengkuas</h5>
                        <p class="product-price mb-4">Rp. 12.000</p>
                        <div class="d-grid gap-2 d-flex">
                            <button class="btn btn-action-edit w-100">Edit</button>
                            <button class="btn btn-action-delete w-100">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="card product-card">
                    <div class="card-img-wrapper">
                        <img src="{{ asset('icon/Makanan.png') }}" class="card-img-top" alt="Nasi Padang">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i> 4.8</div>
                    </div>
                    <div class="card-body">
                        <h5 class="product-title text-truncate">Nasi Padang Komplit</h5>
                        <p class="product-price mb-4">Rp. 15.000</p>
                        <div class="d-grid gap-2 d-flex">
                            <button class="btn btn-action-edit w-100">Edit</button>
                            <button class="btn btn-action-delete w-100">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="card product-card">
                    <div class="card-img-wrapper">
                        <img src="{{ asset('icon/Makanan.png') }}" class="card-img-top" alt="Es Teh">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i> 5.0</div>
                    </div>
                    <div class="card-body">
                        <h5 class="product-title text-truncate">Es Teh Jumbo</h5>
                        <p class="product-price mb-4">Rp. 5.000</p>
                        <div class="d-grid gap-2 d-flex">
                            <button class="btn btn-action-edit w-100">Edit</button>
                            <button class="btn btn-action-delete w-100">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="card product-card">
                    <div class="card-img-wrapper">
                        <img src="{{ asset('icon/Makanan.png') }}" class="card-img-top" alt="Camilan">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i> 4.5</div>
                    </div>
                    <div class="card-body">
                        <h5 class="product-title text-truncate">Tahu Walik (Isi 5)</h5>
                        <p class="product-price mb-4">Rp. 10.000</p>
                        <div class="d-grid gap-2 d-flex">
                            <button class="btn btn-action-edit w-100">Edit</button>
                            <button class="btn btn-action-delete w-100">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="card product-card">
                    <div class="card-img-wrapper">
                        <img src="{{ asset('icon/Makanan.png') }}" class="card-img-top" alt="Ayam Geprek">
                        <div class="rating-badge"><i class="bi bi-star-fill"></i> 5.0</div>
                    </div>
                    <div class="card-body">
                        <h5 class="product-title text-truncate">Ayam Geprek</h5>
                        <p class="product-price mb-4">Rp. 13.000</p>
                        <div class="d-grid gap-2 d-flex">
                            <button class="btn btn-action-edit w-100">Edit</button>
                            <button class="btn btn-action-delete w-100">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
