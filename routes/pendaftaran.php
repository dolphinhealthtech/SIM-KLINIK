<?php

use App\Http\Controllers\SuperAdmin\PendaftaranController;
use Illuminate\Support\Facades\Route;

// Menu Pendaftaran
Route::prefix('pendaftaran')->group(function () {
    Route::get('/', [PendaftaranController::class,'pendaftaran'])->name('pendaftaran.get');
    Route::post('/add', [PendaftaranController::class,'pendaftaranadd'])->name('pendaftaran.add');
    Route::post('/batal', [PendaftaranController::class,'pendaftaranbatal'])->name('pendaftaran.batal');
    Route::post('/dokterup', [PendaftaranController::class,'pendaftaranupdokter'])->name('pendaftaran.dokter.update');
    Route::post('/hadir', [PendaftaranController::class,'pendaftaranhadir'])->name('pendaftaran.hadir');

});
