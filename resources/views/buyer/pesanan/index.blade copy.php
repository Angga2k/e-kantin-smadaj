<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Saya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* == Custom Soft Colors untuk Card == */
        .card-soft-success {
            background-color: #e8f5e9; /* Hijau Lembut */
            border: 1px solid #c8e6c9;
        }
        .card-soft-warning {
            background-color: #fff8e1; /* Kuning Lembut */
            border: 1px solid #ffecb3;
        }
        .card-soft-danger {
            background-color: #ffebee; /* Merah Lembut */
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

        /* List Barang */
        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .item-list li {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            color: #495057;
        }

        /* Badge Status */
        .status-badge {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Rating Stars di Modal */
        .star-rating {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-rating.active {
            color: #ffc107; /* Warna Kuning Emas */
        }
        .star-rating:hover {
            color: #ffd54f;
        }
    </style>
</head>
<body>

    <div class="container my-5" style="max-width: 800px;">
        <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>

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
                        <span>2x Ayam Goreng Lengkuas</span>
                        <span class="fw-bold">Rp 24.000</span>
                    </li>
                    <li>
                        <span>1x Es Teh Manis</span>
                        <span class="fw-bold">Rp 3.000</span>
                    </li>
                </ul>

                <hr class="opacity-25 border-dark">

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted d-block">Total Pembayaran</small>
                        <h5 class="fw-bold text-success mb-0">Rp 27.000</h5>
                    </div>
                    <button class="btn btn-success btn-sm px-3 rounded-pill fw-bold shadow-sm" 
                            onclick="handleRatingButton('TRX-001', false, ['Ayam Goreng Lengkuas', 'Es Teh Manis'])">
                        <i class="bi bi-star-fill me-1"></i> Beri Ulasan
                    </button>
                </div>
            </div>
        </div>

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
                    <button class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-bold" disabled>
                        Dibatalkan
                    </button>
                </div>
            </div>
        </div>

        <div class="card card-history card-soft-success">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="fw-bold mb-1">01 November 2025</h6>
                        <small class="text-muted">INV-20251101-022</small>
                    </div>
                    <span class="badge bg-success status-badge">Pembayaran Berhasil</span>
                </div>

                <ul class="item-list mb-3">
                    <li>
                        <span>1x Jus Alpukat</span>
                        <span class="fw-bold">Rp 10.000</span>
                    </li>
                </ul>

                <hr class="opacity-25 border-dark">

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted d-block">Total Pembayaran</small>
                        <h5 class="fw-bold text-success mb-0">Rp 10.000</h5>
                    </div>
                    <button class="btn btn-outline-success btn-sm px-3 rounded-pill fw-bold" 
                            onclick="handleRatingButton('TRX-004', true, ['Jus Alpukat'])">
                        <i class="bi bi-star-half me-1"></i> Edit Ulasan
                    </button>
                </div>
            </div>
        </div>

    </div>

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
                            <button type="submit" class="btn btn-success fw-bold py-2">Kirim Ulasan</button>
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

        // 1. Handle Klik Tombol Rating
        function handleRatingButton(transactionId, isRated, items) {
            if (isRated) {
                // Jika sudah rating, tampilkan SweetAlert Konfirmasi
                Swal.fire({
                    title: 'Ulasan Sudah Ada',
                    text: "Anda sudah memberikan rating untuk pesanan ini. Apakah ingin mengubahnya?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#00897b',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Edit Ulasan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        openRatingModal(transactionId, items);
                    }
                });
            } else {
                // Jika belum rating, langsung buka modal
                openRatingModal(transactionId, items);
            }
        }

        // 2. Membuka Modal & Menyiapkan Data
        function openRatingModal(transactionId, items) {
            const modal = new bootstrap.Modal(document.getElementById('ratingModal'));
            const select = document.getElementById('productSelect');
            const selectContainer = document.getElementById('productSelectContainer');

            // Reset Form
            document.getElementById('ratingForm').reset();
            resetStars();
            
            // Logika Dropdown Barang
            select.innerHTML = ''; // Kosongkan dulu
            
            if (items.length > 1) {
                // Jika barang > 1, user harus milih
                selectContainer.style.display = 'block';
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item;
                    option.text = item;
                    select.appendChild(option);
                });
            } else {
                // Jika barang cuma 1, otomatis pilih dan sembunyikan dropdown (atau disable)
                // Disini saya pilih disable tapi tetap tampil agar user tau apa yg direview
                selectContainer.style.display = 'block'; 
                const option = document.createElement('option');
                option.value = items[0];
                option.text = items[0];
                select.appendChild(option);
                // select.disabled = true; // Opsional
            }

            modal.show();
        }

        // 3. Interaksi Bintang
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

        // 4. Submit Dummy
        document.getElementById('ratingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const rating = document.getElementById('ratingValue').value;
            
            if (!rating) {
                Swal.fire('Eits!', 'Jangan lupa pilih bintangnya ya!', 'warning');
                return;
            }

            // Tutup Modal
            const modalEl = document.getElementById('ratingModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            // Tampilkan Sukses
            Swal.fire({
                title: 'Terima Kasih!',
                text: 'Ulasan Anda berhasil dikirim.',
                icon: 'success',
                confirmButtonColor: '#00897b'
            });
        });

    </script>
</body>
</html>