@extends('layouts.Admin')

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.index') }}" class="btn btn-light rounded-circle me-3 border"><i class="bi bi-arrow-left"></i></a>
        <h3 class="fw-bold mb-0">Tambah Akun Baru</h3>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('admin.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Role Akun</label>
                    <select name="role" id="roleSelect" class="form-select form-select-lg bg-light" required onchange="toggleForm()">
                        <option value="" selected disabled>-- Pilih Role --</option>
                        <option value="civitas_akademik">Guru / Civitas Akademik</option>
                        <option value="penjual">Penjual Kantin</option>
                    </select>
                </div>

                {{-- FORM DATA LOGIN --}}
                <h6 class="fw-bold text-muted mb-3 border-bottom pb-2">1. Data Login</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small">Username (Unik)</label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: guru.budi atau kantin.bu.siti" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Password Awal</label>
                        <input type="text" name="password" class="form-control" value="12345678" required>
                        <div class="form-text">Default: 12345678</div>
                    </div>
                </div>

                {{-- FORM KHUSUS GURU --}}
                <div id="formGuru" class="d-none">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2 mt-4">2. Data Guru</h6>
                    <div class="mb-3">
                        <label class="form-label small">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control guru-input" placeholder="Nama Guru">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">NPWP / NIP</label>
                            <input type="text" name="npwp" class="form-control guru-input" placeholder="Nomor Induk">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select guru-input">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- FORM KHUSUS PENJUAL --}}
                <div id="formPenjual" class="d-none">
                    <h6 class="fw-bold text-warning mb-3 border-bottom pb-2 mt-4">2. Data Toko / Penjual</h6>
                    <div class="mb-3">
                        <label class="form-label small">Nama Toko / Kantin</label>
                        <input type="text" name="nama_toko" class="form-control penjual-input" placeholder="Contoh: Kantin Barokah">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Nama Penanggung Jawab</label>
                        <input type="text" name="nama_penanggungjawab" class="form-control penjual-input" placeholder="Nama Pemilik">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">Nama Bank (Opsional)</label>
                            <input type="text" name="nama_bank" class="form-control" placeholder="BCA / BRI">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">No Rekening (Opsional)</label>
                            <input type="text" name="no_rekening" class="form-control" placeholder="123xxxxx">
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
    function toggleForm() {
        const role = document.getElementById('roleSelect').value;
        const formGuru = document.getElementById('formGuru');
        const formPenjual = document.getElementById('formPenjual');
        
        // Input Groups
        const guruInputs = document.querySelectorAll('.guru-input');
        const penjualInputs = document.querySelectorAll('.penjual-input');

        if (role === 'civitas_akademik') {
            formGuru.classList.remove('d-none');
            formPenjual.classList.add('d-none');
            // Set required untuk validasi browser
            guruInputs.forEach(input => input.required = true);
            penjualInputs.forEach(input => input.required = false);
        } else if (role === 'penjual') {
            formGuru.classList.add('d-none');
            formPenjual.classList.remove('d-none');
            guruInputs.forEach(input => input.required = false);
            penjualInputs.forEach(input => input.required = true);
        }
    }
</script>
@endsection