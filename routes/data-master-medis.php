<?php

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
use App\Http\Controllers\DataMaster\medis\SubSpesialisController;
use Illuminate\Support\Facades\Route;

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


    Route::get('/subspesialis/{kode}', [SubSpesialisController::class, 'subspesialis'])->name('subspesialis.get');
    Route::get('/subspesialis/sync/{kode}', [SubSpesialisController::class, 'subspesialisadd'])->name('subspesialis.sync');
    Route::post('/subspesialis/delete', [SubSpesialisController::class, 'subspesialisdelete'])->name('subspesialis.destroy');

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
