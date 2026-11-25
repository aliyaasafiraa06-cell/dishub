<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Autentikasi (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Route Internal (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout (semua user bisa)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard (semua user bisa lihat)
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman Layanan (semua user bisa lihat)
    Route::get('/layanan/{slug}', [LayananController::class, 'show'])->name('layanan.show');

    /*
    |--------------------------------------------------------------------------
    | ROUTE KHUSUS ADMIN SAJA
    |--------------------------------------------------------------------------
    */
    Route::middleware('isAdmin')->group(function () {

        // CRUD Laporan (hanya admin)
        Route::get('/laporan/create/{layanan}', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
        Route::get('/laporan/{laporan}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('/laporan/{laporan}', [LaporanController::class, 'update'])->name('laporan.update');
        Route::delete('/laporan/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');

        // Ekspor Excel (hanya admin)
        Route::get('/layanan/{slug}/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
    });

    // Detail Laporan (semua user bisa lihat)
    Route::get('/laporan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
});
