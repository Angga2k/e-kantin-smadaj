<?php

use Illuminate\Support\Facades\Route;

Route::get('/aa', function () {
    // return view('tes');
    return view('welcome2');
    // return view('slicing_beranda');
    // return view('slicing_u_profile');
});
Route::get('/', function () {
    return view('buyer.beranda.index');
});
Route::get('/makanan', [App\Http\Controllers\MakananController::class, 'index'])->name('makanan.index');
Route::get('/minuman', function () {
    return view('buyer.minuman.index');
});
Route::get('/camilan', function () {
    return view('buyer.camilan.index');
});
Route::get('/detail', function () {
    return view('buyer.detail.index');
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
