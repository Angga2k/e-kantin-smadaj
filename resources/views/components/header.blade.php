<div class="header">
    <div class="main-header">

        <div class="header-left active">
            {{-- Menggunakan helper url() untuk link ke halaman utama --}}
            <a href="{{ url('/') }}" class="logo logo-normal">
                {{-- Menggunakan helper asset() untuk memanggil gambar dari folder public --}}
                <img src="{{ asset('img/logo.svg') }}" alt="Logo">
            </a>
            <a href="{{ url('/') }}" class="logo logo-white">
                <img src="{{ asset('img/logo-white.svg') }}" alt="Logo White">
            </a>
            <a href="{{ url('/') }}" class="logo-small">
                <img src="{{ asset('img/logo-small.png') }}" alt="Logo Small">
            </a>
        </div>
        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <ul class="nav user-menu">

            <li class="nav-item nav-searchinputs">
                <div class="top-nav-search">
                    <a href="javascript:void(0);" class="responsive-search">
                        <i class="fa fa-search"></i>
                    </a>
                    <form action="#" class="dropdown">
                        <div class="searchinputs input-group dropdown-toggle" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <input type="text" placeholder="Search">
                            <div class="search-addon">
                                <span><i class="ti ti-search"></i></span>
                            </div>
                            <span class="input-group-text">
                                <kbd class="d-flex align-items-center"><img src="{{ asset('img/icons/command.svg') }}" alt="Command" class="me-1">K</kbd>
                            </span>
                        </div>
                        <div class="dropdown-menu search-dropdown" aria-labelledby="dropdownMenuClickable">
                            <div class="search-info">
                                <h6><span><i data-feather="search" class="feather-16"></i></span>Recent Searches</h6>
                                <ul class="search-tags">
                                    <li><a href="javascript:void(0);">Products</a></li>
                                    <li><a href="javascript:void(0);">Sales</a></li>
                                    <li><a href="javascript:void(0);">Applications</a></li>
                                </ul>
                            </div>
                            <div class="search-info">
                                <h6><span><i data-feather="help-circle" class="feather-16"></i></span>Help</h6>
                                <p>How to Change Product Volume from 0 to 200 on Inventory management</p>
                                <p>Change Product Name</p>
                            </div>
                            <div class="search-info">
                                <h6><span><i data-feather="user" class="feather-16"></i></span>Customers</h6>
                                <ul class="customers">
                                    {{-- Path gambar di dalam perulangan juga harus diperbaiki --}}
                                    <li><a href="javascript:void(0);">Aron Varu<img src="{{ asset('img/profiles/avator1.jpg') }}" alt="Img" class="img-fluid"></a></li>
                                    <li><a href="javascript:void(0);">Jonita<img src="{{ asset('img/profiles/avatar-01.jpg') }}" alt="Img" class="img-fluid"></a></li>
                                    <li><a href="javascript:void(0);">Aaron<img src="{{ asset('img/profiles/avatar-10.jpg') }}" alt="Img" class="img-fluid"></a></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </li>
            <li class="nav-item dropdown has-arrow main-drop select-store-dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle nav-link select-store" data-bs-toggle="dropdown">
                    <span class="user-info">
                        <span class="user-letter">
                            <img src="{{ asset('img/store/store-01.png') }}" alt="Store Logo" class="img-fluid">
                        </span>
                        <span class="user-detail">
                            <span class="user-name">Freshmart</span>
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ asset('img/store/store-01.png') }}" alt="Store Logo" class="img-fluid">Freshmart
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ asset('img/store/store-02.png') }}" alt="Store Logo" class="img-fluid">Grocery Apex
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ asset('img/store/store-03.png') }}" alt="Store Logo" class="img-fluid">Grocery Bevy
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ asset('img/store/store-04.png') }}" alt="Store Logo" class="img-fluid">Grocery Eden
                    </a>
                </div>
            </li>
            {{-- ... sisa kode header lainnya juga perlu diperbaiki dengan cara yang sama ... --}}
            {{-- Contoh untuk Flag --}}
            <li class="nav-item dropdown has-arrow flag-nav nav-item-box">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
                    <img src="{{ asset('img/flags/us-flag.svg') }}" alt="Language" class="img-fluid">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ asset('img/flags/english.svg') }}" alt="Img" height="16">English
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ asset('img/flags/arabic.svg') }}" alt="Img" height="16">Arabic
                    </a>
                </div>
            </li>
            {{-- Contoh untuk Notifikasi dan Profile --}}
            <li class="nav-item dropdown has-arrow main-drop profile-nav">
                <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                    <span class="user-info p-0">
                        <span class="user-letter">
                            <img src="{{ asset('img/profiles/avator1.jpg') }}" alt="Img" class="img-fluid">
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profileset d-flex align-items-center">
                        <span class="user-img me-2">
                            <img src="{{ asset('img/profiles/avator1.jpg') }}" alt="Img">
                        </span>
                        <div>
                            <h6 class="fw-medium">John Smilga</h6>
                            <p>Admin</p>
                        </div>
                    </div>
                    {{-- Ganti link-link ini dengan route yang sesuai nanti --}}
                    <a class="dropdown-item" href="{{ url('/profile') }}"><i class="ti ti-user-circle me-2"></i>MyProfile</a>
                    <a class="dropdown-item" href="{{ url('/reports') }}"><i class="ti ti-file-text me-2"></i>Reports</a>
                    <a class="dropdown-item" href="{{ url('/settings') }}"><i class="ti ti-settings-2 me-2"></i>Settings</a>
                    <hr class="my-2">
                    <a class="dropdown-item logout pb-0" href="{{ url('/logout') }}"><i class="ti ti-logout me-2"></i>Logout</a>
                </div>
            </li>

        </ul>
        </div>
</div>
