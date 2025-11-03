<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::with('user')->get();
        return view('siswa.index', compact('siswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:siswa',
            'nama' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:P,L',
            'password' => 'required|string|min:8',
        ]);

        // Create user first
        $user = User::create([
            'id_user' => Str::uuid(),
            'username' => $validated['nisn'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
        ]);

        // Create siswa
        $siswa = Siswa::create([
            'id' => Str::uuid(),
            'id_user' => $user->id_user,
            'nisn' => $validated['nisn'],
            'nama' => $validated['nama'],
            'tgl_lahir' => $validated['tgl_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
        ]);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        return view('siswa.show', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nisn' => 'string|max:20|unique:siswa,nisn,' . $id,
            'nama' => 'string|max:255',
            'tgl_lahir' => 'date',
            'jenis_kelamin' => 'in:P,L',
        ]);

        $siswa->update($validated);

        // Update username in user table if nisn changed
        if (isset($validated['nisn'])) {
            $siswa->user->update(['username' => $validated['nisn']]);
        }

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->user->delete(); // This will cascade delete siswa

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
    }
}
