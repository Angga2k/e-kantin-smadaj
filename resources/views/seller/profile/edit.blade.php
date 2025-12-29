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
                    {{-- Tambahkan ID pada form untuk selector JS --}}
                    <form id="form-edit-profile" action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Kolom Kiri: Foto Profil --}}
                            <div class="col-md-4 text-center mb-4 mb-md-0 border-end">
                                <h5 class="mb-3 fw-bold text-muted small text-uppercase">Foto Profil</h5>

                                <div class="position-relative d-inline-block">
                                    {{-- Menggunakan width/height attribute + style object-fit agar bulat sempurna --}}
                                    <img src="{{ asset(Auth::user()->foto_profile ?? 'icon/profile.png') }}"
                                         class="rounded-circle mb-3 shadow-sm"
                                         id="profile-preview"
                                         width="150" height="150"
                                         style="object-fit: cover; object-position: center; border: 4px solid #f8f9fa;"
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
                                        {{-- Tambahkan ID --}}
                                        <input type="text" class="form-control" name="nama_toko" id="nama_toko"
                                               value="{{ old('nama_toko', $profile->nama_toko ?? '') }}"
                                               placeholder="Masukkan nama kantin/toko" required>
                                    </div>
                                </div>

                                {{-- Nama Penanggung Jawab --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Nama Penanggung Jawab</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-person text-primary"></i></span>
                                        {{-- Tambahkan ID --}}
                                        <input type="text" class="form-control" name="nama_penanggungjawab" id="nama_penanggungjawab"
                                               value="{{ old('nama_penanggungjawab', $profile->nama_penanggungjawab ?? '') }}"
                                               placeholder="Nama pemilik/penanggung jawab" required>
                                    </div>
                                </div>

                                <div class="mt-5 text-end border-top pt-3">
                                    {{-- Tambahkan ID pada tombol --}}
                                    <button type="submit" class="btn btn-primary px-4 fw-bold" id="btn-save">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. LOGIKA PREVIEW IMAGE ---
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

        // --- 2. LOGIKA CEK PERUBAHAN & LOADING STATE ---
        const form = document.getElementById('form-edit-profile');
        const btnSave = document.getElementById('btn-save');
        const inputNamaToko = document.getElementById('nama_toko');
        const inputNamaPJ = document.getElementById('nama_penanggungjawab');

        // Simpan nilai awal saat halaman dimuat untuk pembanding
        const initialData = {
            nama_toko: inputNamaToko.value,
            nama_penanggungjawab: inputNamaPJ.value
        };

        form.addEventListener('submit', function(e) {
            // Ambil nilai saat ini
            const currentNamaToko = inputNamaToko.value;
            const currentNamaPJ = inputNamaPJ.value;
            
            // Cek apakah ada file foto yang dipilih
            const isFileSelected = fileInput.files.length > 0;

            // Cek apakah text berubah
            const isTextChanged = (currentNamaToko !== initialData.nama_toko) || 
                                  (currentNamaPJ !== initialData.nama_penanggungjawab);

            // JIKA TIDAK ADA PERUBAHAN SAMA SEKALI (Text sama & Tidak ada file baru)
            if (!isTextChanged && !isFileSelected) {
                e.preventDefault(); // Batalkan submit
                
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak ada perubahan',
                    text: 'Anda belum mengubah data apapun.',
                    confirmButtonColor: '#0d6efd'
                });
                return; // Stop eksekusi
            }

            // JIKA ADA PERUBAHAN -> LANJUT KE PROSES SIMPAN
            
            // 1. Disable tombol agar tidak bisa klik ganda (spam)
            btnSave.disabled = true;
            
            // 2. Ubah teks tombol menjadi indikator loading
            btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menyimpan...';
            
            // 3. Tampilkan SweetAlert Loading (Block Screen)
            Swal.fire({
                title: 'Menyimpan Perubahan',
                text: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Form akan lanjut submit secara natural ke server setelah kode ini selesai
        });
    });
</script>
@endsection