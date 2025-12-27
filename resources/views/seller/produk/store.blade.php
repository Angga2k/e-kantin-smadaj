@extends('layouts.Seller')

@section('title', isset($produk) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<style>
    @media (max-width: 575.98px) {
        .breadcrumb {
            font-size: 0.75rem;
            margin-bottom: 0.5rem !important;
            padding: 0 !important;
        }

        .breadcrumb-item {
            padding: 0 !important;
        }

        h2 {
            font-size: 1.3rem;
        }

        .card-body {
            padding: 1rem !important;
        }

        .row.g-5 {
            gap: 1.5rem !important;
        }

        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.35rem !important;
        }

        .form-control,
        .form-control:focus {
            font-size: 0.95rem;
            padding: 0.5rem 0.75rem;
        }

        .input-group-text {
            font-size: 0.8rem;
            padding: 0.5rem 0.6rem;
        }

        .input-group .form-control {
            font-size: 0.9rem;
        }

        .btn {
            padding: 0.5rem 1rem !important;
            font-size: 0.9rem !important;
        }

        .pill-options {
            gap: 0.5rem !important;
        }

        .pill-options .btn {
            padding: 0.4rem 0.8rem !important;
            font-size: 0.85rem !important;
        }

        .img-upload-box {
            min-height: 100px !important;
        }

        .img-upload-box {
            min-height: 100px !important;
        }

        textarea {
            font-size: 0.95rem;
        }

        .d-flex.flex-column.flex-md-row {
            gap: 1rem !important;
        }
    }
</style>

<div class="container my-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    {{-- PERBAIKAN: Gunakan 'seller.beranda.index' bukan 'seller.index' --}}
                    <li class="breadcrumb-item"><a href="{{ route('seller.beranda.index') }}" class="text-decoration-none text-muted">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('seller.produk.index') }}" class="text-decoration-none text-muted">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ isset($produk) ? 'Edit' : 'Tambah' }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">{{ isset($produk) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h2>
        </div>
    </div>

    <div class="card product-card">
        <div class="card-body p-4 p-md-5">
            {{--
                LOGIKA FORM:
                1. Action: Jika ada $produk, arahkan ke route update. Jika tidak, ke route store.
                2. Enctype: Wajib ada multipart/form-data karena ada upload gambar.
            --}}
            <form action="{{ isset($produk) ? route('seller.produk.update', $produk->id_barang) : route('seller.produk.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="formProduk">

                @csrf
                {{-- Jika Edit, tambahkan method PUT --}}
                @if(isset($produk))
                    @method('PUT')
                @endif

                <div class="row g-5">
                    {{-- KOLOM KIRI (GAMBAR & DESKRIPSI) --}}
                    <div class="col-lg-4">
                        <div class="mb-4">
                            <label class="form-label">Foto Produk</label>
                            <label for="uploadGambar" class="img-upload-box d-block w-100 position-relative" style="cursor: pointer; border: 2px dashed #ddd; border-radius: 8px; overflow: hidden; min-height: 200px; display: flex; align-items: center; justify-content: center;">

                                {{-- Preview Gambar: Tampilkan jika sedang edit dan ada fotonya --}}
                                <img src="{{ isset($produk) && $produk->foto_barang ? asset($produk->foto_barang) : '' }}"
                                     alt="Preview Gambar"
                                     class="img-preview w-100 h-100 {{ isset($produk) && $produk->foto_barang ? 'd-block' : 'd-none' }}"
                                     id="imagePreview"
                                     style="object-fit: cover; position: absolute; top:0; left:0;">

                                {{-- Placeholder Text: Sembunyikan jika gambar sudah ada --}}
                                <div id="placeholderText" class="text-center p-3 {{ isset($produk) && $produk->foto_barang ? 'd-none' : '' }}">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                    <div class="mt-2 text-muted small">Klik untuk upload gambar</div>
                                </div>
                            </label>
                            <input type="file" name="foto_barang" id="uploadGambar" class="d-none" accept="image/*" onchange="previewImage(this)">
                            @error('foto_barang') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                            {{-- Value: Gunakan old() untuk validasi gagal, atau data dari DB --}}
                            <textarea class="form-control @error('deskripsi_barang') is-invalid @enderror"
                                      name="deskripsi_barang"
                                      id="deskripsi"
                                      rows="4"
                                      placeholder="Tulis deskripsi singkat produk...">{{ old('deskripsi_barang', $produk->deskripsi_barang ?? '') }}</textarea>
                            @error('deskripsi_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- KOLOM KANAN (DETAIL PRODUK) --}}
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="namaProduk" class="form-label">Nama Produk</label>
                            <input type="text"
                                   class="form-control @error('nama_barang') is-invalid @enderror"
                                   name="nama_barang"
                                   id="namaProduk"
                                   placeholder="Contoh: Ayam Goreng Lengkuas"
                                   value="{{ old('nama_barang', $produk->nama_barang ?? '') }}">
                            @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hargaProduk" class="form-label">Harga Produk (Rp)</label>
                            <input type="number"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   name="harga"
                                   id="hargaProduk"
                                   placeholder="Contoh: 12000"
                                   value="{{ old('harga', isset($produk) ? (int)$produk->harga : '') }}">
                            @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Stok --}}
                         <div class="mb-3">
                            <label for="stokProduk" class="form-label">Stok</label>
                            <input type="number"
                                   class="form-control @error('stok') is-invalid @enderror"
                                   name="stok"
                                   id="stokProduk"
                                   placeholder="Contoh: 100"
                                   value="{{ old('stok', $produk->stok ?? 10) }}">
                            @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori Produk</label>
                            <div class="d-flex gap-2 pill-options flex-wrap">
                                @php
                                    $kategori = old('jenis_barang', $produk->jenis_barang ?? '');
                                @endphp

                                <input type="radio" class="btn-check" name="jenis_barang" id="cat-makanan" value="Makanan" {{ $kategori == 'Makanan' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="cat-makanan">Makanan</label>

                                <input type="radio" class="btn-check" name="jenis_barang" id="cat-minuman" value="Minuman" {{ $kategori == 'Minuman' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="cat-minuman">Minuman</label>

                                <input type="radio" class="btn-check" name="jenis_barang" id="cat-camilan" value="Camilan" {{ $kategori == 'Camilan' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="cat-camilan">Camilan</label>
                            </div>
                            @error('jenis_barang') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- KANDUNGAN GIZI --}}
                        <div class="mb-3">
                            <label class="form-label d-block">Kandungan (Opsional)</label>
                            <div class="d-flex flex-column flex-md-row gap-3">
                                <div class="input-group">
                                    <span class="input-group-text">Lemak (g)</span>
                                    <input type="number" step="0.01" name="lemak_g" class="form-control" placeholder="0" value="{{ old('lemak_g', $produk->lemak_g ?? '') }}">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">Karbo (g)</span>
                                    <input type="number" step="0.01" name="karbo_g" class="form-control" placeholder="0" value="{{ old('karbo_g', $produk->karbo_g ?? '') }}">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">Protein (g)</span>
                                    <input type="number" step="0.01" name="protein_g" class="form-control" placeholder="0" value="{{ old('protein_g', $produk->protein_g ?? '') }}">
                                </div>
                            </div>
                            <div class="input-group mt-2" style="max-width: 200px;">
                                <span class="input-group-text">Kalori (kkal)</span>
                                <input type="number" name="kalori_kkal" class="form-control" placeholder="0" value="{{ old('kalori_kkal', $produk->kalori_kkal ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('seller.produk.index') }}" class="btn btn-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-success px-4">{{ isset($produk) ? 'Update Produk' : 'Simpan Produk' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script Sederhana untuk Preview Gambar --}}
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('placeholderText');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                preview.classList.add('d-block');
                placeholder.classList.add('d-none');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

@push('styles')
<style>
    .btn-outline-primary:hover {
        background-color: #00838f;
        border-color: #00838f;
        color: white;
    }
    .btn-check:checked + .btn-outline-primary {
        background-color: #00838f;
        border-color: #00838f;
        color: white;
    }
    .btn-outline-primary {
        color: #00838f;
        border-color: #00838f;
    }
    .btn-success {
        background-color: #2e7d32;
        border-color: #2e7d32;
    }
</style>
@endpush
