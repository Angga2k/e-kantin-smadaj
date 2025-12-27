<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - Kantin Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-green: #00897b;
            --light-gray-bg: #f0f2f5;
            --text-dark: #333;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray-bg);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden; /* Mencegah scrollbar */
        }

        .error-card {
            max-width: 600px;
            width: 90%;
            text-align: center;
            padding: 2rem;
        }

        .error-illustration {
            width: 100%;
            max-width: 350px;
            margin-bottom: 2rem;
            /* Animasi melayang sedikit */
            animation: float 3s ease-in-out infinite;
        }

        .error-code {
            font-size: 5rem;
            font-weight: 800;
            color: var(--primary-green);
            line-height: 1;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 0px rgba(0,0,0,0.1);
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .error-message {
            color: #6c757d;
            font-weight: 400;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-back {
            background-color: var(--primary-green);
            color: white;
            border-radius: 50px;
            padding: 0.8rem 2.5rem;
            font-weight: 600;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 10px rgba(0, 137, 123, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-back:hover {
            background-color: #00695c;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 137, 123, 0.4);
        }

        /* Animasi Sederhana */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>

    <div class="error-card">
        <img src="{{ asset('asset/piring_kosong.png') }}" alt="Piring Kosong 404" class="error-illustration">

        <div class="error-code">404</div>
        <h1 class="error-title">Yah, Page Tidak di Temukan!</h1>

        <p class="error-message">
            Sepertinya halaman yang kamu cari tidak tersedia.<br class="d-none d-md-block">
            Jangan biarkan perutmu kosong, yuk kembali ke beranda.
        </p>

        <a href="/" class="btn btn-back">
            <i class="bi bi-house-door-fill me-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
