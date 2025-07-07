<?php

use App\Http\Controllers\DataMaster\gudang\GudangRequestController;
use App\Http\Controllers\DataMaster\gudang\GudangUtamaController;
use App\Http\Controllers\DataMaster\gudang\HargaJualController;
use App\Http\Controllers\DataMaster\gudang\KategoriController;
use App\Http\Controllers\DataMaster\gudang\SatuanController;
use App\Http\Controllers\DataMaster\gudang\SetHargaController;
use App\Http\Controllers\DataMaster\gudang\StokBarangController;
use App\Http\Controllers\DataMaster\gudang\SupplierController;
use App\Http\Controllers\DataMaster\inventaris\InventarisRequestController;
use App\Http\Controllers\DataMaster\inventaris\InventarisUtamaController;
use App\Http\Controllers\DataMaster\inventaris\SatuanInventarisController;
use App\Http\Controllers\DataMaster\inventaris\KategoriInventarisController;
use App\Http\Controllers\DataMaster\inventaris\StokInventarisController;
use Illuminate\Support\Facades\Route;

// Menu Data Master Gudang
Route::prefix('data-master-gudang')->group(function () {
    // Menu Jenis Satuan
    Route::get('/satuan', [SatuanController::class, 'satuan'])->name('satuan.get');
    Route::post('/satuan/add', [SatuanController::class, 'satuanadd'])->name('satuan.store');
    Route::post('/satuan/update', [SatuanController::class, 'satuanedit'])->name('satuan.update');
    Route::post('/satuan/delete', [SatuanController::class, 'satuandelete'])->name('satuan.destroy');
    Route::get('/satuan/export', [SatuanController::class, 'satuanexport'])->name('satuan.export');
    Route::post('/satuan/import', [SatuanController::class, 'satuanimport'])->name('satuan.import');

    // Menu Jenis Kategori
    Route::get('/kategori', [KategoriController::class, 'kategori'])->name('kategori.get');
    Route::post('/kategori/add', [KategoriController::class, 'kategoriadd'])->name('kategori.store');
    Route::post('/kategori/update', [KategoriController::class, 'kategoriedit'])->name('kategori.update');
    Route::post('/kategori/delete', [KategoriController::class, 'kategoridelete'])->name('kategori.destroy');
    Route::get('/kategori/export', [KategoriController::class, 'kategoriexport'])->name('kategori.export');
    Route::post('/kategori/import', [KategoriController::class, 'kategoriimport'])->name('kategori.import');

    // Menu Supplier
    Route::get('/supplier-industri', [SupplierController::class, 'supplier'])->name('supplier.get');
    Route::post('/supplier-industri/add', [SupplierController::class, 'supplieradd'])->name('supplier.store');
    Route::post('/supplier-industri/update', [SupplierController::class, 'supplieredit'])->name('supplier.update');
    Route::post('/supplier-industri/delete', [SupplierController::class, 'supplierdelete'])->name('supplier.destroy');
    Route::get('/supplier-industri/export', [SupplierController::class, 'supplierexport'])->name('supplier.export');
    Route::post('/supplier-industri/import', [SupplierController::class, 'supplierimport'])->name('supplier.import');

    // Menu Setting Harga
    Route::get('/setting-harga-jual', [SetHargaController::class, 'setharga'])->name('setharga.get');
    Route::post('/setting-harga-jual/add', [SetHargaController::class, 'sethargaadd'])->name('setharga.store');
    Route::get('/setting-harga-jual/singkron/{id}', [SetHargaController::class, 'sethargasingkron'])->name('setharga.singkron');
    Route::get('/harga-barang-jual', [HargaJualController::class, 'hargajual'])->name('hargajual.get');
    Route::get('/stok-obat-alkes', [StokBarangController::class, 'stokobatalkes'])->name('stokobatalkes.get');

    Route::get('/stok-penyesuaian-opname', [StokBarangController::class, 'stok_penyesuaian'])->name('stok_penyesuaian.get');
    Route::post('/stok-penyesuaian-opname/add', [StokBarangController::class, 'stok_penyesuaianadd'])->name('stok_penyesuaian.store');

    // Menu Satuan Inventaris
    Route::get('/satuan-inventaris', [SatuanInventarisController::class, 'satuan_inventaris'])->name('satuan_inventaris.get');
    Route::post('/satuan-inventaris/add', [SatuanInventarisController::class, 'satuan_inventarisadd'])->name('satuan_inventaris.store');
    Route::post('/satuan-inventaris/update', [SatuanInventarisController::class, 'satuan_inventarisedit'])->name('satuan_inventaris.update');
    Route::post('/satuan-inventaris/delete', [SatuanInventarisController::class, 'satuan_inventarisdelete'])->name('satuan_inventaris.destroy');
    Route::get('/satuan-inventaris/export', [SatuanInventarisController::class, 'satuan_inventarisexport'])->name('satuan_inventaris.export');
    Route::post('/satuan-inventaris/import', [SatuanInventarisController::class, 'satuan_inventarisimport'])->name('satuan_inventaris.import');

    // Menu Kategori Inventaris
    Route::get('/kategori-inventaris', [KategoriInventarisController::class, 'katin'])->name('katin.get');
    Route::post('/kategori-inventaris/add', [KategoriInventarisController::class, 'katinadd'])->name('katin.store');
    Route::post('/kategori-inventaris/update', [KategoriInventarisController::class, 'katinedit'])->name('katin.update');
    Route::post('/kategori-inventaris/delete', [KategoriInventarisController::class, 'katindelete'])->name('katin.destroy');
    Route::get('/kategori-inventaris/export', [KategoriInventarisController::class, 'katinexport'])->name('katin.export');
    Route::post('/kategori-inventaris/import', [KategoriInventarisController::class, 'katinimport'])->name('katin.import');

    // Menu Stok Inventaris
    Route::get('/stok-inventaris', [StokInventarisController::class, 'stokin'])->name('stokin.get');
    Route::get('/stok-inventaris/data/{id}', [StokInventarisController::class, 'stokin_data'])->name('stokin_data.get');
    Route::post('/stok-inventaris/data/update', [StokInventarisController::class, 'stokin_dataedit'])->name('stokin_data.update');
    Route::post('/stok-inventaris/data/delete', [StokInventarisController::class, 'stokin_datadelete'])->name('stokin_data.destroy');

    // Menu Request Obat Klinik Omega
    Route::get('/inventaris-request', [InventarisRequestController::class, 'inventarisrequest'])->name('inventarisrequest.get');
    Route::post('/inventaris-request/add', [InventarisRequestController::class, 'inventarisrequestadd'])->name('inventarisrequest.store');

    // Menu Utama Klinik Omega
    Route::get('/inventaris-utama', [InventarisUtamaController::class, 'inventarisutama'])->name('inventarisutama.get');
    Route::post('/inventaris-utama/konfirmasi', [InventarisUtamaController::class, 'inventarisutamakonfirmasi'])->name('inventarisutama.konfirmasi');

    // Menu Request Obat Klinik Omega
    Route::get('/gudang-request', [GudangRequestController::class, 'gudangrequest'])->name('gudangrequest.get');
    Route::post('/gudang-request/add', [GudangRequestController::class, 'gudangrequestadd'])->name('gudangrequest.store');

    // Menu Utama Klinik Omega
    Route::get('/gudang-utama', [GudangUtamaController::class, 'gudangutama'])->name('gudangutama.get');
    Route::post('/gudang-utama/konfirmasi', [GudangUtamaController::class, 'gudangutamakonfirmasi'])->name('gudangutama.konfirmasi');
});
