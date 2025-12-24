<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kantin Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #2c3e50; color: white; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #34495e; color: white; border-left: 4px solid #3498db; }
        .content-wrapper { padding: 2rem; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 12px; }
    </style>
</head>
<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="sidebar d-none d-lg-block" style="width: 250px;">
            <div class="p-4 text-center border-bottom border-secondary">
                <h5 class="fw-bold m-0">ADMIN PANEL</h5>
                <small class="text-white-50">E-Kantin Smada</small>
            </div>
            <nav class="nav flex-column mt-3">
                {{-- <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') }}"> --}}
                <a class="nav-link ">
                    <i class="bi bi-people me-2"></i> Manajemen User
                </a>
                <a class="nav-link" href="#" onclick="document.getElementById('logout-form').submit()">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                </a>
            </nav>
        </div>

        {{-- Mobile Toggle & Content --}}
        <div class="flex-grow-1">
            <nav class="navbar navbar-light bg-white shadow-sm d-lg-none">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1 fw-bold">Admin Panel</span>
                </div>
            </nav>

            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>