<?php

use App\Http\Controllers\SuperAdmin\InventarisController;
use Illuminate\Support\Facades\Route;

// Menu Inventaris
Route::get('/data-inventaris', [InventarisController::class,'inventaris'])->name('inventaris.get');
Route::post('/data-inventaris/add', [InventarisController::class,'inventarisadd'])->name('inventaris.store');
Route::post('/data-inventaris/update', [InventarisController::class,'inventarisedit'])->name('inventaris.update');
Route::post('/data-inventaris/delete', [InventarisController::class,'inventarisdelete'])->name('inventaris.destroy');
Route::get('/data-inventaris/export', [InventarisController::class, 'inventarisexport'])->name('inventaris.export');
Route::post('/data-inventaris/import', [InventarisController::class, 'inventarisimport'])->name('inventaris.import');
// Inventaris koneksi antar database
    Route::get('/data-inventaris/singkron/{id}', [InventarisController::class, 'inventarissingkron'])->name('inventaris.singkron');

Route::get('/inventaris-pembelian', [InventarisController::class, 'inventaris_pembelian'])->name('inventaris_pembelian.get');
Route::post('/inventaris-pembelian/add', [InventarisController::class, 'inventaris_pembelianadd'])->name('inventaris_pembelian.add');
