<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="cartOffcanvasLabel">Keranjang</h5>
    {{-- Total hitungan barang akan diisi oleh JavaScript --}}
    <span class="text-muted cart-count-span">0 barang</span>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>

<div class="offcanvas-body">

    {{-- CONTAINER UTAMA UNTUK ITEM KERANJANG --}}
    {{-- JavaScript akan me-render item di sini --}}
    <div id="cartItemsContainer">
        {{-- Placeholder default saat keranjang kosong --}}
        <p class="text-center text-muted my-5">Keranjang kosong. Yuk, tambahkan menu favoritmu!</p>
    </div>

    <hr>
    {{-- JADWAL PENGAMBILAN --}}
    <div class="mt-4 schedule-section">
        <h6 class="fw-bold">JADWAL PENGAMBILAN</h6>
        <div class="input-group mt-3" style="max-width: 400px;">
            <label for="tanggalPicker" class="form-label fw-bold">Pilih Tanggal Pengambilan</label>
            <div class="input-group my-3">
                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                <input type="text" class="form-control" id="tanggalPicker" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-clock"></i></span>
            <select class="form-select">
                <option selected>Istirahat 1</option>
                <option value="1">Istirahat 2</option>
                <option value="2">Pulang Sekolah</option>
            </select>
        </div>
    </div>


    <div class="mt-4 end-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted mb-0">Grand Total</h6>
            {{-- Grand Total akan diisi oleh JavaScript --}}
            <h5 class="fw-bold mb-0" id="cartGrandTotal">Rp. 0</h5>
        </div>
        <div class="payment-method mb-3">
            <div class="icon-bg"><i class="bi bi-three-dots"></i></div>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold small">Metode Pembayaran</h6>
                <p class="text-danger mb-0">Pilih Metode Pembayaran</p>
            </div>
            <i class="bi bi-chevron-right"></i>
        </div>
        <button class="btn btn-confirm w-100">Konfirmasi Pembelian</button>
    </div>

</div>
