@php
    $user = Auth::user();
    $isLoggedIn = Auth::check();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>HASIL DEBUG OTENTIKASI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .container { margin-top: 50px; }
        .success-box { border-left: 5px solid #28a745; }
        .failure-box { border-left: 5px solid #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Pengujian Sesi Login (Auth::check)</h1>

        @if ($isLoggedIn)
            <div class="alert alert-success success-box">
                <h4 class="alert-heading">✅ STATUS: LOGIN BERHASIL</h4>
                <p>Pengguna berhasil login dan sesi aktif! Masalahnya ada di **Middleware RoleChecker**.</p>
            </div>

            <h5 class="mt-4">Data Pengguna (Auth::user())</h5>
            <table class="table table-striped table-bordered">
                <tr><th>ID Pengguna</th><td>{{ $user->id_user ?? 'N/A' }}</td></tr>
                <tr><th>Username</th><td>{{ $user->username ?? 'N/A' }}</td></tr>
                <tr><th>Role</th><td><span class="badge bg-primary">{{ $user->role ?? 'N/A' }}</span></td></tr>
            </table>

            <a href="{{ route('beranda.index') }}" class="btn btn-success mt-3">Lanjutkan ke Beranda</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger mt-3">Logout</button>
            </form>

        @else
            <div class="alert alert-danger failure-box">
                <h4 class="alert-heading">❌ STATUS: BELUM LOGIN (Guest)</h4>
                <p>Sesi hilang atau tidak valid. Masalah ada pada konfigurasi Session/Cookie Anda.</p>
                <hr>
                <p class="mb-0">Akses {{ route('login') }} untuk mencoba lagi.</p>
            </div>
        @endif
    </div>
</body>
</html>
