<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperadminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




// Dashboard - Role
Route::get('/role', [SuperadminController::class,'rolecreate'])->name('role.get');
Route::post('/role/add', [SuperadminController::class,'rolestore'])->name('role.store');
Route::post('/role/update', [SuperadminController::class,'rolesupdate'])->name('role.update');
Route::post('/role/delete', [SuperadminController::class,'rolesdestroy'])->name('role.destroy');
// Dashboard - Premission
Route::get('/permission', [SuperadminController::class,'permissioncreate'])->name('permission.get');
Route::post('/permission/add', [SuperadminController::class,'permissiontore'])->name('permission.store');
Route::post('/permission/update', [SuperadminController::class,'permissionupdate'])->name('permission.update');
Route::post('/permission/delete', [SuperadminController::class,'permissiondestroy'])->name('permission.destroy');

require __DIR__.'/auth.php';
