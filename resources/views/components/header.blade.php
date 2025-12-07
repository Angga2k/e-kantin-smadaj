<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('asset/logo.png') }}" alt="Logo" style="height: 40px;">
            <span class="fw-bold d-none d-md-inline">SMA NEGERI 2 JEMBER</span> </a>
        <div class="d-flex align-items-center d-lg-none ms-auto">
            <div class="search-box search-box-mobile mx-1">
                <i class="bi bi-search search-icon"></i>
                <input class="form-control search-filter-input" type="search" placeholder="Cari...">
            </div>
            @auth
                @php $userRole = Auth::user()->role; @endphp
                @if ($userRole === 'siswa' || $userRole === 'civitas_akademik')
                    <button class="btn btn-keranjang mx-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                        <i class="bi bi-cart-fill"></i>
                    </button>
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
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('makanan') ? 'active' : '' }}" href="/makanan">Makanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('minuman') ? 'active' : '' }}" href="/minuman">Minuman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('camilan') ? 'active' : '' }}" href="/camilan">Camilan</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="/profile">Profile</a>
                </li> --}}
                @auth
                    <li class="nav-item">
                        {{-- <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}" href="{{ route('buyer.profile.index') }}">Profile</a> --}}
                        <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="/profile">Profile</a>
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
                        <a class="nav-link btn btn-success btn-sm border rounded-pill mt-2" href="{{ route('login') }}" style="width: 100%;">
                            <i class="bi bi-person-fill me-1"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>

            <div class="d-none d-lg-flex align-items-center">
                <div class="search-box me-3">
                    <i class="bi bi-search search-icon"></i>
                    <input class="form-control search-filter-input" type="search" placeholder="Cari...">
                </div>

                @auth
                    @php $userRole = Auth::user()->role; @endphp
                    @if ($userRole === 'siswa' || $userRole === 'civitas_akademik')
                    <button class="btn btn-keranjang" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                        <i class="bi bi-cart-fill me-2"></i>Keranjang
                    </button>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link btn btn-success btn-sm border rounded-pill p-2" href="{{ route('login') }}" style="width: 100%;">
                            <i class="bi bi-person-fill me-1"></i> Login
                        </a>
                    </li>
                @endauth
            </div>
        </div>
    </div>
</nav>
