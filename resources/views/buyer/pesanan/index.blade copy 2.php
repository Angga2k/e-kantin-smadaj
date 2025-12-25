@extends('layouts.Buyer')

@section('title', 'Pesanan')

@section('content')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    /* == Custom Soft Colors untuk Card == */
    .card-soft-success {
        background-color: #e8f5e9;
        border: 1px solid #c8e6c9;
    }
    .card-soft-warning {
        background-color: #fff8e1;
        border: 1px solid #ffecb3;
    }
    .card-soft-danger {
        background-color: #ffebee;
        border: 1px solid #ffcdd2;
    }

    .card-history {
        border-radius: 12px;
        margin-bottom: 1.5rem;
        transition: transform 0.2s;
    }
    .card-history:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* Modifikasi List Barang untuk Akomodasi Bintang */
    .item-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .item-list li {
        display: flex;
        justify-content: space-between;
        align-items: flex-start; /* Supaya harga tetap di atas jika nama barang punya bintang di bawahnya */
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        color: #495057;
        border-bottom: 1px dashed #e0e0e0;
        padding-bottom: 0.5rem;
    }
    .item-list li:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    /* Badge Status */
    .status-badge {
        font-size: 0.8rem;
        padding: 0.4em 0.8em;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Rating Stars Tampilan Statis (di List) */
    .static-rating i {
        font-size: 0.8rem;
        color: #e0e0e0; /* Warna abu untuk bintang kosong */
    }
    .static-rating i.filled {
        color: #ffc107; /* Warna kuning untuk bintang isi */
    }

    /* Rating Stars Interaktif (di Modal) */
    .star-rating {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }
    .star-rating.active {
        color: #ffc107;
    }
    .star-rating:hover {
        color: #ffd54f;
    }

    /* Style Item Status Badge Kecil */
    .item-status { font-size: 0.7rem; font-weight: bold; margin-left: 5px; }
    .text-process { color: #ff9800; }
    .text-done { color: #198754; }
</style>

<div class="container my-5" style="max-width: 800px;">
    <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>

    {{-- Contoh Card: Pesanan Selesai --}}
    <div class="card card-history card-soft-success">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="fw-bold mb-1">10 November 2025</h6>
                    <small class="text-muted">INV-20251110-001</small>
                </div>
                <span class="badge bg-success status-badge">Pembayaran Berhasil</span>
            </div>

            <hr class="opacity-25 border-dark">

            <ul class="item-list mb-3">
                <li>
                    <div class="d-flex flex-column">
                        <span>
                            2x Ayam Goreng Lengkuas 
                            <small class="item-status text-done"><i class="bi bi-check-circle-fill"></i> Diambil</small>
                        </span>
                        {{-- Contoh Rating Statis (Jika sudah dirating) --}}
                        <div class="static-rating mt-1">
                            <i class="bi bi-star-fill filled"></i>
                            <i class="bi bi-star-fill filled"></i>
                            <i class="bi bi-star-fill filled"></i>
                            <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <span class="ms-1 text-muted" style="font-size: 0.75rem;">(3.0)</span>
                        </div>
                    </div>
                    <span class="fw-bold">Rp 24.000</span>
                </li>
                
                <li>
                    <div class="d-flex flex-column">
                        <span>
                            1x Es Teh Manis 
                            <small class="item-status text-process"><i class="bi bi-hourglass-split"></i> Proses</small>
                        </span>
                    </div>
                    <span class="fw-bold">Rp 3.000</span>
                </li>
            </ul>

            <hr class="opacity-25 border-dark">

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted d-block">Total Pembayaran</small>
                    <h5 class="fw-bold text-success mb-0">Rp 27.000</h5>
                </div>
                
                {{-- Tombol Trigger Modal --}}
                <button class="btn btn-success btn-sm px-3 rounded-pill fw-bold shadow-sm" 
                    onclick='handleRatingButton("TRX-001", [
                        {name: "Ayam Goreng Lengkuas", status: "diambil", rating: 3},
                        {name: "Es Teh Manis", status: "proses", rating: null}
                    ])'>
                    <i class="bi bi-star-fill me-1"></i> Beri/Edit Ulasan
                </button>
            </div>
        </div>
    </div>

    {{-- Contoh Card: Menunggu Pembayaran --}}
    <div class="card card-history card-soft-warning">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="fw-bold mb-1">11 November 2025</h6>
                    <small class="text-muted">INV-20251111-045</small>
                </div>
                <span class="badge bg-warning text-dark status-badge">Menunggu Pembayaran</span>
            </div>

            <ul class="item-list mb-3">
                <li>
                    <span>1x Nasi Pecel Spesial</span>
                    <span class="fw-bold">Rp 12.000</span>
                </li>
            </ul>

            <hr class="opacity-25 border-dark">

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted d-block">Total Pembayaran</small>
                    <h5 class="fw-bold text-dark mb-0">Rp 12.000</h5>
                </div>
                <button class="btn btn-warning btn-sm px-3 rounded-pill fw-bold text-dark shadow-sm">
                    <i class="bi bi-wallet2 me-1"></i> Bayar Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- Contoh Card: Pembayaran Gagal --}}
    <div class="card card-history card-soft-danger">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="fw-bold mb-1">05 November 2025</h6>
                    <small class="text-muted">INV-20251105-099</small>
                </div>
                <span class="badge bg-danger status-badge">Pembayaran Gagal</span>
            </div>

            <ul class="item-list mb-3">
                <li>
                    <span>5x Tahu Walik</span>
                    <span class="fw-bold">Rp 10.000</span>
                </li>
            </ul>

            <hr class="opacity-25 border-dark">

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted d-block">Total Pembayaran</small>
                    <h5 class="fw-bold text-danger mb-0">Rp 10.000</h5>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL RATING --}}
<div class="modal fade" id="ratingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Beri Ulasan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                
                <form id="ratingForm">
                    <div class="mb-3 text-start" id="productSelectContainer">
                        <label class="form-label fw-bold small text-muted">Pilih Produk untuk Diulas:</label>
                        <select class="form-select" id="productSelect">
                        </select>
                        <div id="statusWarning" class="form-text text-danger d-none small fst-italic mt-1">
                            * Item yang masih diproses tidak dapat diberi ulasan.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Rating Anda</label>
                        <div id="starContainer">
                            <i class="bi bi-star star-rating" data-value="1"></i>
                            <i class="bi bi-star star-rating" data-value="2"></i>
                            <i class="bi bi-star star-rating" data-value="3"></i>
                            <i class="bi bi-star star-rating" data-value="4"></i>
                            <i class="bi bi-star star-rating" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue">
                    </div>

                    <div class="mb-3 text-start">
                        <label for="reviewText" class="form-label fw-bold small text-muted">Ulasan (Opsional)</label>
                        <textarea class="form-control" id="reviewText" rows="3" placeholder="Bagaimana rasa makanannya?"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success fw-bold py-2" id="btnSubmitRating">Kirim Ulasan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // === LOGIKA RATING ===

    // Data Global Sementara untuk menyimpan item yang sedang dibuka di modal
    let currentItems = [];

    // 1. Handle Klik Tombol (Menerima Array Object Items)
    function handleRatingButton(transactionId, items) {
        currentItems = items; // Simpan ke variabel global
        openRatingModal();
    }

    // 2. Buka Modal & Render Dropdown
    function openRatingModal() {
        const modal = new bootstrap.Modal(document.getElementById('ratingModal'));
        const select = document.getElementById('productSelect');
        const statusWarning = document.getElementById('statusWarning');
        const btnSubmit = document.getElementById('btnSubmitRating');
        
        // Reset Form
        document.getElementById('ratingForm').reset();
        resetStars();
        select.innerHTML = ''; // Kosongkan dropdown
        
        let hasProcessItem = false;
        let firstSelectableIndex = -1;

        // Loop Barang untuk membuat Option
        currentItems.forEach((item, index) => {
            const option = document.createElement('option');
            option.value = index; // Kita pakai index array sebagai value
            
            if (item.status !== 'diambil') {
                // KONDISI: BARANG BELUM DITERIMA -> DISABLED
                option.text = item.name + " (Belum Diterima)";
                option.disabled = true; // Tidak bisa dipilih
                option.style.color = "#999"; // Visual abu-abu
                hasProcessItem = true;
            } else {
                // KONDISI: BARANG SUDAH DITERIMA -> BISA RATING
                option.text = item.name;
                
                // Cek jika sudah pernah rating
                if(item.rating) {
                    option.text += " (Edit Ulasan)";
                }
                
                // Simpan index item pertama yang bisa dipilih
                if (firstSelectableIndex === -1) firstSelectableIndex = index;
            }
            
            select.appendChild(option);
        });

        // Tampilkan warning text jika ada item yg disable
        if(hasProcessItem) {
            statusWarning.classList.remove('d-none');
        } else {
            statusWarning.classList.add('d-none');
        }

        // Auto Select item pertama yang valid
        if (firstSelectableIndex !== -1) {
            select.value = firstSelectableIndex;
            loadExistingRating(firstSelectableIndex); // Load bintang jika sudah ada
            btnSubmit.disabled = false;
        } else {
            // Jika SEMUA barang masih proses
            const option = document.createElement('option');
            option.text = "Tidak ada barang yang bisa diulas";
            select.appendChild(option);
            select.disabled = true;
            btnSubmit.disabled = true;
        }

        modal.show();
    }

    // 3. Event Listener saat Dropdown Berubah (Pindah barang)
    document.getElementById('productSelect').addEventListener('change', function() {
        const index = this.value;
        loadExistingRating(index);
    });

    // Fungsi untuk memunculkan bintang lama jika user memilih barang yg sudah dirating
    function loadExistingRating(index) {
        resetStars();
        const item = currentItems[index];
        if (item && item.rating) {
            updateStars(item.rating);
            document.getElementById('ratingValue').value = item.rating;
        }
    }

    // 4. Interaksi Klik Bintang
    const stars = document.querySelectorAll('.star-rating');
    const ratingInput = document.getElementById('ratingValue');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            ratingInput.value = value;
            updateStars(value);
        });
    });

    function updateStars(value) {
        stars.forEach(star => {
            const starVal = star.getAttribute('data-value');
            if (starVal <= value) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
                star.classList.add('active');
            } else {
                star.classList.remove('bi-star-fill');
                star.classList.remove('active');
                star.classList.add('bi-star');
            }
        });
    }

    function resetStars() {
        ratingInput.value = '';
        stars.forEach(star => {
            star.classList.remove('bi-star-fill', 'active');
            star.classList.add('bi-star');
        });
    }

    // 5. Submit Form
    document.getElementById('ratingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const rating = document.getElementById('ratingValue').value;
        
        if (!rating) {
            Swal.fire('Eits!', 'Jangan lupa pilih bintangnya ya!', 'warning');
            return;
        }

        const modalEl = document.getElementById('ratingModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        Swal.fire({
            title: 'Terima Kasih!',
            text: 'Ulasan berhasil disimpan.',
            icon: 'success',
            confirmButtonColor: '#00897b'
        });
    });
</script>
@endsection