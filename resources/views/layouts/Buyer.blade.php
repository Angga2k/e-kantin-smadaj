@php
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'penjual') {
            header("Location: " . url('/penjual'));
            exit;
        } elseif ($role === 'admin') {
            header("Location: " . url('/admin'));
            exit;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kantin SMA Negeri 2 Jember')</title>

    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="{{ asset('css/buyyer.css') }}">
</head>
<body>

    <header class="bg-white shadow-sm sticky-top">
        @include('components.header')
    </header>

    <section class="hero-banner d-none {{ request()->is('detail*', 'profile', 'profile/update', 'pesanan', 'tesssss') ? '' : 'd-md-block' }}">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center">

                <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="hero-logo">

                <div class="hero-text ms-4">
                    <h1 class="hero-title">KANTIN DIGITAL</h1>
                    <p class="hero-subtitle">SMA NEGERI 2 JEMBER</p>
                </div>

            </div>
        </div>
    </section>

    <main class="container my-4">
        @yield('content')
    </main>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        @include('components.keranjang')
    </div>

    {{-- LOGIKA KERANJANG (LOCAL STORAGE) --}}
    <script src="{{ asset('js/cart.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- TANGGAL DI KERANJANG GUYS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        flatpickr("#tanggalPicker", {
            "dateFormat": "d/m/Y",
            "defaultDate": "today",
            "altInput": true,
            "altFormat": "l, J F Y",
            "locale": "id"
        });
    </script>


    {{-- SEARCH --}}
    {{-- <script>
        const searchInputs = document.querySelectorAll('.search-filter-input');
        searchInputs.forEach(input => {
            input.addEventListener('input', function() {
                const searchTerm = input.value;
                searchInputs.forEach(inputToSync => {
                    if (inputToSync !== input) {
                        inputToSync.value = searchTerm;
                    }
                });

                const productCards = document.querySelectorAll('.col');
                const notFoundMessage = document.getElementById('notFoundMessage');
                const textEarlyElement = document.querySelector('.early');

                let visibleCount = 0;

                if (textEarlyElement) {
                    if (searchTerm.toLowerCase().length > 0) {
                        textEarlyElement.classList.add('d-none');
                    } else {
                        textEarlyElement.classList.remove('d-none');
                    }
                }

                productCards.forEach(card => {
                    const titleElement = card.querySelector('.card-title');
                    if (titleElement) {
                        const titleText = titleElement.textContent.toLowerCase();
                        if (titleText.startsWith(searchTerm)) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });

                if (notFoundMessage) {
                    if (visibleCount === 0 && searchTerm.length > 0) {
                        notFoundMessage.classList.remove('d-none');
                    } else {
                        notFoundMessage.classList.add('d-none');
                    }
                }
            });
        });
    </script> --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInputs = document.querySelectorAll('.search-filter-input');
        const productCards = document.querySelectorAll('.col');
        const notFoundMessage = document.getElementById('notFoundMessage');
        const textEarlyElement = document.querySelector('.early');

        searchInputs.forEach(input => {
            input.addEventListener('input', function() {
                const searchTerm = input.value.toLowerCase().trim();

                // 1. Sinkronisasi antar input (Mobile & Desktop)
                searchInputs.forEach(inputToSync => {
                    if (inputToSync !== input) inputToSync.value = input.value;
                });

                // 2. Kontrol Teks Headline (Early)
                if (textEarlyElement) {
                    searchTerm.length > 0 
                        ? textEarlyElement.classList.add('d-none') 
                        : textEarlyElement.classList.remove('d-none');
                }

                let visibleCount = 0;

                // 3. Filter Produk
                productCards.forEach(card => {
                    const titleElement = card.querySelector('.card-title');
                    if (titleElement) {
                        const titleText = titleElement.textContent.toLowerCase();
                        
                        // PERBAIKAN: Gunakan includes agar bisa cari kata di tengah
                        if (titleText.includes(searchTerm)) {
                            card.style.setProperty('display', 'block', 'important');
                            visibleCount++;
                        } else {
                            card.style.setProperty('display', 'none', 'important');
                        }
                    }
                });

                // 4. Pesan Tidak Ditemukan
                if (notFoundMessage) {
                    if (visibleCount === 0 && searchTerm.length > 0) {
                        notFoundMessage.classList.remove('d-none');
                    } else {
                        notFoundMessage.classList.add('d-none');
                    }
                }
            });
        });
    });
</script>


    {{-- UPDATE PROFILE --}}
    <script>
        // Ambil elemen input tanggal
        const ttlInput = document.getElementById("ttl");

        // Cek apakah elemen ada (agar tidak error di halaman lain)
        if(ttlInput) {
            flatpickr("#ttl", {
                altInput: true,
                altFormat: "j F Y", // Tampilan: 20 Mei 2024
                dateFormat: "Y-m-d", // Format kirim ke server: 2024-05-20

                // LOGIKA PENTING:
                // Jika input punya value (dari database), pakai itu.
                // Jika kosong, baru pakai tanggal default.
                defaultDate: ttlInput.value ? ttlInput.value : "2007-01-13",

                "locale": "id"
            });
        }

        // ... script upload foto ...
    </script>

    {{-- SCRIPT AUTO REFRESH SESSION --}}
    <script>
        // Ambil waktu lifetime session dari config Laravel (dalam menit)
        // Default biasanya 120 menit. Kita ubah ke milidetik.
        let sessionLifetime = {{ config('session.lifetime') }} * 60 * 1000;

        // Kurangi 2 menit (120000 ms) sebagai buffer aman sebelum benar-benar expired
        let refreshTime = sessionLifetime - 120000; 

        setTimeout(function() {
            // Opsi 1: Reload halaman (User akan otomatis terlempar ke login jika session habis)
            window.location.reload();
            
            // Opsi 2 (Alternatif): Langsung arahkan ke halaman login
            // window.location.href = "{{ route('login') }}";
        }, refreshTime);
    </script>

</body>
</html>
