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
                    {{-- PERBAIKAN DI SINI: --}}
                    {{-- 1. Hapus 'img-fluid' agar ukuran fix tidak diganggu --}}
                    {{-- 2. Tambah object-fit: cover (agar gambar tidak gepeng, tapi ter-crop rapi) --}}
                    {{-- 3. Tambah object-position: center (agar fokus gambar di tengah) --}}
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset(Auth::user()->foto_profile ?? 'icon/profile.png') }}"
                             alt="{{ $profile->nama_toko ?? 'Toko' }}"
                             class="rounded-circle mb-2 border p-1"
                             style="width: 128px; height: 128px; object-fit: cover; object-position: center;"
                             onerror="this.src='{{ asset('asset/default-profile.png') }}'">
                    </div>

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
            width: '450px', // Batasi lebar modal agar lebih proporsional
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">Password Lama</label>
                        <input type="password" id="current_password" class="form-control" placeholder="Masukkan password lama">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">Password Baru</label>
                        <input type="password" id="new_password" class="form-control" placeholder="Min. 8 Karakter">
                    </div>

                    <div class="mb-1">
                        <label class="form-label small fw-bold text-muted mb-1">Konfirmasi Password Baru</label>
                        <input type="password" id="new_password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>
            `,
            confirmButtonText: 'Simpan',
            confirmButtonColor: '#0d6efd',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            focusConfirm: false,
            preConfirm: () => {
                const current_password = Swal.getPopup().querySelector('#current_password').value;
                const new_password = Swal.getPopup().querySelector('#new_password').value;
                const new_password_confirmation = Swal.getPopup().querySelector('#new_password_confirmation').value;

                if (!current_password || !new_password || !new_password_confirmation) {
                    Swal.showValidationMessage('Semua kolom harus diisi!');
                    return false;
                } 
                
                else if (new_password === current_password) {
                    Swal.showValidationMessage('Password baru tidak boleh sama dengan password lama!');
                    return false;
                } 
                
                else if (new_password !== new_password_confirmation) {
                    Swal.showValidationMessage('Konfirmasi password tidak cocok!');
                    return false;
                }
                
                else if (new_password.length < 8) {
                    Swal.showValidationMessage('Password baru minimal 8 karakter!');
                    return false;
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
