<?php

use Illuminate\Support\Facades\Route;

Route::get('/user', function () {
    return view('pasien.index');
})->middleware(['auth', 'verified'])->name('user');
