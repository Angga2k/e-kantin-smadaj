@extends('layouts.Admin')

@section('content')
<div class="container-fluid">
    
    {{-- Header & Tombol Tambah --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Manajemen User</h3>
            <p class="text-muted small">Kelola data siswa, guru, dan penjual.</p>
        </div>
        <a href="{{ route('admin.create') }}" class="btn btn-primary px-4 rounded-pill">
            <i class="bi bi-plus-lg me-1"></i> Tambah Akun
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            {{-- <form action="{{ route('admin.index') }}" method="GET" class="row align-items-center g-2"> --}}
            <form action="" method="GET" class="row align-items-center g-2">
                <div class="col-auto">
                    <label class="fw-bold small text-muted">Tampilkan:</label>
                </div>
                <div class="col-auto">
                    <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" {{ $roleFilter == 'all' ? 'selected' : '' }}>Semua User</option>
                        <option value="siswa" {{ $roleFilter == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="civitas_akademik" {{ $roleFilter == 'civitas_akademik' ? 'selected' : '' }}>Guru / Staf</option>
                        <option value="penjual" {{ $roleFilter == 'penjual' ? 'selected' : '' }}>Penjual</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabel User --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Username / Login ID</th>
                        <th>Nama Lengkap / Toko</th>
                        <th>Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="ps-4">{{ $users->firstItem() + $index }}</td>
                            <td class="font-monospace fw-bold text-primary">{{ $user->username }}</td>
                            <td>
                                {{-- Logika menampilkan nama berdasarkan Role --}}
                                @if($user->role == 'siswa')
                                    {{ $user->siswa->nama ?? '-' }}
                                @elseif($user->role == 'civitas_akademik')
                                    {{ $user->civitasAkademik->nama ?? '-' }} <br>
                                    <small class="text-muted">NPWP: {{ $user->civitasAkademik->npwp ?? '-' }}</small>
                                @elseif($user->role == 'penjual')
                                    {{ $user->penjual->nama_toko ?? '-' }} <br>
                                    <small class="text-muted">PJ: {{ $user->penjual->nama_penanggungjawab ?? '-' }}</small>
                                @else
                                    Administrator
                                @endif
                            </td>
                            <td>
                                @php
                                    $badges = [
                                        'siswa' => 'bg-info text-dark',
                                        'civitas_akademik' => 'bg-primary',
                                        'penjual' => 'bg-warning text-dark',
                                        'admin' => 'bg-dark'
                                    ];
                                    $labels = [
                                        'siswa' => 'Siswa',
                                        'civitas_akademik' => 'Guru',
                                        'penjual' => 'Penjual',
                                        'admin' => 'Admin'
                                    ];
                                @endphp
                                <span class="badge {{ $badges[$user->role] ?? 'bg-secondary' }} rounded-pill px-3">
                                    {{ $labels[$user->role] ?? ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    {{-- Tombol Ganti Password --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalPassword"
                                            data-userid="{{ $user->id_user }}"
                                            data-username="{{ $user->username }}">
                                        <i class="bi bi-key"></i> Ganti Sandi
                                    </button>
                                    
                                    {{-- Tombol Hapus --}}
                                    @if($user->role !== 'admin')
                                    <form action="{{ route('admin.destroy', $user->id_user) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- Modal Ganti Password --}}
<div class="modal fade" id="modalPassword" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" id="formGantiPassword">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Ganti Kata Sandi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Mengganti password untuk user: <span id="spanUsername" class="fw-bold text-dark"></span></p>
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="text" name="new_password" class="form-control" placeholder="Min. 8 karakter" required>
                    <div class="form-text">Password akan langsung terganti tanpa verifikasi lama.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Password</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Script untuk memindahkan ID User ke form modal saat tombol diklik
    const modalPassword = document.getElementById('modalPassword');
    modalPassword.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-userid');
        const username = button.getAttribute('data-username');
        
        const spanUsername = modalPassword.querySelector('#spanUsername');
        const form = modalPassword.querySelector('#formGantiPassword');
        
        spanUsername.textContent = username;
        // Update action URL form
        form.action = `/admin/user/${userId}/password`;
    });
</script>
@endpush
@endsection