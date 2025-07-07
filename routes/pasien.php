<?php

use App\Http\Controllers\DataMaster\superadmin\PasienController;
use Illuminate\Support\Facades\Route;

// Menu Pasien
Route::prefix('pasien')->group(function () {
    Route::get('/', [PasienController::class, 'pasiens'])->name('pasien.get');
    Route::post('/add', [PasienController::class, 'pasiensadd'])->name('pasien.store');
    Route::post('/verifikasi', [PasienController::class, 'pasienvefiv'])->name('pasien.verifikasi');
    Route::post('/update', [PasienController::class, 'pasienupdate'])->name('pasien.update');
});

Route::post('/pasien/panggil/{id}', [PasienController::class, 'panggilPasien'])->name('pasien.panggil');
