<?php

namespace App\Http\Controllers;

use App\Models\Penjual;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PenjualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penjual = Penjual::with(['user', 'barang'])->get();
        return response()->json($penjual);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255|unique:penjual',
            'nama_penanggungjawab' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:20',
            'nama_bank' => 'required|string|max:50',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create user first
        $user = User::create([
            'id_user' => Str::uuid(),
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'penjual',
        ]);

        // Create penjual
        $penjual = Penjual::create([
            'id' => Str::uuid(),
            'id_user' => $user->id_user,
            'nama_toko' => $validated['nama_toko'],
            'nama_penanggungjawab' => $validated['nama_penanggungjawab'],
            'no_rekening' => $validated['no_rekening'],
            'nama_bank' => $validated['nama_bank'],
        ]);

        return response()->json($penjual->load('user'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penjual = Penjual::with(['user', 'barang'])->findOrFail($id);
        return response()->json($penjual);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penjual = Penjual::findOrFail($id);

        $validated = $request->validate([
            'nama_toko' => 'string|max:255|unique:penjual,nama_toko,' . $id,
            'nama_penanggungjawab' => 'string|max:255',
            'no_rekening' => 'string|max:20',
            'nama_bank' => 'string|max:50',
            'username' => 'string|max:255|unique:users,username,' . $penjual->id_user . ',id_user',
        ]);

        $penjual->update(collect($validated)->except('username')->toArray());

        // Update username in user table if provided
        if (isset($validated['username'])) {
            $penjual->user->update(['username' => $validated['username']]);
        }

        return response()->json($penjual->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penjual = Penjual::findOrFail($id);
        $penjual->user->delete(); // This will cascade delete penjual

        return response()->json(['message' => 'Penjual deleted successfully']);
    }
}