<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\WebSettingController;
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





Route::prefix('setting')->group(function () {
    // Dashboard - Role
    Route::get('/role', [SuperadminController::class,'rolecreate'])->name('role.get');
    Route::post('/role/add', [SuperadminController::class,'rolestore'])->name('role.store');
    Route::post('/role/update', [SuperadminController::class,'rolesupdate'])->name('role.update');
    Route::post('/role/delete', [SuperadminController::class,'rolesdestroy'])->name('role.destroy');
    Route::post('/role/give-permission', [SuperadminController::class, 'givePermission'])->name('role.givePermission');

    // Dashboard - Premission
    Route::get('/permission', [SuperadminController::class,'permissioncreate'])->name('permission.get');
    Route::post('/permission/add', [SuperadminController::class,'permissiontore'])->name('permission.store');
    Route::post('/permission/update', [SuperadminController::class,'permissionupdate'])->name('permission.update');
    Route::post('/permission/delete', [SuperadminController::class,'permissiondestroy'])->name('permission.destroy');
    // Dashboard - Users
    Route::get('/user', [SuperadminController::class,'usercreate'])->name('user.get');
    Route::post('/user/aktiva', [SuperadminController::class,'usernonaktif'])->name('user.aktiva');
    Route::post('/user/giverole', [SuperadminController::class,'usersgiverole'])->name('user.giverole');
    Route::post('/user/destroy', [SuperadminController::class,'usersdestroy'])->name('user.destroy');

    // Dashboard - Web Seting
    Route::get('/web', [SuperadminController::class,'setingweb'])->name('web.get');
    Route::post('/web/update', [WebSettingController::class, 'update'])->name('web.update');
    Route::post('/web/satusehat', [WebSettingController::class, 'set_satusehat'])->name('web.update.satusehat');
    Route::post('/web/bpjs', [WebSettingController::class, 'set_bpjs'])->name('web.update.bpjs');

});


require __DIR__.'/auth.php';
