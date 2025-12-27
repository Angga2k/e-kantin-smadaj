
<script>
    // ==========================================
    // 1. SCRIPT COUNTDOWN TIMER
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
    function showPaymentOptions(transactionId, currentMethod, currentLink, totalFormatted) {
        let displayMethod = currentMethod.replace('_', ' ');
        if(currentMethod === 'BANK_TRANSFER') displayMethod = 'Transfer Bank';
        if(currentMethod === 'E_WALLET') displayMethod = 'E-Wallet / QRIS';

        // Parsing nilai total dari string rupiah (misal "Rp 10.200" -> 10200)
        const rawTotal = parseInt(totalFormatted.replace(/[^0-9]/g, '')) || 0;

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
            customClass: { denyButton: 'text-dark fw-bold' }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = currentLink;
            } else if (result.isDenied) {
                // Oper rawTotal ke fungsi selectNewMethod
                selectNewMethod(transactionId, currentMethod, rawTotal);
            }
        });
    }

    function selectNewMethod(transactionId, currentMethod, currentTotal) {
        // --- 1. Hitung Mundur (Reverse Calculation) untuk dapat Subtotal ---
        let subtotal = 0;
        
        if (currentMethod === 'BANK_TRANSFER') {
            // Bank = Subtotal + 4500
            subtotal = currentTotal - 4500;
        } else if (currentMethod === 'E_WALLET') {
            // E-Wallet = Subtotal + (Subtotal * 2%) -> Subtotal * 1.02
            subtotal = currentTotal / 1.02;
        } else {
            // Fallback jika data aneh
            subtotal = currentTotal;
        }

        // --- 2. Hitung Estimasi Total Baru ---
        const totalBank = subtotal + 4500;
        const totalEwallet = subtotal + (subtotal * 0.02);

        // Helper Format Rupiah
        const fmt = (num) => 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        Swal.fire({
            title: 'Pilih Metode Baru',
            input: 'select',
            inputOptions: {
                'BANK_TRANSFER': `Transfer Bank (${fmt(totalBank)})`,
                'E_WALLET': `E-Wallet / QRIS (${fmt(totalEwallet)})`
            },
            inputValue: currentMethod, // Default select ke metode saat ini
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

                // CEK JIKA METODE SAMA
                if (value === currentMethod) {
                    Swal.showValidationMessage('Metode pembayaran masih sama seperti sebelumnya.');
                    return false;
                }
                
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
                    if (!response.ok) throw new Error(response.statusText);
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

            if (status === 'sudah_diambil') {
                option.text = item.name;
                if(item.rating) option.text += " (Edit Ulasan)";
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
            if(status === 'belum_diambil') warningText.innerHTML = "Silakan ambil pesanan Anda terlebih dahulu sebelum memberi ulasan.";
            else warningText.innerHTML = "Tidak dapat memberi ulasan karena pesanan masih dalam <strong>proses</strong>.";
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
                Swal.fire({title: 'Berhasil!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false}).then(() => location.reload());
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