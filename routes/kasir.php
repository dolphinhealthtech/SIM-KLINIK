<?php

use App\Http\Controllers\SuperAdmin\KasirController;
use Illuminate\Support\Facades\Route;

// Menu Kasir
Route::get('/kasir', [KasirController::class, 'kasir'])->name('kasir');
Route::get('/kasir/pembayaran/{kode_faktur}', [KasirController::class, 'kasirPembayaran'])->name('kasir.pembayaran');
Route::post('/kasir/add', [KasirController::class, 'kasiradd'])->name('kasir.store');
