<?php

use App\Http\Controllers\kategoriController;
use App\Http\Controllers\transaksiController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/users', function () {
    return response()->json([
        'status' => 'OK',
        'service' => 'backend'
    ]);
});

Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    #User Management SuperAdmin Only
    Route::middleware('role:superAdmin')->group(function () {
        Route::post('users', [UserController::class, 'store']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);
    });


    Route::middleware('role:superAdmin,admin')->group(function () {
        #User Management
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'show']);

        #Kategori Management
        Route::apiResource('kategori', kategoriController::class);

        #Pendapatan Management
        Route::apiResource('transaksi', transaksiController::class);
        Route::get('laporanPendapatan', [transaksiController::class, 'laporanPendapatan']);
        Route::get('laporanPengeluaran', [transaksiController::class, 'laporanPengeluaran']);

        #Laporan
        Route::get('jumlahTransaksi', [transaksiController::class, 'jumlahTransaski']);
        Route::get('saldoBersih', [transaksiController::class, 'saldoBersih']);
        Route::get('trentahunan', [transaksiController::class, 'trentahun']);
        Route::get('trenBulanan/{tahun}', [TransaksiController::class, 'trenBulan']);
        Route::get('summary', [TransaksiController::class, 'summary']);

        #Export Excel
        Route::get('exportTransaksi', [transaksiController::class, 'export']);
        Route::get('laporanKeuangan', [transaksiController::class, 'laporanKeuangan']);
        Route::get('exportLaporanKeuangan', [transaksiController::class, 'exportLaporanKeuangan']);


        #chart
        Route::get('chart', [transaksiController::class, 'chart']);
    });
});
