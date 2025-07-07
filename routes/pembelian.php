<?php

use App\Http\Controllers\DataMaster\superadmin\PembelianController;
use Illuminate\Support\Facades\Route;

// Menu Pembelian
Route::get('/pembelian', [PembelianController::class, 'pembelian'])->name('pembelian.get');
Route::post('/pembelian/add', [PembelianController::class, 'pembelianadd'])->name('pembelian.add');
Route::get('/pembelian/cetak/{nomor_faktur}', [PembelianController::class, 'cetakPembelianPdf'])->name('pembelian.cetak');
