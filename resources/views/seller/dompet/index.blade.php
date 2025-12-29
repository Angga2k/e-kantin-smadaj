@extends('layouts.Seller')

@section('title', 'Dompet & Penarikan')

@section('content')

<style>
    /* ... (Style CSS tetap sama) ... */
    @media (max-width: 575.98px) {
        /* ... (CSS Mobile tetap sama) ... */
        .container { padding: 0.75rem !important; }
        .row { --bs-gutter-x: 1rem !important; }
        .col-lg-5, .col-lg-7 { margin-bottom: 1.5rem !important; }
        .alert { font-size: 0.85rem !important; padding: 0.6rem 0.9rem !important; margin-bottom: 1rem !important; }
        .balance-card { margin-bottom: 1.2rem !important; }
        .balance-card .card-body { padding: 1rem !important; }
        .balance-card p { font-size: 0.75rem !important; margin-bottom: 0.4rem !important; }
        .balance-card h2 { font-size: 1.3rem !important; }
        .balance-card .balance-icon { font-size: 1.5rem !important; }
        .card-header { padding: 0.75rem !important; }
        .card-header h5 { font-size: 0.95rem !important; margin-bottom: 0 !important; }
        .card-body { padding: 0.9rem !important; }
        .form-label { font-size: 0.7rem !important; margin-bottom: 0.3rem !important; }
        .input-group { margin-bottom: 0.5rem !important; }
        .input-group-text { font-size: 0.8rem !important; padding: 0.4rem 0.6rem !important; }
        .form-control, .form-select { font-size: 0.8rem !important; padding: 0.4rem 0.6rem !important; height: auto !important; }
        .form-control-lg { font-size: 0.85rem !important; padding: 0.4rem 0.6rem !important; }
        .form-text { font-size: 0.65rem !important; margin-top: 0.2rem !important; }
        .btn-group { gap: 0.3rem !important; }
        .btn-group .btn { font-size: 0.7rem !important; padding: 0.35rem 0.5rem !important; flex: 1 !important; }
        #section_tersimpan, #section_baru { padding: 0.75rem !important; margin-bottom: 0.8rem !important; border-radius: 6px !important; }
        #section_tersimpan .small, #section_baru .small { font-size: 0.65rem !important; margin-bottom: 0.3rem !important; }
        .form-check-label { font-size: 0.7rem !important; margin-left: 0.4rem !important; }
        .btn-primary { font-size: 0.85rem !important; padding: 0.5rem 1rem !important; }
        .table { font-size: 0.75rem !important; margin-bottom: 0 !important; min-width: 500px !important; }
        .table thead th { font-size: 0.65rem !important; padding: 0.4rem 0.5rem !important; font-weight: 700 !important; background-color: #f8f9fa !important; white-space: nowrap !important; }
        .table tbody td { padding: 0.4rem 0.5rem !important; font-size: 0.7rem !important; vertical-align: middle !important; white-space: nowrap !important; }
        .table .fw-bold { font-size: 0.7rem !important; }
        .table .small { font-size: 0.6rem !important; line-height: 1.1 !important; }
        .table .text-muted { font-size: 0.6rem !important; }
        .table .font-monospace { font-size: 0.6rem !important; }
        .badge { font-size: 0.6rem !important; padding: 0.25rem 0.4rem !important; }
        .table-responsive { margin-bottom: 0 !important; overflow-x: auto !important; -webkit-overflow-scrolling: touch !important; }
        .table tbody tr { border-bottom: 0.5px solid #e9ecef !important; }
        .card-footer { font-size: 0.75rem !important; padding: 0.75rem !important; }
        .card-footer nav { margin-bottom: 0 !important; }
        .pagination { margin-bottom: 0 !important; gap: 0.2rem !important; }
        .pagination .page-link { font-size: 0.65rem !important; padding: 0.25rem 0.4rem !important; }
        .table td.ps-4 { padding-left: 0.4rem !important; }
        .mb-4 { margin-bottom: 1rem !important; }
        .mb-3 { margin-bottom: 0.8rem !important; }
    }
</style>

<main class="container my-4">

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
            <div class="card balance-card mb-4 bg-primary text-white shadow-sm" style="background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 text-white">Saldo Aktif Anda</p>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($dompet->saldo, 0, ',', '.') }}</h2>
                    </div>
                    <div class="balance-icon fs-1 opacity-50">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-cash-coin me-2"></i>Ajukan Penarikan</h5>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('seller.dompet.store') }}" method="POST" id="formPenarikan">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small text-muted fw-bold">Nominal Penarikan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number" name="jumlah" id="inputJumlah" class="form-control form-control-lg fw-bold" placeholder="0" min="10000" required>
                            </div>
                            <div class="form-text text-end">Min. penarikan Rp 10.000</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold mb-2">Tujuan Penarikan</label>
                            <div class="btn-group w-100 mb-3" role="group">
                                <input type="radio" class="btn-check" name="metode_penarikan" id="opt_tersimpan" value="tersimpan" autocomplete="off" checked onclick="toggleRekening('tersimpan')">
                                <label class="btn btn-outline-primary btn-sm" for="opt_tersimpan">Rekening Tersimpan</label>

                                <input type="radio" class="btn-check" name="metode_penarikan" id="opt_baru" value="baru" autocomplete="off" onclick="toggleRekening('baru')">
                                <label class="btn btn-outline-primary btn-sm" for="opt_baru">Rekening Baru</label>
                            </div>

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

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-2" id="btnSubmitPenarikan">
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
                    
                    {{-- DROPDOWN FILTER JS --}}
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-filter me-1"></i> Semua
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterTable('all', 'Semua')">Semua</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterTable('pending', 'Menunggu')">Menunggu</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterTable('berhasil', 'Berhasil')">Berhasil</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterTable('gagal', 'Gagal')">Gagal</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        {{-- ID untuk selector JS Pagination --}}
                        <table class="table table-hover align-middle mb-0" id="tableRiwayat">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Tujuan</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($riwayat as $item)
                                    {{-- Class dan Data Attribute untuk Filter & Pagination JS --}}
                                    <tr class="history-row" data-status="{{ $item->status }}">
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $item->created_at->format('d M Y') }}</div>
                                            <div class="small text-muted">{{ $item->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark small">{{ $item->bank_tujuan }}</div>
                                            <div class="font-monospace small text-muted">
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
                                
                                {{-- Placeholder jika filter kosong --}}
                                <tr id="empty-row-placeholder" style="display: none;">
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <p class="mb-0 small fst-italic">Tidak ada data dengan status ini.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination Control JS --}}
                <div class="card-footer bg-white border-top-0 py-3" id="paginationControls" style="display: none;">
                     <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0" id="paginationList">
                            {{-- JS will inject pagination here --}}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- KONFIGURASI PAGINATION ---
    const ROWS_PER_PAGE = 10;
    let currentPage = 1;
    let currentFilterStatus = 'all';

    // 1. FUNGSI FILTER & RENDER
    function filterTable(status, label) {
        // Update UI Dropdown
        document.getElementById('filterDropdownBtn').innerHTML = `<i class="bi bi-filter me-1"></i> ${label}`;
        
        currentFilterStatus = status;
        currentPage = 1; // Reset ke halaman 1 saat filter berubah
        renderTable();
    }

    function renderTable() {
        const allRows = document.querySelectorAll('.history-row');
        const emptyPlaceholder = document.getElementById('empty-row-placeholder');
        const paginationControls = document.getElementById('paginationControls');
        const paginationList = document.getElementById('paginationList');

        // 1. Filter Data
        let filteredRows = [];
        allRows.forEach(row => {
            if (currentFilterStatus === 'all' || row.dataset.status === currentFilterStatus) {
                filteredRows.push(row);
            }
            row.style.display = 'none'; // Sembunyikan semua dulu
        });

        // 2. Cek Kosong
        if (filteredRows.length === 0 && allRows.length > 0) {
            emptyPlaceholder.style.display = '';
            paginationControls.style.display = 'none';
            return;
        } else {
            emptyPlaceholder.style.display = 'none';
        }

        // 3. Hitung Pagination
        const totalPages = Math.ceil(filteredRows.length / ROWS_PER_PAGE);
        
        // Validasi Current Page
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * ROWS_PER_PAGE;
        const endIndex = startIndex + ROWS_PER_PAGE;

        // 4. Tampilkan Row Halaman Ini
        filteredRows.slice(startIndex, endIndex).forEach(row => {
            row.style.display = '';
        });

        // 5. Generate Tombol Pagination (Jika lebih dari 1 halaman)
        if (totalPages > 1) {
            paginationControls.style.display = 'block';
            let paginationHTML = '';

            // Tombol Previous
            paginationHTML += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage - 1})">Prev</a>
            </li>`;

            // Tombol Angka
            for (let i = 1; i <= totalPages; i++) {
                paginationHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changePage(${i})">${i}</a>
                </li>`;
            }

            // Tombol Next
            paginationHTML += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage + 1})">Next</a>
            </li>`;

            paginationList.innerHTML = paginationHTML;
        } else {
            paginationControls.style.display = 'none';
        }
    }

    function changePage(page) {
        currentPage = page;
        renderTable();
    }

    // --- FUNGSI LAINNYA (Toggling & Form) ---

    function toggleRekening(tipe) {
        const sectionTersimpan = document.getElementById('section_tersimpan');
        const sectionBaru = document.getElementById('section_baru');
        const inputsBaru = sectionBaru.querySelectorAll('input, select');
        const inputsTersimpan = sectionTersimpan.querySelectorAll('select');

        if (tipe === 'tersimpan') {
            sectionTersimpan.classList.remove('d-none');
            sectionBaru.classList.add('d-none');
            inputsTersimpan.forEach(input => input.required = true);
            inputsBaru.forEach(input => input.required = false);
        } else {
            sectionTersimpan.classList.add('d-none');
            sectionBaru.classList.remove('d-none');
            inputsTersimpan.forEach(input => input.required = false);
            inputsBaru.forEach(input => {
                if(input.type !== 'checkbox') input.required = true;
            });
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Init Table
        renderTable();
        
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "Permintaan penarikan sedang diproses.",
                icon: 'success',
                confirmButtonColor: '#0d6efd',
                timer: 3000
            });
        @endif

        // Init Toggle Rekening
        if(document.getElementById('opt_baru').checked) {
            toggleRekening('baru');
        } else {
            toggleRekening('tersimpan');
        }

        // Logic Form Submit
        const form = document.getElementById('formPenarikan');
        const btnSubmit = document.getElementById('btnSubmitPenarikan');
        const inputJumlah = document.getElementById('inputJumlah');
        const userSaldo = {{ $dompet->saldo ?? 0 }};

        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const jumlah = parseInt(inputJumlah.value) || 0;
            if (jumlah < 10000) {
                Swal.fire('Gagal', 'Minimal penarikan adalah Rp 10.000', 'error');
                return;
            }
            if (jumlah > userSaldo) {
                Swal.fire('Gagal', 'Saldo Anda tidak mencukupi.', 'error');
                return;
            }
            Swal.fire({
                title: 'Konfirmasi Penarikan',
                text: `Apakah Anda yakin ingin menarik dana sebesar Rp ${new Intl.NumberFormat('id-ID').format(jumlah)}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tarik Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Permintaan Anda sedang dikirim ke bank.',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    form.submit();
                }
            });
        });
    });
</script>
@endpush