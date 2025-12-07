<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PenjualController;
use App\Http\Middleware\RoleChecker;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/aa', function () {
    return view('welcome2');
});

Route::get('/a', function () {
    return view('seller.dompet.index');
});

Route::get('/tesauth', function () {
    return view('auth.tes');
})->name('auth.tes');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Makanan (Public)
Route::get('/', [MakananController::class, 'index'])->name('beranda.index');
Route::get('/minuman', [MakananController::class, 'minuman'])->name('minuman.index');
Route::get('/makanan', [MakananController::class, 'makanan'])->name('makanan.index');
Route::get('/camilan', [MakananController::class, 'camilan'])->name('camilan.index');
Route::get('/detail/{barang}', [MakananController::class, 'show'])->name('detail.index');

Route::get('/payment/status', [CheckoutController::class, 'paymentStatus'])->name('payment.status');

// Route Authenticated
Route::middleware(['auth'])->group(function () {
    // Route Siswa / Civitas
    Route::middleware([RoleChecker::class . ':siswa,civitas_akademik'])->group(function () {
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    });

    // Route Penjual
    Route::middleware([RoleChecker::class . ':penjual'])->prefix('penjual')->group(function () {

        // Dashboard & Pesanan
        Route::get('/', [PenjualController::class, 'beranda'])->name('seller.beranda.index');
        Route::get('/pesanan/{statusFilter?}', [PenjualController::class, 'index'])->name('seller.pesanan.index');
        Route::post('/pesanan/update-status', [PenjualController::class, 'updateStatus'])->name('seller.pesanan.update_status');
        Route::get('/riwayat', [PenjualController::class, 'history'])->name('seller.history.index');
        Route::get('/dana', [PenjualController::class, 'dompet'])->name('seller.dompet.index');
        Route::post('/dana/tarik', [PenjualController::class, 'storePenarikan'])->name('seller.dompet.store');

        // CRUD Produk
        // PERBAIKAN: Gunakan 'seller.produk.' agar sesuai dengan panggilan di View
        Route::prefix('produk')->name('seller.produk.')->group(function () {

            // Hasil: seller.produk.index
            Route::get('/', [PenjualController::class, 'showProduk'])->name('index');

            // Hasil: seller.produk.create
            Route::get('/tambah', [PenjualController::class, 'createProduk'])->name('create');

            // Hasil: seller.produk.store
            Route::post('/', [PenjualController::class, 'storeProduk'])->name('store');

            // Hasil: seller.produk.edit
            Route::get('/{id}/edit', [PenjualController::class, 'editProduk'])->name('edit');

            // Hasil: seller.produk.update
            Route::put('/{id}', [PenjualController::class, 'updateProduk'])->name('update');

            // Hasil: seller.produk.destroy
            Route::delete('/{id}', [PenjualController::class, 'destroyProduk'])->name('destroy');
        });
    });
});
