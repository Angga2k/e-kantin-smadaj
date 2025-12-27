<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Penjualan - Kantin Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-green: #00897b;
            --light-gray-bg: #f0f2f5;
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--light-gray-bg); }
        .navbar { background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,.05); }
        .navbar-brand img { height: 35px; }
        .navbar .nav-link { font-weight: 500; color: #333; }
        .navbar .nav-link.active { font-weight: 600; color: var(--primary-green); }
        .avatar { width: 40px; height: 40px; border-radius: 50%; background-color: #5d4037; }

        /* Filter & Table Styles */
        .filter-card { background-color: white; border-radius: 12px; border: none; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
        .table-card { background-color: white; border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; }
        .table thead th { background-color: #f8f9fa; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; color: #6c757d; border-bottom: 2px solid #e9ecef; }
        .table td { vertical-align: top; font-size: 0.95rem; padding: 1rem 0.75rem; }

        /* Item List inside Table */
        .item-list { list-style: none; padding: 0; margin: 0; }
        .item-list li { display: flex; justify-content: space-between; margin-bottom: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px dashed #e9ecef; }
        .item-list li:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .badge-success-soft { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo" class="me-2"><span class="fw-bold d-none d-lg-inline">SMA NEGERI 2 JEMBER</span></a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Tarik Dana</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">History</a></li>
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

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Riwayat Penjualan</h4>
                <p class="text-muted small mb-0">Daftar barang keluar bulan ini.</p>
            </div>
        </div>

        <div class="card filter-card mb-4">
            <div class="card-body py-3">
                <form class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label class="col-form-label fw-bold small text-muted me-1">Periode:</label>
                    </div>

                    <div class="col-auto">
                        <select class="form-select form-select-sm" id="filterMonth">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <select class="form-select form-select-sm" id="filterYear">
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                        </select>
                    </div>

                    <div class="col-auto ms-2">
                        <button type="submit" class="btn btn-sm btn-primary px-3"><i class="bi bi-search me-1"></i> Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" width="15%">Waktu</th>
                            <th width="15%">Kode Transaksi</th>
                            <th width="40%">Detail Barang (Milik Anda)</th>
                            <th width="15%">Total Pendapatan</th>
                            <th width="15%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">19 Nov 2025</div>
                                <div class="small text-muted">14:14 WIB</div>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">INV-1763441778</span>
                                <div class="small text-muted">Pembeli: Magistra</div>
                            </td>
                            <td>
                                <ul class="item-list small">
                                    <li>
                                        <span>1x Ayam Goreng (Pedas)</span>
                                        <span class="fw-bold">Rp 15.000</span>
                                    </li>
                                    <li>
                                        <span>1x Es Teh (Manis)</span>
                                        <span class="fw-bold">Rp 5.000</span>
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <h6 class="fw-bold text-success mb-0">Rp 20.000</h6>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success-soft rounded-pill px-3">Selesai</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">19 Nov 2025</div>
                                <div class="small text-muted">13:00 WIB</div>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">INV-1763441780</span>
                                <div class="small text-muted">Pembeli: Budi Santoso</div>
                            </td>
                            <td>
                                <ul class="item-list small">
                                    <li>
                                        <span>2x Nasi Pecel</span>
                                        <span class="fw-bold">Rp 20.000</span>
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <h6 class="fw-bold text-success mb-0">Rp 20.000</h6>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success-soft rounded-pill px-3">Selesai</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">18 Nov 2025</div>
                                <div class="small text-muted">09:30 WIB</div>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">INV-1763441755</span>
                                <div class="small text-muted">Pembeli: Siti Aminah</div>
                            </td>
                            <td>
                                <ul class="item-list small">
                                    <li>
                                        <span>1x Tahu Walik (Isi 5)</span>
                                        <span class="fw-bold">Rp 10.000</span>
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <h6 class="fw-bold text-success mb-0">Rp 10.000</h6>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success-soft rounded-pill px-3">Selesai</span>
                            </td>
                        </tr>

                        </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top-0 py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted small mb-2 mb-md-0">
                    Menampilkan <span class="fw-bold">1</span> sampai <span class="fw-bold">30</span> dari <span class="fw-bold">145</span> data transaksi.
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end mb-0 small">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const date = new Date();
            const currentMonth = date.getMonth() + 1; // getMonth() mulai dari 0 (Januari)
            const currentYear = date.getFullYear();

            // Set value dropdown sesuai tanggal sekarang
            document.getElementById('filterMonth').value = currentMonth;
            document.getElementById('filterYear').value = currentYear;
        });
    </script>
</body>
</html>
