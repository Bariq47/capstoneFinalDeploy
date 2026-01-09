<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\laporanController;
use App\Http\Controllers\pendapatanController;
use App\Http\Controllers\pengeluaranController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userViewController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [UserViewController::class, 'showLogin'])->name('login');
Route::post('/login', [UserViewController::class, 'login']);
Route::post('/logout', [UserViewController::class, 'logout'])->name('logout');


Route::middleware('jwt.session')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::middleware('role.access:superAdmin')->group(function () {

        Route::get('/akun', [AkunController::class, 'index'])->name('akun');
        Route::get('/akun/tambah', [AkunController::class, 'create'])->name('akun.create');
        Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
        Route::get('/akun/{id}/edit', [AkunController::class, 'edit'])->name('akun.edit');
        Route::put('/akun/{id}', [AkunController::class, 'update'])->name('akun.update');
        Route::delete('/akun/{id}', [AkunController::class, 'destroy'])->name('akun.destroy');
    });

    Route::middleware('role.access:admin,superAdmin')->group(function () {

        // Pendapatan
        Route::get('/pendapatan', [PendapatanController::class, 'index'])->name('pendapatan');
        Route::get('/pendapatan/tambah', [PendapatanController::class, 'create'])->name('pendapatan.create');
        Route::post('/pendapatan', [PendapatanController::class, 'store'])->name('pendapatan.store');
        Route::get('/pendapatan/{id}/edit', [PendapatanController::class, 'edit'])->name('pendapatan.edit');
        Route::put('/pendapatan/{id}', [PendapatanController::class, 'update'])->name('pendapatan.update');
        Route::delete('/pendapatan/{id}', [PendapatanController::class, 'destroy'])->name('pendapatan.destroy');

        // Pengeluaran
        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');
        Route::get('/pengeluaran/tambah', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
        Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::get('/pengeluaran/{id}/edit', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
        Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
        Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/export', [LaporanController::class, 'export'])->name('transaksi.export');
        Route::get('/laporan-keuangan/export',[LaporanController::class, 'exportLaporanKeuangan'])->name('laporan.export');


        Route::get('/laporanKeuangan', [LaporanController::class, 'trenBulanan'])->name('laporan.laporanKeuangan');
    });
});
