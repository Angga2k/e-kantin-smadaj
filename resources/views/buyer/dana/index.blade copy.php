<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Saldo - Kantin Digital</title>

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
    .avatar { width: 40px; height: 40px; border-radius: 50%; background-color: #5d4037; }

    /* Wallet Card */
    .wallet-card {
        background: linear-gradient(135deg, #00897b 0%, #004d40 100%);
        border-radius: 20px;
        color: white;
        padding: 1.5rem; /* Default Mobile Padding */
        box-shadow: 0 10px 20px rgba(0, 137, 123, 0.3);
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .wallet-card::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* Responsive Padding untuk Desktop */
    @media (min-width: 768px) {
        .wallet-card { padding: 2rem; margin-bottom: 2rem; }
    }
    
    /* Card Container Topup */
    .card-topup {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* === PERBAIKAN INPUT GROUP (Rp menyatu dengan Angka) === */
    
    /* Wrapper transisi */
    .input-group-custom {
        transition: all 0.2s;
    }

    /* Style Bagian "Rp" (Kiri) */
    .input-group-custom .input-group-text {
        background-color: white;
        border: 2px solid #e0e0e0; /* Samakan tebal garis */
        border-right: none;        /* Hapus garis kanan biar nyambung */
        color: #6c757d;
        font-weight: bold;
        font-size: 1.5rem;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        transition: border-color 0.15s ease-in-out;
    }

    /* Style Input Angka (Kanan) */
    .form-control-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-green);
        border: 2px solid #e0e0e0; /* Samakan tebal garis */
        border-left: none;         /* Hapus garis kiri biar nyambung */
        padding: 0.75rem;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        box-shadow: none !important; /* Hapus shadow biru default */
        transition: border-color 0.15s ease-in-out;
    }

    /* LOGIKA FOKUS: Saat input diklik, warnai KEDUANYA jadi hijau */
    .input-group-custom:focus-within .input-group-text,
    .input-group-custom:focus-within .form-control-amount {
        border-color: var(--primary-green);
    }

    /* LOGIKA ERROR: Jika validasi gagal, warnai input jadi merah */
    .form-control-amount.is-invalid {
        border-color: #dc3545 !important;
    }

    /* === END PERBAIKAN INPUT === */

    /* Tombol Preset (Pilihan Cepat) */
    .btn-preset {
        border: 1px solid #ced4da;
        color: #6c757d;
        font-weight: 600;
        padding: 0.6rem;
        border-radius: 10px;
        background-color: white;
        transition: all 0.2s;
        width: 100%;
        font-size: 0.95rem; 
    }
    .btn-preset:hover {
        background-color: #f8f9fa;
        border-color: var(--primary-green);
        color: var(--primary-green);
    }
    .btn-preset.active {
        background-color: #e0f2f1;
        border-color: var(--primary-green);
        color: var(--primary-green);
    }

    /* Payment Methods */
    .payment-option {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .payment-option:hover {
        border-color: var(--primary-green);
        background-color: #f9f9f9;
    }
    .payment-option.selected {
        border-color: var(--primary-green);
        background-color: #e0f2f1;
    }
    .radio-custom {
        accent-color: var(--primary-green);
        transform: scale(1.2);
    }

    /* History Table Badge */
    .badge-soft-success { background-color: #e8f5e9; color: #1b5e20; }
    .badge-soft-warning { background-color: #fff8e1; color: #f57f17; }
    .badge-soft-danger { background-color: #ffebee; color: #c62828; }
</style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="{{ asset('logo/smanda.png') }}" alt="Logo" class="me-2"><span class="fw-bold d-none d-lg-inline">SMA NEGERI 2 JEMBER</span></a>
                <div class="d-flex align-items-center ms-auto">
                    <div class="avatar"></div>
                </div>
            </div>
        </nav>
    </header>

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
    // 1. Fungsi saat tombol Preset diklik
    function setNominal(amount, btnElement) {
        const input = document.getElementById('inputNominal');
        const alertMsg = document.getElementById('alertMin');

        // Update nilai input
        input.value = amount;
        
        // Karena semua preset pasti > 10.000, kita sembunyikan error otomatis
        alertMsg.classList.add('d-none');
        input.classList.remove('is-invalid');

        // Update tampilan tombol
        document.querySelectorAll('.btn-preset').forEach(btn => btn.classList.remove('active'));
        btnElement.classList.add('active');
    }

    // 2. Fungsi Logika Pembayaran
    function selectPayment(labelElement) {
        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
        labelElement.classList.add('selected');
        const radio = labelElement.querySelector('input[type="radio"]');
        radio.checked = true;
    }

    // 3. Inisialisasi & Event Listener
    document.addEventListener("DOMContentLoaded", function() {
        // Init payment method selected
        const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
        if(checkedRadio) {
            checkedRadio.closest('.payment-option').classList.add('selected');
        }

        // === LOGIKA UTAMA ===
        const inputNominal = document.getElementById('inputNominal');
        const presetButtons = document.querySelectorAll('.btn-preset');
        const alertMin = document.getElementById('alertMin');

        inputNominal.addEventListener('input', function() {
            // Ambil nilai angka (parse ke Integer agar bisa dibandingkan)
            // Jika kosong (''), kita anggap 0
            const currentValue = parseInt(this.value) || 0; 
            
            // --- A. VALIDASI MINIMAL 10.000 ---
            // Jika nilai ada (lebih dari 0) TAPI kurang dari 10.000
            if (currentValue > 0 && currentValue < 10000) {
                alertMin.classList.remove('d-none'); // Munculkan teks error
                this.classList.add('is-invalid');    // Tambah border merah
            } else {
                alertMin.classList.add('d-none');    // Sembunyikan teks error
                this.classList.remove('is-invalid'); // Hapus border merah
            }

            // --- B. PENCOCOKAN TOMBOL PRESET ---
            // Kita gunakan nilai string (this.value) untuk pencocokan eksak
            const stringValue = this.value; 
            
            presetButtons.forEach(btn => {
                const btnValue = btn.getAttribute('data-value');

                if (stringValue === btnValue) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        });
    });
</script>
</body>
</html>