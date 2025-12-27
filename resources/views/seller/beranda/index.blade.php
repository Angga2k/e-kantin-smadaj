@extends('layouts.Seller')

@section('title', 'Beranda')

@section('content')
<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-1" style="font-size: 0.9rem;">Beranda /</p>
            <h1 class="h2 fw-bold">Dashboard</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-card-teal">
                <div class="inner"><p>Jumlah Produk Terjual</p>
                    <h4>
                        {{ number_format($summaryPenjualan->total_produk_terjual ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
                <div class="icon"><i class="bi bi-bag-check-fill"></i></div>
                <a href="#" class="small-box-footer">More info <i class="bi bi-arrow-right-circle-fill ms-1"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-card-yellow">
                <div class="inner"><p>Jumlah Review</p>
                    <h4>
                        {{ number_format($summaryRating->total_ulasan ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
                <div class="icon"><i class="bi bi-chat-right-text-fill"></i></div>
                <a href="#" class="small-box-footer">More info <i class="bi bi-arrow-right-circle-fill ms-1"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-card-red">
                <div class="inner"><p>Rating</p>
                    <h4>
                        @php
                            $ratingVal = $summaryRating->rata_rata_rating ?? 0;
                        @endphp
                        {{ number_format($ratingVal, 1) }}
                        <span class="fs-6 ms-1 text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $ratingVal ? '-fill' : '' }}"></i>
                            @endfor
                        </span>
                    </h4>
                </div>
                <div class="icon"><i class="bi bi-heart-fill"></i></div>
                <a href="#" class="small-box-footer">More info <i class="bi bi-arrow-right-circle-fill ms-1"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-card-green">
                <div class="inner"><p>Omset Penjualan</p>
                    <h4>
                        Rp {{ number_format($summaryPenjualan->total_omset ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
                <div class="icon"><i class="bi bi-cash-coin"></i></div>
                <a href="#" class="small-box-footer">More info <i class="bi bi-arrow-right-circle-fill ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="d-flex align-items-center">
            <label for="yearFilter" class="me-2 text-muted small fw-bold">Tahun:</label>
            <select class="form-select form-select-sm w-auto shadow-sm" id="yearFilter">
                @foreach(array_keys($chartData) as $year)
                    <option value="{{ $year }}" {{ $loop->last ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
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
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // PERBAIKAN: Mengambil data JSON dari Controller
    const chartData = @json($chartData);

    // Ambil tahun default dari dropdown (biasanya tahun terakhir/terbaru)
    const defaultYear = document.getElementById('yearFilter').value;

    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    // --- Grafik 1: Produk (Bar Chart) ---
    const ctxProduct = document.getElementById('productChart').getContext('2d');
    let productChart = new Chart(ctxProduct, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Item',
                // Gunakan defaultYear untuk inisialisasi awal
                data: chartData[defaultYear] ? chartData[defaultYear].produk : Array(12).fill(0),
                backgroundColor: '#00838f', // Teal
                borderRadius: 4,
                barPercentage: 0.6
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
                // Gunakan defaultYear untuk inisialisasi awal
                data: chartData[defaultYear] ? chartData[defaultYear].pendapatan : Array(12).fill(0),
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

        // Pastikan data tahun tersebut ada sebelum update
        if (chartData[selectedYear]) {
            // Update Produk Chart
            productChart.data.datasets[0].data = chartData[selectedYear].produk;
            productChart.update();

            // Update Revenue Chart
            revenueChart.data.datasets[0].data = chartData[selectedYear].pendapatan;
            revenueChart.update();
        } else {
            console.error('Data untuk tahun ' + selectedYear + ' tidak ditemukan.');
        }
    });
</script>
@endpush
