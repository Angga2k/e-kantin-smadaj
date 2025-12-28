@extends('layouts.Buyer')

@section('title', 'Dana')

@section('content')

<main class="container my-3 my-md-4"> <div class="row g-4">
            
            <div class="col-lg-5 order-1 order-lg-2">
                
                <div class="wallet-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge bg-white text-success px-3 py-2 rounded-pill fw-bold">Siswa</span>
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <small class="opacity-75">Saldo Aktif Anda</small>
                    <h1 class="fw-bold mt-1 mb-0 display-6">Rp 12.500</h1>
                    <div class="mt-4 pt-3 border-top border-white border-opacity-25 d-flex justify-content-between align-items-center text-truncate">
                        <small>Budi Santoso</small>
                        <small class="font-monospace">1234 **** **** 5678</small>
                    </div>
                </div>

                <div class="card card-topup d-none d-lg-block">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Riwayat Top Up</h5>
                        <a href="#" class="text-decoration-none small text-muted">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">19 Nov</div>
                                            <div class="small text-muted">QRIS</div>
                                        </td>
                                        <td class="fw-bold text-end text-success">+Rp 50.000</td>
                                        <td class="text-end pe-4"><span class="badge badge-soft-success rounded-pill">Sukses</span></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">01 Nov</div>
                                            <div class="small text-muted">VA BCA</div>
                                        </td>
                                        <td class="fw-bold text-end text-muted">+Rp 100.000</td>
                                        <td class="text-end pe-4"><span class="badge badge-soft-warning rounded-pill">Pending</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-7 order-2 order-lg-1">
                <div class="card card-topup h-100">
                    <div class="card-body p-3 p-md-4">
                        <h4 class="fw-bold mb-4">Isi Saldo</h4>

                        <form action="/topup/process" method="POST">
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold">Mau isi saldo berapa?</label>
                                <div class="input-group has-validation"> <span class="input-group-text bg-white border-end-0 fw-bold fs-4 text-muted">Rp</span>
                                    <input type="number" name="nominal" id="inputNominal" class="form-control form-control-amount border-start-0 ps-0" placeholder="0" min="10000" required>
                                    
                                    <div class="invalid-feedback ps-3 fw-bold" id="alertMin">
                                        Minimal top up Rp 10.000
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold mb-2">Pilihan Cepat</label>
                                <div class="row g-2">
                                    <div class="col-6 col-sm-4">
                                        <button type="button" class="btn btn-preset" data-value="10000" onclick="setNominal(10000, this)">10.000</button>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <button type="button" class="btn btn-preset" data-value="20000" onclick="setNominal(20000, this)">20.000</button>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <button type="button" class="btn btn-preset" data-value="50000" onclick="setNominal(50000, this)">50.000</button>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <button type="button" class="btn btn-preset" data-value="75000" onclick="setNominal(75000, this)">75.000</button>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <button type="button" class="btn btn-preset" data-value="100000" onclick="setNominal(100000, this)">100.000</button>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <button type="button" class="btn btn-preset" data-value="200000" onclick="setNominal(200000, this)">200.000</button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold mb-2">Metode Pembayaran</label>
                                
                                <label class="payment-option mb-2" onclick="selectPayment(this)">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-qr-code-scan fs-4 text-dark me-3 flex-shrink-0"></i>
                                        <div>
                                            <div class="fw-bold text-dark">QRIS / E-Wallet</div>
                                            <div class="small text-muted lh-sm">Gopay, OVO, Dana, ShopeePay</div>
                                        </div>
                                    </div>
                                    <input type="radio" name="payment_method" value="qris" class="radio-custom" checked>
                                </label>

                                <label class="payment-option mb-2" onclick="selectPayment(this)">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-bank fs-4 text-dark me-3 flex-shrink-0"></i>
                                        <div>
                                            <div class="fw-bold text-dark">Transfer Bank</div>
                                            <div class="small text-muted lh-sm">BCA, BRI, BNI, Mandiri</div>
                                        </div>
                                    </div>
                                    <input type="radio" name="payment_method" value="bank_transfer" class="radio-custom">
                                </label>

                                <label class="payment-option" onclick="selectPayment(this)">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-cash-stack fs-4 text-dark me-3 flex-shrink-0"></i>
                                        <div>
                                            <div class="fw-bold text-dark">Bayar Tunai</div>
                                            <div class="small text-muted lh-sm">Bayar di Tata Usaha</div>
                                        </div>
                                    </div>
                                    <input type="radio" name="payment_method" value="cash" class="radio-custom">
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-3 fw-bold fs-5 shadow-sm" style="background-color: var(--primary-green); border: none;">
                                Top Up Sekarang <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card card-topup mt-4 d-block d-lg-none">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Riwayat Terakhir</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                                <tbody>
                                    <tr>
                                        <td class="ps-3 py-3">
                                            <div class="fw-bold text-dark">19 Nov</div>
                                        </td>
                                        <td class="fw-bold text-end text-success py-3">+Rp 50.000</td>
                                        <td class="text-end pe-3 py-3"><span class="badge badge-soft-success rounded-pill">Sukses</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Fungsi Update Input saat tombol Preset diklik
    function setNominal(amount, btnElement) {
        // Update nilai input
        document.getElementById('inputNominal').value = amount;
        
        // Hapus class active dari semua tombol preset
        document.querySelectorAll('.btn-preset').forEach(btn => btn.classList.remove('active'));
        
        // Tambah class active ke tombol yang diklik
        btnElement.classList.add('active');
    }

    // Fungsi Visualisasi Pilihan Pembayaran
    function selectPayment(labelElement) {
        // Hapus class selected dari semua opsi
        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
        
        // Tambah class selected ke opsi yang diklik
        labelElement.classList.add('selected');

        // Trigger klik pada radio button di dalamnya (jika user klik div pembungkus)
        const radio = labelElement.querySelector('input[type="radio"]');
        radio.checked = true;
    }

    // Inisialisasi visual payment option pertama saat load
    document.addEventListener("DOMContentLoaded", function() {
            const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
            if(checkedRadio) {
                checkedRadio.closest('.payment-option').classList.add('selected');
            }
    });
</script>
@endsection