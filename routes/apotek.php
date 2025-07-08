<?php

use App\Http\Controllers\SuperAdmin\ApotekController;
use Illuminate\Support\Facades\Route;

// Menu Apotek
Route::get('/apotek', [ApotekController::class, 'apotek'])->middleware(['auth'])->name('apotek.index');
Route::post('/apotek/add', [ApotekController::class, 'apotekadd'])->name('apotek.store');

Route::post('/apotek/print-resep/dokter', [ApotekController::class, 'resep_dokter'])->name('apotek.resep_dokter');
Route::post('/apotek/print-resep/revisi', [ApotekController::class, 'resep_revisi'])->name('apotek.resep_revisi');
