<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\dashboard;
// Route untuk Superadmin (akses umum dashboard)
Route::get('/dashboard', [dashboard::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard-dokter', [dashboard::class, 'dokter'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard-dokter');

Route::get('/dashboard-registrasi', [dashboard::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard-registrasi');



Route::get('/dashboard', [dashboard::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
