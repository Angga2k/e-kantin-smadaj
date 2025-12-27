<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\CivitasAkademikController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\RatingUlasanController;
use App\Http\Controllers\TesController;
use App\Http\Controllers\Api\XenditWebhookController;


/*
|--------------------------------------------------------------------------
| API Routes - e-Kantin SMA 2 Jember
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Default auth route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| User Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Siswa Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->group(function () {
    Route::get('/', [SiswaController::class, 'index']);
    Route::post('/', [SiswaController::class, 'store']);
    Route::get('/{id}', [SiswaController::class, 'show']);
    Route::put('/{id}', [SiswaController::class, 'update']);
    Route::delete('/{id}', [SiswaController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Civitas Akademik Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('civitas-akademik')->group(function () {
    Route::get('/', [CivitasAkademikController::class, 'index']);
    Route::post('/', [CivitasAkademikController::class, 'store']);
    Route::get('/{id}', [CivitasAkademikController::class, 'show']);
    Route::put('/{id}', [CivitasAkademikController::class, 'update']);
    Route::delete('/{id}', [CivitasAkademikController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Penjual Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('penjual')->group(function () {
    Route::get('/', [PenjualController::class, 'index']);
    Route::post('/', [PenjualController::class, 'store']);
    Route::get('/{id}', [PenjualController::class, 'show']);
    Route::put('/{id}', [PenjualController::class, 'update']);
    Route::delete('/{id}', [PenjualController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Barang Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('barang')->group(function () {
    Route::get('/', [BarangController::class, 'index']);
    Route::post('/', [BarangController::class, 'store']);
    Route::get('/{id}', [BarangController::class, 'show']);
    Route::put('/{id}', [BarangController::class, 'update']);
    Route::delete('/{id}', [BarangController::class, 'destroy']);

    // Additional barang routes
    Route::get('/penjual/{idUserPenjual}', [BarangController::class, 'getByPenjual']);
    Route::patch('/{id}/stock', [BarangController::class, 'updateStock']);
});

/*
|--------------------------------------------------------------------------
| Transaksi Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('transaksi')->group(function () {
    Route::get('/', [TransaksiController::class, 'index']);
    Route::post('/', [TransaksiController::class, 'store']);
    Route::get('/{id}', [TransaksiController::class, 'show']);
    Route::put('/{id}', [TransaksiController::class, 'update']);
    Route::delete('/{id}', [TransaksiController::class, 'destroy']);

    // Additional transaksi routes
    Route::patch('/{id}/payment-status', [TransaksiController::class, 'updatePaymentStatus']);
    Route::get('/user/{idUser}', [TransaksiController::class, 'getByUser']);
});

/*
|--------------------------------------------------------------------------
| Detail Transaksi Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('detail-transaksi')->group(function () {
    Route::get('/', [DetailTransaksiController::class, 'index']);
    Route::get('/{id}', [DetailTransaksiController::class, 'show']);
    Route::put('/{id}', [DetailTransaksiController::class, 'update']);

    // Additional detail transaksi routes
    Route::get('/transaksi/{idTransaksi}', [DetailTransaksiController::class, 'getByTransaksi']);
    Route::patch('/{id}/taken', [DetailTransaksiController::class, 'markAsTaken']);
    Route::patch('/{id}/not-taken', [DetailTransaksiController::class, 'markAsNotTaken']);
});

/*
|--------------------------------------------------------------------------
| Rating & Ulasan Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('rating-ulasan')->group(function () {
    Route::get('/', [RatingUlasanController::class, 'index']);
    Route::post('/', [RatingUlasanController::class, 'store']);
    Route::get('/{id}', [RatingUlasanController::class, 'show']);
    Route::put('/{id}', [RatingUlasanController::class, 'update']);
    Route::delete('/{id}', [RatingUlasanController::class, 'destroy']);

    // Additional rating ulasan routes
    Route::get('/barang/{idBarang}', [RatingUlasanController::class, 'getByBarang']);
    Route::get('/user/{idUser}', [RatingUlasanController::class, 'getByUser']);
    Route::get('/barang/{idBarang}/average', [RatingUlasanController::class, 'getAverageRating']);
});

Route::get('/tes', [TesController::class, 'index']);
Route::get('/tes/{barang}', [TesController::class, 'index2']);


Route::post('/xendit/disbursement', [XenditWebhookController::class, 'handleDisbursement']);
Route::post('/xendit/invoice', [XenditWebhookController::class, 'handleInvoice']);
