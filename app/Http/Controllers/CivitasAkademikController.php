<?php

namespace App\Http\Controllers;

use App\Models\CivitasAkademik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CivitasAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $civitasAkademik = CivitasAkademik::with('user')->get();
        return view('civitas-akademik.index', compact('civitasAkademik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'npwp' => 'required|string|max:20|unique:civitas_akademik',
            'nama' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:P,L',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'id_user' => Str::uuid(),
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'civitas_akademik',
        ]);

        $civitasAkademik = CivitasAkademik::create([
            'id' => Str::uuid(),
            'id_user' => $user->id_user,
            'npwp' => $validated['npwp'],
            'nama' => $validated['nama'],
            'tgl_lahir' => $validated['tgl_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
        ]);

        return redirect()->route('civitas-akademik.index')->with('success', 'Civitas akademik berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $civitasAkademik = CivitasAkademik::with('user')->findOrFail($id);
        return view('civitas-akademik.show', compact('civitasAkademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $civitasAkademik = CivitasAkademik::findOrFail($id);

        $validated = $request->validate([
            'npwp' => 'string|max:20|unique:civitas_akademik,npwp,' . $id,
            'nama' => 'string|max:255',
            'tgl_lahir' => 'date',
            'jenis_kelamin' => 'in:P,L',
            'username' => 'string|max:255|unique:users,username,' . $civitasAkademik->id_user . ',id_user',
        ]);

        $civitasAkademik->update(collect($validated)->except('username')->toArray());

        // Update username in user table if provided
        if (isset($validated['username'])) {
            $civitasAkademik->user->update(['username' => $validated['username']]);
        }

        return redirect()->route('civitas-akademik.index')->with('success', 'Civitas akademik berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $civitasAkademik = CivitasAkademik::findOrFail($id);
        $civitasAkademik->user->delete(); // This will cascade delete civitas akademik

        return redirect()->route('civitas-akademik.index')->with('success', 'Civitas akademik berhasil dihapus');
    }
}
