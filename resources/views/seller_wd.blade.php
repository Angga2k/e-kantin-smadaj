<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarik Dana - Kantin Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-green: #00897b;
            --light-gray-bg: #f0f2f5;
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--light-gray-bg); }

        /* Navbar */
        .navbar { background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,.05); }
        .navbar-brand img { height: 35px; }
        .navbar .nav-link { font-weight: 500; color: #333; }
        .navbar .nav-link.active { font-weight: 600; color: var(--primary-green); }
        .avatar { width: 40px; height: 40px; border-radius: 50%; background-color: #5d4037; }

        /* Cards */
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background-color: white; }

        /* Balance Card Special Style */
        .balance-card {
            background: linear-gradient(135deg, #00897b 0%, #00695c 100%);
            color: white;
        }
        .balance-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        /* Table Styles */
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.85rem;
            color: #6c757d;
            border-bottom: 2px solid #e9ecef;
        }
        .status-badge { font-size: 0.75rem; padding: 0.4em 0.8em; border-radius: 50rem; }

        /* Readonly Input Style (Bank Info) */
        .form-control:disabled, .form-control[readonly] {
            background-color: #f8f9fa;
            opacity: 1;
            border-color: #e9ecef;
            color: #495057;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo" class="me-2"><span class="fw-bold d-none d-lg-inline">SMA NEGERI 2 JEMBER</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">Tarik Dana</a></li>
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

    <main class="container my-4">

        <div class="row g-4">

            <div class="col-lg-5">
                <div class="card balance-card mb-4">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">Saldo Aktif Anda</p>
                            <h2 class="fw-bold mb-0">Rp 240.000</h2>
                        </div>
                        <div class="balance-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="fw-bold mb-0">Ajukan Penarikan</h5>
                    </div>
                    <div class="card-body pt-0">
                        <form>
                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">Nominal Penarikan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold">Rp</span>
                                    <input type="number" class="form-control form-control-lg fw-bold" placeholder="0" min="10000">
                                </div>
                                <div class="form-text text-end">Min. penarikan Rp 10.000</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small text-muted fw-bold">Rekening Tujuan</label>
                                <div class="p-3 bg-light rounded border">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-bank me-2 text-secondary"></i>
                                        <span class="fw-bold text-dark">Bank BCA</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0 text-dark font-monospace">1234567890</span>
                                        <span class="badge bg-secondary">Utama</span>
                                    </div>
                                    <div class="small text-muted mt-1">a.n. Budi Santoso</div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="bi bi-box-arrow-up-right me-2"></i> Tarik Dana Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Riwayat Penarikan</h5>
                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-filter"></i> Filter</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Tanggal Request</th>
                                        <th>ID Transaksi</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">19 Nov 2025</div>
                                            <div class="small text-muted">14:30 WIB</div>
                                        </td>
                                        <td class="font-monospace small text-secondary">WD-2025111901</td>
                                        <td class="fw-bold">Rp 100.000</td>
                                        <td><span class="badge bg-warning text-dark status-badge">Menunggu</span></td>
                                    </tr>

                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">10 Nov 2025</div>
                                            <div class="small text-muted">09:15 WIB</div>
                                        </td>
                                        <td class="font-monospace small text-secondary">WD-2025111005</td>
                                        <td class="fw-bold">Rp 500.000</td>
                                        <td><span class="badge bg-success status-badge">Berhasil</span></td>
                                    </tr>

                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">01 Nov 2025</div>
                                            <div class="small text-muted">16:00 WIB</div>
                                        </td>
                                        <td class="font-monospace small text-secondary">WD-2025110102</td>
                                        <td class="fw-bold">Rp 250.000</td>
                                        <td><span class="badge bg-success status-badge">Berhasil</span></td>
                                    </tr>

                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">25 Okt 2025</div>
                                            <div class="small text-muted">10:00 WIB</div>
                                        </td>
                                        <td class="font-monospace small text-secondary">WD-2025102509</td>
                                        <td class="fw-bold text-muted text-decoration-line-through">Rp 1.000.000</td>
                                        <td><span class="badge bg-danger status-badge">Gagal</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 py-3">
                        <nav>
                            <ul class="pagination justify-content-end mb-0 small">
                                <li class="page-item disabled"><a class="page-link" href="#">Prev</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
