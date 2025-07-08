<?php

use App\Http\Controllers\SuperAdmin\PendataanController;
use App\Http\Controllers\DataMaster\gudang\GudangUtamaController;
use Illuminate\Support\Facades\Route;

// Menu Pendataan
Route::prefix('pendataan')->group(function () {
    Route::get('/antrian', [PendataanController::class,'pendataan_antrian'])->name('pendataan_antrian.get');
    Route::post('/print/antrian', [PendataanController::class,'print_antrian'])->name('print_antrian');

    Route::get('/pendaftaran', [PendataanController::class,'pendataan_pendaftaran'])->name('pendataan_pendaftaran.get');
    Route::post('/print/pendaftaran', [PendataanController::class,'print_pendaftaran'])->name('print_pendaftaran');

    Route::get('/soap-dokter', [PendataanController::class,'pendataan_dokter'])->name('pendataan_dokter.get');
    Route::post('/print/dokter', [PendataanController::class,'print_dokter'])->name('print_dokter');

    Route::get('/so-perawat', [PendataanController::class,'pendataan_perawat'])->name('pendataan_perawat.get');
    Route::post('/print/perawat', [PendataanController::class,'print_perawat'])->name('print_perawat');

    Route::get('/stok-penyesuaian', [PendataanController::class,'laporan_stok_penyesuaian'])->name('laporan_stok_penyesuaian.get');
    Route::post('/print/stok-penyesuaian', [PendataanController::class,'print_stok_penyesuaian'])->name('print_stok_penyesuaian');

    Route::get('/stok-opname', [PendataanController::class,'stok_opname'])->name('stok_opname.get');
    Route::post('/print/stok-opname', [PendataanController::class,'print_stok_opname'])->name('print_stok_opname');

    //Gudang utama
    Route::get('/gudang-utama', [GudangUtamaController::class,'laporan_gudang_utama'])->name('laporan_gudang_utama.get');
    Route::post('/print/gudang-utama', [GudangUtamaController::class,'print_gudang_utama'])->name('print_gudang_utama');
});
