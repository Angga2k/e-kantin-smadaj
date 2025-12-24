<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use App\Models\Dompet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            else if ($user->role === 'admin') {
                return redirect()->route('admin.index');
            }
            return redirect()->route('beranda.index');
        }
            // dd($user->role);

        // 4. Autentikasi Gagal
        throw ValidationException::withMessages([
            'username' => ['Username atau kata sandi tidak cocok.'],
        ])->redirectTo(route('login'))->withInput($request->only('username'));
    }

    public function showRegistrationForm()
    {
        return view('auth.signup');
    }

    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            // NISN harus unik di tabel siswa DAN users (sebagai username)
            'nisn' => 'required|string|max:20|unique:siswa,nisn|unique:users,username', 
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' akan mengecek field password_confirmation
            'role' => 'required|in:siswa', // Validasi role harus siswa (sesuai hidden input di view)
        ]);

        DB::beginTransaction();
        try {
            // 2. Buat Akun User (Tabel Users)
            // NISN digunakan sebagai Username login
            $user = User::create([
                'id_user' => Str::uuid(),
                'username' => $request->nisn, 
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            // 3. Buat Data Profil Siswa (Tabel Siswa)
            Siswa::create([
                'id' => Str::uuid(),
                'id_user' => $user->id_user, // Relasi ke User
                'nisn' => $request->nisn,
                'nama' => $request->name,
                'tgl_lahir' => $request->tgl_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            DB::commit();

            // 4. Auto Login (Opsional, agar user tidak perlu login ulang)
            Auth::login($user);

            // 5. Redirect ke Halaman Beranda
            return redirect()->route('beranda.index')->with('success', 'Registrasi berhasil! Selamat datang.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Kembalikan ke form jika ada error database dll
            return back()->withInput()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()]);
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
