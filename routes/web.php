<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BuyerOrderController;
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

Route::get('/tesssss', function () {
    return view('buyer.pesanan.index');
});

Route::get('/tesauth', function () {
    return view('auth.tes');
})->name('auth.tes');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Makanan (Public)
Route::get('/', [MakananController::class, 'index'])->name('beranda.index');
Route::get('/minuman', [MakananController::class, 'minuman'])->name('minuman.index');
Route::get('/makanan', [MakananController::class, 'makanan'])->name('makanan.index');
Route::get('/camilan', [MakananController::class, 'camilan'])->name('camilan.index');
Route::get('/detail/{barang}', [MakananController::class, 'show'])->name('detail.index');

Route::get('/payment/status', [CheckoutController::class, 'paymentStatus'])->name('payment.status');

Route::get('/pesanan', [BuyerOrderController::class, 'index'])
        ->name('buyer.orders.index');

// Aksi Simpan Rating (AJAX)
Route::post('/pesanan/ulasan', [BuyerOrderController::class, 'storeRating'])
    ->name('buyer.orders.rating.store');


// Route Authenticated
Route::middleware(['auth'])->group(function () {

    // Route Siswa / Civitas
    Route::middleware([RoleChecker::class . ':siswa,civitas_akademik'])->group(function () {
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/foto-profile', 'index')->name('profile.index');
            Route::get('/profile/edit', 'edit')->name('profile.edit');
            Route::put('/profile/update', 'update')->name('profile.update');
            Route::post('/profile/password', 'updatePassword')->name('profile.password');
        });
    });


    Route::middleware([RoleChecker::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/tambah', [AdminController::class, 'create'])->name('create');
        Route::post('/store', [AdminController::class, 'store'])->name('store');
        Route::put('/user/{id}/password', [AdminController::class, 'updatePassword'])->name('updatePassword');
        Route::delete('/user/{id}', [AdminController::class, 'destroy'])->name('destroy');
    });
    // Route Penjual
    Route::middleware([RoleChecker::class . ':penjual'])->prefix('penjual')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/foto-profile', 'index')->name('seller.profile.index');
            Route::get('/profile/edit', 'edit')->name('seller.profile.edit');
            Route::put('/profile/update', 'update')->name('seller.profile.update');
            Route::post('/profile/password', 'updatePassword')->name('seller.profile.password');
        });

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
