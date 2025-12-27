<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-0" href="/">
            <img src="{{ asset('asset/logo.png') }}" alt="Logo" style="height: 30px;">
            <span class="fw-bold d-none d-md-inline ms-2">SMA NEGERI 2 JEMBER</span>
        </a>

        <div class="d-flex align-items-center d-lg-none ms-auto">
            <div class="search-box search-box-mobile mx-1" style="{{ !Auth::check() ? 'width: 200px;' : '' }}">
                <i class="bi bi-search search-icon"></i>
                <input class="form-control search-filter-input" type="search" placeholder="Cari...">
            </div>
            @auth
                @php
                $userRole = Auth::user()->role;
                $profile = Auth::user()->foto_profile;
                @endphp
                @if ($userRole === 'siswa' || $userRole === 'civitas_akademik')
                    <button class="btn btn-keranjang mx-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                        <i class="bi bi-cart-fill"></i>
                    </button>
                    <a href='{{ route('profile.index') }}'>
                        @if($profile)
                            <img src="{{ asset($profile) }}" class="rounded-circle me-2" alt="Profil" style="height: 35px; width: 35px; object-fit: cover;">
                        @endif
                    </a>
                @endif
            @endauth

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a>
                </li>
                <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle {{ request()->is('makanan', 'minuman', 'camilan') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Kategori
                     </a>
                     <ul class="dropdown-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('makanan') ? 'active' : '' }}" href="/makanan">Makanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('minuman') ? 'active' : '' }}" href="/minuman">Minuman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('camilan') ? 'active' : '' }}" href="/camilan">Camilan</a>
                        </li>
                     </ul>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pesanan') ? 'active' : '' }}" href="/pesanan">Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm border rounded-pill mt-1" style="width: 100%;">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item d-lg-none">
                        <a class="btn btn-success btn-sm rounded-pill mt-2 w-100 d-flex align-items-center justify-content-center fw-bold" href="{{ route('login') }}" style="width: 100%;">
                            <i class="bi bi-person-fill me-1"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>

            <div class="d-none d-lg-flex align-items-center">
                <div class="search-box me-1" style="width: 300px;">
                    <i class="bi bi-search search-icon"></i>
                    <input class="form-control search-filter-input" type="search" placeholder="Cari...">
                </div>

                @auth
                    @php $userRole = Auth::user()->role; @endphp
                    @if ($userRole === 'siswa' || $userRole === 'civitas_akademik')
                    <button class="btn btn-keranjang me-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                        <i class="bi bi-cart-fill"></i>Keranjang
                    </button>
                    <a href='{{ route('profile.index') }}'>
                        @if($profile)
                            <img src="{{ asset($profile) }}" class="rounded-circle me-2" alt="Profil" style="height: 35px; width: 35px; object-fit: cover;">
                        @endif
                    </a>
                    @endif
                @else
                    <a class="nav-link btn btn-success btn-sm border rounded-pill p-2" href="{{ route('login') }}" style="width: 150px;">
                        <i class="bi bi-person-fill me-1"></i> Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
