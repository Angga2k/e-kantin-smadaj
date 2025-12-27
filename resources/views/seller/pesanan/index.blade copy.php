@extends('layouts.Seller')

@section('title', 'Pesanan')

@section('content')
<div class="mb-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Beranda</a></li><li class="breadcrumb-item active" aria-current="page">Pesanan</li></ol></nav>
    <h1 class="h3 fw-bold">Pesanan</h1>
</div>

<div class="status-filters d-flex gap-2 mb-4">
    <button class="btn active" data-status="baru">Pesanan Baru</button>
    <button class="btn" data-status="diproses">Pesanan Diproses</button>
    <button class="btn" data-status="siap">Siap Diambil</button>
</div>

<div class="row" id="order-grid">
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="order-card  h-100" data-status="baru">
            <h6 class="fw-bold mb-3">Order ID: #123</h6>
            <div class="order-card-body"><dl class="mb-0"><div class="order-row"><dt>Nama Pelanggan</dt><dd>Magistra</dd></div><div class="order-row"><dt>Waktu Pesan</dt><dd>12 Feb 2025, 14:14</dd></div><div class="order-row"><dt>Detail Pesan</dt><dd>1x Ayam Goreng, Pedas<br>1x Es Teh, Tawar</dd></div><div class="order-row"><dt>Pengambilan</dt><dd>15 Feb 2025, Istirahat 1</dd></div><div class="order-row"><dt>Total</dt><dd class="fw-bold">Rp. 20.000</dd></div></dl></div>
            <hr>
            <div class="order-card-footer"><div class="row g-2"><div class="col-6"><button class="btn btn-cancel">Detail</button></div><div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div></div></div>
        </div>
    </div>

        <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="order-card h-100" data-status="baru">
            <h6 class="fw-bold mb-3">Order ID: #124</h6>
            <div class="order-card-body"><dl class="mb-0"><div class="order-row"><dt>Nama Pelanggan</dt><dd>Budi Hartono</dd></div><div class="order-row"><dt>Waktu Pesan</dt><dd>12 Feb 2025, 14:18</dd></div><div class="order-row"><dt>Detail Pesan</dt><dd>2x Nasi Padang</dd></div><div class="order-row"><dt>Pengambilan</dt><dd>15 Feb 2025, Istirahat 1</dd></div><div class="order-row"><dt>Total</dt><dd class="fw-bold">Rp. 24.000</dd></div></dl></div>
            <hr>
            <div class="order-card-footer"><div class="row g-2"><div class="col-6"><button class="btn btn-cancel">Detail</button></div><div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div></div></div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="order-card h-100" data-status="baru">
            <h6 class="fw-bold mb-3">Order ID: #125</h6>
            <div class="order-card-body"><dl class="mb-0"><div class="order-row"><dt>Nama Pelanggan</dt><dd>Citra Lestari</dd></div><div class="order-row"><dt>Waktu Pesan</dt><dd>12 Feb 2025, 14:21</dd></div><div class="order-row"><dt>Detail Pesan</dt><dd>1x Es Teler</dd></div><div class="order-row"><dt>Pengambilan</dt><dd>15 Feb 2025, Istirahat 2</dd></div><div class="order-row"><dt>Total</dt><dd class="fw-bold">Rp. 12.000</dd></div></dl></div>
            <hr>
            <div class="order-card-footer"><div class="row g-2"><div class="col-6"><button class="btn btn-cancel">Detail</button></div><div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div></div></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="order-card h-100" data-status="baru">
            <h6 class="fw-bold mb-3">Order ID: #125</h6>
            <div class="order-card-body"><dl class="mb-0"><div class="order-row"><dt>Nama Pelanggan</dt><dd>Citra Lestari</dd></div><div class="order-row"><dt>Waktu Pesan</dt><dd>12 Feb 2025, 14:21</dd></div><div class="order-row"><dt>Detail Pesan</dt><dd>1x Es Teler</dd></div><div class="order-row"><dt>Pengambilan</dt><dd>15 Feb 2025, Istirahat 2</dd></div><div class="order-row"><dt>Total</dt><dd class="fw-bold">Rp. 12.000</dd></div></dl></div>
            <hr>
            <div class="order-card-footer"><div class="row g-2"><div class="col-6"><button class="btn btn-cancel">Detail</button></div><div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div></div></div>
        </div>
    </div>
</div>
@endsection
