<?php

use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\MonitorController;
use App\Http\Controllers\WebSettingController;
use Illuminate\Support\Facades\Route;

// Menu setting
Route::middleware('auth')->prefix('setting')->group(function () {
    // Dashboard - Role
    Route::get('/role', [UserController::class,'rolecreate'])->name('role.get');
    Route::post('/role/add', [UserController::class,'rolestore'])->name('role.store');
    Route::post('/role/update', [UserController::class,'rolesupdate'])->name('role.update');
    Route::post('/role/delete', [UserController::class,'rolesdestroy'])->name('role.destroy');
    Route::post('/role/give-permission', [UserController::class, 'givePermission'])->name('role.givePermission');

    // Dashboard - Premission
    Route::get('/permission', [UserController::class,'permissioncreate'])->name('permission.get');
    Route::post('/permission/add', [UserController::class,'permissiontore'])->name('permission.store');
    Route::post('/permission/update', [UserController::class,'permissionupdate'])->name('permission.update');
    Route::post('/permission/delete', [UserController::class,'permissiondestroy'])->name('permission.destroy');
    // Dashboard - Users
    Route::get('/user', [UserController::class,'usercreate'])->name('user.get');
    Route::post('/user/store', [UserController::class,'userstore'])->name('users.store');
    Route::post('/user/aktiva', [UserController::class,'usernonaktif'])->name('user.aktiva');
    Route::post('/user/giverole', [UserController::class,'usersgiverole'])->name('user.giverole');
    Route::post('/user/destroy', [UserController::class,'usersdestroy'])->name('user.destroy');

    // Dashboard - Web Seting
    Route::get('/web', [MonitorController::class,'setingweb'])->name('web.get');
    Route::post('/web/update', [WebSettingController::class, 'update'])->name('web.update');
    Route::post('/web/satusehat', [WebSettingController::class, 'set_satusehat'])->name('web.update.satusehat');
    Route::post('/web/bpjs', [WebSettingController::class, 'set_bpjs'])->name('web.update.bpjs');

});
