@php
    // === LOGIKA PENGECEKAN TRANSAKSI PENDING ===
    $pendingTransaction = null;
    $deadlineTimestamp = 0;

    if (auth()->check()) {
        $pendingTransaction = \App\Models\Transaksi::where('id_user_pembeli', auth()->id())
            ->where('status_pembayaran', 'pending')
            ->where('waktu_transaksi', '>=', now()->subMinutes(30)) 
            ->latest('waktu_transaksi')
            ->first();

        if ($pendingTransaction) {
            $deadline = \Carbon\Carbon::parse($pendingTransaction->waktu_transaksi)->addMinutes(30);
            $deadlineTimestamp = $deadline->timestamp * 1000; 
        }
    }
@endphp

<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="cartOffcanvasLabel">Keranjang</h5>
    <span class="text-muted cart-count-span">0 barang</span>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>

<div class="offcanvas-body d-flex flex-column bg-light">

    {{-- LIST ITEM --}}
    <div id="cartItemsContainer" class="flex-grow-1 overflow-auto bg-white p-3 rounded shadow-sm mb-3">
        <div class="text-center my-5 empty-cart-placeholder">
            <i class="bi bi-basket3 fs-1 text-muted opacity-50"></i>
            <p class="text-muted mt-3">Keranjang kosong. Yuk, jajan dulu!</p>
        </div>
    </div>

    {{-- FORM CHECKOUT --}}
    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm"
          data-has-pending="{{ $pendingTransaction ? 'true' : 'false' }}"
          data-pending-deadline="{{ $deadlineTimestamp }}"
          data-orders-url="{{ route('buyer.orders.index') }}">
        
        @csrf
        <input type="hidden" name="total_bayar" id="inputTotalBayar"> {{-- Total Murni Barang --}}
        <div id="hiddenItemsContainer"></div>

        <div class="p-3 bg-white rounded shadow-sm">
            {{-- PILIH METODE PEMBAYARAN --}}
            <div class="mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted ls-1">Metode Pembayaran</label>
                <select class="form-select border-primary" name="payment_method" id="selectPaymentMethod" required>
                    <option value="" selected disabled>-- Pilih Cara Bayar --</option>
                    <option value="BANK_TRANSFER" data-fee-type="flat" data-fee="4500">
                        Transfer Bank (Admin Rp 4.500)
                    </option>
                    <option value="E_WALLET" data-fee-type="percent" data-fee="0.02">
                        E-Wallet / QRIS (Admin 2%)
                    </option>
                </select>
            </div>

            {{-- JADWAL PENGAMBILAN --}}
            <div class="mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted ls-1">Jadwal Pengambilan</label>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="bi bi-calendar-event"></i></span>
                            <input type="text" class="form-control" name="tanggal_pengambilan" id="tanggalPicker" placeholder="Tgl..." required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="bi bi-clock"></i></span>
                            <select class="form-select" name="detail_pengambilan" id="selectDetailPengambilan" required>
                                <option value="Istirahat 1">Istirahat 1</option>
                                <option value="Istirahat 2">Istirahat 2</option>
                                <option value="Pulang Sekolah">Pulang</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-2 border-secondary opacity-10">

            {{-- RINCIAN BIAYA --}}
            <div class="d-flex justify-content-between align-items-center mb-1 small">
                <span class="text-muted">Subtotal Barang</span>
                <span class="fw-bold" id="displaySubtotal">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2 small">
                <span class="text-muted">Biaya Layanan</span>
                <span class="fw-bold text-danger" id="displayAdminFee">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Total Bayar</h6>
                <h5 class="mb-0 fw-bold text-primary" id="displayGrandTotal">Rp 0</h5>
            </div>
        </div>

        {{-- TOMBOL CHECKOUT --}}
        <div class="mt-3">
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" id="btnKonfirmasi" disabled>
                Konfirmasi & Bayar
            </button>
        </div>
    </form>
</div>

<script>
    // Script Khusus untuk Kalkulasi Admin Fee Realtime di View ini
    document.addEventListener("DOMContentLoaded", function() {
        const selectPayment = document.getElementById('selectPaymentMethod');
        const displaySubtotal = document.getElementById('displaySubtotal');
        const displayAdminFee = document.getElementById('displayAdminFee');
        const displayGrandTotal = document.getElementById('displayGrandTotal');
        // Elemen cartGrandTotal berasal dari cart.js (disembunyikan/diganti fungsinya)
        const cartGrandTotalEl = document.getElementById('cartGrandTotal'); 

        // Fungsi Format Rupiah
        const formatRupiah = (num) => 'Rp ' + num.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        // Fungsi parse Rupiah string kembali ke angka (jika ambil dari innerText elemen lain)
        const parseRupiah = (str) => parseInt(str.replace(/[^0-9]/g, '')) || 0;

        // Fungsi Kalkulasi Utama
        function calculateFinalTotal() {
            // Ambil subtotal murni dari elemen yang diupdate oleh cart.js (jika ada)
            // Atau hitung ulang dari localStorage jika perlu. 
            // Untuk amannya, kita baca dari elemen #cartGrandTotal jika cart.js merender ke sana, 
            // TAPI karena di kode ini saya ganti ID tampilannya, kita ambil dari LocalStorage langsung agar akurat.
            
            let cartItems = [];
            try {
                cartItems = JSON.parse(localStorage.getItem('e_kantin_cart')) || [];
            } catch(e) {}

            const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            // Ambil fee dari dropdown
            const selectedOption = selectPayment.options[selectPayment.selectedIndex];
            const feeType = selectedOption.getAttribute('data-fee-type');
            const feeValue = parseFloat(selectedOption.getAttribute('data-fee')) || 0;

            let adminFee = 0;
            if (feeType === 'flat') {
                adminFee = feeValue;
            } else if (feeType === 'percent') {
                adminFee = subtotal * feeValue;
            }

            const grandTotal = subtotal + adminFee;

            // Update Tampilan
            if (displaySubtotal) displaySubtotal.innerText = formatRupiah(subtotal);
            if (displayAdminFee) displayAdminFee.innerText = formatRupiah(adminFee);
            if (displayGrandTotal) displayGrandTotal.innerText = formatRupiah(grandTotal);
        }

        // Listener saat Dropdown Berubah
        if (selectPayment) {
            selectPayment.addEventListener('change', calculateFinalTotal);
        }

        // Listener saat Offcanvas Dibuka (agar hitungan terupdate saat item keranjang berubah)
        const cartOffcanvas = document.getElementById('cartOffcanvas');
        if (cartOffcanvas) {
            cartOffcanvas.addEventListener('shown.bs.offcanvas', calculateFinalTotal);
        }
        
        // Listener tambahan: MutationObserver untuk memantau jika cart.js mengubah item
        // (Opsional, tapi bagus untuk sinkronisasi)
        const observerTarget = document.getElementById('cartItemsContainer');
        if (observerTarget) {
            const observer = new MutationObserver(calculateFinalTotal);
            observer.observe(observerTarget, { childList: true, subtree: true });
        }
    });
</script>