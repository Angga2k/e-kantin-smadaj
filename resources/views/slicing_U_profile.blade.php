<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Kantin Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }

        /* Navbar Styles */
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
            background-color: #6c757d;
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

        .profile-picture-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            margin: -80px auto 0 auto;
        }

        .profile-picture {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .upload-button {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 35px;
            height: 35px;
            background-color: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .upload-button:hover {
            background-color: #0a58ca;
        }
        .upload-button input[type="file"] {
            display: none;
        }

        /* Form Styles */
        .profile-form .form-label {
            font-weight: 600;
            color: #343a40;
        }

        .profile-form .form-control,
        .profile-form .form-select {
            border-radius: 8px;
            font-weight: 500;
        }

        @media (max-width: 767.98px) {
            .profile-form .form-label {
                margin-bottom: 0.25rem;
                text-align: left;
            }
        }
    </style>
</head>
<body>

    <header class="bg-white shadow-sm sticky-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="#"><img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo" class="me-2"> <span class="fw-bold d-none d-md-inline">SMA NEGERI 2 JEMBER</span></a>
                <div class="collapse navbar-collapse"><ul class="navbar-nav mx-auto"><li class="nav-item"><a class="nav-link" href="#">Beranda</a></li><li class="nav-item"><a class="nav-link" href="#">Makanan</a></li><li class="nav-item"><a class="nav-link" href="#">Minuman</a></li><li class="nav-item"><a class="nav-link" href="#">Camilan</a></li></ul></div>
                <div class="d-flex align-items-center">
                    <div class="search-box me-3 d-none d-lg-block"><i class="bi bi-search search-icon"></i><input class="form-control" type="search" placeholder="Cari..."></div>
                    <button class="btn btn-keranjang" type="button"><i class="bi bi-cart-fill me-1"></i><span class="d-none d-md-inline">Keranjang</span></button>
                    <div class="avatar"></div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Profil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>

                <div class="profile-card text-center">

                    <div class="profile-picture-wrapper">
                        <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Foto Profil" class="profile-picture" id="profile-image">
                        <label for="file-upload" class="upload-button">
                            <i class="bi bi-camera-fill"></i>
                            <input type="file" id="file-upload" accept="image/*">
                        </label>
                    </div>

                    <form class="profile-form mt-4 text-start">
                        <div class="row mb-3 align-items-center">
                            <label for="nama" class="col-md-3 col-form-label">Nama</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="nama" value="SIAPA SAJA">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="nisn" class="col-md-3 col-form-label">NISN</label>
                            <div class="col-md-9">
                                <input type="text" readonly class="form-control-plaintext" id="nisn" value="25148411515051">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="ttl" class="col-md-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" id="ttl" value="2007-01-13">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="gender" class="col-md-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-md-9">
                                <select class="form-select" id="gender">
                                    <option>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" selected>Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script sederhana untuk preview gambar saat diupload
        document.getElementById('file-upload').onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                document.getElementById('profile-image').src = URL.createObjectURL(file);
            }
        };
    </script>
</body>
</html>
