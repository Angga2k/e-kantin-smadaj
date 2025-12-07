@extends('layouts.Seller')

@section('title', 'Dompet & Penarikan')

@section('content')
<main class="container my-4">

    {{-- Alert Sukses/Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- KOLOM KIRI: SALDO & FORM --}}
        <div class="col-lg-5">
            {{-- Kartu Saldo --}}
            <div class="card balance-card mb-4 bg-primary text-white shadow-sm" style="background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 text-white">Saldo Aktif Anda</p>
                        {{-- Menampilkan Saldo Terdekripsi --}}
                        <h2 class="fw-bold mb-0">Rp {{ number_format($dompet->saldo, 0, ',', '.') }}</h2>
                    </div>
                    <div class="balance-icon fs-1 opacity-50">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>

            {{-- Form Penarikan --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-cash-coin me-2"></i>Ajukan Penarikan</h5>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('seller.dompet.store') }}" method="POST">
                        @csrf

                        {{-- Input Nominal --}}
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Nominal Penarikan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number"
                                       name="jumlah"
                                       class="form-control form-control-lg fw-bold"
                                       placeholder="0"
                                       min="100000"
                                       required>
                            </div>
                            <div class="form-text text-end">Min. penarikan Rp 100.000</div>
                        </div>

                        {{-- Input Rekening Tujuan --}}
                        <div class="mb-4">
                            <label class="form-label small text-muted fw-bold">Rekening Tujuan</label>
                            <div class="p-3 bg-light rounded border">
                                <div class="mb-2">
                                    <label class="small text-muted mb-1">Nama Bank / E-Wallet</label>
                                    <select class="form-select form-select-sm" name="bank_tujuan" required>
                                        <option value="BCA">Bank BCA</option>
                                        <option value="BRI">Bank BRI</option>
                                        <option value="Mandiri">Bank Mandiri</option>
                                        <option value="BNI">Bank BNI</option>
                                        <option value="Gopay">Gopay</option>
                                        <option value="Dana">Dana</option>
                                        <option value="OVO">OVO</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="small text-muted mb-1">Nomor Rekening / HP</label>
                                    <input type="number"
                                           name="no_rekening"
                                           class="form-control form-control-sm font-monospace"
                                           placeholder="Contoh: 1234567890"
                                           required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="bi bi-box-arrow-up-right me-2"></i> Tarik Dana Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: RIWAYAT --}}
        <div class="col-lg-7">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Riwayat Penarikan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Tanggal Request</th>
                                    <th>Tujuan</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $item->created_at->format('d M Y') }}</div>
                                            <div class="small text-muted">{{ $item->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark small">{{ $item->bank_tujuan }}</div>
                                            <div class="font-monospace small text-muted">{{ $item->no_rekening }}</div>
                                        </td>
                                        {{-- $item->jumlah otomatis didekripsi oleh Model --}}
                                        <td class="fw-bold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        <td>
                                            @if($item->status == 'berhasil')
                                                <span class="badge bg-success bg-opacity-10 text-success px-3">Berhasil</span>
                                            @elseif($item->status == 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-3">Menunggu</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-3">Gagal</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <img src="{{ asset('icon/empty-box.png') }}" alt="Empty" style="width: 50px; opacity: 0.5;" class="mb-2" onerror="this.style.display='none'">
                                            <p class="mb-0 small">Belum ada riwayat penarikan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if($riwayat->hasPages())
                    <div class="card-footer bg-white border-top-0 py-3">
                        {{ $riwayat->links() }}
                        {{-- Pastikan Anda menggunakan Bootstrap Pagination di AppServiceProvider jika tampilan berantakan --}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
