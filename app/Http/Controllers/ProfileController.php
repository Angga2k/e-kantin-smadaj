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

        // Ambil data detail profil (Siswa/Civitas/Penjual)
        // Menggunakan null coalescing operator untuk fallback pengecekan berurutan
        $profile = $user->siswa ?? $user->civitasAkademik ?? $user->penjual;

        return view('buyer.profile.index', compact('user', 'profile'));
    }

    /**
     * Menampilkan Halaman Edit Profil
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->siswa ?? $user->civitasAkademik ?? $user->penjual;

        // Pastikan nama file view Anda adalah resources/views/buyer/profile/edit.blade.php
        return view('buyer.profile.edit', compact('user', 'profile'));
    }

    /**
     * Menyimpan Perubahan Profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $profile = null;
        if ($user->role === 'siswa') {
            $profile = $user->siswa;
        } elseif ($user->role === 'civitas_akademik') {
            $profile = $user->civitasAkademik;
        } elseif ($user->role === 'penjual') {
            $profile = $user->penjual;
        }

        if (!$profile) {
            return back()->with('error', 'Data profil tidak ditemukan.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $profile->nama = $request->nama;

        if ($request->filled('tgl_lahir')) {
            $profile->tgl_lahir = $request->tgl_lahir;
        }

        if ($request->filled('jenis_kelamin')) {
            $profile->jenis_kelamin = $request->jenis_kelamin;
        }

        // Handle Upload Foto
        if ($request->hasFile('foto')) {
            try {
                // 1. HAPUS FOTO LAMA
                // Kita ambil path lengkap dari DB, misal: "foto_profile/abc.jpg"
                $oldPathDB = $profile->foto_profile;

                // Ambil nama filenya saja (abc.jpg) untuk menghindari duplikasi folder saat penyusunan path
                $oldFileName = basename($oldPathDB);

                $defaultImages = ['default.png', 'profile.png', 'L.png', 'P.png', 'default-profile.png'];

                if ($oldFileName && !in_array($oldFileName, $defaultImages)) {

                    // Cek lokasi BARU: public/foto_profile/abc.jpg
                    $pathPublic = public_path('foto_profile/' . $oldFileName);

                    if (File::exists($pathPublic)) {
                        File::delete($pathPublic);
                    }

                    // Cek lokasi LAMA (storage) untuk bersih-bersih
                    $pathStorage = storage_path('app/public/foto_profile/' . $oldFileName);
                    if (File::exists($pathStorage)) {
                        File::delete($pathStorage);
                    }
                }

                // 2. SIMPAN FOTO BARU
                $file = $request->file('foto');
                // Format nama file: dmy-Hisu (tanggal-jam-mikrodetik) di-hash md5
                $timestamp = now()->format('dmY-Hisu');
                $filename = md5($timestamp) . '.' . $file->getClientOriginalExtension();

                // Pindahkan langsung ke public/foto_profile
                $file->move(public_path('foto_profile'), $filename);

                // Update nama file di database dengan prefix folder
                $profile->foto_profile = 'foto_profile/' . $filename;

            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
            }
        }

        $profile->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
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
