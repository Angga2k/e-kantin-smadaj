<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('tes');
    return view('welcome');
    // return view('slicing_beranda');
});
Route::get('/makanan', function () {
    return view('slicing_makanan');
});
Route::get('/minuman', function () {
    return view('slicing_minuman');
});
Route::get('/camilan', function () {
    return view('slicing_camilan');
});
Route::get('/detail', function () {
    return view('slicing_detail');
});
