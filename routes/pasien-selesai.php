<?php

use App\Http\Controllers\Soap\PelayananController;
use Illuminate\Support\Facades\Route;

// Menu Panggil Pasien
Route::middleware('auth')->prefix('pasien-selesai')->group(function () {
    Route::get('/', [PelayananController::class, 'pelayana_selesai'])->name('list_pasien.get');
    Route::get('/rme/{norawat}', [PelayananController::class, 'pelayana_rme_selesai'])->name('list_pasien_rme.get');
});

Route::get('/list_pasien', [PelayananController::class, 'list_pasien'])->name('list_pasien.get');
Route::get('/rme/{norawat}', [PelayananController::class, 'pelayana_rme_selesai'])->name('list_pasien_rme.get');
