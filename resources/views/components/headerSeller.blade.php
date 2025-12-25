<nav class="navbar navbar-expand-lg navbar-custom-pill shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand" href="#">
            <img src="{{ asset('asset/logo.png') }}" alt="Logo" style="height: 35px;">
            <span class="fw-bold d-none d-md-inline">SMA NEGERI 2 JEMBER</span>
        </a>

        <div class="d-flex d-lg-none align-items-center ms-auto">

            <span class="fw-bold me-2" style="font-size: 0.9rem;">Rp {{ number_format($saldoNavbar ?? 0, 0, ',', '.') }}</span>

            <a href="#" class="fs-5 text-secondary me-2"><i class="bi bi-bell-fill"></i></a>

            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="d-inline me-2">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm border rounded-pill mt-1" style="width: 100%;">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>

            <a href="{{ route('seller.profile.index') }}">
                <img src="{{ asset(Auth::user()->foto_profile ?? 'icon/profile.png') }}" class="rounded-circle" alt="Profil" style="height: 30px; width: 30px; object-fit: cover;">
            </a>

        </div>
        <button class="navbar-toggler d-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('penjual') ? 'active' : '' }}" href="/penjual">Beranda</a>
                </li>
                <li class="nav-item">
                    {{-- <a class="nav-link {{ request()->is('seller') ? 'active' : '' }}" href="/seller">Laporan</a> --}}
                    <a class="nav-link {{ request()->is('penjual/pesanan') ? 'active' : '' }}" href="/penjual/pesanan">Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('penjual/produk', 'penjual/produk/store') ? 'active' : '' }}" href="/penjual/produk">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('penjual/riwayat') ? 'active' : '' }}" href="/penjual/riwayat">Riwayat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.dompet.*') ? 'active' : '' }}" href="{{ route('seller.dompet.index') }}">Dana</a>
                </li>
                <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle {{ request()->is('asdasdas*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        asdadasds
                     </a>
                     <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">a</a></li>
                        <li><a class="dropdown-item" href="#">b</a></li>
                     </ul>
                </li>
            </ul>

            <div class="d-none d-lg-flex align-items-center">

                {{-- UPDATE: Tampilkan Saldo Dinamis --}}
                <span class="fw-bold me-3">
                    Rp {{ number_format($saldoNavbar ?? 0, 0, ',', '.') }}
                </span>

                <form method="POST" action="{{ route('logout') }}" class="d-inline me-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm border rounded-pill mt-1" style="width: 100%;">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
                <a href={{ route('seller.profile.index') }}>
                    <img src="{{ asset(Auth::user()->foto_profile ?? 'icon/profile.png') }}" class="rounded-circle" alt="Profil" style="height: 35px; width: 35px; object-fit: cover;">
                </a>
            </div>

        </div>

    </div>
</nav>
