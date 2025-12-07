<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="cartOffcanvasLabel">Keranjang</h5>
    <span class="text-muted cart-count-span">0 barang</span>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>

<div class="offcanvas-body d-flex flex-column">

    {{-- LIST ITEM (Akan diisi oleh cart.js) --}}
    <div id="cartItemsContainer" class="flex-grow-1 overflow-auto">
        <div class="text-center my-5 empty-cart-placeholder">
            <i class="bi bi-basket3 fs-1 text-muted opacity-50"></i>
            <p class="text-muted mt-3">Keranjang kosong. Yuk, jajan dulu!</p>
        </div>
    </div>

    <hr>

    {{-- FORM CHECKOUT --}}
    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf

        {{-- Container Input Hidden (Diisi oleh cart.js) --}}
        <input type="hidden" name="total_bayar" id="inputTotalBayar">
        <div id="hiddenItemsContainer"></div>

        {{-- JADWAL PENGAMBILAN --}}
        <div class="mt-2 schedule-section">
            <h6 class="fw-bold small text-uppercase text-muted ls-1">Jadwal Pengambilan</h6>

            <div class="mb-3">
                <label for="tanggalPicker" class="form-label small mb-1">Tanggal</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-calendar-event text-primary"></i></span>
                    <input type="text" class="form-control" name="tanggal_pengambilan" id="tanggalPicker" placeholder="Pilih tanggal..." required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small mb-1">Waktu / Jam Istirahat</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-clock text-primary"></i></span>
                    <select class="form-select" name="detail_pengambilan" id="selectDetailPengambilan" required>
                        <option value="Istirahat 1">Istirahat 1 (09:30 - 10:00)</option>
                        <option value="Istirahat 2">Istirahat 2 (11:30 - 12:30)</option>
                        <option value="Pulang Sekolah">Pulang Sekolah (15:00)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- TOTAL & TOMBOL --}}
        <div class="mt-4 pt-3 border-top bg-white sticky-bottom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-muted mb-0">Total Bayar</h6>
                <h5 class="fw-bold mb-0 text-primary" id="cartGrandTotal">Rp 0</h5>
            </div>

            <div class="payment-info mb-3 p-2 bg-light rounded d-flex align-items-center">
                <i class="bi bi-shield-lock-fill text-success fs-4 me-3"></i>
                <div class="small lh-sm">
                    <span class="fw-bold d-block text-dark">Pembayaran Aman via Xendit</span>
                    <span class="text-muted" style="font-size: 0.75rem;">QRIS, E-Wallet, Transfer Bank</span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" id="btnKonfirmasi" disabled>
                Konfirmasi & Bayar
            </button>
        </div>
    </form>
</div>
