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

        /* Sidebar Styles */
        .sidebar { background-color: #2c3e50; color: white; height: 100vh; overflow-y: auto; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; transition: all 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #34495e; color: white; border-left: 4px solid #3498db; }

        /* Layout Styles */
        .content-wrapper { padding: 2rem; width: 100%; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 12px; }

        /* Offcanvas Sidebar adjustments for Mobile */
        @media (max-width: 991.98px) {
            .sidebar-offcanvas {
                width: 250px;
                background-color: #2c3e50;
            }
            .sidebar-offcanvas .offcanvas-header .btn-close {
                filter: invert(1); /* Make close button white */
            }
        }

        /* Desktop Sidebar (Fixed width) */
        @media (min-width: 992px) {
            .sidebar-desktop {
                width: 250px;
                min-width: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">

        {{-- SIDEBAR DESKTOP (Selalu Muncul di Layar Besar) --}}
        <div class="sidebar sidebar-desktop d-none d-lg-block">
            <div class="p-4 text-center border-bottom border-secondary">
                <h5 class="fw-bold m-0">ADMIN PANEL</h5>
                <small class="text-white-50">E-Kantin Smada</small>
            </div>
            @include('layouts.partials.admin-sidebar-nav')
        </div>

        {{-- SIDEBAR MOBILE (Offcanvas) --}}
        <div class="offcanvas offcanvas-start sidebar-offcanvas" tabindex="-1" id="sidebarMobile" aria-labelledby="sidebarMobileLabel">
            <div class="offcanvas-header border-bottom border-secondary">
                <h5 class="offcanvas-title text-white fw-bold" id="sidebarMobileLabel">ADMIN PANEL</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                @include('layouts.partials.admin-sidebar-nav')
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-grow-1 d-flex flex-column min-vh-100">

            {{-- Navbar Mobile (Hanya muncul di layar kecil) --}}
            <nav class="navbar navbar-light bg-white shadow-sm d-lg-none px-3">
                <div class="d-flex align-items-center">
                    {{-- Tombol Hamburger untuk Buka Sidebar --}}
                    <button class="btn btn-outline-secondary me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile" aria-controls="sidebarMobile">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1 fw-bold">Admin Panel</span>
                </div>
            </nav>

            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Form Logout --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
