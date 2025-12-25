<nav class="nav flex-column mt-3">
    <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') }}">
        <i class="bi bi-people me-2"></i> Manajemen User
    </a>
    {{-- Tambahkan menu admin lainnya di sini --}}

    <a class="nav-link text-danger mt-auto" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
        <i class="bi bi-box-arrow-left me-2"></i> Logout
    </a>
</nav>
