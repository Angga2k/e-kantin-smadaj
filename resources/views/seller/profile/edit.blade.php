@extends('layouts.Seller')

@section('title', 'Edit Profil')

@section('content')
<div class="container-fluid p-0">

    {{-- Header & Tombol Kembali --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 fw-bold">Edit Profil Toko</h1>
        <a href="{{ route('seller.profile.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Alert Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Kolom Kiri: Foto Profil --}}
                            <div class="col-md-4 text-center mb-4 mb-md-0 border-end">
                                <h5 class="mb-3 fw-bold text-muted small text-uppercase">Foto Profil</h5>

                                <div class="position-relative d-inline-block">
                                    {{-- PERBAIKAN DI SINI: --}}
                                    {{-- 1. Hapus 'img-fluid' --}}
                                    {{-- 2. Style width & height dipaksa 150px --}}
                                    {{-- 3. object-position: center agar wajah di tengah --}}
                                    <img src="{{ asset(Auth::user()->foto_profile ?? 'icon/profile.png') }}"
                                         class="rounded-circle mb-3 shadow-sm"
                                         id="profile-preview"
                                         style="width: 150px; height: 150px; object-fit: cover; object-position: center; border: 4px solid #f8f9fa;"
                                         onerror="this.src='{{ asset('asset/default-profile.png') }}'">

                                    <div class="mt-2">
                                        <label for="file-upload" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-camera-fill me-1"></i> Ganti Foto
                                        </label>
                                        <input type="file" id="file-upload" name="foto" accept="image/*" class="d-none">
                                    </div>
                                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">Maks. 2MB (JPG/PNG)</small>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Form Data Toko --}}
                            <div class="col-md-8 ps-md-4">
                                <h5 class="mb-4 fw-bold text-muted small text-uppercase">Data Toko</h5>

                                {{-- Nama Toko --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Nama Toko</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-shop text-primary"></i></span>
                                        <input type="text" class="form-control" name="nama_toko"
                                               value="{{ old('nama_toko', $profile->nama_toko ?? '') }}"
                                               placeholder="Masukkan nama kantin/toko" required>
                                    </div>
                                </div>

                                {{-- Nama Penanggung Jawab --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Nama Penanggung Jawab</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-person text-primary"></i></span>
                                        <input type="text" class="form-control" name="nama_penanggungjawab"
                                               value="{{ old('nama_penanggungjawab', $profile->nama_penanggungjawab ?? '') }}"
                                               placeholder="Nama pemilik/penanggung jawab" required>
                                    </div>
                                </div>

                                <div class="mt-5 text-end border-top pt-3">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Preview Image --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file-upload');
        const imgPreview = document.getElementById('profile-preview');

        if (fileInput && imgPreview) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    imgPreview.src = URL.createObjectURL(file);
                }
            });
        }
    });
</script>
@endsection
