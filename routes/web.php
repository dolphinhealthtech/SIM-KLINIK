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
use App\Http\Controllers\DataMaster\main\AsuransiController;
use App\Http\Controllers\DataMaster\main\PenjaminController;
use App\Http\Controllers\DataMaster\main\LoketController;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Agama_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Asuransi_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Bahasa_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Bangsa_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Bank_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Goldar_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Kelamin_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Loket_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Pekerjaan_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Pendidikan_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Penjamin_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Pernikahan_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Umum\Suku_Controller;
use App\Http\Controllers\soap;
use App\Http\Controllers\Soap\OdoController;
use App\Http\Controllers\Soap\PelayananController;
use App\Http\Controllers\Soap\RujukanController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\SuperAdmin\ApotekController;
use App\Http\Controllers\SuperAdmin\DabarController;
use App\Http\Controllers\SuperAdmin\DokterController;
use App\Http\Controllers\Module\SDM\Dokter\Dokter_Controller;
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

use App\Http\Controllers\Module\Pasien\Pasien_Controller;
use App\Http\Controllers\Module\Pendaftaran\Pendaftaran_Controller;
use App\Http\Controllers\Module\Pelayanan\Pelayanan_Dokter_Controller;
use App\Http\Controllers\Module\Pelayanan\Pelayanan_Perawat_Controller;
use App\Http\Controllers\Module\SDM\Staff\Staff_Controller;

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
Route::get('/data-barang-internal/singkron', [DabarController::class, 'dabarsingkroninternal'])->name('dabar.singkron.internal');

// Menu Inventaris
Route::get('/data-inventaris', [InventarisController::class, 'inventaris'])->name('inventaris.get');
Route::post('/data-inventaris/add', [InventarisController::class, 'inventarisadd'])->name('inventaris.store');
Route::post('/data-inventaris/update', [InventarisController::class, 'inventarisedit'])->name('inventaris.update');
Route::post('/data-inventaris/delete', [InventarisController::class, 'inventarisdelete'])->name('inventaris.destroy');
Route::get('/data-inventaris/export', [InventarisController::class, 'inventarisexport'])->name('inventaris.export');
Route::post('/data-inventaris/import', [InventarisController::class, 'inventarisimport'])->name('inventaris.import');
// Inventaris koneksi antar database
Route::get('/data-inventaris/singkron/{id}', [InventarisController::class, 'inventarissingkron'])->name('inventaris.singkron');
Route::get('/data-inventaris-internal/singkron/', [InventarisController::class, 'inventarissingkroninternal'])->name('inventaris.singkron.internal');

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
    Route::get('/setting-harga-jual-klinik/singkron', [SetHargaController::class, 'sethargasingkronklinik'])->name('setharga_klinik.singkron');
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

// Menu Data Master Medis



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




// Menu Kasir
Route::get('/kasir', [KasirController::class, 'kasir'])->name('kasir');
Route::get('/kasir/pembayaran/{kode_faktur}', [KasirController::class, 'kasirPembayaran'])->name('kasir.pembayaran');
Route::post('/kasir/add', [KasirController::class, 'kasiradd'])->name('kasir.store');




// Endpoint JSON untuk polling monitor loket (tanpa reload)
Route::get('/monitor/loket-antrian/data', [MonitorController::class, 'loketAntrianData'])->name('monitor.loket.antrian.data');




Route::post('/pasien/panggil/{id}', [PasienController::class, 'panggilPasien'])->name('pasien.panggil');

// Menu Pembelian
Route::get('/pembelian', [PembelianController::class, 'pembelian'])->name('pembelian.get');
Route::post('/pembelian/add', [PembelianController::class, 'pembelianadd'])->name('pembelian.add');
Route::get('/pembelian/cetak/{nomor_faktur}', [PembelianController::class, 'cetakPembelianPdf'])->name('pembelian.cetak');





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



// Menu Pendataan
Route::middleware('auth')->prefix('pendataan')->group(function () {
    Route::get('/antrian', [PendataanController::class, 'pendataan_antrian'])->name('pendataan_antrian.get');
    Route::post('/print/antrian', [PendataanController::class, 'print_antrian'])->name('print_antrian');

    Route::get('/pendaftaran', [PendataanController::class, 'pendataan_pendaftaran'])->name('pendataan_pendaftaran.get');
    Route::post('/print/pendaftaran', [PendataanController::class, 'print_pendaftaran'])->name('print_pendaftaran');

    Route::get('/soap-dokter', [PendataanController::class, 'pendataan_dokter'])->name('pendataan_dokter.get');
    Route::post('/print/dokter', [PendataanController::class, 'print_dokter'])->name('print_dokter');

    Route::get('/so-perawat', [PendataanController::class, 'pendataan_perawat'])->name('pendataan_perawat.get');
    Route::post('/print/perawat', [PendataanController::class, 'print_perawat'])->name('print_perawat');

    Route::get('/stok-penyesuaian', [PendataanController::class, 'laporan_stok_penyesuaian'])->name('laporan_stok_penyesuaian.get');
    Route::post('/print/stok-penyesuaian', [PendataanController::class, 'print_stok_penyesuaian'])->name('print_stok_penyesuaian');

    Route::get('/stok-opname', [PendataanController::class, 'stok_opname'])->name('stok_opname.get');
    Route::post('/print/stok-opname', [PendataanController::class, 'print_stok_opname'])->name('print_stok_opname');

    //Gudang utama
    Route::get('/gudang-utama', [GudangUtamaController::class, 'laporan_gudang_utama'])->name('laporan_gudang_utama.get');
    Route::post('/print/gudang-utama', [GudangUtamaController::class, 'print_gudang_utama'])->name('print_gudang_utama');
});


// Group route dashboard berdasarkan role agar lebih rapi

// Route untuk Superadmin (akses umum dashboard)
Route::get('/dashboard', [dashboard::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


Route::get('/rujukan/cetak/{no_rawat}', [App\Http\Controllers\Soap\RujukanController::class, 'cetakSuratRujukan'])->name('rujukan.cetak');





// sudah Fix

// Menu Dokter
Route::prefix('sdm')->group(function () {
    Route::prefix('dokter')->group(function () {
        // Menu Pasien
        Route::get('/', [Dokter_Controller::class, 'dokter'])->name('dokter.get');
        Route::post('/add', [Dokter_Controller::class, 'dokteradd'])->name('dokter.store');
        Route::post('/delete', [Dokter_Controller::class, 'dokterdelete'])->name('dokter.destroy');
        Route::post('/verifikasi', [Dokter_Controller::class, 'dokterverifikasi'])->name('dokter.verifikasi');
        Route::post('/update', [Dokter_Controller::class, 'dokteredit'])->name('dokter.update');
        Route::post('/jadwal/store', [Dokter_Controller::class, 'dokterjadwal'])->name('dokter.jadwal');
        Route::delete('/jadwal/hapus/{id}', [Dokter_Controller::class, 'dokterjadwalhapus']);
        Route::get('/sinkron-jadwal-dokter/{id}', [Dokter_Controller::class, 'jadwal_dokter'])->name('jadwal.sinkron');
    });
    //
    // Menu Staff
    Route::middleware('auth')->prefix('staff')->group(function () {
        // Menu Pasien
        Route::get('/', [Staff_Controller::class, 'staff'])->name('staff.get');
        Route::post('/add', [Staff_Controller::class, 'staffadd'])->name('staff.store');
        Route::post('/delete', [Staff_Controller::class, 'staffdelete'])->name('staff.destroy');
        Route::post('/verifikasi', [Staff_Controller::class, 'staffverifikasi'])->name('staff.verifikasi');
        Route::post('/update', [Staff_Controller::class, 'staffedit'])->name('staff.update');
    });
});

// Menu Pelayanan Pemeriksaan
Route::middleware('auth')->prefix('pasien-pelayanan')->group(function () {
    Route::prefix('dokter')->group(function () {
        Route::get('/', [Pelayanan_Dokter_Controller::class, 'pelayana_dokter'])->name('pelayanad.get');
        Route::get('/so/hadir/{norawat}', [Pelayanan_Dokter_Controller::class, 'soappelayananpanggil'])->name('pelayana_dokter.hadir');  //api
        Route::get('/so/{norawat}', [Pelayanan_Dokter_Controller::class, 'soappelayanan'])->name('pelayana_dokter.get');
        Route::get('/so/edit/{norawat}', [PelayananController::class, 'soappelayananedit'])->name('pelayana_dokter.edit');
        Route::post('/so/add', [Pelayanan_Dokter_Controller::class, 'soappelayananandd'])->name('pelayana_dokter.add');
        Route::post('/so/update', [Pelayanan_Dokter_Controller::class, 'soappelayananupdate'])->name('pelayana_dokter.update');
        Route::get('/so/selesai/{norawat}', [Pelayanan_Dokter_Controller::class, 'soappelayananselesai'])->name('pelayana_dokter.selesai');

        Route::post('/so/odontogram/add', [OdoController::class, 'odontogramadd'])->name('odontogram.add'); //api
        Route::post('/so/odontogram/details/add', [OdoController::class, 'odontogramdetailsadd'])->name('odontogram.details.add'); //api


        Route::prefix('surat')->group(function () {
            Route::get('/permintaan/{norawat}', [Pelayanan_Dokter_Controller::class, 'pelayana_permintaan'])->name('pelayana_permintaan.get');
            Route::get('/permintaan-rujuk/{norawat}', [Pelayanan_Dokter_Controller::class, 'pelayana_rujukan'])->name('pelayana_rujuk.get');
            Route::post('/permintaan-rujuk/add', [Pelayanan_Dokter_Controller::class, 'pelayana_rujukan_add'])->name('pelayana_rujuk.add');
            Route::post('/permintaan-sakit/print', [PelayananController::class, 'permintaanSakitPrint'])->name('permintaan.sakit.print'); //api
            Route::post('/permintaan-sehat/print', [PelayananController::class, 'permintaanSehatPrint'])->name('permintaan.sehat.print'); //api
            Route::post('/permintaan-kematian/print', [PelayananController::class, 'permintaanKematianPrint'])->name('permintaan.kematian.print'); //api
            Route::post('/resep/print', [PelayananController::class, 'print'])->name('resep.print');
            Route::post('/permintaan-laboratorium/print', [PelayananController::class, 'laboratoriumPrint'])->name('laboratorium.print');
            Route::post('/permintaan-radiologi/print', [PelayananController::class, 'radiologiPrint'])->name('radiologi.print');
            Route::post('/skd/print', [PelayananController::class, 'skdPrint'])->name('skd.print');
        });
    });
    // Menu Pasien
    Route::prefix('perawat')->group(function () {
        Route::get('/', [Pelayanan_Perawat_Controller::class, 'pelayana'])->name('pelayana.get');
        Route::get('/so/hadir/{norawat}', [Pelayanan_Perawat_Controller::class, 'sopelayananpanggil'])->name('sopelayana.hadir'); //api
        Route::get('/so/{norawat}', [Pelayanan_Perawat_Controller::class, 'sopelayanan'])->name('sopelayana.get');
        Route::get('/so/edit/{norawat}', [Pelayanan_Perawat_Controller::class, 'sopelayananedit'])->name('sopelayana.edit');
        Route::post('/so/add', [Pelayanan_Perawat_Controller::class, 'sopelayanandd'])->name('sopelayana.add');
        Route::post('/so/update', [Pelayanan_Perawat_Controller::class, 'sopelayananupdate'])->name('sopelayana.update');
    });
});

// Menu data Master
Route::middleware('auth')->prefix('data-master')->group(function () {
    Route::prefix('umum')->group(function () {

        Route::prefix('goldar')->group(function () {
            Route::get('/', [Goldar_Controller::class, 'darah'])->name('goldar.get');
            Route::post('/add', [Goldar_Controller::class, 'darahadd'])->name('goldar.store');
            Route::post('/update', [Goldar_Controller::class, 'darahedit'])->name('goldar.update');
            Route::post('/delete', [Goldar_Controller::class, 'darahdelete'])->name('goldar.destroy');
            Route::get('/export', [Goldar_Controller::class, 'darahexport'])->name('goldar.export');
            Route::post('/import', [Goldar_Controller::class, 'darahimport'])->name('goldar.import');
        });

        Route::prefix('suku')->group(function () {
            Route::get('/', [Suku_Controller::class, 'suku'])->name('suku.get');
            Route::post('/add', [Suku_Controller::class, 'sukuadd'])->name('suku.store');
            Route::post('/update', [Suku_Controller::class, 'sukuedit'])->name('suku.update');
            Route::post('/delete', [Suku_Controller::class, 'sukudelete'])->name('suku.destroy');
            Route::get('/export', [Suku_Controller::class, 'sukuexport'])->name('suku.export');
            Route::post('/import', [Suku_Controller::class, 'sukuimport'])->name('suku.import');
        });

        Route::prefix('bangsa')->group(function () {
            Route::get('/', [Bangsa_Controller::class, 'bangsa'])->name('bangsa.get');
            Route::post('/add', [Bangsa_Controller::class, 'bangsaadd'])->name('bangsa.store');
            Route::post('/update', [Bangsa_Controller::class, 'bangsaedit'])->name('bangsa.update');
            Route::post('/delete', [Bangsa_Controller::class, 'bangsadelete'])->name('bangsa.destroy');
            Route::get('/export', [Bangsa_Controller::class, 'bangsaexport'])->name('bangsa.export');
            Route::post('/import', [Bangsa_Controller::class, 'bangsaimport'])->name('bangsa.import');
        });

        Route::prefix('bahasa')->group(function () {
            Route::get('/', [Bahasa_Controller::class, 'bahasa'])->name('bahasa.get');
            Route::post('/add', [Bahasa_Controller::class, 'bahasaadd'])->name('bahasa.store');
            Route::post('/update', [Bahasa_Controller::class, 'bahasaedit'])->name('bahasa.update');
            Route::post('/delete', [Bahasa_Controller::class, 'bahasadelete'])->name('bahasa.destroy');
            Route::get('/export', [Bahasa_Controller::class, 'bahasaexport'])->name('bahasa.export');
            Route::post('/import', [Bahasa_Controller::class, 'bahasaimport'])->name('bahasa.import');
        });

        Route::prefix('agama')->group(function () {
            Route::get('/', [Agama_Controller::class, 'agama'])->name('agama.get');
            Route::post('/add', [Agama_Controller::class, 'agamaadd'])->name('agama.store');
            Route::post('/update', [Agama_Controller::class, 'agamaedit'])->name('agama.update');
            Route::post('/delete', [Agama_Controller::class, 'agamadelete'])->name('agama.destroy');
            Route::get('/export', [Agama_Controller::class, 'agamaexport'])->name('agama.export');
            Route::post('/import', [Agama_Controller::class, 'agamaimport'])->name('agama.import');
        });

        Route::prefix('pendidikan')->group(function () {
            Route::get('/', [Pendidikan_Controller::class, 'pendidikan'])->name('pendidikan.get');
            Route::post('/add', [Pendidikan_Controller::class, 'pendidikanadd'])->name('pendidikan.store');
            Route::post('/update', [Pendidikan_Controller::class, 'pendidikanedit'])->name('pendidikan.update');
            Route::post('/delete', [Pendidikan_Controller::class, 'pendidikandelete'])->name('pendidikan.destroy');
            Route::get('/export', [Pendidikan_Controller::class, 'pendidikanexport'])->name('pendidikan.export');
            Route::post('/import', [Pendidikan_Controller::class, 'pendidikanimport'])->name('pendidikan.import');
        });

        Route::prefix('kelamin')->group(function () {
            Route::get('/', [Kelamin_Controller::class, 'kelamin'])->name('kelamin.get');
            Route::post('/add', [Kelamin_Controller::class, 'kelaminadd'])->name('kelamin.store');
            Route::post('/update', [Kelamin_Controller::class, 'kelaminedit'])->name('kelamin.update');
            Route::post('/delete', [Kelamin_Controller::class, 'kelamindelete'])->name('kelamin.destroy');
            Route::get('/export', [Kelamin_Controller::class, 'kelaminexport'])->name('kelamin.export');
            Route::post('/import', [Kelamin_Controller::class, 'kelaminimport'])->name('kelamin.import');
        });

        Route::prefix('pernikahan')->group(function () {
            Route::get('/', [Pernikahan_Controller::class, 'pernikahan'])->name('pernikahan.get');
            Route::post('/add', [Pernikahan_Controller::class, 'pernikahanadd'])->name('pernikahan.store');
            Route::post('/update', [Pernikahan_Controller::class, 'pernikahanedit'])->name('pernikahan.update');
            Route::post('/delete', [Pernikahan_Controller::class, 'pernikahandelete'])->name('pernikahan.destroy');
            Route::get('/export', [Pernikahan_Controller::class, 'pernikahanexport'])->name('pernikahan.export');
            Route::post('/import', [Pernikahan_Controller::class, 'pernikahanimport'])->name('pernikahan.import');
        });

        Route::prefix('pekerjaan')->group(function () {
            Route::get('/', [Pekerjaan_Controller::class, 'pekerjaan'])->name('pekerjaan.get');
            Route::post('/add', [Pekerjaan_Controller::class, 'pekerjaanadd'])->name('pekerjaan.store');
            Route::post('/update', [Pekerjaan_Controller::class, 'pekerjaanedit'])->name('pekerjaan.update');
            Route::post('/delete', [Pekerjaan_Controller::class, 'pekerjaandelete'])->name('pekerjaan.destroy');
            Route::get('/export', [Pekerjaan_Controller::class, 'pekerjaanexport'])->name('pekerjaan.export');
            Route::post('/import', [Pekerjaan_Controller::class, 'pekerjaanimport'])->name('pekerjaan.import');
        });

        Route::prefix('bank')->group(function () {
            Route::get('/', [Bank_Controller::class, 'bank'])->name('bank.get');
            Route::post('/add', [Bank_Controller::class, 'bankadd'])->name('bank.store');
            Route::post('/update', [Bank_Controller::class, 'bankedit'])->name('bank.update');
            Route::post('/delete', [Bank_Controller::class, 'bankdelete'])->name('bank.destroy');
            Route::get('/export', [Bank_Controller::class, 'bankexport'])->name('bank.export');
            Route::post('/import', [Bank_Controller::class, 'bankimport'])->name('bank.import');
        });

        Route::prefix('asuransi')->group(function () {
            Route::get('/', [Asuransi_Controller::class, 'asuransi'])->name('asuransi.get');
            Route::post('/add', [Asuransi_Controller::class, 'asuransiadd'])->name('asuransi.store');
            Route::post('/update', [Asuransi_Controller::class, 'asuransiedit'])->name('asuransi.update');
            Route::post('/delete', [Asuransi_Controller::class, 'asuransidelete'])->name('asuransi.destroy');
            Route::get('/export', [Asuransi_Controller::class, 'asuransiexport'])->name('asuransi.export');
            Route::post('/import', [Asuransi_Controller::class, 'asuransiimport'])->name('asuransi.import');
        });

        Route::prefix('penjamin')->group(function () {
            Route::get('/', [Penjamin_Controller::class, 'penjamin'])->name('penjamin.get');
            Route::post('/add', [Penjamin_Controller::class, 'penjaminadd'])->name('penjamin.store');
            Route::post('/update', [Penjamin_Controller::class, 'penjaminedit'])->name('penjamin.update');
            Route::post('/delete', [Penjamin_Controller::class, 'penjamindelete'])->name('penjamin.destroy');
            Route::get('/export', [Penjamin_Controller::class, 'penjaminexport'])->name('penjamin.export');
            Route::post('/import', [Penjamin_Controller::class, 'penjaminimport'])->name('penjamin.import');
        });

        Route::prefix('loket')->group(function () {
            Route::get('/', [Loket_Controller::class, 'loket'])->name('loket.get');
            Route::post('/add', [Loket_Controller::class, 'loketadd'])->name('loket.store');
            Route::post('/update', [Loket_Controller::class, 'loketedit'])->name('loket.update');
            Route::post('/delete', [Loket_Controller::class, 'loketdelete'])->name('loket.destroy');
            Route::get('/export', [Loket_Controller::class, 'loketexport'])->name('loket.export');
            Route::post('/import', [Loket_Controller::class, 'loketimport'])->name('loket.import');
        });
    });

    Route::prefix('medis')->group(function () {

        Route::prefix('poli')->group(function () {
            Route::get('/', [PoliController::class, 'poli'])->name('poli.get');
            Route::get('/sync', [PoliController::class, 'poliadd'])->name('poli.sync');
            Route::post('/delete', [PoliController::class, 'polidelete'])->name('poli.destroy');
            Route::get('/export', [PoliController::class, 'poliexport'])->name('poli.export');
            Route::post('/import', [PoliController::class, 'poliimport'])->name('poli.import');
        });

        Route::prefix('sarana')->group(function () {
            Route::get('/', [SaranaController::class, 'sarana'])->name('sarana.get');
            Route::get('/sync', [SaranaController::class, 'saranaadd'])->name('sarana.sync');
            Route::post('/delete', [SaranaController::class, 'saranadelete'])->name('sarana.destroy');
            Route::get('/export', [SaranaController::class, 'saranaexport'])->name('sarana.export');
            Route::post('/import', [SaranaController::class, 'saranaimport'])->name('sarana.import');
        });

        Route::prefix('spesialis')->group(function () {
            Route::get('/', [SpesialisController::class, 'spesialis'])->name('spesialis.get');
            Route::get('/sync', [SpesialisController::class, 'spesialisadd'])->name('spesialis.sync');
            Route::post('/delete', [SpesialisController::class, 'spesialisdelete'])->name('spesialis.destroy');
            Route::get('/export', [SpesialisController::class, 'spesialisexport'])->name('spesialis.export');
            Route::post('/import', [SpesialisController::class, 'spesialisimport'])->name('spesialis.import');
        });

        Route::prefix('subspesialis')->group(function () {
            Route::get('/{kode}', [SubspesialisController::class, 'subspesialis'])->name('subspesialis.get');
            Route::get('/sync/{kode}', [SubspesialisController::class, 'subspesialisadd'])->name('subspesialis.sync');
            Route::post('/delete', [SubspesialisController::class, 'subspesialisdelete'])->name('subspesialis.destroy');
        });

        Route::prefix('katper')->group(function () {
            Route::get('/', [KategoriPerawatanController::class, 'kategori_perawatan'])->name('kategori_perawatan.get');
            Route::post('/add', [KategoriPerawatanController::class, 'kategori_perawatanadd'])->name('kategori_perawatan.store');
            Route::post('/update', [KategoriPerawatanController::class, 'kategori_perawatanedit'])->name('kategori_perawatan.update');
            Route::post('/delete', [KategoriPerawatanController::class, 'kategori_perawatandelete'])->name('kategori_perawatan.destroy');
            Route::get('/export', [KategoriPerawatanController::class, 'kategori_perawatanexport'])->name('kategori_perawatan.export');
            Route::post('/import', [KategoriPerawatanController::class, 'kategori_perawatanimport'])->name('kategori_perawatan.import');
        });

        Route::prefix('perawatan-tindakan')->group(function () {
            Route::get('/', [PerawatanTindakanController::class, 'perawatan_tindakan'])->name('perawatan_tindakan.get');
            Route::post('/add', [PerawatanTindakanController::class, 'perawatan_tindakanadd'])->name('perawatan_tindakan.store');
            Route::post('/update', [PerawatanTindakanController::class, 'perawatan_tindakanedit'])->name('perawatan_tindakan.update');
            Route::post('/delete', [PerawatanTindakanController::class, 'perawatan_tindakandelete'])->name('perawatan_tindakan.destroy');
            Route::get('/export', [PerawatanTindakanController::class, 'perawatan_tindakanexport'])->name('perawatan_tindakan.export');
            Route::post('/import', [PerawatanTindakanController::class, 'perawatan_tindakanimport'])->name('perawatan_tindakan.import');
        });

        Route::prefix('htt-pemeriksaan')->group(function () {
            Route::get('/', [PemeriksaanHttController::class, 'htt_pemeriksaan'])->name('htt_pemeriksaan.get');
            Route::post('/add', [PemeriksaanHttController::class, 'htt_pemeriksaanadd'])->name('htt_pemeriksaan.store');
            Route::post('/update', [PemeriksaanHttController::class, 'htt_pemeriksaanedit'])->name('htt_pemeriksaan.update');
            Route::post('/delete', [PemeriksaanHttController::class, 'htt_pemeriksaandelete'])->name('htt_pemeriksaan.destroy');
            Route::get('/export', [PemeriksaanHttController::class, 'htt_pemeriksaanexport'])->name('htt_pemeriksaan.export');
            Route::post('/import', [PemeriksaanHttController::class, 'htt_pemeriksaaneimport'])->name('htt_pemeriksaan.import');
        });

        Route::prefix('htt_sub_pemeriksaan')->group(function () {
            Route::get('/{kode}', [PemeriksaanHttController::class, 'htt_sub_pemeriksaan'])->name('htt_sub_pemeriksaan.get');
            Route::post('/add', [PemeriksaanHttController::class, 'htt_sub_pemeriksaanadd'])->name('htt_sub_pemeriksaan.store');
            Route::post('/update', [PemeriksaanHttController::class, 'htt_sub_pemeriksaanedit'])->name('htt_sub_pemeriksaan.update');
            Route::post('/delete', [PemeriksaanHttController::class, 'htt_sub_pemeriksaandelete'])->name('htt_sub_pemeriksaan.destroy');
        });

        Route::prefix('alergi')->group(function () {
            Route::get('/', [AlergiController::class, 'alergi'])->name('alergi.get');
            Route::post('/add', [AlergiController::class, 'alergiadd'])->name('alergi.store');
            Route::post('/delete', [AlergiController::class, 'alergidelete'])->name('alergi.destroy');
        });

        Route::prefix('jenis-diet')->group(function () {
            Route::get('/', [JenisDietController::class, 'jenis_diet'])->name('jenis_diet.get');
            Route::post('/add', [JenisDietController::class, 'jenis_dietadd'])->name('jenis_diet.store');
            Route::post('/update', [JenisDietController::class, 'jenis_dietedit'])->name('jenis_diet.update');
            Route::post('/delete', [JenisDietController::class, 'jenis_dietdelete'])->name('jenis_diet.destroy');
            Route::get('/export', [JenisDietController::class, 'jenis_dietexport'])->name('jenis_diet.export');
            Route::post('/import', [JenisDietController::class, 'jenis_dietimport'])->name('jenis_diet.import');
        });

        Route::prefix('nama-makanan')->group(function () {
            Route::get('/', [NamaMakananController::class, 'nama_makanan'])->name('nama_makanan.get');
            Route::post('/add', [NamaMakananController::class, 'nama_makananadd'])->name('nama_makanan.store');
            Route::post('/update', [NamaMakananController::class, 'nama_makananedit'])->name('nama_makanan.update');
            Route::post('/delete', [NamaMakananController::class, 'nama_makanandelete'])->name('nama_makanan.destroy');
            Route::get('/export', [NamaMakananController::class, 'nama_makananexport'])->name('nama_makanan.export');
            Route::post('/import', [NamaMakananController::class, 'nama_makananimport'])->name('nama_makanan.import');
        });

        Route::prefix('icd10')->group(function () {
            Route::get('/', [Icd10Controller::class, 'icd10'])->name('icd10.get');
            Route::post('/sync', [Icd10Controller::class, 'icd10singkron'])->name('icd10.singkron');
            Route::post('/add', [Icd10Controller::class, 'icd10add'])->name('icd10.store');
            Route::post('/update', [Icd10Controller::class, 'icd10edit'])->name('icd10.update');
            Route::post('/delete', [Icd10Controller::class, 'icd10delete'])->name('icd10.destroy');
            Route::get('/export', [Icd10Controller::class, 'icd10export'])->name('icd10.export');
            Route::post('/import', [Icd10Controller::class, 'icd10import'])->name('icd10.import');
        });

        Route::prefix('icd9')->group(function () {
            Route::get('/', [Icd9Controller::class, 'icd9'])->name('icd9.get');
            Route::post('/add', [Icd9Controller::class, 'icd9add'])->name('icd9.store');
            Route::post('/update', [Icd9Controller::class, 'icd9edit'])->name('icd9.update');
            Route::post('/delete', [Icd9Controller::class, 'icd9delete'])->name('icd9.destroy');
            Route::get('/export', [Icd9Controller::class, 'icd9export'])->name('icd9.export');
            Route::post('/import', [Icd9Controller::class, 'icd9import'])->name('icd9.import');
        });

        Route::prefix('radiologi_jenis')->group(function () {
            Route::get('/', [RadiologiJenisController::class, 'radiologi_jenis'])->name('radiologi_jenis.get');
            Route::post('/add', [RadiologiJenisController::class, 'radiologi_jenisadd'])->name('radiologi_jenis.store');
            Route::post('/update', [RadiologiJenisController::class, 'radiologi_jenisedit'])->name('radiologi_jenis.update');
            Route::post('/delete', [RadiologiJenisController::class, 'radiologi_jenisdelete'])->name('radiologi_jenis.destroy');
            Route::get('/export', [RadiologiJenisController::class, 'radiologi_jenisexport'])->name('radiologi_jenis.export');
            Route::post('/import', [RadiologiJenisController::class, 'radiologi_jenisimport'])->name('radiologi_jenis.import');
        });

        Route::prefix('bidang-lab')->group(function () {
            Route::get('/', [LaboratoriumBidangController::class, 'laboratorium_bidang'])->name('laboratorium_bidang.get');
            Route::post('/add', [LaboratoriumBidangController::class, 'laboratorium_bidangadd'])->name('laboratorium_bidang.store');
            Route::post('/update', [LaboratoriumBidangController::class, 'laboratorium_bidangedit'])->name('laboratorium_bidang.update');
            Route::post('/delete', [LaboratoriumBidangController::class, 'laboratorium_bidangdelete'])->name('laboratorium_bidang.destroy');
            Route::get('/export', [LaboratoriumBidangController::class, 'laboratorium_bidangexport'])->name('laboratorium_bidang.export');
            Route::post('/import', [LaboratoriumBidangController::class, 'laboratorium_bidangeimport'])->name('laboratorium_bidang.import');
        });

        Route::prefix('bidang-lab-sub')->group(function () {
            Route::get('/{kode}', [LaboratoriumBidangController::class, 'laboratorium_bidang_sub'])->name('laboratorium_bidang_sub.get');
            Route::post('/add', [LaboratoriumBidangController::class, 'laboratorium_bidang_subadd'])->name('laboratorium_bidang_sub.store');
            Route::post('/update', [LaboratoriumBidangController::class, 'laboratorium_bidang_subedit'])->name('laboratorium_bidang_sub.update');
            Route::post('/delete', [LaboratoriumBidangController::class, 'laboratorium_bidang_subdelete'])->name('laboratorium_bidang_sub.destroy');
        });

        Route::prefix('radiologi_pemeriksaan')->group(function () {
            Route::get('/', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaan'])->name('radiologi_pemeriksaan.get');
            Route::post('/add', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaanadd'])->name('radiologi_pemeriksaan.store');
            Route::post('/update', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaanedit'])->name('radiologi_pemeriksaan.update');
            Route::post('/delete', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaandelete'])->name('radiologi_pemeriksaan.destroy');
            Route::get('/export', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaanexport'])->name('radiologi_pemeriksaan.export');
            Route::post('/import', [RadiologiPemeriksaanController::class, 'radiologi_pemeriksaaneimport'])->name('radiologi_pemeriksaan.import');
        });
    });

    Route::prefix('manajemen')->group(function () {
        // Menu Poli
        Route::prefix('posker')->group(function () {
            Route::get('/', [DataMasterManajemenController::class, 'posisi_kerja'])->name('posker.get');
            Route::post('/add', [DataMasterManajemenController::class, 'posisi_kerjaadd'])->name('posker.store');
            Route::post('/update', [DataMasterManajemenController::class, 'posisi_kerjaedit'])->name('posker.update');
            Route::post('/delete', [DataMasterManajemenController::class, 'posisi_kerjadelete'])->name('posker.destroy');
            Route::get('/export', [DataMasterManajemenController::class, 'posisi_kerjaexport'])->name('posker.export');
            Route::post('/import', [DataMasterManajemenController::class, 'posisi_kerjaimport'])->name('posker.import');
        });
    });
});

// Menu data Pasien
Route::middleware(['auth'])->prefix('data-pasien')->group(function () {
    Route::get('/', [Pasien_Controller::class, 'pasiens'])->name('pasien.get');
    Route::get('/time-line/{norm}', [Pasien_Controller::class, 'pasiens_time_line'])->name('pasiens_time_line.get');
    Route::post('/add', [Pasien_Controller::class, 'pasiensadd'])->name('pasien.store');
    Route::post('/verifikasi', [Pasien_Controller::class, 'pasienvefiv'])->name('pasien.verifikasi');
    Route::post('/update', [Pasien_Controller::class, 'pasienupdate'])->name('pasien.update');
});

// Menu Pendaftaran Online
Route::prefix('pendaftaran-online')->group(function () {
    Route::get('/', [MonitorController::class, 'monitor'])->name('pendaftaran-online.get');
    Route::post('/add/bpjs', [MonitorController::class, 'monitor_bpjs'])->name('pendaftaran-online.add.bpjs');
    Route::post('/add/nobpjs', [MonitorController::class, 'monitor_nobpjs'])->name('pendaftaran-online.add.nobpjs');
    Route::post('/add/pasien', [Pasien_Controller::class, 'pasiensadd'])->name('pendaftaran-online.add.pasien');
});

// Menu Pendaftaran Offline
Route::middleware('auth')->prefix('pendaftaran-offline')->group(function () {
    Route::get('/', [Pendaftaran_Controller::class, 'pendaftaran'])->name('pendaftaran.get');
    Route::post('/add', [Pendaftaran_Controller::class, 'pendaftaranadd'])->name('pendaftaran.add');
    Route::post('/batal', [Pendaftaran_Controller::class, 'pendaftaranbatal'])->name('pendaftaran.batal');
    Route::post('/batal/pcare', [Pendaftaran_Controller::class, 'pendaftaranbatalpcare'])->name('pendaftaran.batal.pcare');
    Route::post('/dokterup', [Pendaftaran_Controller::class, 'pendaftaranupdokter'])->name('pendaftaran.dokter.update');
    Route::post('/hadir', [Pendaftaran_Controller::class, 'pendaftaranhadir'])->name('pendaftaran.hadir');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/user.php';
