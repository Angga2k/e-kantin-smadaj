<?php

use Illuminate\Support\Facades\Route;

Route::get('/aa', function () {
    // return view('tes');
    // return view('welcome');
    return view('slicing_beranda');
});
Route::get('/', function () {
    return view('buyer.beranda.index');
});
Route::get('/makanan', function () {
    return view('buyer.makanan.index');
});
Route::get('/minuman', function () {
    return view('buyer.minuman.index');
});
Route::get('/camilan', function () {
    return view('buyer.camilan.index');
});
Route::get('/detail', function () {
    return view('buyer.detail.index');
});
Route::get('/d', function () {
    return view('slicing_detail');
});
