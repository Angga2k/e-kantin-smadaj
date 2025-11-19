<?php

use Illuminate\Support\Facades\Route;

Route::get('/aaa', function () {
    // return view('seller_pesanan');
    // return view('seller_dash');
    // return view('seller_wd');
    // return view('seller_produk');
    return view('seller_history');
});
Route::get('/aa', function () {
    // return view('tes');
    return view('welcome');
    // return view('slicing_store');
    // return view('slicing_u_profile');
});
Route::get('/a', function () {
    // return view('tes');
    // return view('welcome2');
    return view('slicing_store');
    // return view('slicing_u_profile');
});
Route::get('/', [App\Http\Controllers\MakananController::class, 'index'])->name('beranda.index');
Route::get('/asdasd', function () {
    return view('buyer.beranda.index1');
});
Route::get('/minuman', [App\Http\Controllers\MakananController::class, 'minuman'])->name('minuman.index');
// Route::get('/minuman', function () {
//     return view('buyer.minuman.index');
// });
Route::get('/makanan', [App\Http\Controllers\MakananController::class, 'makanan'])->name('makanan.index');
// Route::get('/makanan', function () {
    //     return view('buyer.makanan.index');
    // });
Route::get('/camilan', [App\Http\Controllers\MakananController::class, 'camilan'])->name('camilan.index');
// Route::get('/camilan', function () {
//     return view('buyer.camilan.index');
// });
Route::get('/detail/{barang}', [App\Http\Controllers\MakananController::class, 'show'])->name('detail.index');
Route::get('/detail', function () {
    return view('buyer.detail.index1');
});
Route::get('/profile', function () {
    return view('buyer.profile.index');
});
Route::get('/profile/update', function () {
    return view('buyer.profile.update');
});
Route::get('/d', function () {
    return view('slicing_detail');
});

Route::get('/seller', function () {
    return view('seller.beranda.index');
});
Route::get('/barang', function () {
    return view('seller.barang.index');
});
Route::get('/barang/store', function () {
    return view('seller.barang.store');
});
// Route::get('/pesanan', function () {
//     return view('seller.pesanan.index');
// });

Route::get('/penjual/pesanan/{statusFilter?}', [App\Http\Controllers\PenjualController::class, 'index'])->name('seller.pesanan.index');
Route::post('/penjual/pesanan/update-status', [App\Http\Controllers\PenjualController::class, 'updateStatus'])->name('seller.pesanan.update_status');
Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
