@extends('layouts.Seller')

@section('title', 'Riwayat Penjualan')

@section('content')
<main class="container my-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Riwayat Penjualan</h4>
            <p class="text-muted small mb-0">Daftar semua transaksi masuk pada periode ini.</p>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card filter-card mb-4">
        <div class="card-body py-3">
            <form action="{{ route('seller.history.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label fw-bold small text-muted me-1">Periode:</label>
                </div>

                <div class="col-auto">
                    <select class="form-select form-select-sm" name="month" id="filterMonth">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <select class="form-select form-select-sm" name="year" id="filterYear">
                        @foreach(range(date('Y'), 2024) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto ms-2">
                    <button type="submit" class="btn btn-sm btn-primary px-3">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card table-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" width="20%">Waktu Transaksi</th>
                        <th width="20%">Kode Transaksi</th>
                        <th width="35%">Detail Barang (Milik Anda)</th>
                        <th width="15%">Total Pendapatan</th>
                        <th width="10%" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $idTransaksi => $items)
                        @php
                            $transaksi = $items->first()->transaksi;
                            $firstItem = $items->first(); // Ambil item pertama untuk cek status
                            $totalPendapatan = $items->sum(function($detail) {
                                return $detail->jumlah * $detail->harga_saat_transaksi;
                            });

                            // Logika Warna Badge Status
                            $status = $firstItem->status_barang;
                            $badgeClass = 'bg-secondary text-white';
                            $statusLabel = $status;

                            switch($status) {
                                case 'baru':
                                    $badgeClass = 'bg-info bg-opacity-10 text-info border border-info border-opacity-25';
                                    $statusLabel = 'Baru';
                                    break;
                                case 'proses':
                                    $badgeClass = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25';
                                    $statusLabel = 'Diproses';
                                    break;
                                case 'belum_diambil':
                                    $badgeClass = 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25';
                                    $statusLabel = 'Siap Diambil';
                                    break;
                                case 'sudah_diambil':
                                    $badgeClass = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25';
                                    $statusLabel = 'Selesai';
                                    break;
                                case 'dibatalkan':
                                    $badgeClass = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
                                    $statusLabel = 'Batal';
                                    break;
                            }
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">
                                    {{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->translatedFormat('d M Y') }}
                                </div>
                                <div class="small text-muted">
                                    {{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('H:i') }} WIB
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-primary font-monospace small">
                                    INV-{{ substr($transaksi->id_transaksi, 0, 8) }}
                                </span>
                                <div class="small text-muted">
                                    Pembeli: {{ $transaksi->user->username ?? 'Umum' }}
                                </div>
                            </td>
                            <td>
                                <ul class="item-list small list-unstyled mb-0">
                                    @foreach($items as $detail)
                                        <li class="mb-1 d-flex justify-content-between pe-3">
                                            <span>
                                                <span class="fw-bold">{{ $detail->jumlah }}x</span>
                                                {{ $detail->barang->nama_barang }}
                                            </span>
                                            <span class="text-muted">
                                                Rp {{ number_format($detail->harga_saat_transaksi * $detail->jumlah, 0, ',', '.') }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <h6 class="fw-bold text-success mb-0">
                                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                </h6>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $badgeClass }} rounded-pill px-3">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <img src="{{ asset('icon/empty-box.png') }}" alt="Empty" style="width: 60px; opacity: 0.5;" class="mb-2" onerror="this.style.display='none'">
                                <p class="mb-0">Tidak ada transaksi pada periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top-0 py-3">
            <div class="text-muted small">
                Menampilkan <span class="fw-bold">{{ count($history) }}</span> transaksi.
            </div>
        </div>
    </div>
</main>
@endsection
