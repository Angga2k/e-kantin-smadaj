<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa - Kantin Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            font-size: 14px; /* Base font size diperkecil agar proporsional */
        }

        /* =========================================== */
        /* == GAYA TAMPILAN MOBILE (DEFAULT) == */
        /* =========================================== */
        .mobile-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 1.2rem;
            background-color: white;
            border-bottom: 1px solid #dee2e6;
        }
        .mobile-navbar .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1rem;
            color: #212529;
            text-decoration: none;
        }
        .mobile-navbar .navbar-brand img {
            height: 25px;
            margin-right: 0.5rem;
        }
        .mobile-navbar .nav-link {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--theme-color);
            text-decoration: none;
        }

        .brand-header {
            background-color: var(--theme-color);
            color: white;
            padding: 1.5rem 1rem 2.5rem 1rem;
            text-align: center;
            border-radius: 0 0 20px 20px;
        }
        .brand-header img {
            max-width: 60px; /* Logo mobile diperkecil */
            margin-bottom: 0.8rem;
        }
        .brand-header h1 {
            font-size: 1.4rem; /* Judul mobile lebih proporsional */
            font-weight: 700;
            margin-bottom: 0.2rem;
        }
        .brand-header p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .form-section {
            padding: 1.5rem 1rem;
            background-color: white;
        }
        .form-section h3 {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 1.2rem;
            text-align: center;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
            font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.3rem;
        }
        .btn-submit {
            padding: 0.6rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            background-color: var(--theme-color);
            border-color: var(--theme-color);
            color: white;
            margin-top: 0.5rem;
        }
        .btn-submit:hover {
            background-color: #00695c;
            border-color: #00695c;
            color: white;
        }

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
                display: block;
            }
            
            .navbar-brand img {
                height: 30px !important;
            }
            .navbar-brand span {
                font-size: 1rem !important;
            }

            main {
                display: flex;
                align-items: center;
                min-height: 100vh;
                position: relative;
                background-image: url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto-format&fit=crop');
                background-size: cover;
                background-position: center;
                padding: 2rem 0; /* Tambah padding atas bawah agar tidak mentok */
            }
            main::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background-color: var(--theme-color);
                opacity: 0.85;
            }

            .brand-header, .form-section {
                position: relative;
                z-index: 2;
                flex: 1;
                padding: 0 3rem;
            }

            .brand-header {
                background: none;
                border-radius: 0;
                text-align: left;
                padding-left: 6rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .brand-header img {
                max-width: 100px; /* Logo desktop lebih proporsional */
                margin-bottom: 1.5rem;
            }
            .brand-header h1 {
                font-size: 2.5rem; /* Judul tidak terlalu raksasa */
                margin-top: 0;
                line-height: 1.2;
            }
            .brand-header p {
                font-size: 1.1rem;
                font-weight: 300;
                letter-spacing: 1px;
            }

            .form-section {
                display: flex;
                justify-content: flex-start;
                background: none;
            }
            .form-card {
                background-color: white;
                border-radius: 16px; /* Radius lebih halus */
                padding: 2rem 2.5rem; /* Padding dalam card */
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
                width: 100%;
                max-width: 420px; /* Lebar card dibatasi agar ramping */
            }
            
            .form-section h3 {
                font-size: 1.5rem;
                text-align: left; /* Judul form rata kiri di desktop */
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <header class="desktop-navbar sticky-top">
        <nav class="navbar bg-white border-bottom shadow-sm py-2">
            <div class="container">
                <a class="navbar-brand d-flex align-items: center" href="#">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="me-2">
                    <span class="fw-bold text-dark">Registrasi Siswa</span>
                </a>
                <span class="navbar-text" style="font-size: 0.9rem;">
                    Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--theme-color); text-decoration: none; font-weight: 600;">Masuk disini</a>
                </span>
            </div>
        </nav>
    </header>

    <main>
        <section class="brand-header">
            <div>
                <img src="{{ asset('asset/logo.png') }}" alt="Logo Kantin Digital">
                <h1>BERGABUNG<br>BERSAMA</h1>
                <p>E-KANTIN SMA NEGERI 2 JEMBER</p>
            </div>
        </section>

        <section class="form-section">
            <div class="form-card">
                <div class="mobile-navbar mb-3" style="border-bottom: none; padding: 0;">
                    <a class="navbar-brand" href="#">
                        <img class="d-none d-md-inline" src="{{ asset('asset/logo.png') }}" alt="Logo">
                        <span>Daftar Siswa</span>
                    </a>
                    <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                </div>

                <h3>Buat Akun</h3>
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <input type="hidden" name="role" value="siswa">

                    <div class="mb-2">
                        <label for="name" class="form-label fw-bold text-muted">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="nisn" class="form-label fw-bold text-muted">NISN</label>
                        <input type="text" class="form-control @error('nisn') is-invalid @enderror" 
                               id="nisn" name="nisn" placeholder="Nomor Induk Siswa" value="{{ old('nisn') }}" required>
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label for="jenis_kelamin" class="form-label fw-bold text-muted">Gender</label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="" selected disabled>Pilih...</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="tgl_lahir" class="form-label fw-bold text-muted">Tgl Lahir</label>
                            <input type="text" class="form-control bg-white @error('tgl_lahir') is-invalid @enderror" 
                                   id="tgl_lahir" name="tgl_lahir" placeholder="Pilih Tgl" value="{{ old('tgl_lahir') }}" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label fw-bold text-muted">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Min. 8 karakter" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold text-muted">Konfirmasi Password</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" placeholder="Ulangi Password" required>
                    </div>

                    <button type="submit" class="btn w-100 btn-submit shadow-sm">Daftar Sekarang</button>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted" style="font-size: 0.75rem;">
                            *Pendaftaran Guru & Penjual hubungi Admin.
                        </small>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#tgl_lahir", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                locale: "id",
                disableMobile: "true"
            });
        });
    </script>
</body>
</html>