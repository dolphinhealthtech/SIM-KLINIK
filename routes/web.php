<?php

use App\Http\Controllers\DataMasterController;
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




Route::get('/monitor', [SuperadminController::class,'monitor'])->name('monitor.get');

Route::prefix('pasien')->group(function () {
    // Menu Pasien
    Route::get('/', [SuperadminController::class,'pasiens'])->name('pasien.get');
});


Route::middleware('auth')->prefix('data-master')->group(function () {
    // Menu Golongan Darah
    Route::get('/goldar', [DataMasterController::class,'darah'])->name('goldar.get');
    Route::post('/goldar/add', [DataMasterController::class,'darahadd'])->name('goldar.store');
    Route::post('/goldar/update', [DataMasterController::class,'darahedit'])->name('goldar.update');
    Route::post('/goldar/delete', [DataMasterController::class,'darahdelete'])->name('goldar.destroy');
    Route::get('/goldar/export', [DataMasterController::class, 'darahexport'])->name('goldar.export');
    Route::post('/goldar/import', [DataMasterController::class, 'darahimport'])->name('goldar.import');

    // Menu Suku
    Route::get('/suku', [DataMasterController::class,'suku'])->name('suku.get');
    Route::post('/suku/add', [DataMasterController::class,'sukuadd'])->name('suku.store');
    Route::post('/suku/update', [DataMasterController::class,'sukuedit'])->name('suku.update');
    Route::post('/suku/delete', [DataMasterController::class,'sukudelete'])->name('suku.destroy');
    Route::get('/suku/export', [DataMasterController::class,'sukuexport'])->name('suku.export');
    Route::post('/suku/import', [DataMasterController::class,'sukuimport'])->name('suku.import');

    // Menu Bangsa
    Route::get('/bangsa', [DataMasterController::class,'bangsa'])->name('bangsa.get');
    Route::post('/bangsa/add', [DataMasterController::class,'bangsaadd'])->name('bangsa.store');
    Route::post('/bangsa/update', [DataMasterController::class,'bangsaedit'])->name('bangsa.update');
    Route::post('/bangsa/delete', [DataMasterController::class,'bangsadelete'])->name('bangsa.destroy');
    Route::get('/bangsa/export', [DataMasterController::class,'bangsaexport'])->name('bangsa.export');
    Route::post('/bangsa/import', [DataMasterController::class,'bangsaimport'])->name('bangsa.import');

});


Route::middleware('auth')->prefix('setting')->group(function () {
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
