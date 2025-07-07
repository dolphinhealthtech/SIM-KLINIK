<?php

use App\Http\Controllers\DataMaster\main\GoldarController;
use App\Http\Controllers\DataMaster\main\SukuController;
use App\Http\Controllers\DataMaster\main\BangsaController;
use App\Http\Controllers\DataMaster\main\BahasaController;
use App\Http\Controllers\DataMaster\main\AgamaController;
use App\Http\Controllers\DataMaster\main\PendidikanController;
use App\Http\Controllers\DataMaster\main\KelaminController;
use App\Http\Controllers\DataMaster\main\PernikahanController;
use App\Http\Controllers\DataMaster\main\PekerjaanController;
use App\Http\Controllers\DataMaster\main\BankController;
use App\Http\Controllers\DataMaster\main\PenjaminController;
use App\Http\Controllers\DataMaster\main\LoketController;
use Illuminate\Support\Facades\Route;

// Menu Data Master
Route::middleware('auth')->prefix('data-master')->group(function () {
    // Menu Golongan Darah
    Route::get('/goldar', [GoldarController::class, 'darah'])->name('goldar.get');
    Route::post('/goldar/add', [GoldarController::class, 'darahadd'])->name('goldar.store');
    Route::post('/goldar/update', [GoldarController::class, 'darahedit'])->name('goldar.update');
    Route::post('/goldar/delete', [GoldarController::class, 'darahdelete'])->name('goldar.destroy');
    Route::get('/goldar/export', [GoldarController::class, 'darahexport'])->name('goldar.export');
    Route::post('/goldar/import', [GoldarController::class, 'darahimport'])->name('goldar.import');

    // Menu Suku
    Route::get('/suku', [SukuController::class, 'suku'])->name('suku.get');
    Route::post('/suku/add', [SukuController::class, 'sukuadd'])->name('suku.store');
    Route::post('/suku/update', [SukuController::class, 'sukuedit'])->name('suku.update');
    Route::post('/suku/delete', [SukuController::class, 'sukudelete'])->name('suku.destroy');
    Route::get('/suku/export', [SukuController::class, 'sukuexport'])->name('suku.export');
    Route::post('/suku/import', [SukuController::class, 'sukuimport'])->name('suku.import');

    // Menu Bangsa
    Route::get('/bangsa', [BangsaController::class, 'bangsa'])->name('bangsa.get');
    Route::post('/bangsa/add', [BangsaController::class, 'bangsaadd'])->name('bangsa.store');
    Route::post('/bangsa/update', [BangsaController::class, 'bangsaedit'])->name('bangsa.update');
    Route::post('/bangsa/delete', [BangsaController::class, 'bangsadelete'])->name('bangsa.destroy');
    Route::get('/bangsa/export', [BangsaController::class, 'bangsaexport'])->name('bangsa.export');
    Route::post('/bangsa/import', [BangsaController::class, 'bangsaimport'])->name('bangsa.import');

    // Menu Bahasa
    Route::get('/bahasa', [BahasaController::class, 'bahasa'])->name('bahasa.get');
    Route::post('/bahasa/add', [BahasaController::class, 'bahasaadd'])->name('bahasa.store');
    Route::post('/bahasa/update', [BahasaController::class, 'bahasaedit'])->name('bahasa.update');
    Route::post('/bahasa/delete', [BahasaController::class, 'bahasadelete'])->name('bahasa.destroy');
    Route::get('/bahasa/export', [BahasaController::class, 'bahasaexport'])->name('bahasa.export');
    Route::post('/bahasa/import', [BahasaController::class, 'bahasaimport'])->name('bahasa.import');

    // Menu Agama
    Route::get('/agama', [AgamaController::class, 'agama'])->name('agama.get');
    Route::post('/agama/add', [AgamaController::class, 'agamaadd'])->name('agama.store');
    Route::post('/agama/update', [AgamaController::class, 'agamaedit'])->name('agama.update');
    Route::post('/agama/delete', [AgamaController::class, 'agamadelete'])->name('agama.destroy');
    Route::get('/agama/export', [AgamaController::class, 'agamaexport'])->name('agama.export');
    Route::post('/agama/import', [AgamaController::class, 'agamaimport'])->name('agama.import');

    // Menu Pendidikan
    Route::get('/pendidikan', [PendidikanController::class, 'pendidikan'])->name('pendidikan.get');
    Route::post('/pendidikan/add', [PendidikanController::class, 'pendidikanadd'])->name('pendidikan.store');
    Route::post('/pendidikan/update', [PendidikanController::class, 'pendidikanedit'])->name('pendidikan.update');
    Route::post('/pendidikan/delete', [PendidikanController::class, 'pendidikandelete'])->name('pendidikan.destroy');
    Route::get('/pendidikan/export', [PendidikanController::class, 'pendidikanexport'])->name('pendidikan.export');
    Route::post('/pendidikan/import', [PendidikanController::class, 'pendidikanimport'])->name('pendidikan.import');

    // Menu kelamin
    Route::get('/kelamin', [KelaminController::class, 'kelamin'])->name('kelamin.get');
    Route::post('/kelamin/add', [KelaminController::class, 'kelaminadd'])->name('kelamin.store');
    Route::post('/kelamin/update', [KelaminController::class, 'kelaminedit'])->name('kelamin.update');
    Route::post('/kelamin/delete', [KelaminController::class, 'kelamindelete'])->name('kelamin.destroy');
    Route::get('/kelamin/export', [KelaminController::class, 'kelaminexport'])->name('kelamin.export');
    Route::post('/kelamin/import', [KelaminController::class, 'kelaminimport'])->name('kelamin.import');

    // Menu kelamin
    Route::get('/pernikahan', [PernikahanController::class, 'pernikahan'])->name('pernikahan.get');
    Route::post('/pernikahan/add', [PernikahanController::class, 'pernikahanadd'])->name('pernikahan.store');
    Route::post('/pernikahan/update', [PernikahanController::class, 'pernikahanedit'])->name('pernikahan.update');
    Route::post('/pernikahan/delete', [PernikahanController::class, 'pernikahandelete'])->name('pernikahan.destroy');
    Route::get('/pernikahan/export', [PernikahanController::class, 'pernikahanexport'])->name('pernikahan.export');
    Route::post('/pernikahan/import', [PernikahanController::class, 'pernikahanimport'])->name('pernikahan.import');

    // Menu kelamin
    Route::get('/pekerjaan', [PekerjaanController::class, 'pekerjaan'])->name('pekerjaan.get');
    Route::post('/pekerjaan/add', [PekerjaanController::class, 'pekerjaanadd'])->name('pekerjaan.store');
    Route::post('/pekerjaan/update', [PekerjaanController::class, 'pekerjaanedit'])->name('pekerjaan.update');
    Route::post('/pekerjaan/delete', [PekerjaanController::class, 'pekerjaandelete'])->name('pekerjaan.destroy');
    Route::get('/pekerjaan/export', [PekerjaanController::class, 'pekerjaanexport'])->name('pekerjaan.export');
    Route::post('/pekerjaan/import', [PekerjaanController::class, 'pekerjaanimport'])->name('pekerjaan.import');

    //bank
    Route::get('/bank', [BankController::class, 'bank'])->name('bank.get');
    Route::post('/bank/add', [BankController::class, 'bankadd'])->name('bank.store');
    Route::post('/bank/update', [BankController::class, 'bankedit'])->name('bank.update');
    Route::post('/bank/delete', [BankController::class, 'bankdelete'])->name('bank.destroy');
    Route::get('/bank/export', [BankController::class, 'bankexport'])->name('bank.export');
    Route::post('/bank/import', [BankController::class, 'bankimport'])->name('bank.import');

    //penjamin
    Route::get('/penjamin', [PenjaminController::class, 'penjamin'])->name('penjamin.get');
    Route::post('/penjamin/add', [PenjaminController::class, 'penjaminadd'])->name('penjamin.store');
    Route::post('/penjamin/update', [PenjaminController::class, 'penjaminedit'])->name('penjamin.update');
    Route::post('/penjamin/delete', [PenjaminController::class, 'penjamindelete'])->name('penjamin.destroy');
    Route::get('/penjamin/export', [PenjaminController::class, 'penjaminexport'])->name('penjamin.export');
    Route::post('/penjamin/import', [PenjaminController::class, 'penjaminimport'])->name('penjamin.import');

    //loket
    Route::get('/loket', [LoketController::class, 'loket'])->name('loket.get');
    Route::post('/loket/add', [LoketController::class, 'loketadd'])->name('loket.store');
    Route::post('/loket/update', [LoketController::class, 'loketedit'])->name('loket.update');
    Route::post('/loket/delete', [LoketController::class, 'loketdelete'])->name('loket.destroy');
    Route::get('/loket/export', [LoketController::class, 'loketexport'])->name('loket.export');
    Route::post('/loket/import', [LoketController::class, 'loketimport'])->name('loket.import');
});
