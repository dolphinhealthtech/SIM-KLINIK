<?php

use App\Http\Controllers\PcareController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('pcare')->group(function () {
    // pcare
    Route::get('/token', [PcareController::class, 'get_token'])->name('pcare.token');
    Route::get('/noka/{nomor}', [PcareController::class, 'get_noka_bpjs'])->name('pcare.noka');
    Route::get('/nik/{nomor}', [PcareController::class, 'get_nik_bpjs'])->name('pcare.nik');
    Route::get('/poli', [PcareController::class, 'get_poli_fktp_bpjs'])->name('pcare.poli');
    Route::get('/dokter', [PcareController::class, 'get_dokter_bpjs'])->name('pcare.dokter');
    Route::get('/spesialis', [PcareController::class, 'get_spesialis_bpjs'])->name('pcare.spesialis');
    Route::get('/sub-spesialis/{nama}', [PcareController::class, 'get_sub_spesialis_bpjs'])->name('pcare.subspesialis');
    Route::get('/diagnosis/{nama}', [PcareController::class, 'get_diagnosis_bpjs'])->name('pcare.diagnosis');
    Route::get('/statpul/{nama}', [PcareController::class, 'get_statpul_bpjs'])->name('pcare.statpul');
    Route::get('/kesadaran', [PcareController::class, 'get_kesadaran_bpjs'])->name('pcare.kesadaran');
});
