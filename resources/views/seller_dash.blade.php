<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kantin Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-teal: #00838f;
            --bg-yellow: #fbc02d;
            --bg-red: #c62828;
            --bg-green: #2e7d32;
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
        .navbar .nav-link:hover { color: #000; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; background-color: #5d4037; }

        /* Small Box Cards */
        .small-box {
            border-radius: 10px;
            position: relative;
            display: block;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            color: #fff;
        }
        .small-box .inner { padding: 20px; }
        .small-box h3 { font-size: 2.5rem; font-weight: 700; margin: 0 0 10px 0; white-space: nowrap; }
        .small-box p { font-size: 1rem; margin-bottom: 5px; font-weight: 500; }
        .small-box .icon { position: absolute; top: 15px; right: 15px; font-size: 1.5rem; color: rgba(255, 255, 255, 0.7); }
        .small-box-footer {
            position: relative; text-align: center; padding: 5px 0; color: rgba(255, 255, 255, 0.8);
            display: block; z-index: 10; background: rgba(0, 0, 0, 0.15); text-decoration: none; font-size: 0.9rem;
        }
        .small-box-footer:hover { color: #fff; background: rgba(0, 0, 0, 0.25); }

        /* Card Colors */
        .bg-card-teal { background-color: var(--bg-teal); }
        .bg-card-yellow { background-color: var(--bg-yellow); }
        .bg-card-red { background-color: var(--bg-red); }
        .bg-card-green { background-color: var(--bg-green); }

        /* Chart Section */
        .chart-card {
            background-color: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            height: 100%;
        }
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .form-select-sm {
            border-radius: 20px;
            font-weight: 500;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="https://smanda.sch.id/wp-content/uploads/2020/07/logo-smanda.png" alt="Logo" class="me-2"><span class="fw-bold d-none d-lg-inline">SMA NEGERI 2 JEMBER</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link fw-bold" href="#">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Tarik Dana</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">History</a></li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <span class="fw-bold text-success me-3">Rp 0</span>
                    <a href="#" class="fs-5 text-secondary me-3"><i class="bi bi-bell-fill"></i></a>
                    <div class="avatar"></div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-1" style="font-size: 0.9rem;">Beranda /</p>
                <h1 class="h2 fw-bold">Dashboard</h1>
            </div>
            <div class="d-flex align-items-center">
                <label for="yearFilter" class="me-2 text-muted small fw-bold">Tahun:</label>
                <select class="form-select form-select-sm w-auto shadow-sm" id="yearFilter">
                    <option value="2025" selected>2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-card-teal">
                    <div class="inner"><p>Jumlah Produk Terjual</p><h3>791</h3></div>
                    <div class="icon"><i class="bi bi-bag-check-fill"></i></div>
                    <a href="#" class="small-box-footer">More info <i class="bi bi-arrow-right-circle-fill ms-1"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-card-yellow">
                    <div class="inner"><p>Jumlah Review</p><h3>771</h3></div>
                    <div class="icon"><i class="bi bi-chat-right-text-fill"></i></div>
                    <a href="#" class="small-box-footer">More info <i class="bi bi-arrow-right-circle-fill ms-1"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-card-red">
                    <div class="inner"><p>Rating</p><h3>5.0 <span class="fs-6 ms-1 text-warning"><i class="bi bi-star-fill"></i></span></h3></div>
                    <div class="icon"><i class="bi bi-heart-fill"></i></div>
                    <a href="#" class="small-box-footer">More info <i class="bi bi-arrow-right-circle-fill ms-1"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-card-green">
                    <div class="inner"><p>Omset Penjualan</p><h3>Rp</h3></div>
                    <div class="icon"><i class="bi bi-cash-coin"></i></div>
                    <a href="#" class="small-box-footer">More info <i class="bi bi-arrow-right-circle-fill ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5 class="fw-bold mb-0 text-secondary"><i class="bi bi-bar-chart-line-fill me-2"></i>Produk Terjual</h5>
                    </div>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="productChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5 class="fw-bold mb-0 text-success"><i class="bi bi-graph-up-arrow me-2"></i>Pendapatan (Omset)</h5>
                    </div>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Data Simulasi
        const chartData = {
            '2025': {
                produk: [65, 59, 80, 81, 56, 55, 40, 70, 90, 100, 85, 60],
                pendapatan: [1500000, 1200000, 1800000, 1900000, 1400000, 1300000, 1000000, 1600000, 2100000, 5500000, 2000000, 1500000]
            },
            '2024': {
                produk: [40, 30, 50, 60, 40, 35, 50, 40, 60, 70, 55, 45],
                pendapatan: [900000, 700000, 1100000, 1300000, 900000, 800000, 1100000, 900000, 1400000, 1600000, 1200000, 1000000]
            },
            '2023': {
                produk: [20, 25, 30, 25, 30, 20, 25, 30, 35, 40, 30, 20],
                pendapatan: [400000, 500000, 600000, 500000, 600000, 400000, 500000, 600000, 700000, 800000, 600000, 400000]
            }
        };

        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // --- Grafik 1: Produk (Bar Chart) ---
        const ctxProduct = document.getElementById('productChart').getContext('2d');
        let productChart = new Chart(ctxProduct, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Item',
                    data: chartData['2025'].produk,
                    backgroundColor: '#00838f', // Teal
                    borderRadius: 4,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }, // Sembunyikan legend karena judul sudah di header
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Item';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // --- Grafik 2: Pendapatan (Line Chart) ---
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        let revenueChart = new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Pendapatan',
                    data: chartData['2025'].pendapatan,
                    borderColor: '#2e7d32', // Green
                    backgroundColor: 'rgba(46, 125, 50, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2e7d32',
                    pointRadius: 5,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumSignificantDigits: 3 }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' },
                        ticks: {
                            callback: function(value) {
                                if(value >= 1000000) return (value/1000000) + 'jt';
                                if(value >= 1000) return (value/1000) + 'rb';
                                return value;
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Logika Filter Tahun (Update Kedua Grafik)
        document.getElementById('yearFilter').addEventListener('change', function() {
            const selectedYear = this.value;

            // Update Produk Chart
            productChart.data.datasets[0].data = chartData[selectedYear].produk;
            productChart.update();

            // Update Revenue Chart
            revenueChart.data.datasets[0].data = chartData[selectedYear].pendapatan;
            revenueChart.update();
        });
    </script>
</body>
</html>
