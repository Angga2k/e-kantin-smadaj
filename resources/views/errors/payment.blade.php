<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran - Kantin SMAN 2 Jember</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .status-card { max-width: 400px; margin: 50px auto; border: none; border-radius: 20px; }
        .status-img { width: 150px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="container d-flex align-items-center min-vh-100">
        <div class="card status-card shadow-sm p-4 text-center w-100 bg-white">
            <div class="card-body">

                {{-- Gambar Piring Kosong (Sesuai Request) --}}
                <img src="{{ asset('asset/piring_kosong.png') }}" alt="Status" class="status-img">

                @if($status == 'success')
                    <h4 class="fw-bold text-success mb-2">Pembayaran Berhasil!</h4>
                    <p class="text-muted small mb-4">
                        Terima kasih! Pesananmu sedang disiapkan oleh penjual.
                        Silakan cek status pesanan di menu Riwayat.
                    </p>
                @else
                    <h4 class="fw-bold text-danger mb-2">Pembayaran Gagal/Expired</h4>
                    <p class="text-muted small mb-4">
                        Yah, pembayaranmu belum berhasil atau waktu habis.
                        Silakan coba pesan ulang ya.
                    </p>
                @endif

                <div class="d-grid gap-2">
                    <a href="{{ route('beranda.index') }}" class="btn btn-primary rounded-pill py-2 fw-bold">
                        Kembali ke Beranda
                    </a>

                    {{-- Opsional: Tombol ke Riwayat Pesanan --}}
                    {{-- <a href="/siswa/riwayat" class="btn btn-outline-secondary rounded-pill py-2">Cek Pesanan</a> --}}
                </div>

            </div>
        </div>
    </div>

</body>
</html>
