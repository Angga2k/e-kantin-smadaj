<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles Daftar role yang diizinkan (misalnya: 'seller', 'buyer')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Catatan: Pengecekan Auth::check() utama sudah dilakukan di middleware 'auth' (Langkah 1).
        // Tapi, jika request berhasil sampai sini tanpa Auth::check(), ini adalah safety check.
        if (!Auth::check()) {
            abort(404); // Seharusnya sudah di-handle oleh Authenticate kustom
        }

        $user = Auth::user();
        $userRole = strtolower($user->role);

        // 2. Cek apakah role pengguna ada di daftar role yang diizinkan
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 3. Penanganan Akses Ditolak (LINTAS ROLE)
        // KUNCI PERBAIKAN: Ganti semua redirect lintas role dengan 404
        abort(404);
    }
}
