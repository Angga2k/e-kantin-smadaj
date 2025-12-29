<?php

namespace App\Http\Controllers;

use App\Models\Penjual;
use App\Models\User;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Models\Dompet;
use App\Models\HistoryPenarikan;
use App\Models\RekeningTujuan;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenjualController extends Controller
{
    // ==========================================
    // 1. DASHBOARD & PESANAN
    // ==========================================

    public function beranda()
    {
        $idPenjual = Auth::id();
        // $idPenjual = '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';

        // 2. Inisialisasi struktur array untuk tahun yang diinginkan
        $startYear = 2024;
        $currentYear = (int) date('Y');
        $years = range($startYear, $currentYear);

        // Siapkan kerangka data kosong (0) untuk setiap bulan
        foreach ($years as $year) {
            $chartData[$year] = [
                'produk' => array_fill(0, 12, 0),    // [0, 0, ... 12x]
                'pendapatan' => array_fill(0, 12, 0) // [0, 0, ... 12x]
            ];
        }

        // 3. Query Database
        // Menggunakan JOIN ke tabel 'barang' karena 'id_user_penjual' ada di sana
        $stats = DetailTransaksi::selectRaw('
                YEAR(transaksi.waktu_transaksi) as year,
                MONTH(transaksi.waktu_transaksi) as month,
                SUM(detail_transaksi.jumlah) as total_produk,
                SUM(detail_transaksi.jumlah * detail_transaksi.harga_saat_transaksi) as total_pendapatan
            ')
            ->join('barang', 'detail_transaksi.id_barang', '=', 'barang.id_barang')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('barang.id_user_penjual', $idPenjual)
            ->where('transaksi.status_pembayaran', 'success')
            // Ubah filter YEAR menggunakan transaksi.waktu_transaksi
            ->whereIn(DB::raw('YEAR(transaksi.waktu_transaksi)'), $years)
            ->where('detail_transaksi.status_barang', '!=', 'dibatalkan')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // 4. Mapping data DB ke struktur array Chart
        foreach ($stats as $row) {
            $y = $row->year;
            $m = $row->month - 1; // Konversi bulan SQL (1-12) ke index Array (0-11)

            if (isset($chartData[$y])) {
                $chartData[$y]['produk'][$m] = (int) $row->total_produk;
                $chartData[$y]['pendapatan'][$m] = (int) $row->total_pendapatan;
            }
        }

        $summaryPenjualan = DetailTransaksi::join('barang', 'detail_transaksi.id_barang', '=', 'barang.id_barang')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('barang.id_user_penjual', $idPenjual)
            ->where('transaksi.status_pembayaran', 'success') // Sesuai instruksi: Hanya yang success
            ->where('detail_transaksi.status_barang', '!=', 'dibatalkan')
            ->selectRaw('
                COALESCE(SUM(detail_transaksi.jumlah), 0) as total_produk_terjual,
                COALESCE(SUM(detail_transaksi.jumlah * detail_transaksi.harga_saat_transaksi), 0) as total_omset
            ')
            ->first();

        $summaryRating = DB::table('rating_ulasan')
            ->join('barang', 'rating_ulasan.id_barang', '=', 'barang.id_barang')
            ->where('barang.id_user_penjual', $idPenjual)
            ->selectRaw('
                COUNT(rating_ulasan.id_rating) as total_ulasan,
                COALESCE(AVG(rating_ulasan.rating), 0) as rata_rata_rating
            ')
            ->first();

        // dd($chartData);
        // dd($summaryPenjualan);
        // dd($summaryRating);

        return view('seller.beranda.index', compact('chartData', 'summaryPenjualan', 'summaryRating'));
    }

    public function index(string $statusFilter = 'baru')
    {
        // $idPenjual = '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3'; // Hardcode ID Penjual (Sesuai sesi sebelumnya)
        $idPenjual = auth()->id(); // Gunakan ini jika login sudah fix

        $detailStatusMap = ['baru' => 'baru', 'diproses' => 'proses', 'siap' => 'belum_diambil'];
        $detailStatus = $detailStatusMap[$statusFilter] ?? 'baru';

        $pesananDetail = DetailTransaksi::whereHas('barang', function ($query) use ($idPenjual) {
            $query->where('id_user_penjual', $idPenjual);
        })->whereHas('transaksi', function ($query) {
            $query->where('status_pembayaran', 'success');
        })
        ->where('status_barang', $detailStatus)
        ->whereNotIn('status_barang', ['sudah_diambil'])
        ->with([
            'transaksi.pembeli.siswa', 
            'transaksi.pembeli.civitasAkademik',
            'barang'
            ])
        ->get();

        $pesananGrouped = $pesananDetail->groupBy('id_transaksi');
        return view('seller.pesanan.index', compact('pesananGrouped', 'statusFilter'));
    }

    public function updateStatus(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id_transaksi' => 'required|uuid|exists:transaksi,id_transaksi',
                'new_status' => 'required|in:proses,belum_diambil,sudah_diambil,dibatalkan',
            ]);

            $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';
            $idTransaksi = $validated['id_transaksi'];
            $newStatus = $validated['new_status'];

            DB::beginTransaction();
            $rowsAffected = DetailTransaksi::where('id_transaksi', $idTransaksi)
                ->whereHas('barang', function ($query) use ($idPenjual) {
                    $query->where('id_user_penjual', $idPenjual);
                })
                ->update(['status_barang' => $newStatus]);
            DB::commit();

            if ($rowsAffected === 0) {
                 return response()->json(['success' => false, 'message' => 'Tidak ada item yang perlu diperbarui.'], 403);
            }

            return response()->json(['success' => true, 'message' => 'Status pesanan berhasil diperbarui.']);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi Gagal.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update status pesanan: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status.'], 500);
        }
    }

    // ==========================================
    // 2. MANAJEMEN PRODUK
    // ==========================================

    public function showProduk()
    {
        $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';
        $produk = Barang::where('id_user_penjual', $idPenjual)->get();
        return view('seller.produk.index', compact('produk'));
    }

    public function createProduk()
    {
        return view('seller.produk.store');
    }

    public function storeProduk(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'jenis_barang' => 'required|string',
            'foto_barang' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi_barang' => 'nullable|string',
            'kalori_kkal' => 'nullable|numeric',
            'protein_g' => 'nullable|numeric',
            'lemak_g' => 'nullable|numeric',
            'karbo_g' => 'nullable|numeric',
            'serat_g' => 'nullable|numeric',
            'gula_g' => 'nullable|numeric',
        ]);

        $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';

        // Upload Foto (Menggunakan move ke public/gambar)
        $fotoPath = null;
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('gambar'), $fileName);
            $fotoPath = 'gambar/' . $fileName;
        }

        Barang::create([
            'id_barang' => Str::uuid(),
            'id_user_penjual' => $idPenjual,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'jenis_barang' => $request->jenis_barang,
            'deskripsi_barang' => $request->deskripsi_barang,
            'foto_barang' => $fotoPath,
            'kalori_kkal' => $request->kalori_kkal,
            'protein_g' => $request->protein_g,
            'lemak_g' => $request->lemak_g,
            'karbohidrat_g' => $request->karbo_g, // Mapping ke karbohidrat_g
            'serat_g' => $request->serat_g,
            'gula_g' => $request->gula_g,
        ]);

        return redirect()->route('seller.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduk($id)
    {
        $produk = Barang::findOrFail($id);
        return view('seller.produk.store', compact('produk'));
    }

    public function updateProduk(Request $request, $id)
    {
        $produk = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'jenis_barang' => 'required|string',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi_barang' => 'nullable|string',
            'kalori_kkal' => 'nullable|numeric',
            'protein_g' => 'nullable|numeric',
            'lemak_g' => 'nullable|numeric',
            'karbo_g' => 'nullable|numeric',
            'serat_g' => 'nullable|numeric',
            'gula_g' => 'nullable|numeric',
        ]);

        if ($request->hasFile('foto_barang')) {
            // Hapus file lama pakai File Facade
            if ($produk->foto_barang && File::exists(public_path($produk->foto_barang))) {
                File::delete(public_path($produk->foto_barang));
            }
            // Upload baru
            $file = $request->file('foto_barang');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('gambar'), $fileName);
            $produk->foto_barang = 'gambar/' . $fileName;
        }

        $produk->nama_barang = $request->nama_barang;
        $produk->harga = $request->harga;
        $produk->stok = $request->stok;
        $produk->jenis_barang = $request->jenis_barang;
        $produk->deskripsi_barang = $request->deskripsi_barang;
        $produk->kalori_kkal = $request->kalori_kkal;
        $produk->protein_g = $request->protein_g;
        $produk->lemak_g = $request->lemak_g;
        $produk->karbohidrat_g = $request->karbo_g;
        $produk->serat_g = $request->serat_g;
        $produk->gula_g = $request->gula_g;

        $produk->save();

        return redirect()->route('seller.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroyProduk($id)
    {
        $produk = Barang::findOrFail($id);
        if ($produk->foto_barang && File::exists(public_path($produk->foto_barang))) {
            File::delete(public_path($produk->foto_barang));
        }
        $produk->delete();
        return redirect()->route('seller.produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    // ==========================================
    // 3. RIWAYAT PENJUALAN
    // ==========================================

    public function history(Request $request)
    {
        $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        $details = DetailTransaksi::with(['transaksi', 'barang'])
            ->whereHas('barang', function ($query) use ($idPenjual) {
                $query->where('id_user_penjual', $idPenjual);
            })
            ->whereHas('transaksi', function ($query) use ($month, $year) {
                $query->where('status_pembayaran', 'success')
                      ->whereMonth('waktu_transaksi', $month)
                      ->whereYear('waktu_transaksi', $year);
            })
            // Filter 'sudah_diambil' dihapus sesuai permintaan
            ->latest('created_at')
            ->get();

        $history = $details->groupBy('id_transaksi');
        return view('seller.history.index', compact('history', 'month', 'year'));
    }

    // ==========================================
    // 4. DOMPET DIGITAL & XENDIT
    // ==========================================

    public function dompet()
    {
        $idUser = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';

        // Setup Dompet
        $dompet = Dompet::firstOrCreate(
            ['id_user' => $idUser],
            ['saldo' => 0]
        );

        $riwayat = HistoryPenarikan::where('id_dompet', $dompet->id_dompet)
                    ->latest()
                    ->get();

        // Ambil Rekening Tersimpan
        $rekeningTersimpan = RekeningTujuan::where('id_user', $idUser)->get();

        return view('seller.dompet.index', compact('dompet', 'riwayat', 'rekeningTersimpan'));
    }

    public function storePenarikan(Request $request, XenditService $xenditService)
    {
        $idUser = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';

        // Validasi
        $request->validate([
            'jumlah' => 'required|numeric|min:10000',
            'metode_penarikan' => 'required|in:tersimpan,baru',
        ]);

        if ($request->metode_penarikan == 'tersimpan') {
            $request->validate(['id_rekening_tujuan' => 'required|exists:rekening_tujuan,id_rekening']);
        } else {
            $request->validate([
                'bank_tujuan' => 'required|string|max:50',
                'no_rekening' => 'required|numeric',
                'atas_nama'   => 'required|string|max:100',
            ]);
        }

        $dompet = Dompet::where('id_user', $idUser)->firstOrFail();
        if ($dompet->saldo < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Saldo tidak mencukupi.']);
        }

        // Tentukan Data Rekening
        if ($request->metode_penarikan == 'tersimpan') {
            $rek = RekeningTujuan::where('id_rekening', $request->id_rekening_tujuan)->firstOrFail();
            $bankTujuan = $rek->nama_bank;
            $noRekening = $rek->no_rekening;
            $atasNama   = $rek->atas_nama;
        } else {
            $bankTujuan = $request->bank_tujuan;
            $noRekening = $request->no_rekening;
            $atasNama   = $request->atas_nama;

            if ($request->has('simpan_rekening')) {
                RekeningTujuan::create([
                    'id_user' => $idUser, 'nama_bank' => $bankTujuan, 'no_rekening' => $noRekening, 'atas_nama' => $atasNama
                ]);
            }
        }

        DB::beginTransaction();
        try {
            $externalId = 'WD-' . time() . '-' . Str::random(4);

            // Kurangi Saldo
            $dompet->saldo = $dompet->saldo - $request->jumlah;
            $dompet->save();

            // History Pending
            HistoryPenarikan::create([
                'id_dompet'   => $dompet->id_dompet,
                'external_id' => $externalId,
                'jumlah'      => $request->jumlah,
                'bank_tujuan' => $bankTujuan,
                'no_rekening' => $noRekening,
                'status'      => 'pending',
            ]);

            // Eksekusi Xendit
            $xenditService->createDisbursement(
                $externalId,
                $request->jumlah,
                $bankTujuan,
                $atasNama,
                $noRekening
            );

            DB::commit();
            return back()->with('success', 'Permintaan penarikan sedang diproses oleh bank (Xendit).');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal Xendit: " . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal memproses penarikan: ' . $e->getMessage()]);
        }
    }
}
