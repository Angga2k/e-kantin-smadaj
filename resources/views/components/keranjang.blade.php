<div class="offcanvas-header border-bottom">
<h5 class="offcanvas-title fw-bold" id="cartOffcanvasLabel">Keranjang</h5>
<span class="text-muted"> 4 barang</span>
<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>

<div class="offcanvas-body">

<div class="cart-item card1">
    <img src="{{ asset('tes/ayam.png') }}" alt="Ayam Goreng" style="background-color: #eee;">
    <div class="cart-item-details">
        <h6>Ayam Goreng</h6>
        <p class="cart-item-price">Harga : Rp. 12.000<br>
        <span class="cart-item-price-total">Total : Rp. 12.000 </span></p>
    </div>
    <div class="cart-quantity-selector">
        <button class="btn btn-light btn-sm">+</button>
        <span class="fw-bold">1</span>
        <button class="btn btn-light btn-sm">-</button>
    </div>
</div>

<div class="cart-item card2">
    <img src="{{ asset('tes/esteler.png') }}" alt="EsTeler" style="background-color: #eee;">
    <div class="cart-item-details">
        <h6>Es Teler</h6>
        <p class="cart-item-price">Harga : Rp. 8.000<br>
        <span class="cart-item-price-total">Total : Rp. 24.000 </span></p>
    </div>
    <div class="cart-quantity-selector">
        <button class="btn btn-light btn-sm">+</button>
        <span class="fw-bold">3</span>
        <button class="btn btn-light btn-sm">-</button>
    </div>
</div>

<div class="cart-item card3">
    <img src="{{ asset('tes/kripik.jpg') }}" alt="kripik" style="background-color: #eee;">
    <div class="cart-item-details">
        <h6>Keripik Pedas</h6>
        <p class="cart-item-price">Harga : Rp. 5.000<br>
        <span class="cart-item-price-total">Total : Rp. 5.000 </span></p>
    </div>
    <div class="cart-quantity-selector">
        <button class="btn btn-light btn-sm">+</button>
        <span class="fw-bold">1</span>
        <button class="btn btn-light btn-sm">-</button>
    </div>
</div>

<div class="cart-item card3">
    <img src="{{ asset('tes/gorengan.jpg') }}" alt="gorengan" style="background-color: #eee;">
    <div class="cart-item-details">
        <h6>Gorengan</h6>
        <p class="cart-item-price">Harga : Rp. 2.000<br>
        <span class="cart-item-price-total">Total : Rp. 4.000 </span></p>
    </div>
    <div class="cart-quantity-selector">
        <button class="btn btn-light btn-sm">+</button>
        <span class="fw-bold">2</span>
        <button class="btn btn-light btn-sm">-</button>
    </div>
</div>
<hr>
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
        <h5 class="fw-bold mb-0">Rp. 45.000</h5>
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
