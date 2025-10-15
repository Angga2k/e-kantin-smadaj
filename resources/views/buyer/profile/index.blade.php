@extends('layouts.Buyer')

@section('title', 'Profile')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-lg-8 col-md-10">
        <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb" class="mb-5">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
        </nav>
        <div class="bg-img"></div>
        <div class="profile-card text-center">
            <a href="/profile/update" class="edit-link"><i class="bi bi-pencil-square me-1"></i>Edit</a>
            <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Foto Profil" class="profile-picture">


            <div class="profile-form mt-4 text-start">
                <div class="row mb-3">
                    <label for="nama" class="col-md-3 col-form-label">Nama</label>
                    <div class="col-md-9">
                        <input type="text" readonly class="form-control" id="nama" value="SIAPA SAJA">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="nisn" class="col-md-3 col-form-label">NISN</label>
                    <div class="col-md-9">
                        <input type="text" readonly class="form-control" id="nisn" value="25148411515051">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="ttl" class="col-md-3 col-form-label">Tanggal Lahir</label>
                    <div class="col-md-9">
                        <input type="text" readonly class="form-control" id="ttl" value="Jember 13 Januari 2007">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="gender" class="col-md-3 col-form-label">Jenis Kelamin</label>
                    <div class="col-md-9">
                        <input type="text" readonly class="form-control" id="gender" value="Perempuan">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
