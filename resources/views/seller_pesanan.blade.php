<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Penjual</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-green: #1e8e3e;
            --light-gray-bg: #f0f2f5;
            --text-gray: #6c757d;

            /* Warna Tombol Aksi */
            --btn-process-bg: #1e8e3e; /* Merah - Terima & Proses */
            --btn-ready-bg: #0d6efd;   /* Biru - Siap Ambil */
            --btn-done-bg: #198754;    /* Hijau Tua - Telah Diambil (Contoh) */
            --btn-done-bright-bg: #20c997; /* Hijau Terang - Telah Diambil */
            --btn-cancel-bg: #6c757d;  /* Abu-abu - Batalkan/Detail */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray-bg);
        }

        /* Navbar */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }
        .navbar-brand img {
            height: 35px;
        }
        .navbar .nav-link {
            font-weight: 500;
            color: #333;
        }

        /* Filter Status Pesanan */
        .status-filters .btn {
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-weight: 500;
            border: none;
            transition: background-color 0.2s;
        }
        .status-filters .btn.active {
            background-color: var(--primary-green);
            color: white;
        }
        .status-filters .btn:not(.active) {
            background-color: #e9ecef;
            color: #495057;
        }

        /* Kartu Pesanan (Mobile First) */
        .order-card {
            background-color: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        /* Style border berbeda untuk setiap status */
        .order-card[data-status="baru"] { border: 2px dashed #e0e0e0; }
        .order-card[data-status="diproses"] { border: 2px dashed var(--btn-ready-bg); }
        .order-card[data-status="siap"] { border: 2px dashed var(--btn-done-bright-bg); }

        .order-card-body dt { font-weight: 600; }
        .order-card-body dd { color: var(--text-gray); }
        .order-card-footer .btn {
            border-radius: 20px;
            font-weight: 600;
            width: 100%;
            padding: 0.6rem;
            border: none;
        }

        /* Kelas untuk Styling Tombol Aksi */
        .btn-cancel { background-color: var(--btn-cancel-bg); color: white; }
        .btn-action-process { background-color: var(--btn-process-bg); color: white; }
        .btn-action-ready { background-color: var(--btn-ready-bg); color: white; }
        .btn-action-done { background-color: var(--btn-done-bright-bg); color: white; }

        /* Tampilan Desktop */
        @media (min-width: 768px) {
            .order-card {
                border: 1px solid #dee2e6;
                box-shadow: 0 4px 8px rgba(0,0,0,0.04);
                padding: 1.25rem;
            }
             .order-card[data-status="baru"] { border: 1px solid #dee2e6; }
             .order-card[data-status="diproses"] { border: 2px dashed var(--btn-ready-bg); }
             .order-card[data-status="siap"] { border: 2px dashed var(--btn-done-bright-bg); }

            .order-card-body .order-row { display: flex; justify-content: space-between; }
            .order-card-body dd { text-align: right; }
            .order-card-footer .btn { width: auto; padding: 0.5rem 1.25rem; }
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo" class="me-2"><span class="fw-bold d-none d-lg-inline">SMA NEGERI 2 JEMBER</span></a>
                <div class="collapse navbar-collapse" id="navbarNav"><ul class="navbar-nav mx-auto"><li class="nav-item"><a class="nav-link" href="#">Laporan</a></li><li class="nav-item"><a class="nav-link" href="#">Produk</a></li><li class="nav-item dropdown"><a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Pesanan</a><ul class="dropdown-menu"><li><a class="dropdown-item" href="#">Pesanan Baru</a></li><li><a class="dropdown-item" href="#">Pesanan Diproses</a></li><li><a class="dropdown-item" href="#">Siap Diambil</a></li></ul></li></ul></div>
                <div class="d-flex align-items-center"><span class="fw-bold me-3">Rp 0</span><a href="#" class="fs-5 text-secondary me-3"><i class="bi bi-bell-fill"></i></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button></div>
            </div>
        </nav>
    </header>

    <main class="container my-4">
        <div class="mb-3">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Beranda</a></li><li class="breadcrumb-item active" aria-current="page">Pesanan</li></ol></nav>
            <h1 class="h3 fw-bold">Pesanan</h1>
        </div>

        <div class="status-filters d-flex gap-2 mb-4">
            <button class="btn active" data-status="baru">Pesanan Baru</button>
            <button class="btn" data-status="diproses">Pesanan Diproses</button>
            <button class="btn" data-status="siap">Siap Diambil</button>
        </div>

        <div class="row" id="order-grid">
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="order-card  h-100" data-status="baru">
                    <h6 class="fw-bold mb-3">Order ID: #123</h6>
                    <div class="order-card-body"><dl class="mb-0"><div class="order-row"><dt>Nama Pelanggan</dt><dd>Magistra</dd></div><div class="order-row"><dt>Waktu Pesan</dt><dd>12 Feb 2025, 14:14</dd></div><div class="order-row"><dt>Detail Pesan</dt><dd>1x Ayam Goreng, Pedas<br>1x Es Teh, Tawar</dd></div><div class="order-row"><dt>Pengambilan</dt><dd>15 Feb 2025, Istirahat 1</dd></div><div class="order-row"><dt>Total</dt><dd class="fw-bold">Rp. 20.000</dd></div></dl></div>
                    <hr>
                    <div class="order-card-footer"><div class="row g-2"><div class="col-6"><button class="btn btn-cancel">Detail</button></div><div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div></div></div>
                </div>
            </div>

             <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="order-card h-100" data-status="baru">
                    <h6 class="fw-bold mb-3">Order ID: #124</h6>
                    <div class="order-card-body"><dl class="mb-0"><div class="order-row"><dt>Nama Pelanggan</dt><dd>Budi Hartono</dd></div><div class="order-row"><dt>Waktu Pesan</dt><dd>12 Feb 2025, 14:18</dd></div><div class="order-row"><dt>Detail Pesan</dt><dd>2x Nasi Padang</dd></div><div class="order-row"><dt>Pengambilan</dt><dd>15 Feb 2025, Istirahat 1</dd></div><div class="order-row"><dt>Total</dt><dd class="fw-bold">Rp. 24.000</dd></div></dl></div>
                    <hr>
                    <div class="order-card-footer"><div class="row g-2"><div class="col-6"><button class="btn btn-cancel">Detail</button></div><div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div></div></div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="order-card h-100" data-status="baru">
                    <h6 class="fw-bold mb-3">Order ID: #125</h6>
                    <div class="order-card-body"><dl class="mb-0"><div class="order-row"><dt>Nama Pelanggan</dt><dd>Citra Lestari</dd></div><div class="order-row"><dt>Waktu Pesan</dt><dd>12 Feb 2025, 14:21</dd></div><div class="order-row"><dt>Detail Pesan</dt><dd>1x Es Teler</dd></div><div class="order-row"><dt>Pengambilan</dt><dd>15 Feb 2025, Istirahat 2</dd></div><div class="order-row"><dt>Total</dt><dd class="fw-bold">Rp. 12.000</dd></div></dl></div>
                    <hr>
                    <div class="order-card-footer"><div class="row g-2"><div class="col-6"><button class="btn btn-cancel">Detail</button></div><div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div></div></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="order-card h-100" data-status="baru">
                    <h6 class="fw-bold mb-3">Order ID: #125</h6>
                    <div class="order-card-body"><dl class="mb-0"><div class="order-row"><dt>Nama Pelanggan</dt><dd>Citra Lestari</dd></div><div class="order-row"><dt>Waktu Pesan</dt><dd>12 Feb 2025, 14:21</dd></div><div class="order-row"><dt>Detail Pesan</dt><dd>1x Es Teler</dd></div><div class="order-row"><dt>Pengambilan</dt><dd>15 Feb 2025, Istirahat 2</dd></div><div class="order-row"><dt>Total</dt><dd class="fw-bold">Rp. 12.000</dd></div></dl></div>
                    <hr>
                    <div class="order-card-footer"><div class="row g-2"><div class="col-6"><button class="btn btn-cancel">Detail</button></div><div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div></div></div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.status-filters .btn');
            const orderCards = document.querySelectorAll('.order-card');

            // Fungsi untuk mengubah tampilan kartu berdasarkan status
            const updateCards = (status) => {
                orderCards.forEach(card => {
                    const footer = card.querySelector('.order-card-footer');
                    let actionButtonHTML = '';

                    // Ubah border card
                    card.dataset.status = status;

                    // Ganti tombol berdasarkan status yang dipilih
                    if (status === 'baru') {
                        // Tombol untuk 'Pesanan Baru'
                        footer.innerHTML = `
                            <div class="row g-2">
                                <div class="col-6"><button class="btn btn-cancel">Detail</button></div>
                                <div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div>
                            </div>`;
                    } else if (status === 'diproses') {
                        // Tombol untuk 'Pesanan Diproses'
                        footer.innerHTML = `
                            <div class="row g-2">
                                <div class="col-6"><button class="btn btn-cancel">Batalkan</button></div>
                                <div class="col-6"><button class="btn btn-action-ready">Siap Ambil</button></div>
                            </div>`;
                    } else if (status === 'siap') {
                        // Tombol untuk 'Siap Diambil'
                        footer.innerHTML = `
                            <div class="row g-2">
                                <div class="col-6"><button class="btn btn-cancel">Batalkan</button></div>
                                <div class="col-6"><button class="btn btn-action-done">Telah Diambil</button></div>
                            </div>`;
                    }
                });
            };

            // Tambahkan event listener ke setiap tombol filter
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Hapus kelas 'active' dari semua tombol
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Tambahkan kelas 'active' ke tombol yang diklik
                    this.classList.add('active');

                    // Dapatkan status dari atribut data-status
                    const status = this.dataset.status;

                    // Panggil fungsi untuk update kartu
                    updateCards(status);
                });
            });

            // Inisialisasi tampilan awal
            updateCards('baru');
        });
    </script>
</body>
</html>
