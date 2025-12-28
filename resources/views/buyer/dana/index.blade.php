@extends('layouts.Buyer')

@section('title', 'Isi Saldo')

@section('content')
<style>
    /* Custom Style untuk Halaman Top Up - THEMA HIJAU */
    .wallet-card {
        /* Gradient Hijau */
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 10px 20px rgba(25, 135, 84, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    /* Dekorasi Background Card */
    .wallet-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(30deg);
        pointer-events: none;
    }

    /* Responsif Font Size untuk Saldo */
    @media (max-width: 576px) {
        .wallet-card { padding: 1.25rem; }
        .wallet-card h1 { font-size: 2rem; } /* Lebih kecil di HP */
    }

    .btn-preset {
        width: 100%;
        border: 1px solid #dee2e6;
        background-color: #fff;
        color: #495057;
        font-weight: 600;
        transition: all 0.2s;
        padding: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-preset:hover, .btn-preset.active {
        background-color: #e8f5e9; /* Hijau muda */
        border-color: #198754;
        color: #198754;
    }

    .payment-option {
        display: block;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }

    .payment-option:hover {
        border-color: #198754;
        background-color: #f8f9fa;
    }

    .payment-option.selected {
        border-color: #198754;
        background-color: #e8f5e9; /* Hijau muda */
        box-shadow: 0 0 0 1px #198754;
    }

    .radio-custom {
        position: absolute;
        top: 1rem;
        right: 1rem;
        accent-color: #198754; /* Hijau */
    }

    .form-control-amount {
        font-size: 1.5rem;
        font-weight: bold;
        color: #198754; /* Hijau */
    }
    
    .form-control-amount:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }

    /* Badge Styles */
    .badge-soft-success { background-color: #d1e7dd; color: #0f5132; }
    .badge-soft-warning { background-color: #fff3cd; color: #664d03; }
    .badge-soft-danger { background-color: #f8d7da; color: #842029; }
    
    /* Text Color Utilities */
    .text-success-custom { color: #198754 !important; }
</style>

<main class="container my-3 my-md-4" style="max-width: 1000px;"> 
    <div class="row g-4">
        
        {{-- KOLOM KIRI: KARTU DOMPET & RIWAYAT (DESKTOP) --}}
        <div class="col-lg-5 order-1 order-lg-2">
            
            {{-- Kartu Saldo --}}
            <div class="wallet-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
                    <span class="badge bg-white text-success px-3 py-2 rounded-pill fw-bold">@php echo Auth::user()->role ?? '[Siswa]';@endphp</span>
                    <i class="bi bi-wallet2 fs-3"></i>
                </div>
                <small class="opacity-75">Saldo Aktif Anda</small>
                <h1 class="fw-bold mt-1 mb-0 display-6">
                    Rp {{ number_format($dompet->saldo ?? 0, 0, ',', '.') }}
                </h1>
                <div class="mt-4 pt-3 border-top border-white border-opacity-25 d-flex justify-content-between align-items-center text-truncate">
                    <small>{{ Auth::user()->username ?? 'User' }}</small>
                    <small class="font-monospace">Dana-{{ substr(Auth::user()->id_user ?? '000000', 0, 6) }}</small>
                </div>
            </div>

            {{-- Riwayat Top Up Desktop (Tabel) --}}
            <div class="card card-topup d-none d-lg-block border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Riwayat Top Up</h5>
                    <a href="#" class="text-decoration-none small text-muted">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                @php
                                    $topups = \App\Models\Transaksi::where('id_user_pembeli', Auth::id())
                                                ->where('detail_pengambilan', 'LIKE', 'TOPUP_%')
                                                ->latest('waktu_transaksi')
                                                ->take(5)
                                                ->get();
                                @endphp

                                @forelse($topups as $topup)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($topup->waktu_transaksi)->format('d M') }}</div>
                                        <div class="small text-muted">{{ $topup->metode_pembayaran }}</div>
                                    </td>
                                    <td class="fw-bold text-end {{ $topup->status_pembayaran == 'success' ? 'text-success' : 'text-muted' }}">
                                        +Rp {{ number_format($topup->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($topup->status_pembayaran == 'pending')
                                            <a href="{{ $topup->payment_link }}" target="_blank" class="btn btn-sm btn-warning rounded-pill fw-bold text-dark px-3" style="font-size: 0.75rem;">
                                                Bayar <i class="bi bi-box-arrow-up-right ms-1"></i>
                                            </a>
                                        @elseif($topup->status_pembayaran == 'success')
                                            <span class="badge badge-soft-success rounded-pill">Sukses</span>
                                        @else
                                            <span class="badge badge-soft-danger rounded-pill">Gagal</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">Belum ada riwayat top up</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: FORM INPUT --}}
        <div class="col-lg-7 order-2 order-lg-1">
            <div class="card card-topup h-100 border-0 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <h4 class="fw-bold mb-4 text-success-custom">Isi Saldo</h4>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('buyer.dana.process') }}" method="POST" id="topupForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Mau isi saldo berapa?</label>
                            <div class="input-group has-validation input-group-lg"> 
                                <span class="input-group-text bg-white border-end-0 fw-bold text-success-custom">Rp</span>
                                <input type="number" name="amount" id="inputNominal" 
                                       class="form-control form-control-amount border-start-0 ps-0 @error('amount') is-invalid @enderror" 
                                       placeholder="0" min="10000" required value="{{ old('amount') }}">
                                
                                @error('amount')
                                    <div class="invalid-feedback ps-3 fw-bold">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text small text-muted mt-1 ms-1">Minimal top up Rp 10.000</div>

                            {{-- Rincian Biaya --}}
                            <div id="pricingDetail" class="mt-3 d-none p-3 bg-light rounded border border-dashed">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Nominal Top Up</span>
                                    <span class="fw-bold" id="displayNominal">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between small mb-2">
                                    <span class="text-muted">Biaya Admin <span id="adminFeeLabel" class="badge bg-secondary ms-1" style="font-size: 0.65rem;"></span></span>
                                    <span class="fw-bold text-danger" id="displayFee">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-25 pt-2 mt-2">
                                    <span class="fw-bold text-dark">Total Bayar</span>
                                    <span class="fw-bold text-success fs-5" id="displayTotal">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        {{-- Pilihan Cepat --}}
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold mb-2">Pilihan Cepat</label>
                            <div class="row g-2">
                                @foreach([10000, 20000, 50000, 75000, 100000, 200000] as $val)
                                <div class="col-4">
                                    <button type="button" class="btn btn-preset" onclick="setNominal({{ $val }}, this)">{{ number_format($val, 0, ',', '.') }}</button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold mb-2">Metode Pembayaran</label>
                            
                            <label class="payment-option mb-2" onclick="selectPayment(this)">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-qr-code-scan fs-4 text-success me-3 flex-shrink-0"></i>
                                    <div>
                                        <div class="fw-bold text-dark">QRIS / E-Wallet</div>
                                        <div class="small text-muted lh-sm">Gopay, OVO, Dana, ShopeePay</div>
                                    </div>
                                </div>
                                <input type="radio" name="payment_method" value="E_WALLET" class="radio-custom" checked data-fee-type="percent" data-fee="0.02">
                            </label>

                            <label class="payment-option mb-2" onclick="selectPayment(this)">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-bank fs-4 text-success me-3 flex-shrink-0"></i>
                                    <div>
                                        <div class="fw-bold text-dark">Transfer Bank</div>
                                        <div class="small text-muted lh-sm">BCA, BRI, BNI, Mandiri</div>
                                    </div>
                                </div>
                                <input type="radio" name="payment_method" value="BANK_TRANSFER" class="radio-custom" data-fee-type="flat" data-fee="4500">
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 fw-bold fs-5 shadow-sm">
                            Top Up Sekarang <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Riwayat Top Up Mobile (List Layout) --}}
            <div class="card card-topup mt-4 d-block d-lg-none border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-2">
                    <h6 class="fw-bold mb-0">Riwayat Terakhir</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topups as $topup)
                            <div class="list-group-item px-3 py-3 border-bottom-0 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($topup->waktu_transaksi)->format('d M, H:i') }}</span>
                                    <span class="fw-bold {{ $topup->status_pembayaran == 'success' ? 'text-success' : 'text-muted' }}">
                                        +Rp {{ number_format($topup->total_harga, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-muted">{{ $topup->metode_pembayaran }}</span>
                                    
                                    @if($topup->status_pembayaran == 'pending')
                                        <a href="{{ $topup->payment_link }}" target="_blank" class="btn btn-sm btn-warning rounded-pill fw-bold text-dark px-2 py-1" style="font-size: 0.7rem;">
                                            Bayar
                                        </a>
                                    @elseif($topup->status_pembayaran == 'success')
                                        <span class="badge badge-soft-success rounded-pill" style="font-size: 0.7rem;">Sukses</span>
                                    @else
                                        <span class="badge badge-soft-danger rounded-pill" style="font-size: 0.7rem;">Gagal</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Belum ada riwayat top up</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const inputNominal = document.getElementById('inputNominal');
    const pricingDetail = document.getElementById('pricingDetail');
    const displayNominal = document.getElementById('displayNominal');
    const displayFee = document.getElementById('displayFee');
    const adminFeeLabel = document.getElementById('adminFeeLabel');
    const displayTotal = document.getElementById('displayTotal');

    function formatRupiah(num) {
        return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function calculateTotal() {
        const nominal = parseInt(inputNominal.value) || 0;
        
        if (nominal < 10000) {
            pricingDetail.classList.add('d-none');
            return;
        }

        pricingDetail.classList.remove('d-none');

        const selectedRadio = document.querySelector('input[name="payment_method"]:checked');
        const feeType = selectedRadio.getAttribute('data-fee-type');
        const feeValue = parseFloat(selectedRadio.getAttribute('data-fee'));

        let fee = 0;
        let feeLabel = '';

        if (feeType === 'percent') {
            fee = nominal * feeValue;
            feeLabel = '(2%)';
        } else {
            fee = feeValue;
            feeLabel = '(Flat)';
        }

        const total = nominal + fee;

        displayNominal.innerText = formatRupiah(nominal);
        displayFee.innerText = formatRupiah(fee);
        adminFeeLabel.innerText = feeLabel;
        displayTotal.innerText = formatRupiah(total);
    }

    function setNominal(amount, btnElement) {
        inputNominal.value = amount;
        document.querySelectorAll('.btn-preset').forEach(btn => btn.classList.remove('active'));
        btnElement.classList.add('active');
        calculateTotal();
    }

    function selectPayment(labelElement) {
        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
        labelElement.classList.add('selected');
        const radio = labelElement.querySelector('input[type="radio"]');
        radio.checked = true;
        calculateTotal();
    }

    document.addEventListener("DOMContentLoaded", function() {
        const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
        if(checkedRadio) {
            checkedRadio.closest('.payment-option').classList.add('selected');
        }
        calculateTotal();
    });

    inputNominal.addEventListener('input', calculateTotal);
</script>
@endsection