<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use App\Models\Dompet;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Jika pengguna sudah login, arahkan ke halaman utama
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // dd($credentials);

        $field = 'username';

        // 2. Coba Autentikasi
        if (Auth::attempt($credentials)) {
            // dd('SUCCESS: Login Berhasil. Cek DB jika gagal redirect.');
            $request->session()->regenerate();

            $user = Auth::user();
            // return redirect()->route('auth.tes');
            if ($user->role === 'penjual') {
                $dompet = Dompet::where('id_user', $user->id_user)->first();

                // Jika tidak punya dompet, buat baru
                if (! $dompet) {
                    Dompet::create([
                        'id_user' => $user->id_user,
                        'saldo'   => 0,
                    ]);
                }
                return redirect()->route('seller.beranda.index');
            }
            return redirect()->route('beranda.index');
        }
        // dd('gagal masuk redirect');

        // 4. Autentikasi Gagal
        if (!Auth::attempt($credentials)) {
            // ...
        throw ValidationException::withMessages([
            'username' => ['Username atau kata sandi tidak cocok.'],
        ])->redirectTo(route('login'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('beranda.index');
    }
}
