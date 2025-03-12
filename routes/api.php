<?php

use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PcareController;
use App\Http\Controllers\SatusehatController;
use App\Http\Controllers\SuperadminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-pasien/{id}', [SuperadminController::class, 'getPasien']);


Route::prefix('lokasi')->group(function(){
    Route::get('/kabupaten', [LokasiController::class, 'getKabupaten'])->name('get.kabupaten');
    Route::get('/kecamatan', [LokasiController::class, 'getKecamatan'])->name('get.kecamatan');
    Route::get('/kelurahan', [LokasiController::class, 'getKelurahan'])->name('get.kelurahan');

});
Route::prefix('satusehat')->group(function(){
    Route::get('/token', [SatusehatController::class, 'get_token'])->name('satusehat.token'); // di privat fungsi nya
    Route::get('/nik/{nomor}', [SatusehatController::class, 'get_nik_satusehat'])->name('satusehat.nik'); // di privat fungsi nya
    Route::get('/nik-practitioner/{nomor}', [SatusehatController::class, 'get_nik_practitioner_satusehat'])->name('satusehat.nik_practitione'); // di privat fungsi nya
});
Route::prefix('pcare')->group(function () {
    // pcare
    // Route::get('/token', [PcareController::class, 'get_token'])->name('pcare.token'); // di privat fungsi nya
    Route::get('/noka/{nomor}', [PcareController::class, 'get_noka_bpjs'])->name('pcare.noka');
    Route::get('/nik/{nomor}', [PcareController::class, 'get_nik_bpjs'])->name('pcare.nik');
    Route::get('/poli', [PcareController::class, 'get_poli_fktp_bpjs'])->name('pcare.poli');
    Route::get('/dokter', [PcareController::class, 'get_dokter_bpjs'])->name('pcare.dokter');
    Route::get('/spesialis', [PcareController::class, 'get_spesialis_bpjs'])->name('pcare.spesialis');
    Route::get('/sub-spesialis/{nama}', [PcareController::class, 'get_sub_spesialis_bpjs'])->name('pcare.subspesialis');
    Route::get('/diagnosis/{nama}', [PcareController::class, 'get_diagnosis_bpjs'])->name('pcare.diagnosis');
    Route::get('/statpul/{nama}', [PcareController::class, 'get_statpul_bpjs'])->name('pcare.statpul');
    Route::get('/kesadaran', [PcareController::class, 'get_kesadaran_bpjs'])->name('pcare.kesadaran');
    Route::get('/provider', [PcareController::class, 'get_provider_bpjs'])->name('pcare.provider');
    Route::get('/khusus', [PcareController::class, 'get_khusus_bpjs'])->name('pcare.khusus');
    Route::get('/dpho/{nama}', [PcareController::class, 'get_dphoobat_bpjs'])->name('pcare.dpho');
    Route::get('/prognosa', [PcareController::class, 'get_prognosa_bpjs'])->name('pcare.prognosa');
    Route::get('/alergi/{kode}', [PcareController::class, 'get_alergi_bpjs'])->name('pcare.alergi');
});

