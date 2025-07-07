<?php

use App\Http\Controllers\DataMasterManajemenController;
use Illuminate\Support\Facades\Route;

// Menu Data Master Manajemen
Route::middleware('auth')->prefix('data-master-manajemen')->group(function () {
    // Menu Poli
    Route::get('/posker', [DataMasterManajemenController::class, 'posisi_kerja'])->name('posker.get');
    Route::post('/posker/add', [DataMasterManajemenController::class, 'posisi_kerjaadd'])->name('posker.store');
    Route::post('/posker/update', [DataMasterManajemenController::class, 'posisi_kerjaedit'])->name('posker.update');
    Route::post('/posker/delete', [DataMasterManajemenController::class, 'posisi_kerjadelete'])->name('posker.destroy');
    Route::get('/posker/export', [DataMasterManajemenController::class, 'posisi_kerjaexport'])->name('posker.export');
    Route::post('/posker/import', [DataMasterManajemenController::class, 'posisi_kerjaimport'])->name('posker.import');
});
