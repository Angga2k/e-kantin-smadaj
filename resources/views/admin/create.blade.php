@extends('layouts.Admin')

@section('content')
<style>
    /* Transisi halus untuk perubahan warna */
    .card {
        transition: all 0.3s ease-in-out;
        border: 1px solid #dee2e6; /* Border default tipis */
        border-top: 5px solid #6c757d; /* Border atas default abu-abu */
    }
    
    /* Warna 1: Civitas Akademik (BIRU - JELAS) */
    .theme-civitas {
        border-color: #0d6efd !important; /* Border samping/bawah biru */
        border-top-color: #0d6efd !important; /* Border atas TEBAL biru */
        background-color: #e7f1ff !important; /* Biru muda yang lebih terlihat */
    }
    /* Mengubah warna judul form */
    .theme-civitas h6 {
        color: #004085 !important;
        border-bottom: 2px solid #0d6efd !important;
    }

    /* Warna 2: Penjual (INDIGO/UNGU - JELAS) */
    .theme-penjual {
        border-color: #6610f2 !important; /* Border samping/bawah ungu */
        border-top-color: #6610f2 !important; /* Border atas TEBAL ungu */
        background-color: #f3e8ff !important; /* Ungu muda yang lebih terlihat */
    }
    /* Mengubah warna judul form */
    .theme-penjual h6 {
        color: #3d0096 !important;
        border-bottom: 2px solid #6610f2 !important;
    }
</style>

<div class="container-fluid" style="max-width: 800px;">
    
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.index') }}" class="btn btn-light rounded-circle me-3 border"><i class="bi bi-arrow-left"></i></a>
        <h3 class="fw-bold mb-0">Tambah Akun Baru</h3>
    </div>

    {{-- ID 'mainCard' digunakan untuk manipulasi Style via JS --}}
    <div class="card shadow-sm" id="mainCard">
        <div class="card-body p-4">
            <form action="{{ route('admin.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Role Akun</label>
                    {{-- Tambahkan onchange --}}
                    <select name="role" id="roleSelect" class="form-select form-select-lg bg-light border-secondary" required onchange="toggleForm()">
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                        <option value="civitas_akademik" {{ old('role') == 'civitas_akademik' ? 'selected' : '' }}>Guru / Civitas Akademik</option>
                        <option value="penjual" {{ old('role') == 'penjual' ? 'selected' : '' }}>Penjual Kantin</option>
                    </select>
                </div>

                {{-- FORM DATA LOGIN --}}
                {{-- Class 'form-header' dihapus, styling langsung via CSS selector h6 diatas --}}
                <h6 class="fw-bold text-muted mb-3 border-bottom pb-2">1. Data Login</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small">Username (Unik)</label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: guru.budi atau kantin.bu.siti" value="{{ old('username') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Password Awal</label>
                        <input type="text" name="password" class="form-control" value="12345678" required>
                        <div class="form-text">Default: 12345678</div>
                    </div>
                </div>

                {{-- FORM KHUSUS GURU --}}
                {{-- Cek old input untuk menentukan visibilitas awal --}}
                <div id="formGuru" class="{{ old('role') == 'civitas_akademik' ? '' : 'd-none' }}">
                    <h6 class="fw-bold mb-3 border-bottom pb-2 mt-4">2. Data Guru</h6>
                    <div class="mb-3">
                        <label class="form-label small">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control guru-input" placeholder="Nama Guru" value="{{ old('nama') }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">NPWP / NIP</label>
                            <input type="text" name="npwp" class="form-control guru-input" placeholder="Nomor Induk" value="{{ old('npwp') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select guru-input">
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- FORM KHUSUS PENJUAL --}}
                <div id="formPenjual" class="{{ old('role') == 'penjual' ? '' : 'd-none' }}">
                    <h6 class="fw-bold mb-3 border-bottom pb-2 mt-4">2. Data Toko / Penjual</h6>
                    <div class="mb-3">
                        <label class="form-label small">Nama Toko / Kantin</label>
                        <input type="text" name="nama_toko" class="form-control penjual-input" placeholder="Contoh: Kantin Barokah" value="{{ old('nama_toko') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Nama Penanggung Jawab</label>
                        <input type="text" name="nama_penanggungjawab" class="form-control penjual-input" placeholder="Nama Pemilik" value="{{ old('nama_penanggungjawab') }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">Nama Bank (Opsional)</label>
                            <input type="text" name="nama_bank" class="form-control" placeholder="BCA / BRI" value="{{ old('nama_bank') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">No Rekening (Opsional)</label>
                            <input type="text" name="no_rekening" class="form-control" placeholder="123xxxxx" value="{{ old('no_rekening') }}">
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-primary px-5 fw-bold">Simpan Data</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // Jalankan saat halaman selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        toggleForm();
    });

    function toggleForm() {
        const roleSelect = document.getElementById('roleSelect');
        const role = roleSelect.value;
        const mainCard = document.getElementById('mainCard');
        
        const formGuru = document.getElementById('formGuru');
        const formPenjual = document.getElementById('formPenjual');
        
        // Input Groups
        const guruInputs = document.querySelectorAll('.guru-input');
        const penjualInputs = document.querySelectorAll('.penjual-input');

        // Reset Classes: Hapus tema lama
        mainCard.classList.remove('theme-civitas', 'theme-penjual');

        if (role === 'civitas_akademik') {
            // Tampilkan Form Guru
            formGuru.classList.remove('d-none');
            formPenjual.classList.add('d-none');
            
            // Validasi
            guruInputs.forEach(input => input.required = true);
            penjualInputs.forEach(input => input.required = false);

            // Ubah Warna -> BIRU
            mainCard.classList.add('theme-civitas');

        } else if (role === 'penjual') {
            // Tampilkan Form Penjual
            formGuru.classList.add('d-none');
            formPenjual.classList.remove('d-none');
            
            // Validasi
            guruInputs.forEach(input => input.required = false);
            penjualInputs.forEach(input => input.required = true);

            // Ubah Warna -> UNGU
            mainCard.classList.add('theme-penjual');
        }
    }
</script>
@endsection