<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - Penjual</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-green: #00897b;
            --light-gray-bg: #f0f2f5;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray-bg);
        }
        /* Navbar Styles */
        .navbar { background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,.05); }
        .navbar-brand img { height: 35px; }
        .navbar .nav-link { font-weight: 500; color: #333; }
        .navbar .icon-link { font-size: 1.3rem; color: #555; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; background-color: #6c757d; }

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
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="{{ asset('logo/smanda.png') }}" alt="Logo" class="me-2"><span class="fw-bold d-none d-lg-inline">SMA NEGERI 2 JEMBER</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Tarik Dana</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">History</a></li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <span class="fw-bold text-success me-3">Rp 240.000</span>
                    <div class="avatar"></div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-4">

        <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Produk Baru</li>
            </ol>
        </nav>

        <div class="card product-card">
            <div class="card-body p-4 p-md-5">
                <form id="formTambahProduk">
                    <div class="row g-5">
                        <div class="col-lg-4">
                            <div class="mb-4">
                                <label for="uploadGambar" class="img-upload-box">
                                    <img src="" alt="Preview Gambar" class="img-preview d-none" id="imagePreview">
                                    <div id="placeholderText">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <span>Klik untuk upload gambar</span>
                                    </div>
                                </label>
                                <input type="file" id="uploadGambar" class="d-none" accept="image/*">
                            </div>
                            <div>
                                <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                                <textarea class="form-control" id="deskripsi" rows="4" placeholder="Tulis deskripsi singkat produk..."></textarea>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="namaProduk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="namaProduk" placeholder="Contoh: Ayam Goreng Lengkuas">
                            </div>
                            <div class="mb-3">
                                <label for="hargaProduk" class="form-label">Harga Produk</label>
                                <input type="number" class="form-control" id="hargaProduk" placeholder="Contoh: 12000">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori Produk</label>
                                <div class="d-flex gap-2 pill-options">
                                    <input type="radio" class="btn-check" name="kategori" id="cat-makanan" autocomplete="off">
                                    <label class="btn" for="cat-makanan">Makanan</label>
                                    <input type="radio" class="btn-check" name="kategori" id="cat-minuman" autocomplete="off">
                                    <label class="btn" for="cat-minuman">Minuman</label>
                                    <input type="radio" class="btn-check" name="kategori" id="cat-camilan" autocomplete="off">
                                    <label class="btn" for="cat-camilan">Camilan</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="varianInput" class="form-label">Varian Produk (Opsional)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="varianInput" placeholder="Contoh: Pedas, Manis, Original">
                                    <button class="btn btn-outline-secondary" type="button" id="addVarianBtn">+</button>
                                </div>
                                <div class="mt-2" id="varianContainer"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Kandungan (Opsional)</label>
                                <div class="d-flex flex-column flex-md-row gap-3">
                                    <div class="input-group"><span class="input-group-text">Lemak</span><input type="number" class="form-control" placeholder="0"></div>
                                    <div class="input-group"><span class="input-group-text">Karbo</span><input type="number" class="form-control" placeholder="0"></div>
                                    <div class="input-group"><span class="input-group-text">Protein</span><input type="number" class="form-control" placeholder="0"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-batal px-4">Batal</button>
                        <button type="submit" class="btn btn-simpan px-4">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
</body>
</html>
