<?php

use App\Http\Controllers\DataMaster\superadmin\StaffController;
use Illuminate\Support\Facades\Route;

// Menu Staff
Route::prefix('staff')->group(function () {
    // Menu Pasien
    Route::get('/', [StaffController::class, 'staff'])->name('staff.get');
    Route::post('/add', [StaffController::class, 'staffadd'])->name('staff.store');
    Route::post('/delete', [StaffController::class, 'staffdelete'])->name('staff.destroy');
    Route::post('/verifikasi', [StaffController::class, 'staffverifikasi'])->name('staff.verifikasi');
    Route::post('/update', [StaffController::class, 'staffedit'])->name('staff.update');
});
