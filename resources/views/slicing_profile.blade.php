<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Kantin Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }

        /* Navbar Styles (Sama seperti sebelumnya) */
        .navbar-brand img { height: 40px; }
        .navbar-nav .nav-link { font-weight: 500; color: #6c757d; }
        .search-box { position: relative; }
        .search-box .form-control { padding-left: 2.5rem; border-radius: 20px; background-color: #f3f4f6; border: none; }
        .search-box .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6c757d; }
        .btn-keranjang { background-color: #e53935; color: white; border-radius: 10px; font-weight: 500; }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #6c757d; /* Placeholder color */
            margin-left: 1rem;
        }

        /* Profile Card Styles */
        .profile-card {
            background-color: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 2rem;
            position: relative;
        }

        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: -80px; /* Tarik gambar ke atas agar menumpuk */
        }

        .edit-link {
            position: absolute;
            top: 20px;
            right: 20px;
            text-decoration: none;
            color: #6c757d;
            font-weight: 500;
        }
        .edit-link:hover {
            color: #0d6efd;
        }

        /* Form Styles */
        .profile-form .row {
            align-items: center;
        }

        .profile-form .form-label {
            font-weight: 600;
            color: #343a40;
            padding-left: 0;
        }

        .profile-form .form-control[readonly] {
            background-color: white;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-weight: 500;
        }

        /* Penyesuaian untuk mobile */
        @media (max-width: 767.98px) {
            .profile-form .form-label {
                margin-bottom: 0.25rem;
                text-align: left;
            }
            .profile-form .row .col-md-3,
            .profile-form .row .col-md-9 {
                padding-left: 0;
                padding-right: 0;
            }
        }

    </style>
</head>
<body>

    <header class="bg-white shadow-sm sticky-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="#"><img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo" class="me-2"> <span class="fw-bold d-none d-md-inline">SMA NEGERI 2 JEMBER</span></a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Makanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Minuman</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Camilan</a></li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <div class="search-box me-3 d-none d-lg-block">
                       <i class="bi bi-search search-icon"></i>
                       <input class="form-control" type="search" placeholder="Cari...">
                    </div>
                    <button class="btn btn-keranjang" type="button"><i class="bi bi-cart-fill me-1"></i><span class="d-none d-md-inline">Keranjang</span></button>
                    <div class="avatar"></div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb" class="mb-5">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profil</li>
                    </ol>
                </nav>

                <div class="profile-card text-center">
                    <a href="#" class="edit-link"><i class="bi bi-pencil-square me-1"></i>Edit</a>

                    <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Foto Profil" class="profile-picture">

                    <div class="profile-form mt-4 text-start">
                        <div class="row mb-3">
                            <label for="nama" class="col-md-3 col-form-label">Nama</label>
                            <div class="col-md-9">
                                <input type="text" readonly class="form-control" id="nama" value="SIAPA SAJA">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nisn" class="col-md-3 col-form-label">NISN</label>
                            <div class="col-md-9">
                                <input type="text" readonly class="form-control" id="nisn" value="25148411515051">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ttl" class="col-md-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-md-9">
                                <input type="text" readonly class="form-control" id="ttl" value="Jember 13 Januari 2007">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="gender" class="col-md-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-md-9">
                                <input type="text" readonly class="form-control" id="gender" value="Laki Laki">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
