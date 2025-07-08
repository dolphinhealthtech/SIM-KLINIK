<?php

use App\Http\Controllers\dashboard;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\DataMasterManajemenController;
use App\Http\Controllers\DataMasterMedisController;
use App\Http\Controllers\DataMasterGudangController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\soap;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\WebSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk Superadmin
Route::get('/dashboard', [dashboard::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});



require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/apotek.php';
require __DIR__.'/data-barang.php';
require __DIR__.'/data-inventaris.php';
require __DIR__.'/data-master-gudang.php';
require __DIR__.'/data-master-manajemen.php';
require __DIR__.'/data-master-medis.php';
require __DIR__.'/data-master.php';
require __DIR__.'/datakasir.php';
require __DIR__.'/dokter.php';
require __DIR__.'/kasir.php';
require __DIR__.'/monitor.php';
require __DIR__.'/pasien-selesai.php';
require __DIR__.'/pasien.php';
require __DIR__.'/pembelian.php';
require __DIR__.'/pemeriksaan.php';
require __DIR__.'/pendaftaran.php';
require __DIR__.'/pendataan.php';
require __DIR__.'/setting.php';
require __DIR__.'/staff.php';










