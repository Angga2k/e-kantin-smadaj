@extends('layouts.Seller')

@section('title', 'Beranda')

@section('content')
<div class="card product-card">
            <div class="card-body p-4 p-md-5">
                <form id="formTambahProduk">
                    <div class="row g-5">
                        <div class="col-lg-4">
                            <div class="mb-4">
                                <label for="uploadGambar" class="img-upload-box">
                                    <img src="" alt="Preview Gambar" class="img-preview d-none" id="imagePreview">
                                    <div id="placeholderText">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <span>Klik untuk upload gambar</span>
                                    </div>
                                </label>
                                <input type="file" id="uploadGambar" class="d-none" accept="image/*">
                            </div>
                            <div>
                                <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                                <textarea class="form-control" id="deskripsi" rows="4" placeholder="Tulis deskripsi singkat produk..."></textarea>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="namaProduk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="namaProduk" placeholder="Contoh: Ayam Goreng Lengkuas">
                            </div>
                            <div class="mb-3">
                                <label for="hargaProduk" class="form-label">Harga Produk</label>
                                <input type="number" class="form-control" id="hargaProduk" placeholder="Contoh: 12000">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori Produk</label>
                                <div class="d-flex gap-2 pill-options">
                                    <input type="radio" class="btn-check" name="kategori" id="cat-makanan" autocomplete="off">
                                    <label class="btn" for="cat-makanan">Makanan</label>
                                    <input type="radio" class="btn-check" name="kategori" id="cat-minuman" autocomplete="off">
                                    <label class="btn" for="cat-minuman">Minuman</label>
                                    <input type="radio" class="btn-check" name="kategori" id="cat-camilan" autocomplete="off">
                                    <label class="btn" for="cat-camilan">Camilan</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="varianInput" class="form-label">Varian Produk (Opsional)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="varianInput" placeholder="Contoh: Pedas, Manis, Original">
                                    <button class="btn btn-outline-secondary" type="button" id="addVarianBtn">+</button>
                                </div>
                                <div class="mt-2" id="varianContainer"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Kandungan (Opsional)</label>
                                <div class="d-flex flex-column flex-md-row gap-3">
                                    <div class="input-group"><span class="input-group-text">Lemak</span><input type="number" class="form-control" placeholder="0"></div>
                                    <div class="input-group"><span class="input-group-text">Karbo</span><input type="number" class="form-control" placeholder="0"></div>
                                    <div class="input-group"><span class="input-group-text">Protein</span><input type="number" class="form-control" placeholder="0"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-batal px-4">Batal</button>
                        <button type="submit" class="btn btn-simpan px-4">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
@endsection
