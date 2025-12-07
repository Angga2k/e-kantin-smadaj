<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kantin SMA Negeri 2 Jember')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}

    {{-- <link rel="stylesheet" href="{{ asset('css/buyyer.css') }}"> --}}
    <style>
        :root {
            --primary-green: #00897b;
            --light-gray-bg: #f0f2f5;

            --btn-process-bg: #1e8e3e; /* Merah - Terima & Proses */
            --btn-ready-bg: #0d6efd;   /* Biru - Siap Ambil */
            --btn-done-bg: #198754;    /* Hijau Tua - Telah Diambil (Contoh) */
            --btn-done-bright-bg: #20c997; /* Hijau Terang - Telah Diambil */
            --btn-cancel-bg: #6c757d;  /* Abu-abu - Batalkan/Detail */

            --bg-teal: #00838f;
            --bg-yellow: #fbc02d;
            --bg-red: #c62828;
            --bg-green: #2e7d32;


            --primary-green: #00897b;
            --primary-red: #d32f2f;
            --btn-grey: #757575;
            --light-gray-bg: #f0f2f5;
        }


        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray-bg);
        }

        /* NAV */

        /* Mengatur ukuran toggler agar tidak terlalu besar */
        .navbar-toggler {
            padding: .25rem .5rem;
            font-size: .875rem;
            border: none;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Penyesuaian ikon bell di mobile agar tidak terlalu besar */
        .d-lg-none .bi-bell-fill {
            font-size: 1.1rem;
        }

        /* Main Content Card */
        .product-card {
            background-color: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* Image Upload Box */
        .img-upload-box {
            width: 100%;
            height: 300px;
            background-color: #f8f9fa;
            border: 2px dashed #ced4da;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-weight: 500;
            cursor: pointer;
            overflow: hidden; /* BARU: Agar gambar tidak keluar box */
            position: relative;
        }
        .img-upload-box:hover { background-color: #e9ecef; }
        .img-upload-box i { font-size: 3rem; }

        .pill-options .btn-check:checked + .btn {
            background-color: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
        }

        .pill-options .btn-check:checked + .btn {
            background-color: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
        }

        .pill-options .btn-check:not(:checked) + .btn:hover {
            background-color: rgba(0, 137, 123, 0.1); /* Warna hijau transparan */
        }

        /* BARU: Style untuk preview gambar */
        .img-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
        }

        /* Form Label */
        .form-label {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
             background-color: white;
             border: 1px solid #ced4da;
             border-radius: 8px;
             font-weight: 500;
        }
        .form-control:focus {
             background-color: white;
             border-color: var(--primary-green);
             box-shadow: none;
        }

        /* Varian Dinamis */
        .variant-pill {
            display: inline-flex;
            align-items: center;
            background-color: var(--primary-green);
            color: white;
            padding: 0.3rem 0.5rem 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .variant-pill .btn-remove-variant {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            line-height: 1;
            padding: 0 0 0 0.25rem;
        }

        /* Action Buttons */
        .btn-simpan { background-color: var(--primary-green); color: white; font-weight: 600; }
        .btn-batal { background-color: #6c757d; color: white; font-weight: 600; }


        /* Filter Status Pesanan */
        .status-filters .btn {
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-weight: 500;
            border: none;
            transition: background-color 0.2s;
        }
        .status-filters .btn.active {
            background-color: var(--primary-green);
            color: white;
        }
        .status-filters .btn:not(.active) {
            background-color: #e9ecef;
            color: #495057;
        }

        /* Kartu Pesanan (Mobile First) */
        .order-card {
            background-color: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        /* Style border berbeda untuk setiap status */
        .order-card[data-status="baru"] { border: 2px dashed #e0e0e0; }
        .order-card[data-status="diproses"] { border: 2px dashed var(--btn-ready-bg); }
        .order-card[data-status="siap"] { border: 2px dashed var(--btn-done-bright-bg); }

        .order-card-body dt { font-weight: 600; }
        .order-card-body dd { color: var(--text-gray); }
        .order-card-footer .btn {
            border-radius: 20px;
            font-weight: 600;
            width: 100%;
            padding: 0.6rem;
            border: none;
        }

        /* Kelas untuk Styling Tombol Aksi */
        .btn-cancel { background-color: var(--btn-cancel-bg); color: white; }
        .btn-action-process { background-color: var(--btn-process-bg); color: white; }
        .btn-action-ready { background-color: var(--btn-ready-bg); color: white; }
        .btn-action-done { background-color: var(--btn-done-bright-bg); color: white; }

        /* Tampilan Desktop */
        @media (min-width: 768px) {
            .order-card {
                border: 1px solid #dee2e6;
                box-shadow: 0 4px 8px rgba(0,0,0,0.04);
                padding: 1.25rem;
            }
             .order-card[data-status="baru"] { border: 1px solid #dee2e6; }
             .order-card[data-status="diproses"] { border: 2px dashed var(--btn-ready-bg); }
             .order-card[data-status="siap"] { border: 2px dashed var(--btn-done-bright-bg); }

            .order-card-body .order-row { display: flex; justify-content: space-between; }
            .order-card-body dd { text-align: right; }
            .order-card-footer .btn { width: auto; padding: 0.5rem 1.25rem; }
        }


        /* dashboard */


        /* Small Box Cards */
        .small-box {
            border-radius: 10px;
            position: relative;
            display: block;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            color: #fff;
        }
        .small-box .inner { padding: 20px; }
        .small-box h3 { font-size: 2.5rem; font-weight: 700; margin: 0 0 10px 0; white-space: nowrap; }
        .small-box p { font-size: 1rem; margin-bottom: 5px; font-weight: 500; }
        .small-box .icon { position: absolute; top: 15px; right: 15px; font-size: 1.5rem; color: rgba(255, 255, 255, 0.7); }
        .small-box-footer {
            position: relative; text-align: center; padding: 5px 0; color: rgba(255, 255, 255, 0.8);
            display: block; z-index: 10; background: rgba(0, 0, 0, 0.15); text-decoration: none; font-size: 0.9rem;
        }
        .small-box-footer:hover { color: #fff; background: rgba(0, 0, 0, 0.25); }

        /* Card Colors */
        .bg-card-teal { background-color: var(--bg-teal); }
        .bg-card-yellow { background-color: var(--bg-yellow); }
        .bg-card-red { background-color: var(--bg-red); }
        .bg-card-green { background-color: var(--bg-green); }

        /* Chart Section */
        .chart-card {
            background-color: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            height: 100%;
        }
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .form-select-sm {
            border-radius: 20px;
            font-weight: 500;
            border: 1px solid #ced4da;
        }

        /* Product Card */
        .products-card {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .products-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }

        .card-img-wrapper {
            position: relative;
            height: 180px;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .rating-badge .bi-star-fill { color: #ffc107; }

        .card-body { padding: 1rem; }
        .product-title { font-weight: 600; font-size: 1rem; margin-bottom: 0.25rem; color: #212529; }
        .product-price { color: #0d6efd; font-weight: 700; font-size: 1.1rem; }

        /* Buttons */
        .btn-add-new {
            background-color: var(--primary-green);
            color: white;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            border: none;
            text-decoration: none; /* Penting agar tidak ada underline */
            display: inline-flex;
            align-items: center;
        }
        .btn-add-new:hover { background-color: #b71c1c; color: white; }

        .btn-action-edit {
            background-color: var(--btn-grey);
            color: white;
            border: none;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .btn-action-edit:hover { background-color: #616161; color: white; }

        .btn-action-delete {
            background-color: var(--primary-red);
            color: white;
            border: none;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .btn-action-delete:hover { background-color: #b71c1c; color: white; }
    </style>
</head>
<body>

    <header class="bg-white shadow-sm sticky-top">
        @include('components.headerSeller')
    </header>

    <main class="container my-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Barang store --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // == BAGIAN VARIAN DINAMIS (Tetap sama) ==
            const addVarianBtn = document.getElementById('addVarianBtn');
            const varianInput = document.getElementById('varianInput');
            const varianContainer = document.getElementById('varianContainer');

            const addVarian = () => {
                const varianText = varianInput.value.trim();
                if (varianText) {
                    const pill = document.createElement('span');
                    pill.className = 'variant-pill';
                    pill.innerHTML = `
                        ${varianText}
                        <input type="hidden" name="varian[]" value="${varianText}">
                        <button type="button" class="btn-remove-variant">&times;</button>
                    `;
                    varianContainer.appendChild(pill);
                    varianInput.value = '';
                }
            };
            addVarianBtn.addEventListener('click', addVarian);
            varianInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') { e.preventDefault(); addVarian(); }
            });
            varianContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('btn-remove-variant')) {
                    e.target.closest('.variant-pill').remove();
                }
            });

            // == BARU: BAGIAN PREVIEW GAMBAR ==
            const uploadGambar = document.getElementById('uploadGambar');
            const imagePreview = document.getElementById('imagePreview');
            const placeholderText = document.getElementById('placeholderText');

            uploadGambar.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Buat URL sementara untuk file gambar
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        // Tampilkan gambar di preview
                        imagePreview.src = event.target.result;
                        imagePreview.classList.remove('d-none');
                        // Sembunyikan teks placeholder
                        placeholderText.classList.add('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ====================================================================
            // == BAGIAN LOGIKA BARANG STORE (Varian Dinamis & Preview Gambar) ==
            // Hanya jalankan jika elemen utama ada (misalnya di halaman Barang Store)
            // ====================================================================
            const addVarianBtn = document.getElementById('addVarianBtn');
            const uploadGambar = document.getElementById('uploadGambar');

            if (addVarianBtn) { // Check untuk Varian Dinamis
                const varianInput = document.getElementById('varianInput');
                const varianContainer = document.getElementById('varianContainer');

                const addVarian = () => {
                    const varianText = varianInput.value.trim();
                    if (varianText) {
                        const pill = document.createElement('span');
                        pill.className = 'variant-pill';
                        pill.innerHTML = `
                            ${varianText}
                            <input type="hidden" name="varian[]" value="${varianText}">
                            <button type="button" class="btn-remove-variant">&times;</button>
                        `;
                        varianContainer.appendChild(pill);
                        varianInput.value = '';
                    }
                };
                addVarianBtn.addEventListener('click', addVarian);
                varianInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') { e.preventDefault(); addVarian(); }
                });
                varianContainer.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-remove-variant')) {
                        e.target.closest('.variant-pill').remove();
                    }
                });
            }

            if (uploadGambar) { // Check untuk Preview Gambar
                const imagePreview = document.getElementById('imagePreview');
                const placeholderText = document.getElementById('placeholderText');

                uploadGambar.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            imagePreview.src = event.target.result;
                            imagePreview.classList.remove('d-none');
                            placeholderText.classList.add('d-none');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }


            // ===============================================
            // == BAGIAN LOGIKA PESANAN PENJUAL (AJAX & Filter) ==
            // Hanya jalankan jika elemen utama order-grid ada
            // ===============================================
            const orderGrid = document.getElementById('order-grid');

            if (orderGrid) { // KUNCI PERBAIKAN: Membungkus kode yang spesifik untuk halaman pesanan

                // Fungsi untuk mengubah tampilan kartu berdasarkan status (Filter Tombol Lokal)
                const updateCards = (status) => {
                    const orderCards = document.querySelectorAll('.order-card');
                    orderCards.forEach(card => {
                        const footer = card.querySelector('.order-card-footer');
                        let actionButtonHTML = '';

                        card.dataset.status = status;

                        // Tombol Logika (Prototype/UI Only)
                        if (status === 'baru') {
                            footer.innerHTML = `
                                <div class="row g-2">
                                    <div class="col-6"><button class="btn btn-cancel">Detail</button></div>
                                    <div class="col-6"><button class="btn btn-action-process">Terima & Proses</button></div>
                                </div>`;
                        } else if (status === 'diproses') {
                            footer.innerHTML = `
                                <div class="row g-2">
                                    <div class="col-6"><button class="btn btn-cancel">Batalkan</button></div>
                                    <div class="col-6"><button class="btn btn-action-ready">Siap Ambil</button></div>
                                </div>`;
                        } else if (status === 'siap') {
                            footer.innerHTML = `
                                <div class="row g-2">
                                    <div class="col-6"><button class="btn btn-cancel">Batalkan</button></div>
                                    <div class="col-6"><button class="btn btn-action-done">Telah Diambil</button></div>
                                </div>`;
                        }
                    });
                };
            }
        });
    </script>

    @stack('scripts')

</body>
</html>
