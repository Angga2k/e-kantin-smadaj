<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Dompet;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Menggunakan Bootstrap 5 untuk Pagination (agar tampilan paging rapi)
        Paginator::useBootstrapFive();

        // 2. View Composer untuk Navbar (components.headerSeller)
        // Logic ini akan berjalan setiap kali view 'components.headerSeller' dirender
        View::composer('components.headerSeller', function ($view) {
            $saldo = 0;

            // Ambil ID User yang sedang login
            $idUser = Auth::id();

            // --- OPSIONAL: MODE TESTING ---
            // Jika Anda sedang testing tanpa login, aktifkan baris ini:
            if (!$idUser) {
                 $idUser = '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3'; // ID Penjual Dummy
            }
            // ------------------------------

            if ($idUser) {
                // Ambil data dompet
                $dompet = Dompet::where('id_user', $idUser)->first();
                // Ambil saldo (otomatis didekripsi oleh Model)
                $saldo = $dompet ? $dompet->saldo : 0;
            }

            // Kirim variabel $saldoNavbar ke view
            $view->with('saldoNavbar', $saldo);
        });
    }
}
