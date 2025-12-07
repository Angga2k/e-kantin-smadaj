@extends('layouts.Seller')

@section('title', 'Dompet & Penarikan')

@section('content')
<main class="container my-4">

    {{-- Alert Sukses/Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- KOLOM KIRI: SALDO & FORM --}}
        <div class="col-lg-5">
            {{-- Kartu Saldo --}}
            <div class="card balance-card mb-4 bg-primary text-white shadow-sm" style="background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 text-white">Saldo Aktif Anda</p>
                        {{-- Menampilkan Saldo Terdekripsi --}}
                        <h2 class="fw-bold mb-0">Rp {{ number_format($dompet->saldo, 0, ',', '.') }}</h2>
                    </div>
                    <div class="balance-icon fs-1 opacity-50">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>

            {{-- Form Penarikan --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-cash-coin me-2"></i>Ajukan Penarikan</h5>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('seller.dompet.store') }}" method="POST" id="formPenarikan">
                        @csrf

                        {{-- Input Nominal --}}
                        <div class="mb-4">
                            <label class="form-label small text-muted fw-bold">Nominal Penarikan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number"
                                       name="jumlah"
                                       class="form-control form-control-lg fw-bold"
                                       placeholder="0"
                                       min="10000"
                                       required>
                            </div>
                            <div class="form-text text-end">Min. penarikan Rp 10.000</div>
                        </div>

                        {{-- Pilihan Metode Rekening --}}
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold mb-2">Tujuan Penarikan</label>

                            {{-- Tab Pilihan --}}
                            <div class="btn-group w-100 mb-3" role="group">
                                <input type="radio" class="btn-check" name="metode_penarikan" id="opt_tersimpan" value="tersimpan" autocomplete="off" checked onclick="toggleRekening('tersimpan')">
                                <label class="btn btn-outline-primary btn-sm" for="opt_tersimpan">Rekening Tersimpan</label>

                                <input type="radio" class="btn-check" name="metode_penarikan" id="opt_baru" value="baru" autocomplete="off" onclick="toggleRekening('baru')">
                                <label class="btn btn-outline-primary btn-sm" for="opt_baru">Rekening Baru</label>
                            </div>

                            {{-- OPSI 1: Rekening Tersimpan --}}
                            <div id="section_tersimpan" class="p-3 bg-light rounded border">
                                @if(isset($rekeningTersimpan) && $rekeningTersimpan->count() > 0)
                                    <label class="small text-muted mb-1">Pilih Rekening</label>
                                    <select class="form-select form-select-sm" name="id_rekening_tujuan">
                                        @foreach($rekeningTersimpan as $rek)
                                            <option value="{{ $rek->id_rekening }}">
                                                {{ $rek->nama_bank }} - {{ $rek->masked }} ({{ $rek->atas_nama }})
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="text-center py-3">
                                        <p class="small text-muted mb-2">Belum ada rekening tersimpan.</p>
                                        <button type="button" class="btn btn-sm btn-link text-decoration-none" onclick="document.getElementById('opt_baru').click()">Input Rekening Baru</button>
                                    </div>
                                @endif
                            </div>

                            {{-- OPSI 2: Rekening Baru --}}
                            <div id="section_baru" class="p-3 bg-light rounded border d-none">
                                <div class="mb-2">
                                    <label class="small text-muted mb-1">Nama Bank / E-Wallet</label>
                                    <select class="form-select form-select-sm" name="bank_tujuan">
                                        <option value="BCA">Bank BCA</option>
                                        <option value="BRI">Bank BRI</option>
                                        <option value="Mandiri">Bank Mandiri</option>
                                        <option value="BNI">Bank BNI</option>
                                        <option value="Gopay">Gopay</option>
                                        <option value="Dana">Dana</option>
                                        <option value="OVO">OVO</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="small text-muted mb-1">Nomor Rekening / HP</label>
                                    <input type="number" name="no_rekening" class="form-control form-control-sm font-monospace" placeholder="Contoh: 1234567890">
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted mb-1">Atas Nama</label>
                                    <input type="text" name="atas_nama" class="form-control form-control-sm" placeholder="Contoh: Budi Santoso">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="simpan_rekening" value="1" id="simpanRekeningCheck" checked>
                                    <label class="form-check-label small text-muted" for="simpanRekeningCheck">
                                        Simpan rekening ini untuk penarikan selanjutnya
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-2">
                            <i class="bi bi-box-arrow-up-right me-2"></i> Tarik Dana Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: RIWAYAT --}}
        <div class="col-lg-7">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Riwayat Penarikan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Tujuan</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $item->created_at->format('d M Y') }}</div>
                                            <div class="small text-muted">{{ $item->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark small">{{ $item->bank_tujuan }}</div>
                                            <div class="font-monospace small text-muted">
                                                {{-- Masking manual sederhana jika model history blm ada accessor --}}
                                                ****{{ substr($item->no_rekening, -4) }}
                                            </div>
                                        </td>
                                        <td class="fw-bold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        <td>
                                            @if($item->status == 'berhasil')
                                                <span class="badge bg-success bg-opacity-10 text-success px-3">Berhasil</span>
                                            @elseif($item->status == 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-3">Menunggu</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-3">Gagal</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <img src="{{ asset('icon/empty-box.png') }}" alt="Empty" style="width: 50px; opacity: 0.5;" class="mb-2" onerror="this.style.display='none'">
                                            <p class="mb-0 small">Belum ada riwayat penarikan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if($riwayat->hasPages())
                    <div class="card-footer bg-white border-top-0 py-3">
                        {{ $riwayat->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function toggleRekening(tipe) {
        const sectionTersimpan = document.getElementById('section_tersimpan');
        const sectionBaru = document.getElementById('section_baru');

        // Ambil elemen input di section baru untuk mengatur 'required'
        const inputsBaru = sectionBaru.querySelectorAll('input, select');
        const inputsTersimpan = sectionTersimpan.querySelectorAll('select');

        if (tipe === 'tersimpan') {
            sectionTersimpan.classList.remove('d-none');
            sectionBaru.classList.add('d-none');

            // Aktifkan required untuk dropdown tersimpan
            inputsTersimpan.forEach(input => input.required = true);
            // Matikan required untuk input baru (agar form bisa submit)
            inputsBaru.forEach(input => input.required = false);
        } else {
            sectionTersimpan.classList.add('d-none');
            sectionBaru.classList.remove('d-none');

            // Matikan required untuk dropdown tersimpan
            inputsTersimpan.forEach(input => input.required = false);
            // Aktifkan required untuk input baru (kecuali checkbox)
            inputsBaru.forEach(input => {
                if(input.type !== 'checkbox') input.required = true;
            });
        }
    }

    // Jalankan saat load (menjaga state jika halaman ter-refresh misal karena validasi error)
    document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById('opt_baru').checked) {
            toggleRekening('baru');
        } else {
            toggleRekening('tersimpan');
        }
    });
</script>
@endpush
