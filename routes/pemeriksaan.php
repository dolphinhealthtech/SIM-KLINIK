<?php

use App\Http\Controllers\DataMaster\soap\PelayananController;
use App\Http\Controllers\DataMaster\soap\OdoController;
use App\Http\Controllers\DataMaster\soap\RujukanController;
use Illuminate\Support\Facades\Route;

// Menu Pemeriksaan
Route::prefix('pemeriksaan')->group(function () {
    Route::get('/dokter', [PelayananController::class, 'pelayana_dokter'])->name('pelayanad.get');
    Route::get('/dokter/so/{norawat}', [PelayananController::class, 'soappelayanan'])->name('pelayana_dokter.get');
    Route::post('/dokter/so/add', [PelayananController::class, 'soappelayananandd'])->name('pelayana_dokter.add');
    Route::get('/dokter/so/hadir/{norawat}', [PelayananController::class, 'soappelayananpanggil'])->name('pelayana_dokter.hadir');
    Route::get('/dokter/so/selesai/{norawat}', [PelayananController::class, 'soappelayananselesai'])->name('pelayana_dokter.selesai');
    Route::get('/rujuk/{norawat}', [RujukanController::class, 'pelayana_rujukan'])->name('pelayana_rujuk.get');
    Route::post('/rujuk/add', [RujukanController::class, 'pelayana_rujukan_add'])->name('pelayana_rujuk.add');

    Route::post('/rujuk', [RujukanController::class, 'pelayana_rujukanadd'])->name('pelayana_rujuk.add');
    Route::get('/rme/{norawat}', [PelayananController::class, 'pelayana_rme'])->name('pelayana_rme.get');
    Route::get('/permintaan/{norawat}', [PelayananController::class, 'pelayana_permintaan'])->name('pelayana_permintaan.get');
    Route::post('/resep/print', [PelayananController::class, 'print'])->name('resep.print');
    Route::post('/laboratorium/print', [PelayananController::class, 'laboratoriumPrint'])->name('laboratorium.print');
    Route::post('/radiologi/print', [PelayananController::class, 'radiologiPrint'])->name('radiologi.print');
    Route::post('/skd/print', [PelayananController::class, 'skdPrint'])->name('skd.print');
    Route::post('/dokter/so/odontogram/add', [OdoController::class, 'odontogramadd'])->name('odontogram.add');
    Route::post('/dokter/so/odontogram/details/add', [OdoController::class, 'odontogramdetailsadd'])->name('odontogram.details.add');

    // Menu Pasien
    Route::get('/perawat', [PelayananController::class, 'pelayana'])->name('pelayana.get');
    Route::get('/perawat/so/{norawat}', [PelayananController::class, 'sopelayanan'])->name('sopelayana.get');
    Route::post('/perawat/so/add', [PelayananController::class, 'sopelayanandd'])->name('sopelayana.add');
    Route::get('/perawat/so/hadir/{norawat}', [PelayananController::class, 'sopelayananpanggil'])->name('sopelayana.hadir');
});
