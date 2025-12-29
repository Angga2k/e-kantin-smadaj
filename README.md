# e-Kantin SMA 2 Jember

tes

Selamat datang di **e-Kantin SMA 2 Jember**, sebuah aplikasi berbasis web yang dirancang untuk mempermudah pengelolaan dan pemesanan makanan di kantin sekolah. Aplikasi ini bertujuan untuk memberikan pengalaman yang lebih efisien dan modern bagi siswa, guru, dan pengelola kantin.

## Fitur Utama

- **Pemesanan Online**: Siswa dan guru dapat memesan makanan dan minuman secara online melalui aplikasi.
- **Manajemen Menu**: Pengelola kantin dapat menambahkan, mengedit, dan menghapus menu makanan dengan mudah.
- **Notifikasi Pesanan**: Notifikasi real-time untuk pesanan baru, siap diambil, atau selesai.
- **Riwayat Transaksi**: Lihat riwayat pemesanan dan transaksi untuk mempermudah pelacakan.
- **Integrasi Pembayaran**: Mendukung berbagai metode pembayaran qris dan dompet digital.

## Teknologi yang Digunakan

- **Framework**: Laravel 12
- **Frontend**: Tailwind CSS
- **Database**: MySQL
- **Server**: PHP 8.2
- **Build Tools**: Vite

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi ini di lingkungan lokal Anda:

1. **Clone Repository**
   ```bash
   git clone https://github.com/Angga2k/ekantin-smadaj.git
   cd ekantin-smadaj
   ```

2. **Instal Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Lingkungan**
   Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database**
   Jalankan migrasi untuk membuat tabel di database:
   ```bash
   php artisan migrate
   ```

5. **Jalankan Server**
   ```bash
   php artisan serve
   npm run dev
   ```

6. **Akses Aplikasi**
   Buka browser dan akses [http://localhost:8000](http://localhost:8000).

## Kontribusi

Kami sangat menghargai kontribusi Anda untuk mengembangkan aplikasi ini. Jika Anda ingin berkontribusi, silakan ikuti langkah-langkah berikut:

1. Fork repository ini.
2. Buat branch baru untuk fitur atau perbaikan Anda.
3. Kirim pull request ke branch `main`.

## Lisensi

Aplikasi ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).

---

Dibuat dengan ❤️ oleh tim pengembang untuk SMA 2 Jember.
