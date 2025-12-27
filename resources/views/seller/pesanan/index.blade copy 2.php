@extends('layouts.Seller')

@section('title', 'Pesanan')

@section('content')
<div class="mb-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/penjual/pesanan') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
        </ol>
    </nav>
    <h1 class="h3 fw-bold">Pesanan</h1>
</div>

{{-- Filter Tombol Status --}}
<div class="status-filters d-flex gap-2 mb-4">
    {{-- route() harus merujuk ke statusFilter di URL --}}
    <a href="{{ route('seller.pesanan.index', ['statusFilter' => 'baru']) }}"
       class="btn {{ $statusFilter == 'baru' ? 'active' : '' }}" data-status="baru">Pesanan Baru</a>
    <a href="{{ route('seller.pesanan.index', ['statusFilter' => 'diproses']) }}"
       class="btn {{ $statusFilter == 'diproses' ? 'active' : '' }}" data-status="diproses">Pesanan Diproses</a>
    <a href="{{ route('seller.pesanan.index', ['statusFilter' => 'siap']) }}"
       class="btn {{ $statusFilter == 'siap' ? 'active' : '' }}" data-status="siap">Siap Diambil</a>
</div>

<div class="row" id="order-grid">
    @if ($pesananGrouped->isEmpty())
        <p class="text-center text-muted my-5">Tidak ada pesanan dengan status "{{ ucfirst($statusFilter) }}" saat ini.</p>
    @else
        {{-- LOOP GROUPED TRANSACTIONS --}}
        @foreach($pesananGrouped as $id_transaksi => $details)
            @php
                // Ambil data transaksi utama (sama untuk semua detail)
                $transaksi = $details->first()->transaksi;
                $grandTotal = $transaksi->total_harga;
                // Tentukan status saat ini dari detail (ini hanya untuk tampilan)
                $currentDetailStatus = $details->first()->status_barang;
            @endphp

            <div class="col-lg-4 col-md-6 col-12 mb-3 order-item-col" data-transaksi-id="{{ $id_transaksi }}">
                <div class="order-card h-100" data-status="{{ $statusFilter }}">
                    <h6 class="fw-bold mb-3">Order ID: {{ $transaksi->kode_transaksi }}</h6>

                    <div class="order-card-body">
                        <dl class="mb-0">
                            {{-- Nama Pelanggan (Asumsi ada relasi ke Model User) --}}
                            <div class="order-row">
                                <dt>Nama Pelanggan</dt>
                                <dd>{{ $transaksi->id_user_pembeli }}</dd>
                            </div>

                            {{-- Waktu Pesan --}}
                            <div class="order-row">
                                <dt>Waktu Pesan</dt>
                                <dd>{{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->translatedFormat('j M Y, H:i') }}</dd>
                            </div>

                            {{-- Detail Pesanan --}}
                            <div class="order-row">
                                <dt>Detail Pesan</dt>
                                <dd>
                                    @foreach($details as $detail)
                                        {{ $detail->jumlah }}x {{ $detail->barang->nama_barang }} ({{ $detail->status_barang }})<br>
                                    @endforeach
                                </dd>
                            </div>

                            {{-- Pengambilan --}}
                            <div class="order-row">
                                <dt>Pengambilan</dt>
                                <dd>{{ \Carbon\Carbon::parse($transaksi->waktu_pengambilan)->translatedFormat('j M Y') }}, {{ $transaksi->detail_pengambilan }}</dd>
                            </div>

                            {{-- Total --}}
                            <div class="order-row">
                                <dt>Total</dt>
                                <dd class="fw-bold">Rp. {{ number_format($grandTotal, 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <hr>

                    <div class="order-card-footer">
                        <div class="row g-2">
                            {{-- Aksi Tombol (Sesuai statusFilter) --}}
                            <div class="col-6">
                                <button class="btn btn-cancel">Detail</button>
                            </div>
                            <div class="col-6">
                                @if ($statusFilter == 'baru')
                                    {{-- NEXT STATUS: 'proses' --}}
                                    <button class="btn btn-action-process btn-status-update" data-transaksi-id="{{ $id_transaksi }}" data-status="proses">Terima & Proses</button>
                                @elseif ($statusFilter == 'diproses')
                                    {{-- NEXT STATUS: 'belum_diambil' (Siap Ambil) --}}
                                    <button class="btn btn-action-ready btn-status-update" data-transaksi-id="{{ $id_transaksi }}" data-status="belum_diambil">Siap Ambil</button>
                                @elseif ($statusFilter == 'siap')
                                    {{-- NEXT STATUS: 'sudah_diambil' --}}
                                    <button class="btn btn-action-done btn-status-update" data-transaksi-id="{{ $id_transaksi }}" data-status="sudah_diambil">Telah Diambil</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

{{-- SCRIPT AJAX UNTUK UPDATE STATUS --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderGrid = document.getElementById('order-grid');

    // Fungsi umum untuk mengirim AJAX update status
    const updateOrderStatus = async (transactionId, newStatus) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Dapatkan elemen kolom utama yang membungkus kartu (col-lg-4)
        const itemColumn = document.querySelector(`.order-item-col[data-transaksi-id="${transactionId}"]`);

        // Dapatkan tombol (untuk status Loading)
        const currentButton = itemColumn ? itemColumn.querySelector('.btn-status-update') : null;

        if (currentButton) {
            currentButton.textContent = 'Loading...';
            currentButton.disabled = true;
        }

        try {
            const response = await fetch('{{ route('seller.pesanan.update_status') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    id_transaksi: transactionId,
                    new_status: newStatus
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // --- PERBAIKAN: HAPUS CARD DARI DOM ---
                alert('Status berhasil diperbarui! Pesanan dipindahkan.');
                if (itemColumn) {
                    // Cari parent row dan hapus kolom item
                    itemColumn.remove();
                    // Optional: Cek apakah grid kosong dan tampilkan pesan "Tidak ada pesanan"
                    if (orderGrid.children.length === 0) {
                        orderGrid.innerHTML = '<p class="text-center text-muted my-5">Semua pesanan di status ini telah selesai diproses.</p>';
                    }
                }

            } else {
                alert('Gagal update status: ' + (result.message || 'Error server.'));
            }

        } catch (error) {
            console.error('AJAX Error:', error);
            alert('Gagal terhubung ke server.');
        } finally {
            // Jika gagal, kembalikan tombol
            if (currentButton && !response.ok) {
                currentButton.disabled = false;
                // Logika pengembalian teks tombol asli sangat kompleks.
                // Cukup reload jika gagal total, atau biarkan pengguna mencoba lagi.
                alert('Aksi gagal total. Silakan coba muat ulang halaman.');
                window.location.reload();
            }
        }
    };

    // Pasang listener pada seluruh grid (event delegation)
    orderGrid.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-status-update')) {
            const button = e.target;
            const transactionId = button.dataset.transaksiId;
            const newStatus = button.dataset.status;

            // Panggil fungsi update
            updateOrderStatus(transactionId, newStatus);
        }
    });

});
</script>
@endsection
