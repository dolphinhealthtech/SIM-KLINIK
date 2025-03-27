<?php

use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\DataMasterMedisController;
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
    Route::post('/add', [SuperadminController::class,'pasiensadd'])->name('pasien.store');
    Route::post('/verifikasi', [SuperadminController::class,'pasienvefiv'])->name('pasien.verifikasi');
    Route::post('/update', [SuperadminController::class,'pasienupdate'])->name('pasien.update');
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

    // Menu Bahasa
    Route::get('/bahasa', [DataMasterController::class,'bahasa'])->name('bahasa.get');
    Route::post('/bahasa/add', [DataMasterController::class,'bahasaadd'])->name('bahasa.store');
    Route::post('/bahasa/update', [DataMasterController::class,'bahasaedit'])->name('bahasa.update');
    Route::post('/bahasa/delete', [DataMasterController::class,'bahasadelete'])->name('bahasa.destroy');
    Route::get('/bahasa/export', [DataMasterController::class,'bahasaexport'])->name('bahasa.export');
    Route::post('/bahasa/import', [DataMasterController::class,'bahasaimport'])->name('bahasa.import');

    // Menu Agama
    Route::get('/agama', [DataMasterController::class,'agama'])->name('agama.get');
    Route::post('/agama/add', [DataMasterController::class,'agamaadd'])->name('agama.store');
    Route::post('/agama/update', [DataMasterController::class,'agamaedit'])->name('agama.update');
    Route::post('/agama/delete', [DataMasterController::class,'agamadelete'])->name('agama.destroy');
    Route::get('/agama/export', [DataMasterController::class,'agamaexport'])->name('agama.export');
    Route::post('/agama/import', [DataMasterController::class,'agamaimport'])->name('agama.import');

    // Menu Pendidikan
    Route::get('/pendidikan', [DataMasterController::class,'pendidikan'])->name('pendidikan.get');
    Route::post('/pendidikan/add', [DataMasterController::class,'pendidikanadd'])->name('pendidikan.store');
    Route::post('/pendidikan/update', [DataMasterController::class,'pendidikanedit'])->name('pendidikan.update');
    Route::post('/pendidikan/delete', [DataMasterController::class,'pendidikandelete'])->name('pendidikan.destroy');
    Route::get('/pendidikan/export', [DataMasterController::class,'pendidikanexport'])->name('pendidikan.export');
    Route::post('/pendidikan/import', [DataMasterController::class,'pendidikanimport'])->name('pendidikan.import');

    // Menu kelamin
    Route::get('/kelamin', [DataMasterController::class,'kelamin'])->name('kelamin.get');
    Route::post('/kelamin/add', [DataMasterController::class,'kelaminadd'])->name('kelamin.store');
    Route::post('/kelamin/update', [DataMasterController::class,'kelaminedit'])->name('kelamin.update');
    Route::post('/kelamin/delete', [DataMasterController::class,'kelamindelete'])->name('kelamin.destroy');
    Route::get('/kelamin/export', [DataMasterController::class,'kelaminexport'])->name('kelamin.export');
    Route::post('/kelamin/import', [DataMasterController::class,'kelaminimport'])->name('kelamin.import');

    // Menu kelamin
    Route::get('/pernikahan', [DataMasterController::class,'pernikahan'])->name('pernikahan.get');
    Route::post('/pernikahan/add', [DataMasterController::class,'pernikahanadd'])->name('pernikahan.store');
    Route::post('/pernikahan/update', [DataMasterController::class,'pernikahanedit'])->name('pernikahan.update');
    Route::post('/pernikahan/delete', [DataMasterController::class,'pernikahandelete'])->name('pernikahan.destroy');
    Route::get('/pernikahan/export', [DataMasterController::class,'pernikahanexport'])->name('pernikahan.export');
    Route::post('/pernikahan/import', [DataMasterController::class,'pernikahanimport'])->name('pernikahan.import');

    // Menu kelamin
    Route::get('/pekerjaan', [DataMasterController::class,'pekerjaan'])->name('pekerjaan.get');
    Route::post('/pekerjaan/add', [DataMasterController::class,'pekerjaanadd'])->name('pekerjaan.store');
    Route::post('/pekerjaan/update', [DataMasterController::class,'pekerjaanedit'])->name('pekerjaan.update');
    Route::post('/pekerjaan/delete', [DataMasterController::class,'pekerjaandelete'])->name('pekerjaan.destroy');
    Route::get('/pekerjaan/export', [DataMasterController::class,'pekerjaanexport'])->name('pekerjaan.export');
    Route::post('/pekerjaan/import', [DataMasterController::class,'pekerjaanimport'])->name('pekerjaan.import');

});

Route::middleware('auth')->prefix('data-master-medis')->group(function () {
    // Menu Golongan Darah
    Route::get('/poli', [DataMasterMedisController::class,'poli'])->name('poli.get');
    Route::get('/poli/sync', [DataMasterMedisController::class,'poliadd'])->name('poli.sync');
    Route::post('/poli/delete', [DataMasterMedisController::class,'polidelete'])->name('poli.destroy');


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
require __DIR__.'/user.php';
