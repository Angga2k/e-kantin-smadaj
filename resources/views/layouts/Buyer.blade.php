<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kantin SMA Negeri 2 Jember')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* CSS ini sama dengan kode sebelumnya, tidak ada perubahan */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar .d-lg-none {
            flex-grow: 1;
            justify-content: flex-end;
        }
        .navbar .search-box-mobile {
            max-width: 180px;
        }
        .navbar .btn-keranjang,
        .navbar .navbar-toggler {
            flex-shrink: 0;
        }
        .navbar .btn-keranjang .bi-cart-fill {
            vertical-align: middle;
            font-size: 1.1rem;
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
            padding: 20px;
            text-align: center;
            background: linear-gradient(to right, #ff9800, rgb(250, 201, 56));
            /* padding: 80px 20px; */
            position: relative; /* Diperlukan agar pseudo-elements bisa diposisikan */
            overflow: hidden; /* Mencegah ornamen keluar dari banner jika terlalu besar */
        }

        .hero-banner h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 600;
            color: #2c3e50;
            letter-spacing: 2px;
            text-align: center;
            text-transform: uppercase;
            /* margin-top: 10px; */
            position: relative;
            z-index: 1;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        }
        .hero-banner h1::after {
            content: '';
            display: block;
            width: 250px;
            height: 5px;
            background-color: #2c3e50;
            opacity: 0.6;
            margin: 15px auto 0; /* Jarak dari teks */
        }
        .hero-banner h1::before {
            content: '';
            display: block;
            width: 200px;
            height: 5px;
            background-color: #2c3e50;
            opacity: 0.6;
            margin: 0 auto 15px;
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
        /* Detail */
        .product-image {
            width: 100%;
            height: auto;
            border-radius: 15px;
            border: 1px solid #eee;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07);
            object-fit: cover; 
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
            font-size: 0.8em;
            text-align: center;
        }
        .nutrition-info .badge span {
            display: block;
            font-size: 1em;
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
        @include('components.header')
    </header>

    <section class="hero-banner d-none {{ request()->is('detail') ? '' : 'd-md-block' }}">
        <h1>E-Kantin SMAN 2 Jember</h1>
    </section>

    <main class="container my-4">
        @yield('content')
    </main>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        @include('components.keranjang')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
