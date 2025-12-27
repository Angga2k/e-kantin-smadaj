<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ url('/') }}" class="logo logo-normal">
            <img src="{{ asset('img/logo.svg') }}" alt="Img">
        </a>
        <a href="{{ url('/') }}" class="logo logo-white">
            <img src="{{ asset('img/logo-white.svg') }}" alt="Img">
        </a>
        <a href="{{ url('/') }}" class="logo-small">
            <img src="{{ asset('img/logo-small.png') }}" alt="Img">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    {{-- Saya gabungkan bagian profile untuk contoh --}}
    <div class="modern-profile p-3 pb-0">
        <div class="text-center rounded bg-light p-3 mb-4 user-profile">
            <div class="avatar avatar-lg online mb-3">
                <img src="{{ asset('img/customer/customer15.jpg') }}" alt="Img" class="img-fluid rounded-circle">
            </div>
            <h6 class="fs-14 fw-bold mb-1">Adrian Herman</h6>
            <p class="fs-12 mb-0">System Admin</p>
        </div>
    </div>

    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li><a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}"><i class="ti ti-layout-grid fs-16 me-2"></i><span>Dashboard</span><span></span></a></li>

                        <li class="submenu {{ request()->is('application*') ? 'active subdrop' : '' }}">
                            <a href="javascript:void(0);"><i class="ti ti-brand-apple-arcade fs-16 me-2"></i><span>Application</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ url('/chat') }}" class="{{ request()->is('chat') ? 'active' : '' }}">Chat</a></li>
                                <li><a href="{{ url('/calendar') }}" class="{{ request()->is('calendar') ? 'active' : '' }}">Calendar</a></li>
                                <li><a href="{{ url('/email') }}" class="{{ request()->is('email') ? 'active' : '' }}">Email</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Inventory</h6>
                    <ul>
                        <li><a href="{{ url('/products') }}" class="{{ request()->is('products') ? 'active' : '' }}"><i data-feather="box"></i><span>Products</span></a></li>
                        <li><a href="{{ url('/products/create') }}" class="{{ request()->is('products/create') ? 'active' : '' }}"><i class="ti ti-table-plus fs-16 me-2"></i><span>Create Product</span></a></li>
                        <li><a href="{{ url('/categories') }}" class="{{ request()->is('categories') ? 'active' : '' }}"><i class="ti ti-list-details fs-16 me-2"></i><span>Category</span></a></li>
                    </ul>
                </li>

                {{-- ... Lanjutkan pola yang sama untuk semua menu lainnya ... --}}

            </ul>
        </div>
    </div>
</div>
