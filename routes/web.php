<?php

use App\Http\Controllers\dashboard;
use App\Http\Controllers\DataMasterManajemenController;
use App\Http\Controllers\Auth\ProfileController;
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
use App\Http\Controllers\DataMaster\inventaris\KategoriInventarisController;
use App\Http\Controllers\DataMaster\inventaris\SatuanInventarisController;
use App\Http\Controllers\DataMaster\inventaris\StokInventarisController;
use App\Http\Controllers\DataMaster\medis\AlergiController;
use App\Http\Controllers\DataMaster\medis\Icd9Controller;
use App\Http\Controllers\DataMaster\medis\Icd10Controller;
use App\Http\Controllers\DataMaster\medis\JenisDietController;
use App\Http\Controllers\DataMaster\medis\KategoriPerawatanController;
use App\Http\Controllers\DataMaster\medis\LaboratoriumBidangController;
use App\Http\Controllers\DataMaster\medis\NamaMakananController;
use App\Http\Controllers\DataMaster\medis\PemeriksaanHttController;
use App\Http\Controllers\DataMaster\medis\PerawatanTindakanController;
use App\Http\Controllers\DataMaster\medis\PoliController;
use App\Http\Controllers\DataMaster\medis\RadiologiPemeriksaanController;
use App\Http\Controllers\DataMaster\medis\RadiologiJenisController;
use App\Http\Controllers\DataMaster\medis\SaranaController;
use App\Http\Controllers\DataMaster\medis\SpesialisController;
use App\Http\Controllers\DataMaster\medis\SubspesialisController;
use App\Http\Controllers\DataMaster\main\GoldarController;
use App\Http\Controllers\DataMaster\main\SukuController;
use App\Http\Controllers\DataMaster\main\BangsaController;
use App\Http\Controllers\DataMaster\main\BahasaController;
use App\Http\Controllers\DataMaster\main\AgamaController;
use App\Http\Controllers\DataMaster\main\PendidikanController;
use App\Http\Controllers\DataMaster\main\KelaminController;
use App\Http\Controllers\DataMaster\main\PernikahanController;
use App\Http\Controllers\DataMaster\main\PekerjaanController;
use App\Http\Controllers\DataMaster\main\BankController;
use App\Http\Controllers\DataMaster\main\PenjaminController;
use App\Http\Controllers\DataMaster\main\LoketController;
use App\Http\Controllers\soap;
use App\Http\Controllers\Soap\OdoController;
use App\Http\Controllers\Soap\PelayananController;
use App\Http\Controllers\Soap\RujukanController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\SuperAdmin\ApotekController;
use App\Http\Controllers\SuperAdmin\DabarController;
use App\Http\Controllers\SuperAdmin\DokterController;
use App\Http\Controllers\SuperAdmin\InventarisController;
use App\Http\Controllers\SuperAdmin\KasirController;
use App\Http\Controllers\SuperAdmin\MonitorController;
use App\Http\Controllers\SuperAdmin\PasienController;
use App\Http\Controllers\SuperAdmin\PembelianController;
use App\Http\Controllers\SuperAdmin\PendaftaranController;
use App\Http\Controllers\SuperAdmin\PendataanController;
use App\Http\Controllers\SuperAdmin\StaffController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\UserActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


//
Route::get('/logs', [UserActivityLogController::class, 'index'])->name('logs.index');


// Menu Apotek
Route::get('/apotek', [ApotekController::class, 'apotek'])->middleware(['auth'])->name('apotek.index');
Route::post('/apotek/add', [ApotekController::class, 'apotekadd'])->name('apotek.store');

Route::post('/apotek/print-resep/dokter', [ApotekController::class, 'resep_dokter'])->name('apotek.resep_dokter');
Route::post('/apotek/print-resep/revisi', [ApotekController::class, 'resep_revisi'])->name('apotek.resep_revisi');


// Menu Data Barang (obat)
Route::get('/data-barang', [DabarController::class, 'dabar'])->name('dabar.get');
Route::get('/data-barang-utama', [DabarController::class, 'dabar_utama'])->name('dabar_utama.get');
Route::post('/data-barang/add', [DabarController::class, 'dabaradd'])->name('dabar.store');
Route::post('/data-barang-utama/add', [DabarController::class, 'dabaradd_utama'])->name('dabar_utama.store');
Route::post('/data-barang/update', [DabarController::class, 'dabaredit'])->name('dabar.update');
Route::post('/data-barang-utama/update', [DabarController::class, 'dabaredit_utama'])->name('dabar_utama.update');
Route::post('/data-barang/delete', [DabarController::class, 'dabardelete'])->name('dabar.destroy');
Route::post('/data-barang-utama/delete', [DabarController::class, 'dabardelete_utama'])->name('dabar_utama.destroy');
Route::get('/data-barang/export', [DabarController::class, 'dabarexport'])->name('dabar.export');
Route::get('/data-barang-utama/export', [DabarController::class, 'dabarexport_utama'])->name('dabar_utama.export');
Route::post('/data-barang/import', [DabarController::class, 'dabarimport'])->name('dabar.import');
Route::post('/data-barang-utama/import', [DabarController::class, 'dabarimport_utama'])->name('dabar_utama.import');
//Koneksi antar database
Route::get('/data-barang/singkron/{id}', [DabarController::class, 'dabarsingkron'])->name('dabar.singkron');

// Menu Inventaris
Route::get('/data-inventaris', [InventarisController::class, 'inventaris'])->name('inventaris.get');
Route::post('/data-inventaris/add', [InventarisController::class, 'inventarisadd'])->name('inventaris.store');
Route::post('/data-inventaris/update', [InventarisController::class, 'inventarisedit'])->name('inventaris.update');
Route::post('/data-inventaris/delete', [InventarisController::class, 'inventarisdelete'])->name('inventaris.destroy');
Route::get('/data-inventaris/export', [InventarisController::class, 'inventarisexport'])->name('inventaris.export');
Route::post('/data-inventaris/import', [InventarisController::class, 'inventarisimport'])->name('inventaris.import');
// Inventaris koneksi antar database
Route::get('/data-inventaris/singkron/{id}', [InventarisController::class, 'inventarissingkron'])->name('inventaris.singkron');

Route::get('/inventaris-pembelian', [InventarisController::class, 'inventaris_pembelian'])->name('inventaris_pembelian.get');
Route::post('/inventaris-pembelian/add', [InventarisController::class, 'inventaris_pembelianadd'])->name('inventaris_pembelian.add');

// Menu Inventari Utama
Route::get('/data-inventaris-utama', [InventarisController::class, 'inventaris_utama'])->name('inventaris_utama.get');
Route::post('/data-inventaris-utama/add', [InventarisController::class, 'inventarisadd_utama'])->name('inventaris_utama.store');
Route::post('/data-inventaris-utama/update', [InventarisController::class, 'inventarisedit_utama'])->name('inventaris_utama.update');
Route::post('/data-inventaris-utama/delete', [InventarisController::class, 'inventarisdelete_utama'])->name('inventaris_utama.destroy');
Route::get('/data-inventaris-utama/export', [InventarisController::class, 'inventarisexport_utama'])->name('inventaris_utama.export');
Route::post('/data-inventaris-utama/import', [InventarisController::class, 'inventarisimport_utama'])->name('inventaris_utama.import');

Route::get('/inventaris-pembelian-utama', [InventarisController::class, 'inventaris_pembelian_utama'])->name('inventaris_pembelian_utama.get');
Route::post('/inventaris-pembelian-utama/add', [InventarisController::class, 'inventaris_pembelianadd_utama'])->name('inventaris_pembelian_utama.add');

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
    Route::get('/setting-harga-jual-utama', [SetHargaController::class, 'setharga_utama'])->name('setharga_utama.get');
    Route::post('/setting-harga-jual/add', [SetHargaController::class, 'sethargaadd'])->name('setharga.store');
    Route::post('/setting-harga-jual-utama/add', [SetHargaController::class, 'sethargaadd_utama'])->name('setharga_utama.store');
    Route::get('/setting-harga-jual/singkron/{id}', [SetHargaController::class, 'sethargasingkron'])->name('setharga.singkron');
    Route::get('/harga-barang-jual', [HargaJualController::class, 'hargajual'])->name('hargajual.get');
    Route::get('/harga-barang-jual-utama', [HargaJualController::class, 'hargajualutama'])->name('hargajualutama.get');
    Route::get('/stok-obat-alkes', [StokBarangController::class, 'stokobatalkes'])->name('stokobatalkes.get');
    Route::get('/stok-obat-alkes-utama', [StokBarangController::class, 'stokobatalkes_utama'])->name('stokobatalkes_utama.get');

    Route::get('/stok-penyesuaian-opname', [StokBarangController::class, 'stok_penyesuaian'])->name('stok_penyesuaian.get');
    Route::get('/stok-penyesuaian-opname-utama', [StokBarangController::class, 'stok_penyesuaian_utama'])->name('stok_penyesuaian_utama.get');
    Route::post('/stok-penyesuaian-opname/add', [StokBarangController::class, 'stok_penyesuaianadd'])->name('stok_penyesuaian.store');
    Route::post('/stok-penyesuaian-opname-utama/add', [StokBarangController::class, 'stok_penyesuaianadd_utama'])->name('stok_penyesuaian_utama.store');

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

    // Menu Stok Inventaris Utama
    Route::get('/stok-inventaris-utama', [StokInventarisController::class, 'stokin_utama'])->name('stokin_utama.get');
    Route::get('/stok-inventaris-utama/data/{id}', [StokInventarisController::class, 'stokin_data_utama'])->name('stokin_data_utama.get');
    Route::post('/stok-inventaris-utama/data/update', [StokInventarisController::class, 'stokin_dataedit_utama'])->name('stokin_data_utama.update');
    Route::post('/stok-inventaris-utama/data/delete', [StokInventarisController::class, 'stokin_datadelete_utama'])->name('stokin_data_utama.destroy');


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

    Route::get('/kartu-stok', [StokBarangController::class, 'kartu_stok'])->name('kartu_stok.get');
    Route::get('/kartu-stok-utama', [StokBarangController::class, 'kartu_stok_utama'])->name('kartu_stok_utama.get');
});

// Menu Data Master Manajemen
Route::middleware('auth')->prefix('data-master-manajemen')->group(function () {
    // Menu Poli
    Route::get('/posker', [DataMasterManajemenController::class, 'posisi_kerja'])->name('posker.get');
    Route::post('/posker/add', [DataMasterManajemenController::class, 'posisi_kerjaadd'])->name('posker.store');
    Route::post('/posker/update', [DataMasterManajemenController::class, 'posisi_kerjaedit'])->name('posker.update');
    Route::post('/posker/delete', [DataMasterManajemenController::class, 'posisi_kerjadelete'])->name('posker.destroy');
    Route::get('/posker/export', [DataMasterManajemenController::class, 'posisi_kerjaexport'])->name('posker.export');
    Route::post('/posker/import', [DataMasterManajemenController::class, 'posisi_kerjaimport'])->name('posker.import');
});

// Menu Data Master Medis
Route::middleware('auth')->prefix('data-master-medis')->group(function () {
    // Menu Poli
    Route::get('/poli', [PoliController::class, 'poli'])->name('poli.get');
    Route::get('/poli/sync', [PoliController::class, 'poliadd'])->name('poli.sync');
    Route::post('/poli/delete', [PoliController::class, 'polidelete'])->name('poli.destroy');
    Route::get('/poli/export', [PoliController::class, 'poliexport'])->name('poli.export');
    Route::post('/poli/import', [PoliController::class, 'poliimport'])->name('poli.import');

    Route::get('/sarana', [SaranaController::class, 'sarana'])->name('sarana.get');
    Route::get('/sarana/sync', [SaranaController::class, 'saranaadd'])->name('sarana.sync');
    Route::post('/sarana/delete', [SaranaController::class, 'saranadelete'])->name('sarana.destroy');
    Route::get('/sarana/export', [SaranaController::class, 'saranaexport'])->name('sarana.export');
    Route::post('/sarana/import', [SaranaController::class, 'saranaimport'])->name('sarana.import');

    // Menu Spesialis
    Route::get('/spesialis', [SpesialisController::class, 'spesialis'])->name('spesialis.get');
    Route::get('/spesialis/sync', [SpesialisController::class, 'spesialisadd'])->name('spesialis.sync');
    Route::post('/spesialis/delete', [SpesialisController::class, 'spesialisdelete'])->name('spesialis.destroy');
    Route::get('/spesialis/export', [SpesialisController::class, 'spesialisexport'])->name('spesialis.export');
    Route::post('/spesialis/import', [SpesialisController::class, 'spesialisimport'])->name('spesialis.import');


    Route::get('/subspesialis/{kode}', [SubspesialisController::class, 'subspesialis'])->name('subspesialis.get');
    Route::get('/subspesialis/sync/{kode}', [SubspesialisController::class, 'subspesialisadd'])->name('subspesialis.sync');
    Route::post('/subspesialis/delete', [SubspesialisController::class, 'subspesialisdelete'])->name('subspesialis.destroy');

    // Kategori Perawatan
    Route::get('/katper', [KategoriPerawatanController::class, 'kategori_perawatan'])->name('kategori_perawatan.get');
    Route::post('/katper/add', [KategoriPerawatanController::class, 'kategori_perawatanadd'])->name('kategori_perawatan.store');
    Route::post('/katper/update', [KategoriPerawatanController::class, 'kategori_perawatanedit'])->name('kategori_perawatan.update');
    Route::post('/katper/delete', [KategoriPerawatanController::class, 'kategori_perawatandelete'])->name('kategori_perawatan.destroy');
    Route::get('/katper/export', [KategoriPerawatanController::class, 'kategori_perawatanexport'])->name('kategori_perawatan.export');
    Route::post('/katper/import', [KategoriPerawatanController::class, 'kategori_perawatanimport'])->name('kategori_perawatan.import');

    // Perawatan dan Tindakan
    Route::get('/perawatan-tindakan', [PerawatanTindakanController::class, 'perawatan_tindakan'])->name('perawatan_tindakan.get');
    Route::post('/perawatan-tindakan/add', [PerawatanTindakanController::class, 'perawatan_tindakanadd'])->name('perawatan_tindakan.store');
    Route::post('/perawatan-tindakan/update', [PerawatanTindakanController::class, 'perawatan_tindakanedit'])->name('perawatan_tindakan.update');
    Route::post('/perawatan-tindakan/delete', [PerawatanTindakanController::class, 'perawatan_tindakandelete'])->name('perawatan_tindakan.destroy');
    Route::get('/perawatan-tindakan/export', [PerawatanTindakanController::class, 'perawatan_tindakanexport'])->name('perawatan_tindakan.export');
    Route::post('/perawatan-tindakan/import', [PerawatanTindakanController::class, 'perawatan_tindakanimport'])->name('perawatan_tindakan.import');

    Route::get('/htt-pemeriksaan', [PemeriksaanHttController::class, 'htt_pemeriksaan'])->name('htt_pemeriksaan.get');
    Route::post('/htt_pemeriksaan/add', [PemeriksaanHttController::class, 'htt_pemeriksaanadd'])->name('htt_pemeriksaan.store');
    Route::post('/htt_pemeriksaan/update', [PemeriksaanHttController::class, 'htt_pemeriksaanedit'])->name('htt_pemeriksaan.update');
    Route::post('/htt_pemeriksaan/delete', [PemeriksaanHttController::class, 'htt_pemeriksaandelete'])->name('htt_pemeriksaan.destroy');
    Route::get('/htt_pemeriksaan/export', [PemeriksaanHttController::class, 'htt_pemeriksaanexport'])->name('htt_pemeriksaan.export');
    Route::post('/htt_pemeriksaan/import', [PemeriksaanHttController::class, 'htt_pemeriksaaneimport'])->name('htt_pemeriksaan.import');

    Route::get('/htt_sub_pemeriksaan/{kode}', [PemeriksaanHttController::class, 'htt_sub_pemeriksaan'])->name('htt_sub_pemeriksaan.get');
    Route::post('/htt_sub_pemeriksaan/add', [PemeriksaanHttController::class, 'htt_sub_pemeriksaanadd'])->name('htt_sub_pemeriksaan.store');
    Route::post('/htt_sub_pemeriksaan/update', [PemeriksaanHttController::class, 'htt_sub_pemeriksaanedit'])->name('htt_sub_pemeriksaan.update');
    Route::post('/htt_sub_pemeriksaan/delete', [PemeriksaanHttController::class, 'htt_sub_pemeriksaandelete'])->name('htt_sub_pemeriksaan.destroy');

    Route::get('/alergi', [AlergiController::class, 'alergi'])->name('alergi.get');
    Route::post('/alergi/add', [AlergiController::class, 'alergiadd'])->name('alergi.store');
    Route::post('/alergi/delete', [AlergiController::class, 'alergidelete'])->name('alergi.destroy');

    // Menu jenis_diet
    Route::get('/jenis-diet', [JenisDietController::class, 'jenis_diet'])->name('jenis_diet.get');
    Route::post('/jenis-diet/add', [JenisDietController::class, 'jenis_dietadd'])->name('jenis_diet.store');
    Route::post('/jenis-diet/update', [JenisDietController::class, 'jenis_dietedit'])->name('jenis_diet.update');
    Route::post('/jenis-diet/delete', [JenisDietController::class, 'jenis_dietdelete'])->name('jenis_diet.destroy');
    Route::get('/jenis-diet/export', [JenisDietController::class, 'jenis_dietexport'])->name('jenis_diet.export');
    Route::post('/jenis-diet/import', [JenisDietController::class, 'jenis_dietimport'])->name('jenis_diet.import');

    // Menu nama_
    Route::get('/nama-makanan', [NamaMakananController::class, 'nama_makanan'])->name('nama_makanan.get');
    Route::post('/nama-makanan/add', [NamaMakananController::class, 'nama_makananadd'])->name('nama_makanan.store');
    Route::post('/nama-makanan/update', [NamaMakananController::class, 'nama_makananedit'])->name('nama_makanan.update');
    Route::post('/nama-makanan/delete', [NamaMakananController::class, 'nama_makanandelete'])->name('nama_makanan.destroy');
    Route::get('/nama-makanan/export', [NamaMakananController::class, 'nama_makananexport'])->name('nama_makanan.export');
    Route::post('/nama-makanan/import', [NamaMakananController::class, 'nama_makananimport'])->name('nama_makanan.import');

    Route::get('/icd10', [Icd10Controller::class, 'icd10'])->name('icd10.get');
    Route::post('/icd10/sync', [Icd10Controller::class, 'icd10singkron'])->name('icd10.singkron');
    Route::post('/icd10/add', [Icd10Controller::class, 'icd10add'])->name('icd10.store');
    Route::post('/icd10/update', [Icd10Controller::class, 'icd10edit'])->name('icd10.update');
    Route::post('/icd10/delete', [Icd10Controller::class, 'icd10delete'])->name('icd10.destroy');
    Route::get('/icd10/export', [Icd10Controller::class, 'icd10export'])->name('icd10.export');
    Route::post('/icd10/import', [Icd10Controller::class, 'icd10import'])->name('icd10.import');

    Route::get('/icd9', [Icd9Controller::class, 'icd9'])->name('icd9.get');
    Route::post('/icd9/add', [Icd9Controller::class, 'icd9add'])->name('icd9.store');
    Route::post('/icd9/update', [Icd9Controller::class, 'icd9edit'])->name('icd9.update');
    Route::post('/icd9/delete', [Icd9Controller::class, 'icd9delete'])->name('icd9.destroy');
    Route::get('/icd9/export', [Icd9Controller::class, 'icd9export'])->name('icd9.export');
    Route::post('/icd9/import', [Icd9Controller::class, 'icd9import'])->name('icd9.import');

    //radiologi jenis
    Route::get('/radiologi_jenis', [RadiologiJenisController::class, 'radiologi_jenis'])->name('radiologi_jenis.get');
    Route::post('/radiologi_jenis/add', [RadiologiJenisController::class, 'radiologi_jenisadd'])->name('radiologi_jenis.store');
    Route::post('/radiologi_jenis/update', [RadiologiJenisController::class, 'radiologi_jenisedit'])->name('radiologi_jenis.update');
    Route::post('/radiologi_jenis/delete', [RadiologiJenisController::class, 'radiologi_jenisdelete'])->name('radiologi_jenis.destroy');
    Route::get('/radiologi_jenis/export', [RadiologiJenisController::class, 'radiologi_jenisexport'])->name('radiologi_jenis.export');
    Route::post('/radiologi_jenis/import', [RadiologiJenisController::class, 'radiologi_jenisimport'])->name('radiologi_jenis.import');

    Route::get('/bidang-lab', [LaboratoriumBidangController::class, 'laboratorium_bidang'])->name('laboratorium_bidang.get');
    Route::post('/bidang-lab/add', [LaboratoriumBidangController::class, 'laboratorium_bidangadd'])->name('laboratorium_bidang.store');
    Route::post('/bidang-lab/update', [LaboratoriumBidangController::class, 'laboratorium_bidangedit'])->name('laboratorium_bidang.update');
    Route::post('/bidang-lab/delete', [LaboratoriumBidangController::class, 'laboratorium_bidangdelete'])->name('laboratorium_bidang.destroy');
    Route::get('/bidang-lab/export', [LaboratoriumBidangController::class, 'laboratorium_bidangexport'])->name('laboratorium_bidang.export');
    Route::post('/bidang-lab/import', [LaboratoriumBidangController::class, 'laboratorium_bidangeimport'])->name('laboratorium_bidang.import');

    Route::get('/bidang-lab-sub/{kode}', [LaboratoriumBidangController::class, 'laboratorium_bidang_sub'])->name('laboratorium_bidang_sub.get');
    Route::post('/bidang-lab-sub/add', [LaboratoriumBidangController::class, 'laboratorium_bidang_subadd'])->name('laboratorium_bidang_sub.store');
    Route::post('/bidang-lab-sub/update', [LaboratoriumBidangController::class, 'laboratorium_bidang_subedit'])->name('laboratorium_bidang_sub.update');
    Route::post('/bidang-lab-sub/delete', [LaboratoriumBidangController::class, 'laboratorium_bidang_subdelete'])->name('laboratorium_bidang_sub.destroy');

    Route::get('/radiologi_pemeriksaan', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaan'])->name('radiologi_pemeriksaan.get');
    Route::post('/radiologi_pemeriksaan/add', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaanadd'])->name('radiologi_pemeriksaan.store');
    Route::post('/radiologi_pemeriksaan/update', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaanedit'])->name('radiologi_pemeriksaan.update');
    Route::post('/radiologi_pemeriksaan/delete', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaandelete'])->name('radiologi_pemeriksaan.destroy');
    Route::get('/radiologi_pemeriksaan/export', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaanexport'])->name('radiologi_pemeriksaan.export');
    Route::post('/radiologi_pemeriksaan/import', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaaneimport'])->name('radiologi_pemeriksaan.import');
});

Route::middleware('auth')->prefix('data-master')->group(function () {
    // Menu Golongan Darah
    Route::get('/goldar', [GoldarController::class, 'darah'])->name('goldar.get');
    Route::post('/goldar/add', [GoldarController::class, 'darahadd'])->name('goldar.store');
    Route::post('/goldar/update', [GoldarController::class, 'darahedit'])->name('goldar.update');
    Route::post('/goldar/delete', [GoldarController::class, 'darahdelete'])->name('goldar.destroy');
    Route::get('/goldar/export', [GoldarController::class, 'darahexport'])->name('goldar.export');
    Route::post('/goldar/import', [GoldarController::class, 'darahimport'])->name('goldar.import');

    // Menu Suku
    Route::get('/suku', [SukuController::class, 'suku'])->name('suku.get');
    Route::post('/suku/add', [SukuController::class, 'sukuadd'])->name('suku.store');
    Route::post('/suku/update', [SukuController::class, 'sukuedit'])->name('suku.update');
    Route::post('/suku/delete', [SukuController::class, 'sukudelete'])->name('suku.destroy');
    Route::get('/suku/export', [SukuController::class, 'sukuexport'])->name('suku.export');
    Route::post('/suku/import', [SukuController::class, 'sukuimport'])->name('suku.import');

    // Menu Bangsa
    Route::get('/bangsa', [BangsaController::class, 'bangsa'])->name('bangsa.get');
    Route::post('/bangsa/add', [BangsaController::class, 'bangsaadd'])->name('bangsa.store');
    Route::post('/bangsa/update', [BangsaController::class, 'bangsaedit'])->name('bangsa.update');
    Route::post('/bangsa/delete', [BangsaController::class, 'bangsadelete'])->name('bangsa.destroy');
    Route::get('/bangsa/export', [BangsaController::class, 'bangsaexport'])->name('bangsa.export');
    Route::post('/bangsa/import', [BangsaController::class, 'bangsaimport'])->name('bangsa.import');

    // Menu Bahasa
    Route::get('/bahasa', [BahasaController::class, 'bahasa'])->name('bahasa.get');
    Route::post('/bahasa/add', [BahasaController::class, 'bahasaadd'])->name('bahasa.store');
    Route::post('/bahasa/update', [BahasaController::class, 'bahasaedit'])->name('bahasa.update');
    Route::post('/bahasa/delete', [BahasaController::class, 'bahasadelete'])->name('bahasa.destroy');
    Route::get('/bahasa/export', [BahasaController::class, 'bahasaexport'])->name('bahasa.export');
    Route::post('/bahasa/import', [BahasaController::class, 'bahasaimport'])->name('bahasa.import');

    // Menu Agama
    Route::get('/agama', [AgamaController::class, 'agama'])->name('agama.get');
    Route::post('/agama/add', [AgamaController::class, 'agamaadd'])->name('agama.store');
    Route::post('/agama/update', [AgamaController::class, 'agamaedit'])->name('agama.update');
    Route::post('/agama/delete', [AgamaController::class, 'agamadelete'])->name('agama.destroy');
    Route::get('/agama/export', [AgamaController::class, 'agamaexport'])->name('agama.export');
    Route::post('/agama/import', [AgamaController::class, 'agamaimport'])->name('agama.import');

    // Menu Pendidikan
    Route::get('/pendidikan', [PendidikanController::class, 'pendidikan'])->name('pendidikan.get');
    Route::post('/pendidikan/add', [PendidikanController::class, 'pendidikanadd'])->name('pendidikan.store');
    Route::post('/pendidikan/update', [PendidikanController::class, 'pendidikanedit'])->name('pendidikan.update');
    Route::post('/pendidikan/delete', [PendidikanController::class, 'pendidikandelete'])->name('pendidikan.destroy');
    Route::get('/pendidikan/export', [PendidikanController::class, 'pendidikanexport'])->name('pendidikan.export');
    Route::post('/pendidikan/import', [PendidikanController::class, 'pendidikanimport'])->name('pendidikan.import');

    // Menu kelamin
    Route::get('/kelamin', [KelaminController::class, 'kelamin'])->name('kelamin.get');
    Route::post('/kelamin/add', [KelaminController::class, 'kelaminadd'])->name('kelamin.store');
    Route::post('/kelamin/update', [KelaminController::class, 'kelaminedit'])->name('kelamin.update');
    Route::post('/kelamin/delete', [KelaminController::class, 'kelamindelete'])->name('kelamin.destroy');
    Route::get('/kelamin/export', [KelaminController::class, 'kelaminexport'])->name('kelamin.export');
    Route::post('/kelamin/import', [KelaminController::class, 'kelaminimport'])->name('kelamin.import');

    // Menu kelamin
    Route::get('/pernikahan', [PernikahanController::class, 'pernikahan'])->name('pernikahan.get');
    Route::post('/pernikahan/add', [PernikahanController::class, 'pernikahanadd'])->name('pernikahan.store');
    Route::post('/pernikahan/update', [PernikahanController::class, 'pernikahanedit'])->name('pernikahan.update');
    Route::post('/pernikahan/delete', [PernikahanController::class, 'pernikahandelete'])->name('pernikahan.destroy');
    Route::get('/pernikahan/export', [PernikahanController::class, 'pernikahanexport'])->name('pernikahan.export');
    Route::post('/pernikahan/import', [PernikahanController::class, 'pernikahanimport'])->name('pernikahan.import');

    // Menu kelamin
    Route::get('/pekerjaan', [PekerjaanController::class, 'pekerjaan'])->name('pekerjaan.get');
    Route::post('/pekerjaan/add', [PekerjaanController::class, 'pekerjaanadd'])->name('pekerjaan.store');
    Route::post('/pekerjaan/update', [PekerjaanController::class, 'pekerjaanedit'])->name('pekerjaan.update');
    Route::post('/pekerjaan/delete', [PekerjaanController::class, 'pekerjaandelete'])->name('pekerjaan.destroy');
    Route::get('/pekerjaan/export', [PekerjaanController::class, 'pekerjaanexport'])->name('pekerjaan.export');
    Route::post('/pekerjaan/import', [PekerjaanController::class, 'pekerjaanimport'])->name('pekerjaan.import');

    //bank
    Route::get('/bank', [BankController::class, 'bank'])->name('bank.get');
    Route::post('/bank/add', [BankController::class, 'bankadd'])->name('bank.store');
    Route::post('/bank/update', [BankController::class, 'bankedit'])->name('bank.update');
    Route::post('/bank/delete', [BankController::class, 'bankdelete'])->name('bank.destroy');
    Route::get('/bank/export', [BankController::class, 'bankexport'])->name('bank.export');
    Route::post('/bank/import', [BankController::class, 'bankimport'])->name('bank.import');

    //penjamin
    Route::get('/penjamin', [PenjaminController::class, 'penjamin'])->name('penjamin.get');
    Route::post('/penjamin/add', [PenjaminController::class, 'penjaminadd'])->name('penjamin.store');
    Route::post('/penjamin/update', [PenjaminController::class, 'penjaminedit'])->name('penjamin.update');
    Route::post('/penjamin/delete', [PenjaminController::class, 'penjamindelete'])->name('penjamin.destroy');
    Route::get('/penjamin/export', [PenjaminController::class, 'penjaminexport'])->name('penjamin.export');
    Route::post('/penjamin/import', [PenjaminController::class, 'penjaminimport'])->name('penjamin.import');

    //loket
    Route::get('/loket', [LoketController::class, 'loket'])->name('loket.get');
    Route::post('/loket/add', [LoketController::class, 'loketadd'])->name('loket.store');
    Route::post('/loket/update', [LoketController::class, 'loketedit'])->name('loket.update');
    Route::post('/loket/delete', [LoketController::class, 'loketdelete'])->name('loket.destroy');
    Route::get('/loket/export', [LoketController::class, 'loketexport'])->name('loket.export');
    Route::post('/loket/import', [LoketController::class, 'loketimport'])->name('loket.import');
});
// Menu Kasir
Route::get('/datakasir', [KasirController::class, 'datakasir_lunas'])->name('datakasir_lunas.index');
Route::post('/datakasir/print', [KasirController::class, 'datakasir_lunas_print'])->name('datakasir_lunas.print');

Route::get('/datakasir/detail', [KasirController::class, 'datakasir_detail'])->name('datakasir_detail.index');
Route::post('/datakasir/detail/print', [KasirController::class, 'datakasir_detail_print'])->name('datakasir_detail.print');

Route::get('/datakasir/apotek', [KasirController::class, 'datakasir_apotek'])->name('datakasir_apotek.index');
Route::post('/datakasir/apotek/print', [KasirController::class, 'datakasir_apotek_print'])->name('datakasir_apotek.print');

Route::get('/datakasir/tindakan', [KasirController::class, 'datakasir_tindakan'])->name('datakasir_tindakan.index');
Route::post('/datakasir/tindakan/print', [KasirController::class, 'datakasir_tindakan_print'])->name('datakasir_tindakan.print');

Route::get('/datakasir/diskon', [KasirController::class, 'datakasir_diskon'])->name('datakasir_diskon.index');
Route::post('/datakasir/diskon/print', [KasirController::class, 'datakasir_diskon_print'])->name('datakasir_diskon.print');

// Menu Dokter
Route::prefix('dokter')->group(function () {
    // Menu Pasien
    Route::get('/', [DokterController::class, 'dokter'])->name('dokter.get');
    Route::post('/add', [DokterController::class, 'dokteradd'])->name('dokter.store');
    Route::post('/delete', [DokterController::class, 'dokterdelete'])->name('dokter.destroy');
    Route::post('/verifikasi', [DokterController::class, 'dokterverifikasi'])->name('dokter.verifikasi');
    Route::post('/update', [DokterController::class, 'dokteredit'])->name('dokter.update');
    Route::post('/jadwal/store', [DokterController::class, 'dokterjadwal'])->name('dokter.jadwal');
    Route::delete('/jadwal/hapus/{id}', [DokterController::class, 'dokterjadwalhapus']);
    Route::get('/sinkron-jadwal-dokter/{id}', [DokterController::class, 'jadwal_dokter'])->name('jadwal.sinkron');
});

// Menu Kasir
Route::get('/kasir', [KasirController::class, 'kasir'])->name('kasir');
Route::get('/kasir/pembayaran/{kode_faktur}', [KasirController::class, 'kasirPembayaran'])->name('kasir.pembayaran');
Route::post('/kasir/add', [KasirController::class, 'kasiradd'])->name('kasir.store');

Route::get('/monitor', [MonitorController::class, 'monitor'])->name('monitor.get');
Route::post('/monitor/add/bpjs', [MonitorController::class, 'monitor_bpjs'])->name('monitor.add.bpjs');
Route::post('/monitor/add/nobpjs', [MonitorController::class, 'monitor_nobpjs'])->name('monitor.add.nobpjs');
Route::get('/monitor/loket-antrian', [MonitorController::class, 'loketAntrian'])->name('monitor.loket.antrian');

// Menu Panggil Pasien
Route::middleware('auth')->prefix('pasien-selesai')->group(function () {
    Route::get('/', [PelayananController::class, 'pelayana_selesai'])->name('pelayana_selesai.get');
});

Route::get('/list_pasien', [PelayananController::class, 'list_pasien'])->name('list_pasien.get');
Route::get('/rme/{norawat}', [PelayananController::class, 'pelayana_rme_selesai'])->name('list_pasien_rme.get');

Route::prefix('pasien')->group(function () {
    Route::get('/', [PasienController::class, 'pasiens'])->name('pasien.get');
    Route::post('/add', [PasienController::class, 'pasiensadd'])->name('pasien.store');
    Route::post('/verifikasi', [PasienController::class, 'pasienvefiv'])->name('pasien.verifikasi');
    Route::post('/update', [PasienController::class, 'pasienupdate'])->name('pasien.update');
});

Route::post('/pasien/panggil/{id}', [PasienController::class, 'panggilPasien'])->name('pasien.panggil');

// Menu Pembelian
Route::get('/pembelian', [PembelianController::class, 'pembelian'])->name('pembelian.get');
Route::post('/pembelian/add', [PembelianController::class, 'pembelianadd'])->name('pembelian.add');
Route::get('/pembelian/cetak/{nomor_faktur}', [PembelianController::class, 'cetakPembelianPdf'])->name('pembelian.cetak');

// Menu Pemeriksaan
Route::prefix('pemeriksaan')->group(function () {
    Route::get('/dokter', [PelayananController::class, 'pelayana_dokter'])->name('pelayanad.get');
    Route::get('/dokter/so/{norawat}', [PelayananController::class, 'soappelayanan'])->name('pelayana_dokter.get');
    Route::post('/dokter/so/add', [PelayananController::class, 'soappelayananandd'])->name('pelayana_dokter.add');
    Route::get('/dokter/so/hadir/{norawat}', [PelayananController::class, 'soappelayananpanggil'])->name('pelayana_dokter.hadir');
    Route::get('/dokter/so/selesai/{norawat}', [PelayananController::class, 'soappelayananselesai'])->name('pelayana_dokter.selesai');
    Route::get('/rujuk/{norawat}', [RujukanController::class, 'pelayana_rujukan'])->name('pelayana_rujuk.get');
    Route::post('/rujuk/add', [RujukanController::class, 'pelayana_rujukan_add'])->name('pelayana_rujuk.add');

    Route::get('/rme/{norawat}', [PelayananController::class, 'pelayana_rme'])->name('pelayana_rme.get');
    Route::get('/permintaan/{norawat}', [PelayananController::class, 'pelayana_permintaan'])->name('pelayana_permintaan.get');
    Route::post('/resep/print', [PelayananController::class, 'print'])->name('resep.print');
    Route::post('/laboratorium/print', [PelayananController::class, 'laboratoriumPrint'])->name('laboratorium.print');
    Route::post('/radiologi/print', [PelayananController::class, 'radiologiPrint'])->name('radiologi.print');
    Route::post('/skd/print', [PelayananController::class, 'skdPrint'])->name('skd.print');
    Route::post('/dokter/so/odontogram/add', [OdoController::class, 'odontogramadd'])->name('odontogram.add');
    Route::post('/dokter/so/odontogram/details/add', [OdoController::class, 'odontogramdetailsadd'])->name('odontogram.details.add');

    // Menu Pasien
    Route::get('/perawat', [PelayananController::class, 'pelayana'])->name('pelayana.get');
    Route::get('/perawat/so/{norawat}', [PelayananController::class, 'sopelayanan'])->name('sopelayana.get');
    Route::post('/perawat/so/add', [PelayananController::class, 'sopelayanandd'])->name('sopelayana.add');
    Route::get('/perawat/so/hadir/{norawat}', [PelayananController::class, 'sopelayananpanggil'])->name('sopelayana.hadir');
});

// Menu Pendaftaran
Route::prefix('pendaftaran')->group(function () {
    Route::get('/', [PendaftaranController::class, 'pendaftaran'])->name('pendaftaran.get');
    Route::post('/add', [PendaftaranController::class, 'pendaftaranadd'])->name('pendaftaran.add');
    Route::post('/batal', [PendaftaranController::class, 'pendaftaranbatal'])->name('pendaftaran.batal');
    Route::post('/dokterup', [PendaftaranController::class, 'pendaftaranupdokter'])->name('pendaftaran.dokter.update');
    Route::post('/hadir', [PendaftaranController::class, 'pendaftaranhadir'])->name('pendaftaran.hadir');
});

// Menu setting
Route::middleware('auth')->prefix('setting')->group(function () {
    // Dashboard - Role
    Route::get('/role', [UserController::class, 'rolecreate'])->name('role.get');
    Route::post('/role/add', [UserController::class, 'rolestore'])->name('role.store');
    Route::post('/role/update', [UserController::class, 'rolesupdate'])->name('role.update');
    Route::post('/role/delete', [UserController::class, 'rolesdestroy'])->name('role.destroy');
    Route::post('/role/give-permission', [UserController::class, 'givePermission'])->name('role.givePermission');

    // Dashboard - Premission
    Route::get('/permission', [UserController::class, 'permissioncreate'])->name('permission.get');
    Route::post('/permission/add', [UserController::class, 'permissiontore'])->name('permission.store');
    Route::post('/permission/update', [UserController::class, 'permissionupdate'])->name('permission.update');
    Route::post('/permission/delete', [UserController::class, 'permissiondestroy'])->name('permission.destroy');
    // Dashboard - Users
    Route::get('/user', [UserController::class, 'usercreate'])->name('user.get');
    Route::post('/user/store', [UserController::class, 'userstore'])->name('users.store');
    Route::post('/user/aktiva', [UserController::class, 'usernonaktif'])->name('user.aktiva');
    Route::post('/user/giverole', [UserController::class, 'usersgiverole'])->name('user.giverole');
    Route::post('/user/destroy', [UserController::class, 'usersdestroy'])->name('user.destroy');

    // Dashboard - Web Seting
    Route::get('/web', [MonitorController::class, 'setingweb'])->name('web.get');
    Route::post('/web/update', [WebSettingController::class, 'update'])->name('web.update');
    Route::post('/web/satusehat', [WebSettingController::class, 'set_satusehat'])->name('web.update.satusehat');
    Route::post('/web/bpjs', [WebSettingController::class, 'set_bpjs'])->name('web.update.bpjs');
    Route::post('/web/toggle', [WebSettingController::class, 'updateToggle'])->name('web.update.toggle');
    Route::get('/web/toggle/states', [WebSettingController::class, 'getToggleStates'])->name('web.get.toggle.states');
    Route::post('/web/set-gudang-utama', [WebSettingController::class, 'setActiveGudangUtama'])->name('web.set.gudang.utama');
    Route::post('/web/reset-gudang-utama', [WebSettingController::class, 'resetActiveGudangUtama'])->name('web.reset.gudang.utama');
});

// Menu Staff
Route::prefix('staff')->group(function () {
    // Menu Pasien
    Route::get('/', [StaffController::class, 'staff'])->name('staff.get');
    Route::post('/add', [StaffController::class, 'staffadd'])->name('staff.store');
    Route::post('/delete', [StaffController::class, 'staffdelete'])->name('staff.destroy');
    Route::post('/verifikasi', [StaffController::class, 'staffverifikasi'])->name('staff.verifikasi');
    Route::post('/update', [StaffController::class, 'staffedit'])->name('staff.update');
});

// Menu Pendataan
Route::prefix('pendataan')->group(function () {
    Route::get('/antrian', [PendataanController::class,'pendataan_antrian'])->name('pendataan_antrian.get');
    Route::post('/print/antrian', [PendataanController::class,'print_antrian'])->name('print_antrian');

    Route::get('/pendaftaran', [PendataanController::class,'pendataan_pendaftaran'])->name('pendataan_pendaftaran.get');
    Route::post('/print/pendaftaran', [PendataanController::class,'print_pendaftaran'])->name('print_pendaftaran');

    Route::get('/soap-dokter', [PendataanController::class,'pendataan_dokter'])->name('pendataan_dokter.get');
    Route::post('/print/dokter', [PendataanController::class,'print_dokter'])->name('print_dokter');

    Route::get('/so-perawat', [PendataanController::class,'pendataan_perawat'])->name('pendataan_perawat.get');
    Route::post('/print/perawat', [PendataanController::class,'print_perawat'])->name('print_perawat');

    Route::get('/stok-penyesuaian', [PendataanController::class,'laporan_stok_penyesuaian'])->name('laporan_stok_penyesuaian.get');
    Route::post('/print/stok-penyesuaian', [PendataanController::class,'print_stok_penyesuaian'])->name('print_stok_penyesuaian');

    Route::get('/stok-opname', [PendataanController::class,'stok_opname'])->name('stok_opname.get');
    Route::post('/print/stok-opname', [PendataanController::class,'print_stok_opname'])->name('print_stok_opname');

    //Gudang utama
    Route::get('/gudang-utama', [GudangUtamaController::class,'laporan_gudang_utama'])->name('laporan_gudang_utama.get');
    Route::post('/print/gudang-utama', [GudangUtamaController::class,'print_gudang_utama'])->name('print_gudang_utama');
});


// Route untuk Superadmin
Route::get('/dashboard', [dashboard::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});





require __DIR__ . '/auth.php';
require __DIR__ . '/user.php';
