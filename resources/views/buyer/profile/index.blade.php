@extends('layouts.Buyer')

@section('title', 'Profile')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-lg-8 col-md-10">

        <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb" class="mb-5">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
        </nav>

        {{-- Menampilkan Alert Sukses dari Redirect --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: "{{ session('success') }}",
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            </script>
        @endif

        <div class="bg-img"></div>

        <div class="profile-card text-center pb-5">
            {{-- Tombol Edit --}}
            <a href="{{ route('profile.edit') }}" class="edit-link"><i class="bi bi-pencil-square me-1"></i>Edit</a>

            {{-- Foto Profil (Menggunakan Accessor User) --}}
            <img src="{{ asset( Auth::user()->foto_profile) }}"
                 alt="Foto Profil"
                 class="profile-picture object-fit-cover bg-white"
                 onerror="this.src='{{ asset('asset/default-profile.png') }}'"> <!-- Fallback image -->

            <div class="profile-form mt-4 text-start">

                {{-- Nama --}}
                <div class="row mb-3">
                    <label class="col-md-3 col-form-label fw-bold text-muted">Nama</label>
                    <div class="col-md-9">
                        <input type="text" readonly class="form-control-plaintext fw-bold" value="{{ $profile->nama ?? Auth::user()->username }}">
                    </div>
                </div>

                {{-- Nomor Induk (NISN / NIP) --}}
                <div class="row mb-3">
                    <label class="col-md-3 col-form-label fw-bold text-muted">
                        {{ Auth::user()->role == 'civitas_akademik' ? 'NIP' : 'NISN' }}
                    </label>
                    <div class="col-md-9">
                        <input type="text" readonly class="form-control-plaintext" value="{{ $profile->nisn ?? $profile->nip ?? '-' }}">
                    </div>
                </div>

                {{-- Tanggal Lahir --}}
                <div class="row mb-3">
                    <label class="col-md-3 col-form-label fw-bold text-muted">Tanggal Lahir</label>
                    <div class="col-md-9">
                        <input type="text" readonly class="form-control-plaintext"
                               value="{{ $profile->tgl_lahir ? \Carbon\Carbon::parse($profile->tgl_lahir)->translatedFormat('d F Y') : '-' }}">
                    </div>
                </div>

                {{-- Jenis Kelamin --}}
                <div class="row mb-3">
                    <label class="col-md-3 col-form-label fw-bold text-muted">Jenis Kelamin</label>
                    <div class="col-md-9">
                        <input type="text" readonly class="form-control-plaintext"
                               value="{{ ($profile->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : (($profile->jenis_kelamin ?? '') == 'P' ? 'Perempuan' : '-') }}">
                    </div>
                </div>

                <hr>

                {{-- TOMBOL GANTI PASSWORD --}}
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-outline-danger w-100 py-2" onclick="openChangePasswordModal()">
                        <i class="bi bi-key-fill me-2"></i>Ganti Password
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- SCRIPT GANTI PASSWORD --}}
<script>
    function openChangePasswordModal() {
        Swal.fire({
            title: 'Ganti Password',
            html: `
                <input type="password" id="current_password" class="swal2-input" placeholder="Password Lama">
                <input type="password" id="new_password" class="swal2-input" placeholder="Password Baru (Min. 8 Karakter)">
                <input type="password" id="new_password_confirmation" class="swal2-input" placeholder="Konfirmasi Password Baru">
            `,
            confirmButtonText: 'Simpan Password',
            focusConfirm: false,
            showCancelButton: true,
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const current_password = Swal.getPopup().querySelector('#current_password').value;
                const new_password = Swal.getPopup().querySelector('#new_password').value;
                const new_password_confirmation = Swal.getPopup().querySelector('#new_password_confirmation').value;

                if (!current_password || !new_password || !new_password_confirmation) {
                    Swal.showValidationMessage('Semua kolom harus diisi!');
                } else if (new_password !== new_password_confirmation) {
                    Swal.showValidationMessage('Konfirmasi password tidak cocok!');
                } else if (new_password.length < 8) {
                    Swal.showValidationMessage('Password baru minimal 8 karakter!');
                }

                return { current_password, new_password, new_password_confirmation };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim AJAX Request
                const data = result.value;

                Swal.fire({
                    title: 'Memproses...',
                    didOpen: () => Swal.showLoading()
                });

                fetch("{{ route('profile.password') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        Swal.fire('Berhasil!', body.message, 'success');
                    } else {
                        Swal.fire('Gagal!', body.message || 'Terjadi kesalahan.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                });
            }
        });
    }
</script>
@endsection
