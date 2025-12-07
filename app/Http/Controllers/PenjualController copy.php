<?php

namespace App\Http\Controllers;

use App\Models\Penjual;
use App\Models\User;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Models\Dompet;
use App\Models\HistoryPenarikan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\RekeningTujuan;
use App\Services\XenditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    public function showProduk()
    {
        $idPenjual = Auth::id();
        $produk = Barang::where('id_user_penjual', $idPenjual)->get();
        // dd($produk);
        return view('seller.produk.index', compact('produk'));
    }
    /**
     * Menampilkan form tambah produk
     */
    public function createProduk()
    {
        return view('seller.produk.store');
    }

    /**
     * Menyimpan produk baru ke database
     */
    public function storeProduk(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'jenis_barang' => 'required|string',
            'foto_barang' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Wajib ada foto
            'deskripsi_barang' => 'nullable|string',
            // Validasi kandungan gizi
            'kalori_kkal' => 'nullable|numeric',
            'protein_g' => 'nullable|numeric',
            'lemak_g' => 'nullable|numeric',
            'karbo_g' => 'nullable|numeric',
        ]);

        $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';

        // 1. Upload Foto
        $fotoPath = null;
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('gambar'), $fileName);
            $fotoPath = 'gambar/' . $fileName;
        }

        // 2. Simpan Data
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
            'karbohidrat_g' => $request->karbo_g,
        ]);

        return redirect()->route('seller.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit produk
     */
    public function editProduk($id)
    {
        // Cari barang berdasarkan ID
        $produk = Barang::findOrFail($id);

        // Pastikan barang milik penjual yang login (Security Check)
        $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';
        if ($produk->id_user_penjual !== $idPenjual) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.produk.store', compact('produk'));
    }

    /**
     * Memperbarui data produk
     */
    public function updateProduk(Request $request, $id)
    {
        $produk = Barang::findOrFail($id);

        // Security check
        $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';
        if ($produk->id_user_penjual !== $idPenjual) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'jenis_barang' => 'required|string',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Boleh kosong saat edit
            'deskripsi_barang' => 'nullable|string',
            'kalori_kkal' => 'nullable|numeric',
            'protein_g' => 'nullable|numeric',
            'lemak_g' => 'nullable|numeric',
            'karbo_g' => 'nullable|numeric',
        ]);

        // 1. Cek Upload Foto Baru
        if ($request->hasFile('foto_barang')) {

            // Hapus foto lama jika ada di folder public/gambar
            if ($produk->foto_barang && File::exists(public_path($produk->foto_barang))) {
                File::delete(public_path($produk->foto_barang));
            }

            // Simpan foto baru ke public/gambar
            $file = $request->file('foto_barang');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('gambar'), $fileName);

            // Update path di database
            $produk->foto_barang = 'gambar/' . $fileName;
        }

        // 2. Update Data Lainnya
        $produk->nama_barang = $request->nama_barang;
        $produk->harga = $request->harga;
        $produk->stok = $request->stok;
        $produk->jenis_barang = $request->jenis_barang;
        $produk->deskripsi_barang = $request->deskripsi_barang;
        $produk->kalori_kkal = $request->kalori_kkal;
        $produk->protein_g = $request->protein_g;
        $produk->lemak_g = $request->lemak_g;
        $produk->karbohidrat_g = $request->karbo_g;

        $produk->save();

        return redirect()->route('seller.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk
     */
    public function destroyProduk($id)
    {
        $produk = Barang::findOrFail($id);

        // Security check
        $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';
        if ($produk->id_user_penjual !== $idPenjual) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus file foto
        if ($produk->foto_barang && File::exists(public_path($produk->foto_barang))) {
            File::delete(public_path($produk->foto_barang));
        }

        $produk->delete();

        return redirect()->route('seller.produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function history(Request $request)
    {
        $idPenjual = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';

        // 2. Ambil Filter Bulan & Tahun dari Request (Default: Bulan & Tahun saat ini)
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        // 3. Query Data
        // Kita ambil DetailTransaksi, lalu join ke Barang (filter penjual) dan Transaksi (filter waktu & status)
        $details = DetailTransaksi::with(['transaksi', 'barang'])
            ->whereHas('barang', function ($query) use ($idPenjual) {
                $query->where('id_user_penjual', $idPenjual);
            })
            ->whereHas('transaksi', function ($query) use ($month, $year) {
                $query->where('status_pembayaran', 'success')
                      ->whereMonth('waktu_transaksi', $month)
                      ->whereYear('waktu_transaksi', $year);
            })
            // ->where('status_barang', 'sudah_diambil') // Filter WAJIB sesuai permintaan
            ->latest('created_at') // Urutkan dari yang terbaru
            ->get();

        // 4. Grouping berdasarkan Transaksi
        // Karena 1 transaksi bisa berisi banyak barang, kita kelompokkan agar tampil rapi per struk
        $history = $details->groupBy('id_transaksi');
        // dd($details);

        return view('seller.history.index', compact('history', 'month', 'year'));
    }

    // public function dompet()
    // {
    //     $idUser = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';
    //     if (!$idUser) {
    //          // Contoh ID User dummy jika auth belum jalan (sesuaikan dengan DB Anda)
    //          $idUser = '3a6261df-ac8c-4564-ae7d-42111456b3b6';
    //     }

    //     // 2. Ambil / Buat Dompet
    //     // firstOrCreate: Jika belum punya dompet, buatkan dengan saldo 0
    //     $dompet = Dompet::firstOrCreate(
    //         ['id_user' => $idUser],
    //         ['saldo' => 0] // Default saldo (akan terenkripsi otomatis oleh Model)
    //     );

    //     // 3. Ambil Riwayat Penarikan
    //     $riwayat = HistoryPenarikan::where('id_dompet', $dompet->id_dompet)
    //                 ->latest()
    //                 ->paginate(10); // Pagination 10 item per halaman

    //     return view('seller.dompet.index', compact('dompet', 'riwayat'));
    // }

    // public function storePenarikan(Request $request)
    // {
    //     $idUser = Auth::id();
    //     // Fallback untuk testing jika auth null
    //     if (!$idUser) $idUser = '3a6261df-ac8c-4564-ae7d-42111456b3b6';

    //     // 1. Validasi Input
    //     $request->validate([
    //         'jumlah' => 'required|numeric|min:10000',
    //         'bank_tujuan' => 'required|string|max:50',
    //         'no_rekening' => 'required|numeric',
    //     ]);

    //     // 2. Ambil Dompet User
    //     $dompet = Dompet::where('id_user', $idUser)->firstOrFail();

    //     // 3. Cek Saldo Mencukupi?
    //     // ($dompet->saldo otomatis didekripsi oleh Model saat diakses)
    //     if ($dompet->saldo < $request->jumlah) {
    //         return back()->withErrors(['jumlah' => 'Saldo tidak mencukupi untuk melakukan penarikan ini.']);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // 4. Kurangi Saldo Dompet
    //         // (Saat disimpan, Model akan otomatis mengenkripsi nilai baru)
    //         $dompet->saldo = $dompet->saldo - $request->jumlah;
    //         $dompet->save();

    //         // 5. Catat di History Penarikan
    //         // (Kolom 'jumlah' juga akan otomatis dienkripsi oleh Model HistoryPenarikan)
    //         HistoryPenarikan::create([
    //             'id_dompet' => $dompet->id_dompet,
    //             'jumlah' => $request->jumlah,
    //             'bank_tujuan' => $request->bank_tujuan,
    //             'no_rekening' => $request->no_rekening,
    //             'status' => 'pending', // Default pending
    //         ]);

    //         DB::commit();
    //         return back()->with('success', 'Permintaan penarikan berhasil diajukan! Menunggu persetujuan admin.');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error("Gagal Penarikan: " . $e->getMessage());
    //         return back()->withErrors(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
    //     }
    // }


    public function dompet()
    {
        $idUser = auth()->id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';

        $dompet = Dompet::firstOrCreate(
            ['id_user' => $idUser],
            ['saldo' => 0]
        );

        $riwayat = HistoryPenarikan::where('id_dompet', $dompet->id_dompet)
                    ->latest()
                    ->paginate(10);

        // Ambil rekening tersimpan untuk ditampilkan di dropdown
        $rekeningTersimpan = RekeningTujuan::where('id_user', $idUser)->get();

        return view('seller.dompet.index', compact('dompet', 'riwayat', 'rekeningTersimpan'));
    }

    /**
     * Proses Penarikan Dana via Xendit
     */
    public function storePenarikan(Request $request, XenditService $xenditService)
    {
        $idUser = Auth::id() ?? '14fe0afc-13ef-4db2-94a3-98bc0de0a9c3';

        // 1. Validasi Input
        $request->validate([
            'jumlah' => 'required|numeric|min:10000',
            'metode_penarikan' => 'required|in:tersimpan,baru',
        ]);

        // Validasi tambahan berdasarkan metode
        if ($request->metode_penarikan == 'tersimpan') {
            $request->validate(['id_rekening_tujuan' => 'required|exists:rekening_tujuan,id_rekening']);
        } else {
            $request->validate([
                'bank_tujuan' => 'required|string|max:50',
                'no_rekening' => 'required|numeric',
                'atas_nama'   => 'required|string|max:100',
            ]);
        }

        // 2. Cek Saldo
        $dompet = Dompet::where('id_user', $idUser)->firstOrFail();
        if ($dompet->saldo < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Saldo tidak mencukupi.']);
        }

        // 3. Siapkan Data Tujuan
        if ($request->metode_penarikan == 'tersimpan') {
            $rek = RekeningTujuan::where('id_rekening', $request->id_rekening_tujuan)->firstOrFail();
            $bankTujuan = $rek->nama_bank;
            $noRekening = $rek->no_rekening; // Otomatis didekripsi oleh Model
            $atasNama   = $rek->atas_nama;
        } else {
            $bankTujuan = $request->bank_tujuan;
            $noRekening = $request->no_rekening;
            $atasNama   = $request->atas_nama;

            // Simpan rekening baru jika dicentang
            if ($request->has('simpan_rekening')) {
                RekeningTujuan::create([
                    'id_user' => $idUser, 'nama_bank' => $bankTujuan, 'no_rekening' => $noRekening, 'atas_nama' => $atasNama
                ]);
            }
        }

        DB::beginTransaction();
        try {
            // 4. Buat External ID Unik (Format: WD-TIMESTAMP-RANDOM)
            // Ini penting agar Xendit tahu transaksi mana yang sedang diproses
            $externalId = 'WD-' . time() . '-' . Str::random(4);

            // 5. Kurangi Saldo di Database Lokal
            $dompet->saldo = $dompet->saldo - $request->jumlah;
            $dompet->save();

            // 6. Catat History (Status PENDING)
            HistoryPenarikan::create([
                'id_dompet'   => $dompet->id_dompet,
                'external_id' => $externalId, // Simpan ID ini!
                'jumlah'      => $request->jumlah,
                'bank_tujuan' => $bankTujuan,
                'no_rekening' => $noRekening, // Akan dienkripsi Model saat save
                'status'      => 'pending',
            ]);

            // 7. Panggil Xendit Service
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

            // Jika errornya dari Xendit (misal saldo habis atau bank gangguan), kembalikan error ke user
            return back()->withErrors(['error' => 'Gagal memproses penarikan: ' . $e->getMessage()]);
        }
    }
}
