<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::with(['pembeli', 'detailTransaksi.barang'])->get();
        return view('transaksi.index', compact('transaksi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user_pembeli' => 'required|uuid|exists:users,id_user',
            'metode_pembayaran' => 'nullable|string|max:50',
            'waktu_pengambilan' => 'required|date',
            'detail_pengambilan' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|uuid|exists:barang,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated) {
            $totalHarga = 0;
            $items = [];

            // Calculate total and validate stock
            foreach ($validated['items'] as $item) {
                $barang = Barang::findOrFail($item['id_barang']);

                if ($barang->stok < $item['jumlah']) {
                    throw new \Exception("Stok barang {$barang->nama_barang} tidak mencukupi");
                }

                $subtotal = $barang->harga * $item['jumlah'];
                $totalHarga += $subtotal;

                $items[] = [
                    'barang' => $barang,
                    'jumlah' => $item['jumlah'],
                    'harga_saat_transaksi' => $barang->harga,
                ];
            }

            // Generate kode transaksi
            $kodeTransaksi = 'INV-' . date('ymd') . '-' . str_pad(Transaksi::whereDate('created_at', today())->count() + 1, 5, '0', STR_PAD_LEFT);

            // Create transaksi
            $transaksi = Transaksi::create([
                'id_transaksi' => Str::uuid(),
                'kode_transaksi' => $kodeTransaksi,
                'id_user_pembeli' => $validated['id_user_pembeli'],
                'total_harga' => $totalHarga,
                'id_order_gateway' => Str::uuid(), // This should be from payment gateway
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_pembayaran' => 'pending',
                'waktu_transaksi' => now(),
                'waktu_pengambilan' => $validated['waktu_pengambilan'],
                'detail_pengambilan' => $validated['detail_pengambilan'],
            ]);

            // Create detail transaksi and update stock
            foreach ($items as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_barang' => $item['barang']->id_barang,
                    'jumlah' => $item['jumlah'],
                    'harga_saat_transaksi' => $item['harga_saat_transaksi'],
                    'status_barang' => 'belum_diambil',
                ]);

                // Update stock
                $item['barang']->decrement('stok', $item['jumlah']);
            }

            return response()->json($transaksi->load(['pembeli', 'detailTransaksi.barang']), 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['pembeli', 'detailTransaksi.barang'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validated = $request->validate([
            'status_pembayaran' => 'in:pending,success,failed,expired',
            'metode_pembayaran' => 'string|max:50',
            'waktu_pengambilan' => 'date',
            'detail_pengambilan' => 'string|max:20',
        ]);

        $transaksi->update($validated);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Return stock if transaction is cancelled
        if ($transaksi->status_pembayaran === 'pending') {
            foreach ($transaksi->detailTransaksi as $detail) {
                $detail->barang->increment('stok', $detail->jumlah);
            }
        }

        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validated = $request->validate([
            'status_pembayaran' => 'required|in:pending,success,failed,expired',
        ]);

        $transaksi->update(['status_pembayaran' => $validated['status_pembayaran']]);

        return response()->json($transaksi);
    }

    /**
     * Get transaksi by user
     */
    public function getByUser(string $idUser)
    {
        $transaksi = Transaksi::where('id_user_pembeli', $idUser)
            ->with(['detailTransaksi.barang'])
            ->orderBy('waktu_transaksi', 'desc')
            ->get();

        return response()->json($transaksi);
    }
}
