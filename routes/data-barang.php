<?php

use App\Http\Controllers\SuperAdmin\DabarController;
use Illuminate\Support\Facades\Route;

// Menu Data Barang (obat)
Route::get('/data-barang', [DabarController::class, 'dabar'])->name('dabar.get');
Route::post('/data-barang/add', [DabarController::class, 'dabaradd'])->name('dabar.store');
Route::post('/data-barang/update', [DabarController::class, 'dabaredit'])->name('dabar.update');
Route::post('/data-barang/delete', [DabarController::class, 'dabardelete'])->name('dabar.destroy');
Route::get('/data-barang/export', [DabarController::class, 'dabarexport'])->name('dabar.export');
Route::post('/data-barang/import', [DabarController::class, 'dabarimport'])->name('dabar.import');
//Koneksi antar database
Route::get('/data-barang/singkron/{id}', [DabarController::class, 'dabarsingkron'])->name('dabar.singkron');
