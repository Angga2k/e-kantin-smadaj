<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Menampilkan Halaman Profil
     */
    public function index()
    {
        $user = Auth::user();
        // ... ambil data profile ...
        $profile = $user->siswa ?? $user->civitasAkademik ?? $user->penjual;

        // JIKA PENJUAL -> Tampilkan View khusus Penjual (Layout Seller)
        if ($user->role === 'penjual') {
            return view('seller.profile.index', compact('user', 'profile'));
        }

        // JIKA LAINNYA -> Tampilkan View Pembeli (Layout Buyer)
        return view('buyer.profile.index', compact('user', 'profile'));
    }

    /**
     * Menampilkan Halaman Edit Profil
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->siswa ?? $user->civitasAkademik ?? $user->penjual;

        // LOGIKA PEMISAHAN VIEW EDIT
        if ($user->role === 'penjual') {
            return view('seller.profile.edit', compact('user', 'profile'));
        }

        return view('buyer.profile.edit', compact('user', 'profile'));
    }

    /**
     * Menyimpan Perubahan Profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Tentukan Model Profile untuk diupdate
        $profile = null;
        if ($user->role === 'siswa') $profile = $user->siswa;
        elseif ($user->role === 'civitas_akademik') $profile = $user->civitasAkademik;
        elseif ($user->role === 'penjual') $profile = $user->penjual;

        if (!$profile) {
            return back()->with('error', 'Data profil tidak ditemukan.');
        }

        // 2. LOGIKA VALIDASI & UPDATE DATA (BERDASARKAN ROLE)
        if ($user->role === 'penjual') {
            // --- KHUSUS PENJUAL ---
            $request->validate([
                'nama_toko' => 'required|string|max:255',
                'nama_penanggungjawab' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Update kolom khusus penjual
            // Pastikan kolom 'nama_toko' ada di tabel penjual
            if ($request->filled('nama_toko')) {
                $profile->nama_toko = $request->nama_toko;
            }
            // Update nama penanggung jawab
            $profile->nama_penanggungjawab = $request->nama_penanggungjawab;
            $next_url = 'seller.profile.index';

        } else {
            // --- KHUSUS SISWA / CIVITAS ---
            $request->validate([
                'nama' => 'required|string|max:255',
                'tgl_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable|in:L,P',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Update kolom khusus siswa/civitas
            $profile->nama = $request->nama;

            if ($request->filled('tgl_lahir')) {
                $profile->tgl_lahir = $request->tgl_lahir;
            }

            if ($request->filled('jenis_kelamin')) {
                $profile->jenis_kelamin = $request->jenis_kelamin;
            }
            $next_url = 'profile.index';
        }

        // 3. LOGIKA UPLOAD FOTO (SAMA UNTUK SEMUA ROLE)
        if ($request->hasFile('foto')) {
            try {
                // Hapus foto lama
                $oldPathDB = $profile->foto_profile;
                $oldFileName = basename($oldPathDB);

                $defaultImages = ['default.png', 'profile.png', 'L.png', 'P.png', 'default-profile.png'];

                if ($oldFileName && !in_array($oldFileName, $defaultImages)) {
                    // Hapus di public/foto_profile
                    $pathPublic = public_path('foto_profile/' . $oldFileName);
                    if (File::exists($pathPublic)) {
                        File::delete($pathPublic);
                    }

                    // Hapus sisa di storage jika ada (cleanup sisa lama)
                    $pathStorage = storage_path('app/public/foto_profile/' . $oldFileName);
                    if (File::exists($pathStorage)) {
                        File::delete($pathStorage);
                    }
                }

                // Simpan foto baru
                $file = $request->file('foto');
                $timestamp = now()->format('dmY-Hisu');
                $filename = md5($timestamp) . '.' . $file->getClientOriginalExtension();

                // Pindahkan langsung ke public/foto_profile
                $file->move(public_path('foto_profile'), $filename);

                // Simpan path relatif di database (dengan prefix folder)
                $profile->foto_profile = 'foto_profile/' . $filename;

            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
            }
        }

        $profile->save();

        return redirect()->route($next_url)->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update Password via AJAX (SweetAlert)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // 1. Cek apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password lama tidak sesuai.'
            ], 422);
        }

        // 2. Update password baru
        // Kita gunakan Model User::where agar tidak perlu meload ulang model jika $user sudah instance model
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diubah!'
        ]);
    }
}
