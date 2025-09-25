<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Kantin Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --theme-color: #00897b;
            --primary-blue: #0d6efd;
            --danger-red: #e53935;
        }

        body, html {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: white;
        }

        /* =========================================== */
        /* == GAYA TAMPILAN MOBILE (DEFAULT) == */
        /* =========================================== */
        .mobile-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background-color: white;
            border-bottom: 1px solid #dee2e6;
        }
        .mobile-navbar .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.1rem;
            color: #212529;
            text-decoration: none;
        }
        .mobile-navbar .navbar-brand img {
            height: 30px;
            margin-right: 0.5rem;
        }
        .mobile-navbar .nav-link {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--danger-red);
            text-decoration: none;
        }

        .brand-header {
            background-color: var(--theme-color);
            color: white;
            padding: 2rem 1rem 3rem 1rem;
            text-align: center;
            border-radius: 0 0 25px 25px;
        }
        .brand-header img {
            max-width: 80px;
            margin-bottom: 1rem;
        }
        .brand-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
        }
        .brand-header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .form-section {
            padding: 2rem 1.5rem;
            background-color: white;
        }
        .form-section h3 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-submit {
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Sembunyikan navbar desktop di mobile */
        .desktop-navbar {
            display: none;
        }

        /* ========================================================= */
        /* == GAYA TAMPILAN DESKTOP (Layar > 992px) == */
        /* ========================================================= */
        @media (min-width: 992px) {
            .mobile-navbar {
                display: none;
            }
            .desktop-navbar {
                display: block; /* Tampilkan navbar desktop */
            }

            main {
                display: flex;
                align-items: center;
                min-height: 100vh;
                position: relative;
                background-image: url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto-format&fit=crop');
                background-size: cover;
                background-position: center;
            }
            main::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background-color: var(--theme-color);
                opacity: 0.8;
            }

            .brand-header, .form-section {
                position: relative;
                z-index: 2;
                flex: 1; /* Buat kedua bagian memenuhi space */
                padding: 0 2rem;
            }

            .brand-header {
                background: none; /* Hapus background hijau di desktop */
                border-radius: 0;
            }
            .brand-header img {
                max-width: 150px;
            }
            .brand-header h1 {
                font-size: 3.5rem;
            }
            .brand-header p {
                font-size: 1.25rem;
            }

            .form-section {
                display: flex;
                justify-content: flex-start;
                background: none;
            }
            .form-card {
                background-color: white;
                border-radius: 20px;
                padding: 2.5rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 450px; /* Batasi lebar form */
            }
        }
    </style>
</head>
<body>

    <header class="desktop-navbar sticky-top">
        <nav class="navbar bg-white border-bottom shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items: center" href="#">
                    <img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo" class="me-3" style="height: 35px;">
                    <span class="fw-bold fs-5">Daftar</span>
                </a>
                <span class="navbar-text">
                    <a href="#" style="color: var(--danger-red); text-decoration: none; font-weight: 500;">Butuh bantuan?</a>
                </span>
            </div>
        </nav>
    </header>

    <main>
        <section class="brand-header">
            <img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo Kantin Digital">
            <h1>KANTIN DIGITAL</h1>
            <p>SMA NEGERI 2 JEMBER</p>
        </section>

        <section class="form-section">
            <div class="form-card">
                <div class="mobile-navbar">
                    <a class="navbar-brand" href="#">
                        <img class="d-none d-md-inline" src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo">
                        <span>Daftar</span>
                    </a>
                    <a href="#" class="nav-link">Butuh bantuan?</a>
                </div>

                <h3 class="mt-4">Daftar Akun</h3>
                <form>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama Anda">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-submit">Buat Akun</button>
                </form>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
