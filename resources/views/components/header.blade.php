<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('asset/logo.png') }}" alt="Logo" style="height: 40px;">
            <span class="fw-bold d-none d-md-inline">SMA NEGERI 2 JEMBER</span> </a>
        <div class="d-flex align-items-center d-lg-none ms-auto">
            <div class="search-box search-box-mobile">
                <i class="bi bi-search search-icon"></i>
                <input class="form-control search-filter-input" type="search" placeholder="Cari...">
            </div>

            <button class="btn btn-keranjang mx-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                <i class="bi bi-cart-fill"></i>
            </button>

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
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="/profile">Profile</a>
                </li>
            </ul>

            <div class="d-none d-lg-flex align-items-center">
                <div class="search-box me-3">
                    <i class="bi bi-search search-icon"></i>
                    <input class="form-control search-filter-input" type="search" placeholder="Cari...">
                </div>
                <button class="btn btn-keranjang" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                    <i class="bi bi-cart-fill me-2"></i>Keranjang
                </button>
            </div>
        </div>
    </div>
</nav>
