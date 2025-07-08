<?php

use App\Http\Controllers\SuperAdmin\KasirController;
use Illuminate\Support\Facades\Route;

// Menu Kasir
Route::get('/datakasir', [KasirController::class, 'datakasir_lunas'])->name('datakasir_lunas.index');
Route::post('/datakasir/print', [KasirController::class, 'datakasir_lunas_print'])->name('datakasir_lunas.print');

Route::get('/datakasir/detail', [KasirController::class, 'datakasir_detail'])->name('datakasir_detail.index');
Route::post('/datakasir/detail/print', [KasirController::class, 'datakasir_detail_print'])->name('datakasir_detail.print');

Route::get('/datakasir/apotek', [KasirController::class, 'datakasir_apotek'])->name('datakasir_apotek.index');
Route::post('/datakasir/apotek/print', [KasirController::class, 'datakasir_apotek_print'])->name('datakasir_apotek.print');

Route::get('/datakasir/tindakan', [KasirController::class, 'datakasir_tindakan'])->name('datakasir_tindakan.index');
Route::post('/datakasir/tindakan/print', [KasirController::class, 'datakasir_tindakan_print'])->name('datakasir_tindakan.print');

Route::get('/datakasir/diskon', [KasirController::class, 'datakasir_diskon'])->name('datakasir_diskon.index');
Route::post('/datakasir/diskon/print', [KasirController::class, 'datakasir_diskon_print'])->name('datakasir_diskon.print');
