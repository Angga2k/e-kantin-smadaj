@extends('layouts.Seller')

@section('title', 'Profil Toko')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3 fw-bold">Profil Akun</h1>

    <div class="row">
        {{-- Kartu Profil Kiri --}}
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 fw-bold">Detail Profil</h5>
                </div>
                <div class="card-body text-center">
                    {{-- Foto Profil --}}
                    <img src="{{ asset(Auth::user()->foto_profile ?? 'icon/profile.png') }}"
                         alt="{{ $profile->nama_toko ?? 'Toko' }}"
                         class="img-fluid rounded-circle mb-2 border p-1"
                         width="128" height="128"
                         style="object-fit: cover; aspect-ratio: 1/1;"
                         onerror="this.src='{{ asset('asset/default-profile.png') }}'">

                    <h5 class="card-title mb-0 fw-bold">{{ $profile->nama_toko ?? 'Nama Toko' }}</h5>
                    <div class="text-muted mb-2 small">Penjual Kantin</div>

                    <div>
                        <a class="btn btn-primary btn-sm w-100" href="{{ route('seller.profile.edit') }}">
                            <i class="bi bi-pencil-square me-1"></i> Edit Profil
                        </a>
                    </div>
                </div>

                <hr class="my-0" />

                
            </div>
        </div>

        {{-- Detail Informasi Kanan --}}
        <div class="col-md-8 col-xl-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 fw-bold">Informasi Lengkap</h5>
                </div>
                <div class="card-body h-100">

                    <div class="row mb-3 align-items-center">
                        <label class="col-md-3 col-form-label fw-bold text-muted">Nama Toko</label>
                        <div class="col-md-9">
                            <input type="text" readonly class="form-control-plaintext fw-bold" value="{{ $profile->nama_toko ?? '-' }}">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-md-3 col-form-label fw-bold text-muted">Penanggung Jawab</label>
                        <div class="col-md-9">
                            <input type="text" readonly class="form-control-plaintext" value="{{ $profile->nama_penanggungjawab ?? '-' }}">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-md-3 col-form-label fw-bold text-muted">Username Login</label>
                        <div class="col-md-9">
                            <input type="text" readonly class="form-control-plaintext" value="{{ $user->username }}">
                        </div>
                    </div>

                    <hr>

                    <div class="mt-3">
                        <h5 class="card-title mb-3 fw-bold">Keamanan</h5>
                        <button type="button" class="btn btn-outline-danger" onclick="openChangePasswordModal()">
                            <i class="bi bi-key-fill me-1"></i> Ganti Password
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script SweetAlert untuk Flash Message & Ganti Password --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Flash Message Success
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    // Modal Ganti Password
    function openChangePasswordModal() {
        Swal.fire({
            title: 'Ganti Password',
            html: `
                <div class="text-start">
                    <label class="small fw-bold mb-1">Password Lama</label>
                    <input type="password" id="current_password" class="swal2-input mt-0 mb-3 w-100" placeholder="Masukkan password lama">

                    <label class="small fw-bold mb-1">Password Baru</label>
                    <input type="password" id="new_password" class="swal2-input mt-0 mb-3 w-100" placeholder="Min. 8 Karakter">

                    <label class="small fw-bold mb-1">Konfirmasi Password Baru</label>
                    <input type="password" id="new_password_confirmation" class="swal2-input mt-0 w-100" placeholder="Ulangi password baru">
                </div>
            `,
            confirmButtonText: 'Simpan Password',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            focusConfirm: false,
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
                // Loading State
                Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });

                // AJAX Request ke route khusus seller
                fetch("{{ route('seller.profile.password') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(result.value)
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200 || body.status === 'success') {
                        Swal.fire('Berhasil!', body.message, 'success');
                    } else {
                        Swal.fire('Gagal!', body.message || 'Terjadi kesalahan.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error!', 'Gagal menghubungi server.', 'error'));
            }
        });
    }
</script>
@endsection
