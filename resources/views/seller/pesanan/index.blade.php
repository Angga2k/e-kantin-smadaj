@extends('layouts.Seller')

@section('title', 'Pesanan')

@section('content')
{{-- Tambahkan CDN SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                $transaksi = $details->first()->transaksi;
                $grandTotal = $details->first()->jumlah * $details->first()->harga_saat_transaksi;
                $currentDetailStatus = $details->first()->status_barang;
                $nama_user = "no_name";
                $role_pembelii = $transaksi->pembeli['role'];
                if($role_pembelii === 'siswa'){
                    $nama_user = $transaksi->pembeli->siswa['nama'];
                } else  if($role_pembelii === 'civitas_akademik') {
                    $nama_user = $transaksi->pembeli->civitasAkademik['nama'];
                }
            @endphp

            <div class="col-lg-4 col-md-6 col-12 mb-3 order-item-col" data-transaksi-id="{{ $id_transaksi }}">
                <div class="order-card h-100" data-status="{{ $statusFilter }}">
                    <h6 class="fw-bold mb-3">Order ID: {{ $transaksi->kode_transaksi }}</h6>

                    <div class="order-card-body">
                        <dl class="mb-0">
                            <div class="order-row">
                                <dt>Pembeli</dt>
                                <dd>{{ $role_pembelii }}</dd>
                            </div><hr class="m-0">
                            
                            <div class="order-row">
                                <dt>Nama Pembeli</dt>
                                <dd>{{ $nama_user }}</dd>
                            </div><hr class="m-0">

                            <div class="order-row">
                                <dt>Waktu Pesan</dt>
                                <dd>{{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->translatedFormat('j M Y, H:i') }}</dd>
                            </div><hr class="m-0">

                            <div class="order-row">
                                <dt>Detail Pesan</dt>
                                <dd>
                                    @foreach($details as $detail)
                                        <strong>{{ $detail->jumlah }}x</strong> {{ $detail->barang->nama_barang }}<br>
                                    @endforeach
                                </dd>
                            </div><hr class="m-0">

                            <div class="order-row">
                                <dt>Pengambilan</dt>
                                <dd>{{ \Carbon\Carbon::parse($transaksi->waktu_pengambilan)->translatedFormat('j M Y') }}, <strong>{{ $transaksi->detail_pengambilan }}</strong></dd>
                            </div><hr class="m-0">

                            <div class="order-row">
                                <dt>Total</dt>
                                <dd class="fw-bold text-primary">Rp. {{ number_format($grandTotal, 0, ',', '.') }}</dd>
                            </div><hr class="m-0 mb-3">
                        </dl>
                    </div>


                    <div class="order-card-footer">
                        <div class="row g-2">
                            <div class="col-6">
                                {{-- <button class="btn btn-cancel">Detail</button> --}}
                            </div>
                            <div class="col-6">
                                @if ($statusFilter == 'baru')
                                    <button class="btn btn-action-process btn-status-update" data-transaksi-id="{{ $id_transaksi }}" data-status="proses">Terima & Proses</button>
                                @elseif ($statusFilter == 'diproses')
                                    <button class="btn btn-action-ready btn-status-update" data-transaksi-id="{{ $id_transaksi }}" data-status="belum_diambil">Siap Ambil</button>
                                @elseif ($statusFilter == 'siap')
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

    const updateOrderStatus = async (transactionId, newStatus) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const itemColumn = document.querySelector(`.order-item-col[data-transaksi-id="${transactionId}"]`);
        const currentButton = itemColumn ? itemColumn.querySelector('.btn-status-update') : null;

        if (currentButton) {
            currentButton.textContent = 'Loading...';
            currentButton.disabled = true;
        }

        // Deklarasikan response di luar try agar bisa diakses di finally (untuk cek !response.ok)
        let response = null;

        try {
            response = await fetch('{{ route('seller.pesanan.update_status') }}', {
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
                // --- SUKSES ---
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Status berhasil diperbarui! Pesanan dipindahkan.',
                    timer: 1500,
                    showConfirmButton: false
                });

                if (itemColumn) {
                    itemColumn.remove();
                    if (orderGrid.children.length === 0) {
                        orderGrid.innerHTML = '<p class="text-center text-muted my-5">Semua pesanan di status ini telah selesai diproses.</p>';
                    }
                }

            } else {
                // --- GAGAL DARI SERVER ---
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal update status: ' + (result.message || 'Error server.')
                });
            }

        } catch (error) {
            console.error('AJAX Error:', error);
            // --- GAGAL KONEKSI ---
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Gagal terhubung ke server. Periksa koneksi internet Anda.'
            });
        } finally {
            // Jika gagal (response null atau status tidak ok), kembalikan tombol
            if (currentButton && (!response || !response.ok)) {
                // --- GAGAL TOTAL & RELOAD ---
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal Memuat',
                    text: 'Aksi gagal total. Silakan coba muat ulang halaman.',
                    confirmButtonText: 'Muat Ulang',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            }
        }
    };

    // Event Delegation
    orderGrid.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-status-update')) {
            const button = e.target;
            const transactionId = button.dataset.transaksiId;
            const newStatus = button.dataset.status;

            // Konfirmasi sebelum proses (Opsional, agar tidak salah klik)
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin mengubah status pesanan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateOrderStatus(transactionId, newStatus);
                }
            });
        }
    });

});
</script>
@endsection
