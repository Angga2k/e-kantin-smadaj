@extends('layouts.Buyer')

@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-lg-8 col-md-10">
        <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb" class="mb-5">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.index') }}" class="text-decoration-none text-muted">Profil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        {{-- Tampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-img"></div>
        <div class="profile-card text-center">

            {{-- FORM START --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Foto Profil Upload --}}
                <div class="profile-picture-update-wrapper">
                    <img src="{{ asset( Auth::user()->foto_profile) }}"
                         alt="Foto Profil"
                         class="profile-picture-update object-fit-cover bg-white"
                         id="profile-image"
                         onerror="this.src='{{ asset('asset/default-profile.png') }}'">

                    <label for="file-upload" class="upload-button" style="cursor: pointer;">
                        <i class="bi bi-camera-fill"></i>
                        {{-- Input File disembunyikan tapi tetap berfungsi --}}
                        <input type="file" id="file-upload" name="foto" accept="image/*" style="display: none;">
                    </label>
                </div>

                <div class="profile-form mt-4 text-start">

                    {{-- Nama --}}
                    <div class="row mb-3 align-items-center">
                        <label for="nama" class="col-md-3 col-form-label fw-bold">Nama</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nama" id="nama" value="{{ old('nama', $profile->nama ?? '') }}" required>
                        </div>
                    </div>

                    {{-- NISN / NIP (Readonly) --}}
                    <div class="row mb-3 align-items-center">
                        <label for="nisn" class="col-md-3 col-form-label fw-bold">
                            {{ Auth::user()->role == 'civitas_akademik' ? 'NIP' : 'NISN' }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" readonly class="form-control bg-body-secondary" id="nisn" value="{{ $profile->nisn ?? $profile->nip ?? '-' }}">
                        </div>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="row mb-3 align-items-center">
                        <label for="ttl" class="col-md-3 col-form-label fw-bold">Tanggal Lahir</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control bg-white" name="tgl_lahir" id="ttl"
                                value="{{ old('tgl_lahir', !empty($profile->tgl_lahir) ? \Carbon\Carbon::parse($profile->tgl_lahir)->format('Y-m-d') : '') }}"
                                placeholder="Pilih tanggal lahir...">
                        </div>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="row mb-3 align-items-center">
                        <label for="gender" class="col-md-3 col-form-label fw-bold">Jenis Kelamin</label>
                        <div class="col-md-9">
                            <select class="form-select" name="jenis_kelamin" id="gender">
                                <option value="" disabled>Pilih Jenis Kelamin</option>
                                <option value="L" {{ (old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'L') ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ (old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'P') ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </div>
            </form>
            {{-- FORM END --}}

        </div>
    </div>
</div>

{{-- SCRIPT KHUSUS PREVIEW GAMBAR --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file-upload');
        const profileImage = document.getElementById('profile-image');

        if (fileInput && profileImage) {
            fileInput.addEventListener('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    // Mengganti src gambar dengan URL objek sementara dari file yang dipilih
                    profileImage.src = URL.createObjectURL(file);
                }
            });
        }
    });
</script>
@endsection
