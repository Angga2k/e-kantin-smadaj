<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kantin SMA Negeri 2 Jember')</title>

    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}">

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

    <section class="hero-banner d-none {{ request()->is('detail', 'profile', 'profile/update') ? '' : 'd-md-block' }}">
        <h1>E-Kantin SMAN 2 Jember</h1>
    </section>

    <main class="container my-4">
        @yield('content')
    </main>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        @include('components.keranjang')
    </div>

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
    <script>
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
    </script>


    {{-- UPDATE PROFILE --}}
    <script>
        flatpickr("#ttl", {
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            defaultDate: "2007-01-13",
            "locale": "id"
        });
        document.getElementById('file-upload').onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                document.getElementById('profile-image').src = URL.createObjectURL(file);
            }
        };
    </script>

</body>
</html>
