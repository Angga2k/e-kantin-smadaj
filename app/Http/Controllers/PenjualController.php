<?php

namespace App\Http\Controllers;

use App\Models\Penjual;
use App\Models\User;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\DB;

class PenjualController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  */
    // public function index()
    // {
    //     $penjual = Penjual::with(['user', 'barang'])->get();
    //     return view('penjual.index', compact('penjual'));
    // }


    /**
     * Menampilkan daftar pesanan untuk Penjual berdasarkan status.
     * @param string $statusFilter Status yang diminta: 'baru', 'diproses', 'siap'
     */
    public function index(string $statusFilter = 'baru')
    {
        // 1. Dapatkan ID Penjual yang sedang login (Ganti dengan logika Auth yang benar)
        // Hardcode ID Penjual untuk pengujian:
        // $idPenjual = auth()->id() ;
        $idPenjual = '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';

        // 2. Tentukan Status DB berdasarkan Filter URL
        // MAPPING BARU SESUAI PERMINTAAN ANDA:
        $detailStatusMap = [
            'baru' => 'baru',           // Filter 'baru' menampilkan status DB 'baru'
            'diproses' => 'proses',     // Filter 'diproses' menampilkan status DB 'proses'
            'siap' => 'belum_diambil',  // Filter 'siap' menampilkan status DB 'belum_diambil' (menunggu diambil)
        ];

        // Dapatkan status DB yang sesuai, default ke 'baru'
        $detailStatus = $detailStatusMap[$statusFilter] ?? 'baru';

        // 3. Query Detail Transaksi untuk Penjual ini
        $pesananDetail = DetailTransaksi::whereHas('barang', function ($query) use ($idPenjual) {
            $query->where('id_user_penjual', $idPenjual);
        })
        // Asumsi: Transaksi sudah dibayar (status_pembayaran = success) jika mau diproses
        // ->whereHas('transaksi', function ($query) {
        //     $query->where('status_pembayaran', 'success');
        // })
        ->where('status_barang', $detailStatus) // Filter berdasarkan status yang dipilih
        // KUNCI PERBAIKAN: Kecualikan status barang yang SUDAH SELESAI
        ->whereNotIn('status_barang', ['sudah_diambil'])
        ->with(['transaksi', 'barang'])
        ->get();

        // 4. Kelompokkan berdasarkan Transaksi ID untuk memudahkan tampilan di View
        $pesananGrouped = $pesananDetail->groupBy('id_transaksi');

        // Pastikan Anda menggunakan nama view yang benar: 'seller.pesanan.index'
        return view('seller.pesanan.index', compact('pesananGrouped', 'statusFilter'));
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

        return redirect()->route('seller.index')->with('success', 'Penjual berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penjual = Penjual::with(['user', 'barang'])->findOrFail($id);
        return view('penjual.show', compact('penjual'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request): JsonResponse
    {
        try {
            // 1. Validasi Input
            $validated = $request->validate([
                'id_transaksi' => 'required|uuid|exists:transaksi,id_transaksi',
                // Status yang diizinkan di Enum DB: baru, proses, belum_diambil, sudah_diambil
                'new_status' => 'required|in:proses,belum_diambil,sudah_diambil,dibatalkan',
            ]);

            // Dapatkan ID Penjual yang berhak
            // $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';
            $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';
            $idTransaksi = $validated['id_transaksi'];
            $newStatus = $validated['new_status'];

            DB::beginTransaction();

            // 2. Lakukan Update Status
            // Update hanya detail transaksi yang dimiliki oleh penjual yang sedang login.
            $rowsAffected = DetailTransaksi::where('id_transaksi', $idTransaksi)
                ->whereHas('barang', function ($query) use ($idPenjual) {
                    $query->where('id_user_penjual', $idPenjual);
                })
                ->update(['status_barang' => $newStatus]);

            DB::commit();

            if ($rowsAffected === 0) {
                 return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada item yang perlu diperbarui. Pastikan Anda pemilik barang.',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update status pesanan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status di database. Coba lagi.'
            ], 500);
        }
    }
    // public function update(Request $request, string $id)
    // {
    //     $penjual = Penjual::findOrFail($id);

    //     $validated = $request->validate([
    //         'nama_toko' => 'string|max:255|unique:penjual,nama_toko,' . $id,
    //         'nama_penanggungjawab' => 'string|max:255',
    //         'no_rekening' => 'string|max:20',
    //         'nama_bank' => 'string|max:50',
    //         'username' => 'string|max:255|unique:users,username,' . $penjual->id_user . ',id_user',
    //     ]);

    //     $penjual->update(collect($validated)->except('username')->toArray());

    //     // Update username in user table if provided
    //     if (isset($validated['username'])) {
    //         $penjual->user->update(['username' => $validated['username']]);
    //     }

    //     return redirect()->route('penjual.index')->with('success', 'Penjual berhasil diperbarui');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penjual = Penjual::findOrFail($id);
        $penjual->user->delete(); // This will cascade delete penjual

        return redirect()->route('penjual.index')->with('success', 'Penjual berhasil dihapus');
    }


    public function showPesananMasuk()
    {
        $idPenjual = auth()->user()->id_user; // Ganti dengan logika autentikasi yang benar

        $pesananDetail = DetailTransaksi::select('detail_transaksi.*')
            ->join('barang', 'detail_transaksi.id_barang', '=', 'barang.id_barang')
            ->where('barang.id_user_penjual', $idPenjual)
            // ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            // ->where('transaksi.status_pembayaran', 'success')
            ->where('transaksi.status_barang', 'belum_diambil')
            ->with(['transaksi', 'barang'])
            ->get();


        return view('seller.pesanan.index', compact('pesananDetail'));
    }
}
