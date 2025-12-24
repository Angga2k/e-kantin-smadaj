<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penjual;
use App\Models\CivitasAkademik;
use App\Models\Dompet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Menampilkan daftar user dengan filter
     */
    public function index(Request $request)
    {
        $roleFilter = $request->query('role', 'all');

        $query = User::with(['siswa', 'penjual', 'civitasAkademik']);

        if ($roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }

        // Urutkan terbaru
        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.index', compact('users', 'roleFilter'));
    }

    /**
     * Menampilkan form tambah akun
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Menyimpan akun baru (Guru/Penjual)
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:civitas_akademik,penjual',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8',
            // Validasi Guru
            'nama' => 'required_if:role,civitas_akademik',
            'npwp' => 'nullable|required_if:role,civitas_akademik',
            // Validasi Penjual
            'nama_toko' => 'required_if:role,penjual',
            'nama_penanggungjawab' => 'required_if:role,penjual',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat User Login
            $user = User::create([
                'id_user' => Str::uuid(),
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // 2. Buat Profil Berdasarkan Role
            if ($request->role === 'civitas_akademik') {
                CivitasAkademik::create([
                    'id' => Str::uuid(),
                    'id_user' => $user->id_user,
                    'nama' => $request->nama,
                    'npwp' => $request->npwp,
                    'tgl_lahir' => $request->tgl_lahir ?? now(),
                    'jenis_kelamin' => $request->jenis_kelamin ?? 'L',
                ]);
            } elseif ($request->role === 'penjual') {
                Penjual::create([
                    'id' => Str::uuid(),
                    'id_user' => $user->id_user,
                    'nama_toko' => $request->nama_toko,
                    'nama_penanggungjawab' => $request->nama_penanggungjawab,
                    'no_rekening' => $request->no_rekening ?? '-',
                    'nama_bank' => $request->nama_bank ?? '-',
                ]);

                // Otomatis buat dompet saldo 0 untuk penjual
                Dompet::create([
                    'id_user' => $user->id_user,
                    'saldo' => 0
                ]);
            }

            DB::commit();
            return redirect()->route('admin.index')->with('success', 'Akun berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Ganti Password User
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah untuk user: ' . $user->username);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'User berhasil dihapus.');
    }
}