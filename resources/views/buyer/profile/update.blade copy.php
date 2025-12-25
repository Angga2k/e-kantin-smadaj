@extends('layouts.Buyer')

@section('title', 'Update-Profile')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-lg-8 col-md-10">
        <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb" class="mb-5">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Profil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="bg-img"></div>
        <div class="profile-card text-center">
            <div class="profile-picture-update-wrapper">
                <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Foto Profil" class="profile-picture-update" id="profile-image">
                <label for="file-upload" class="upload-button">
                    <i class="bi bi-camera-fill"></i>
                    <input type="file" id="file-upload" accept="image/*">
                </label>
            </div>

            <form class="profile-form mt-4 text-start">
                <div class="row mb-3 align-items-center">
                    <label for="nama" class="col-md-3 col-form-label">Nama</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="nama" value="SIAPA SAJA">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <label for="nisn" class="col-md-3 col-form-label">NISN</label>
                    <div class="col-md-9">
                        <input type="text" readonly class="form-control bg-body-secondary" id="nisn" value="25148411515051">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <label for="ttl" class="col-md-3 col-form-label">Tanggal Lahir</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="ttl" placeholder="Pilih tanggal lahir...">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <label for="gender" class="col-md-3 col-form-label">Jenis Kelamin</label>
                    <div class="col-md-9">
                        <select class="form-select" id="gender">
                            <option>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan" selected>Perempuan</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                    <div class="d-flex justify-content-end gap-2"><a href="/profile" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
