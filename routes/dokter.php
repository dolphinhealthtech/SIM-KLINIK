<?php

use App\Http\Controllers\DataMaster\superadmin\DokterController;
use Illuminate\Support\Facades\Route;

// Menu Dokter
Route::prefix('dokter')->group(function () {
    // Menu Pasien
    Route::get('/', [DokterController::class, 'dokter'])->name('dokter.get');
    Route::post('/add', [DokterController::class, 'dokteradd'])->name('dokter.store');
    Route::post('/delete', [DokterController::class, 'dokterdelete'])->name('dokter.destroy');
    Route::post('/verifikasi', [DokterController::class, 'dokterverifikasi'])->name('dokter.verifikasi');
    Route::post('/update', [DokterController::class, 'dokteredit'])->name('dokter.update');
    Route::post('/jadwal/store', [DokterController::class, 'dokterjadwal'])->name('dokter.jadwal');
    Route::delete('/jadwal/hapus/{id}', [DokterController::class, 'dokterjadwalhapus']);
    Route::get('/sinkron-jadwal-dokter/{id}', [DokterController::class, 'jadwal_dokter'])->name('jadwal.sinkron');
});
