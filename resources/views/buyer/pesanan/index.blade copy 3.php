@extends('layouts.Buyer')

@section('title', 'Pesanan')

@section('content')
<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
    
    /* == Custom Soft Colors untuk Card == */
    .card-soft-success { background-color: #e8f5e9; border: 1px solid #c8e6c9; }
    .card-soft-warning { background-color: #fff8e1; border: 1px solid #ffecb3; }
    .card-soft-danger { background-color: #ffebee; border: 1px solid #ffcdd2; }

    .card-history {
        border-radius: 12px;
        margin-bottom: 1.5rem;
        transition: transform 0.2s;
    }
    .card-history:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* Modifikasi List Barang */
    .item-list { list-style: none; padding: 0; margin: 0; }
    .item-list li {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        color: #495057;
        border-bottom: 1px dashed #e0e0e0;
        padding-bottom: 0.5rem;
    }
    .item-list li:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }

    /* Badge & Text Styles */
    .status-badge { font-size: 0.8rem; padding: 0.4em 0.8em; border-radius: 20px; font-weight: 600; letter-spacing: 0.5px; }
    .item-status { font-size: 0.7rem; font-weight: bold; margin-left: 5px; }
    .text-process { color: #ff9800; }
    .text-ready { color: #0d6efd; }
    .text-done { color: #198754; }
    
    /* Rating Stars */
    .static-rating i { font-size: 0.8rem; color: #e0e0e0; }
    .static-rating i.filled { color: #ffc107; }
    .star-rating { font-size: 2rem; color: #ddd; cursor: pointer; transition: color 0.2s; }
    .star-rating.active { color: #ffc107; }
    .star-rating:hover { color: #ffd54f; }

    /* Animasi Timer */
    .countdown-timer { font-variant-numeric: tabular-nums; letter-spacing: 0.5px; }
</style>

<div class="container my-5" style="max-width: 800px;">
    <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-receipt text-muted opacity-25" style="font-size: 4rem;"></i>
            <p class="text-muted mt-3">Belum ada riwayat pesanan.</p>
            <a href="{{ route('buyer.menu.index') ?? '#' }}" class="btn btn-primary btn-sm rounded-pill px-4">Pesan Sekarang</a>
        </div>
    @else
        @foreach($orders as $order)
            @php
                // --- SETUP WAKTU & DEADLINE (30 MENIT) ---
                $transaksiTime = \Carbon\Carbon::parse($order->waktu_transaksi);
                $deadline = $transaksiTime->copy()->addMinutes(30);
                $now = \Carbon\Carbon::now();
                
                // Cek apakah sudah lewat 30 menit
                $isExpiredByTime = $now->greaterThan($deadline);

                // --- LOGIKA STATUS CARD ---
                $status = strtolower($order->status_pembayaran);

                $cardClass = 'card-soft-danger';
                $badgeClass = 'bg-danger';
                $statusLabel = 'Pembayaran Gagal';
                $isSuccess = false;
                $isPending = false;
                $showTimer = false;

                if ($status == 'success') {
                    $cardClass = 'card-soft-success';
                    $badgeClass = 'bg-success';
                    $statusLabel = 'Pembayaran Berhasil';
                    $isSuccess = true;
                } 
                elseif ($status == 'pending') {
                    if ($isExpiredByTime) {
                        // JIKA PENDING TAPI WAKTU HABIS -> ANGGAP GAGAL VISUAL
                        $statusLabel = 'Dibatalkan (Waktu Habis)';
                        // Card tetap merah (danger)
                    } else {
                        // JIKA PENDING DAN WAKTU MASIH ADA
                        $cardClass = 'card-soft-warning';
                        $badgeClass = 'bg-warning text-dark';
                        $statusLabel = 'Menunggu Pembayaran';
                        $isPending = true;
                        $showTimer = true;
                    }
                }
                elseif ($status == 'expired' || $status == 'failed') {
                    $statusLabel = $status == 'expired' ? 'Kadaluarsa' : 'Dibatalkan';
                }
            @endphp

            <div class="card card-history {{ $cardClass }}">
                <div class="card-body p-4">
                    {{-- Header Card --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">
                                {{ $transaksiTime->translatedFormat('d F Y, H:i') }}
                            </h6>
                            <small class="text-muted">{{ $order->kode_transaksi }}</small>
                        </div>
                        <span class="badge {{ $badgeClass }} status-badge">{{ $statusLabel }}</span>
                    </div>

                    <hr class="opacity-25 border-dark">

                    {{-- List Item Barang --}}
                    <ul class="item-list mb-3">
                        @foreach($order->detailTransaksi as $detail)
                            <li>
                                <div class="d-flex flex-column">
                                    <span>
                                        {{ $detail->jumlah }}x {{ $detail->barang->nama_barang ?? 'Produk Dihapus' }}
                                        
                                        @php
                                            $statusItem = strtolower(trim($detail->status_barang));
                                        @endphp

                                        {{-- LOGIKA TAMPILAN STATUS BARANG (Disembunyikan jika batal/expired) --}}
                                        @if($status != 'expired' && $status != 'failed' && !($status == 'pending' && $isExpiredByTime))
                                            @if($statusItem == 'sudah_diambil')
                                                <small class="item-status text-done"><i class="bi bi-check-circle-fill"></i> Diambil</small>
                                            @elseif($statusItem == 'belum_diambil')
                                                <small class="item-status text-ready"><i class="bi bi-box-seam-fill"></i> Siap Diambil</small>
                                            @else
                                                <small class="item-status text-process"><i class="bi bi-hourglass-split"></i> Proses</small>
                                            @endif
                                        @endif
                                    </span>

                                    {{-- Rating Statis --}}
                                    @if($isSuccess && $detail->ratingUlasan)
                                        <div class="static-rating mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star-fill {{ $i <= $detail->ratingUlasan->rating ? 'filled' : '' }}"></i>
                                            @endfor
                                            <span class="ms-1 text-muted" style="font-size: 0.75rem;">({{ number_format($detail->ratingUlasan->rating, 1) }})</span>
                                        </div>
                                    @endif
                                </div>
                                <span class="fw-bold">Rp {{ number_format($detail->harga_saat_transaksi * $detail->jumlah, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <hr class="opacity-25 border-dark">

                    {{-- Footer Card (Total & Action Button) --}}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted d-block">Total Pembayaran</small>
                            <h5 class="fw-bold {{ $isPending ? 'text-dark' : ($isSuccess ? 'text-success' : 'text-danger') }} mb-0">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </h5>
                        </div>
                        
                        @if ($isSuccess)
                            {{-- Jika SUCCESS: Tampilkan tombol Ulasan --}}
                            @php
                                $itemsForJs = $order->detailTransaksi->map(function($d) {
                                    return [
                                        'id_detail' => $d->id_detail,
                                        'name' => $d->barang->nama_barang ?? 'Produk',
                                        'status' => strtolower(trim($d->status_barang)),
                                        'rating' => $d->ratingUlasan ? $d->ratingUlasan->rating : null,
                                        'ulasan' => $d->ratingUlasan ? $d->ratingUlasan->ulasan : ''
                                    ];
                                })->toJson();
                            @endphp

                            <button class="btn btn-success btn-sm px-3 rounded-pill fw-bold shadow-sm" 
                                data-transaction="{{ $order->kode_transaksi }}"
                                data-items="{{ $itemsForJs }}"
                                onclick="handleRatingButton(this)">
                                <i class="bi bi-star-fill me-1"></i> Beri/Edit Ulasan
                            </button>

                        @elseif ($isPending)
                            {{-- Jika PENDING: Tampilkan tombol Bayar & Batalkan --}}
                            <div class="d-flex flex-column align-items-end">
                                <div class="d-flex gap-2">
                                    {{-- BUTTON BATALKAN --}}
                                    <button type="button" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-bold shadow-sm"
                                            onclick="cancelOrder('{{ $order->id_transaksi }}')">
                                        <i class="bi bi-x-circle me-1"></i> Batalkan
                                    </button>

                                    {{-- BUTTON BAYAR SEKARANG (Trigger Opsi Pembayaran) --}}
                                    <button type="button" class="btn btn-warning btn-sm px-3 rounded-pill fw-bold text-dark shadow-sm"
                                            onclick="showPaymentOptions('{{ $order->id_transaksi }}', '{{ $order->metode_pembayaran }}', '{{ $order->payment_link }}', 'Rp {{ number_format($order->total_harga, 0, ',', '.') }}')">
                                        <i class="bi bi-wallet2 me-1"></i> Bayar Sekarang
                                    </button>
                                </div>

                                {{-- TIMER HITUNG MUNDUR --}}
                                @if($showTimer)
                                    <small class="text-danger fw-bold countdown-timer mt-1" 
                                           data-deadline="{{ $deadline->timestamp * 1000 }}">
                                           <i class="bi bi-stopwatch"></i> Menghitung...
                                    </small>
                                @endif
                            </div>
                        
                        @else
                            {{-- Jika FAILED/EXPIRED --}}
                            <span class="text-muted small fst-italic text-end">
                                @if($isExpiredByTime && $status == 'pending')
                                    Waktu pembayaran (30 menit) habis.<br>Transaksi dibatalkan otomatis.
                                @else
                                    {{ $status == 'expired' ? 'Waktu pembayaran habis' : 'Transaksi dibatalkan' }}
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
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
                        <select class="form-select" id="productSelect"></select>
                    </div>

                    <div id="reviewUnavailableMessage" class="alert alert-warning d-none" role="alert">
                        <i class="bi bi-exclamation-circle d-block fs-1 mb-2 text-warning opacity-75"></i>
                        <h6 class="fw-bold mb-1">Ups, belum bisa diulas!</h6>
                        <small>Tidak dapat memberi ulasan pada barang ini karena status barang <strong>belum diambil</strong>.</small>
                    </div>

                    <div id="activeReviewSection">
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
                            <textarea class="form-control" id="reviewText" name="ulasan" rows="3" placeholder="Bagaimana rasa makanannya?"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold py-2" id="btnSubmitRating">
                                <span id="btnText">Kirim Ulasan</span>
                                <span id="btnLoading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==========================================
    // 1. SCRIPT COUNTDOWN TIMER (30 Menit)
    // ==========================================
    function startTimers() {
        const timers = document.querySelectorAll('.countdown-timer');

        timers.forEach(timer => {
            const deadline = parseInt(timer.getAttribute('data-deadline'));

            const updateTimer = () => {
                const now = new Date().getTime();
                const distance = deadline - now;

                if (distance < 0) {
                    timer.innerHTML = "Waktu Habis";
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timer.innerHTML = `<i class="bi bi-stopwatch"></i> Sisa Waktu: ${minutes}m ${seconds}d`;
            };

            updateTimer(); 
            setInterval(updateTimer, 1000);
        });
    }
    document.addEventListener('DOMContentLoaded', startTimers);


    // ==========================================
    // 2. LOGIKA PEMBAYARAN (BAYAR / GANTI METODE)
    // ==========================================
    // UPDATE: Menambahkan parameter 'totalFormatted'
    function showPaymentOptions(transactionId, currentMethod, currentLink, totalFormatted) {
        // Format teks metode lama agar lebih enak dibaca
        let displayMethod = currentMethod.replace('_', ' ');
        if(currentMethod === 'BANK_TRANSFER') displayMethod = 'Transfer Bank';
        if(currentMethod === 'E_WALLET') displayMethod = 'E-Wallet / QRIS';

        Swal.fire({
            title: 'Lanjutkan Pembayaran?',
            html: `
                <div class="text-start">
                    <div class="alert alert-light border d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Total Tagihan</span>
                        <span class="fw-bold text-primary fs-5">${totalFormatted}</span>
                    </div>
                    <p class="mb-2">Metode saat ini: <span class="badge bg-primary fs-6">${displayMethod}</span></p>
                    <p class="text-muted small mb-0 mt-3">
                        Anda dapat melanjutkan pembayaran dengan metode ini, atau menggantinya dengan metode lain.
                        <br><span class="text-danger">*Mengganti metode akan membuat Invoice baru.</span>
                    </p>
                </div>
            `,
            icon: 'info',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-arrow-right"></i> Lanjut Bayar',
            denyButtonText: '<i class="bi bi-arrow-repeat"></i> Ganti Metode',
            cancelButtonText: 'Tutup',
            confirmButtonColor: '#0d6efd',
            denyButtonColor: '#ffc107',
            customClass: {
                denyButton: 'text-dark fw-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Opsi 1: Lanjut link lama
                window.open(currentLink, '_blank');
            } else if (result.isDenied) {
                // Opsi 2: Ganti Metode
                // Pass currentMethod to pre-select it
                selectNewMethod(transactionId, currentMethod);
            }
        });
    }

    function selectNewMethod(transactionId, currentMethod) {
        Swal.fire({
            title: 'Pilih Metode Baru',
            input: 'select',
            inputOptions: {
                'BANK_TRANSFER': 'Transfer Bank (Admin Rp 4.500)',
                'E_WALLET': 'E-Wallet / QRIS (Admin 2%)'
            },
            inputValue: currentMethod, // Set default value to current method
            html: `
                <div class="alert alert-warning text-start small mt-2 d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                    <div>
                        <strong>Penting:</strong><br>
                        Biaya admin dan total bayar akan dihitung ulang menyesuaikan metode yang Anda pilih.
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Proses Ganti',
            showLoaderOnConfirm: true,
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('Silakan pilih metode pembayaran!');
                    return false;
                }
                
                // Kirim Request ke Server untuk buat invoice baru
                return fetch("{{ route('buyer.orders.repay') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ 
                        id_transaksi: transactionId, 
                        payment_method: value 
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Gagal memproses: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.status === 'success') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Invoice baru berhasil dibuat. Mengalihkan...',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Redirect ke link pembayaran baru
                    window.location.href = result.value.new_link;
                });
            } else if (result.isConfirmed && result.value.status === 'error') {
                Swal.fire('Gagal', result.value.message, 'error');
            }
        });
    }

    // ==========================================
    // 3. LOGIKA BATALKAN PESANAN
    // ==========================================
    function cancelOrder(transactionId) {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Apakah Anda yakin ingin membatalkan pesanan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({title: 'Memproses...', didOpen: () => Swal.showLoading()});
                
                fetch("{{ route('buyer.orders.cancel') }}", { 
                    method: "POST",
                    headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}"},
                    body: JSON.stringify({ id_transaksi: transactionId })
                })
                .then(r => r.json())
                .then(d => {
                    if(d.status === 'success') {
                        Swal.fire('Dibatalkan!', 'Pesanan berhasil dibatalkan.', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', d.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Gagal memproses permintaan', 'error'));
            }
        });
    }

    // ==========================================
    // 4. LOGIKA RATING & ULASAN
    // ==========================================
    let currentItems = [];

    function handleRatingButton(btnElement) {
        try {
            const items = JSON.parse(btnElement.getAttribute('data-items'));
            currentItems = items; 
            openRatingModal();
        } catch (e) {
            console.error("Gagal memproses data item:", e);
            Swal.fire('Error', 'Gagal memuat data produk.', 'error');
        }
    }

    function openRatingModal() {
        const modal = new bootstrap.Modal(document.getElementById('ratingModal'));
        const select = document.getElementById('productSelect');
        const btnSubmit = document.getElementById('btnSubmitRating');
        
        document.getElementById('ratingForm').reset();
        resetStars();
        select.innerHTML = '';
        
        currentItems.forEach((item, index) => {
            const option = document.createElement('option');
            option.value = index; 
            
            const status = item.status ? item.status.toLowerCase() : '';

            // LOGIKA LABEL DROPDOWN JS
            if (status === 'sudah_diambil') {
                option.text = item.name;
                if(item.rating) {
                    option.text += " (Edit Ulasan)";
                }
            } else if (status === 'belum_diambil') {
                option.text = item.name + " (Siap Diambil)";
            } else {
                option.text = item.name + " (Proses)";
            }
            select.appendChild(option);
        });

        if (currentItems.length > 0) {
            select.value = 0;
            updateModalUI(0); 
        }

        modal.show();
    }

    document.getElementById('productSelect').addEventListener('change', function() {
        const index = this.value;
        updateModalUI(index);
    });

    function updateModalUI(index) {
        const item = currentItems[index];
        if (!item) return;

        const activeSection = document.getElementById('activeReviewSection');
        const warningMessage = document.getElementById('reviewUnavailableMessage');
        const status = item.status ? item.status.toLowerCase() : '';

        resetStars();
        document.getElementById('reviewText').value = '';

        if (status !== 'sudah_diambil') {
            activeSection.classList.add('d-none');
            warningMessage.classList.remove('d-none');
            
            const warningText = warningMessage.querySelector('small');
            if(status === 'belum_diambil') {
                warningText.innerHTML = "Silakan ambil pesanan Anda terlebih dahulu sebelum memberi ulasan.";
            } else {
                warningText.innerHTML = "Tidak dapat memberi ulasan karena pesanan masih dalam <strong>proses</strong>.";
            }
        } else {
            activeSection.classList.remove('d-none');
            warningMessage.classList.add('d-none');
            document.getElementById('reviewText').value = item.ulasan || '';
            if (item && item.rating) {
                updateStars(item.rating);
                document.getElementById('ratingValue').value = item.rating;
            }
        }
    }

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

    document.getElementById('ratingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const rating = document.getElementById('ratingValue').value;
        const reviewText = document.getElementById('reviewText').value;
        const selectedIndex = document.getElementById('productSelect').value;
        const selectedItem = currentItems[selectedIndex];
        const status = selectedItem.status ? selectedItem.status.toLowerCase() : '';

        if (status !== 'sudah_diambil') {
             Swal.fire('Error', 'Barang ini belum selesai diambil, tidak bisa diberi ulasan.', 'error');
             return;
        }

        if (!rating) {
            Swal.fire('Eits!', 'Jangan lupa pilih bintangnya ya!', 'warning');
            return;
        }

        if (!selectedItem || !selectedItem.id_detail) {
            Swal.fire('Error', 'Terjadi kesalahan memilih produk.', 'error');
            return;
        }

        const btn = document.getElementById('btnSubmitRating');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        
        btn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');

        fetch("{{ route('buyer.orders.rating.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                id_detail: selectedItem.id_detail,
                rating: rating,
                ulasan: reviewText
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                const modalEl = document.getElementById('ratingModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); 
                });
            } else {
                Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
        });
    });
</script>
@endsection