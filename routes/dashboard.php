<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\dashboard;


// Route Dashboard
Route::middleware(['auth','verified'])->prefix('dashboard')->group(function () {
    Route::get('/', [dashboard::class, 'index'])->name('dashboard-all');
    Route::get('/dokter', [dashboard::class, 'dokter'])->name('dashboard-dokter');
    Route::get('/registrasi', [dashboard::class, 'index'])->name('dashboard-registrasi');
});


