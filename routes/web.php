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
Route::get('/api/pendapatan-hari-ini', [dashboard::class, 'getPendapatanHariIni'])
    ->middleware(['auth', 'verified']);
Route::get('/api/pendapatan-bulanan', [dashboard::class, 'getPendapatanBulanan'])
    ->middleware(['auth', 'verified']);
Route::get('/api/pendapatan-detail', [dashboard::class, 'getPendapatanDetail'])
    ->middleware(['auth', 'verified']);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Route untuk Kasir
Route::get('/kasir', [SuperadminController::class, 'kasir'])->name('kasir');
Route::get('/kasir/pembayaran/{kode_faktur}', [SuperadminController::class, 'kasirPembayaran'])->name('kasir.pembayaran');
Route::post('/kasir/add', [SuperadminController::class, 'kasiradd'])->name('kasir.store');

//Menu Data Barang (Obat)
Route::get('/data-barang', [SuperadminController::class,'dabar'])->name('dabar.get');
Route::post('/data-barang/add', [SuperadminController::class,'dabaradd'])->name('dabar.store');
Route::post('/data-barang/update', [SuperadminController::class,'dabaredit'])->name('dabar.update');
Route::post('/data-barang/delete', [SuperadminController::class,'dabardelete'])->name('dabar.destroy');
Route::get('/data-barang/export', [SuperadminController::class, 'dabarexport'])->name('dabar.export');
Route::post('/data-barang/import', [SuperadminController::class, 'dabarimport'])->name('dabar.import');
//Koneksi antar database
    Route::get('/data-barang/singkron/{id}', [SuperadminController::class, 'dabarsingkron'])->name('dabar.singkron');

//Menu Pembelian Barang
Route::get('/pembelian', [SuperadminController::class, 'pembelian'])->name('pembelian.get');
Route::post('/pembelian/add', [SuperadminController::class, 'pembelianadd'])->name('pembelian.add');
Route::get('/pembelian/cetak/{nomor_faktur}', [SuperadminController::class, 'cetakPembelianPdf'])->name('pembelian.cetak');

Route::get('/monitor', [SuperadminController::class,'monitor'])->name('monitor.get');
Route::post('/monitor/add/bpjs', [SuperadminController::class,'monitor_bpjs'])->name('monitor.add.bpjs');
Route::post('/monitor/add/nobpjs', [SuperadminController::class,'monitor_nobpjs'])->name('monitor.add.nobpjs');
Route::get('/monitor/loket-antrian', [SuperadminController::class, 'loketAntrian'])->name('monitor.loket.antrian');

// Menu apotek
Route::get('/apotek', [SuperadminController::class, 'apotek'])->middleware(['auth'])->name('apotek.index');
Route::post('/apotek/add', [SuperadminController::class, 'apotekadd'])->name('apotek.store');

Route::post('/apotek/print-resep/dokter', [SuperadminController::class, 'resep_dokter'])->name('apotek.resep_dokter');
Route::post('/apotek/print-resep/revisi', [SuperadminController::class, 'resep_revisi'])->name('apotek.resep_revisi');

//Menu Keuangan
Route::get('/datakasir', [SuperadminController::class, 'datakasir_lunas'])->name('datakasir_lunas.index');
Route::post('/datakasir/print', [SuperadminController::class, 'datakasir_lunas_print'])->name('datakasir_lunas.print');

Route::get('/datakasir/detail', [SuperadminController::class, 'datakasir_detail'])->name('datakasir_detail.index');
Route::post('/datakasir/detail/print', [SuperadminController::class, 'datakasir_detail_print'])->name('datakasir_detail.print');

Route::get('/datakasir/apotek', [SuperadminController::class, 'datakasir_apotek'])->name('datakasir_apotek.index');
Route::post('/datakasir/apotek/print', [SuperadminController::class, 'datakasir_apotek_print'])->name('datakasir_apotek.print');

Route::get('/datakasir/tindakan', [SuperadminController::class, 'datakasir_tindakan'])->name('datakasir_tindakan.index');
Route::post('/datakasir/tindakan/print', [SuperadminController::class, 'datakasir_tindakan_print'])->name('datakasir_tindakan.print');

Route::get('/datakasir/diskon', [SuperadminController::class, 'datakasir_diskon'])->name('datakasir_diskon.index');
Route::post('/datakasir/diskon/print', [SuperadminController::class, 'datakasir_diskon_print'])->name('datakasir_diskon.print');

// Menu SDM
    // Menu Pasien
        Route::prefix('pasien')->group(function () {
            Route::get('/', [SuperadminController::class,'pasiens'])->name('pasien.get');
            Route::post('/add', [SuperadminController::class,'pasiensadd'])->name('pasien.store');
            Route::post('/verifikasi', [SuperadminController::class,'pasienvefiv'])->name('pasien.verifikasi');
            Route::post('/update', [SuperadminController::class,'pasienupdate'])->name('pasien.update');
        });
    //  Menu dokter
        Route::prefix('dokter')->group(function () {
            // Menu Pasien
            Route::get('/', [SuperadminController::class,'dokter'])->name('dokter.get');
            Route::post('/add', [SuperadminController::class,'dokteradd'])->name('dokter.store');
            Route::post('/delete', [SuperadminController::class,'dokterdelete'])->name('dokter.destroy');
            Route::post('/verifikasi', [SuperadminController::class,'dokterverifikasi'])->name('dokter.verifikasi');
            Route::post('/update', [SuperadminController::class,'dokteredit'])->name('dokter.update');
            Route::post('/jadwal/store', [SuperadminController::class, 'dokterjadwal'])->name('dokter.jadwal');
            Route::delete('/jadwal/hapus/{id}', [SuperadminController::class, 'dokterjadwalhapus']);
            Route::get('/sinkron-jadwal-dokter/{id}', [SuperadminController::class, 'jadwal_dokter'])->name('jadwal.sinkron');

        });
    // Menu staff
        Route::prefix('staff')->group(function () {
            // Menu Pasien
            Route::get('/', [SuperadminController::class,'staff'])->name('staff.get');
            Route::post('/add', [SuperadminController::class,'staffadd'])->name('staff.store');
            Route::post('/delete', [SuperadminController::class,'staffdelete'])->name('staff.destroy');
            Route::post('/verifikasi', [SuperadminController::class,'staffverifikasi'])->name('staff.verifikasi');
            Route::post('/update', [SuperadminController::class,'staffedit'])->name('staff.update');
        });

// Menu Pendaftaran
Route::prefix('pendaftaran')->group(function () {
    Route::get('/', [SuperadminController::class,'pendaftaran'])->name('pendaftaran.get');
    Route::post('/add', [SuperadminController::class,'pendaftaranadd'])->name('pendaftaran.add');
    Route::post('/batal', [SuperadminController::class,'pendaftaranbatal'])->name('pendaftaran.batal');
    Route::post('/dokterup', [SuperadminController::class,'pendaftaranupdokter'])->name('pendaftaran.dokter.update');
    Route::post('/hadir', [SuperadminController::class,'pendaftaranhadir'])->name('pendaftaran.hadir');

});

// Menu pemeriksaan
Route::prefix('pemeriksaan')->group(function () {
    Route::get('/dokter', [soap::class,'pelayana_dokter'])->name('pelayanad.get');
    Route::get('/dokter/so/{norawat}', [soap::class,'soappelayanan'])->name('pelayana_dokter.get');
    Route::post('/dokter/so/add', [soap::class,'soappelayananandd'])->name('pelayana_dokter.add');
    Route::get('/dokter/so/hadir/{norawat}', [soap::class,'soappelayananpanggil'])->name('pelayana_dokter.hadir');
    Route::get('/dokter/so/selesai/{norawat}', [soap::class,'soappelayananselesai'])->name('pelayana_dokter.selesai');
    Route::get('/rujuk/{norawat}', [soap::class,'pelayana_rujukan'])->name('pelayana_rujuk.get');
    Route::post('/rujuk/add', [soap::class,'pelayana_rujukan_add'])->name('pelayana_rujuk.add');

    Route::post('/rujuk', [soap::class,'pelayana_rujukanadd'])->name('pelayana_rujuk.add');
    Route::get('/rme/{norawat}', [soap::class, 'pelayana_rme'])->name('pelayana_rme.get');
    Route::get('/permintaan/{norawat}', [soap::class, 'pelayana_permintaan'])->name('pelayana_permintaan.get');
    Route::post('/resep/print', [soap::class, 'print'])->name('resep.print');
    Route::post('/laboratorium/print', [soap::class, 'laboratoriumPrint'])->name('laboratorium.print');
    Route::post('/radiologi/print', [soap::class, 'radiologiPrint'])->name('radiologi.print');
    Route::post('/skd/print', [soap::class, 'skdPrint'])->name('skd.print');
    Route::post('/dokter/so/odontogram/add', [soap::class, 'odontogramadd'])->name('odontogram.add');
    Route::post('/dokter/so/odontogram/details/add', [soap::class, 'odontogramdetailsadd'])->name('odontogram.details.add');

    // Menu Pasien
    Route::get('/perawat', [soap::class,'pelayana'])->name('pelayana.get');
    Route::get('/perawat/so/{norawat}', [soap::class,'sopelayanan'])->name('sopelayana.get');
    Route::post('/perawat/so/add', [soap::class,'sopelayanandd'])->name('sopelayana.add');
    Route::get('/perawat/so/hadir/{norawat}', [soap::class,'sopelayananpanggil'])->name('sopelayana.hadir');

});

//untuk list pasien
Route::middleware('auth')->prefix('pasien-selesai')->group(function () {
    Route::get('/', [soap::class, 'pelayana_selesai'])->name('list_pasien.get');
    Route::get('/rme/{norawat}', [soap::class, 'pelayana_rme_selesai'])->name('list_pasien_rme.get');
});

    Route::get('/list_pasien', [soap::class, 'list_pasien'])->name('list_pasien.get');
    Route::get('/rme/{norawat}', [soap::class, 'pelayana_rme_selesai'])->name('list_pasien_rme.get');

// Menu Laporan
Route::prefix('pendataan')->group(function () {
    Route::get('/antrian', [SuperadminController::class,'pendataan_antrian'])->name('pendataan_antrian.get');
    Route::post('/print/antrian', [SuperadminController::class,'print_antrian'])->name('print_antrian');

    Route::get('/pendaftaran', [SuperadminController::class,'pendataan_pendaftaran'])->name('pendataan_pendaftaran.get');
    Route::post('/print/pendaftaran', [SuperadminController::class,'print_pendaftaran'])->name('print_pendaftaran');

    Route::get('/soap-dokter', [SuperadminController::class,'pendataan_dokter'])->name('pendataan_dokter.get');
    Route::post('/print/dokter', [SuperadminController::class,'print_dokter'])->name('print_dokter');

    Route::get('/so-perawat', [SuperadminController::class,'pendataan_perawat'])->name('pendataan_perawat.get');
    Route::post('/print/perawat', [SuperadminController::class,'print_perawat'])->name('print_perawat');

    //Gudang utama
    Route::get('/gudang-utama', [DataMasterGudangController::class,'laporan_gudang_utama'])->name('laporan_gudang_utama.get');
    Route::post('/print/gudang-utama', [DataMasterGudangController::class,'print_gudang_utama'])->name('print_gudang_utama');
});

// Menu Inventaris
Route::get('/data-inventaris', [SuperadminController::class,'inventaris'])->name('inventaris.get');
Route::post('/data-inventaris/add', [SuperadminController::class,'inventarisadd'])->name('inventaris.store');
Route::post('/data-inventaris/update', [SuperadminController::class,'inventarisedit'])->name('inventaris.update');
Route::post('/data-inventaris/delete', [SuperadminController::class,'inventarisdelete'])->name('inventaris.destroy');
Route::get('/data-inventaris/export', [SuperadminController::class, 'inventarisexport'])->name('inventaris.export');
Route::post('/data-inventaris/import', [SuperadminController::class, 'inventarisimport'])->name('inventaris.import');
// Inventaris koneksi antar database
    Route::get('/data-inventaris/singkron/{id}', [SuperadminController::class, 'inventarissingkron'])->name('inventaris.singkron');

Route::get('/inventaris-pembelian', [SuperadminController::class, 'inventaris_pembelian'])->name('inventaris_pembelian.get');
Route::post('/inventaris-pembelian/add', [SuperadminController::class, 'inventaris_pembelianadd'])->name('inventaris_pembelian.add');

// Menu data master
    // Menu data master umum
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

            //bank
            Route::get('/bank', [DataMasterController::class,'bank'])->name('bank.get');
            Route::post('/bank/add', [DataMasterController::class,'bankadd'])->name('bank.store');
            Route::post('/bank/update', [DataMasterController::class,'bankedit'])->name('bank.update');
            Route::post('/bank/delete', [DataMasterController::class,'bankdelete'])->name('bank.destroy');
            Route::get('/bank/export', [DataMasterController::class,'bankexport'])->name('bank.export');
            Route::post('/bank/import', [DataMasterController::class,'bankimport'])->name('bank.import');

            //penjamin
            Route::get('/penjamin', [DataMasterController::class,'penjamin'])->name('penjamin.get');
            Route::post('/penjamin/add', [DataMasterController::class,'penjaminadd'])->name('penjamin.store');
            Route::post('/penjamin/update', [DataMasterController::class,'penjaminedit'])->name('penjamin.update');
            Route::post('/penjamin/delete', [DataMasterController::class,'penjamindelete'])->name('penjamin.destroy');
            Route::get('/penjamin/export', [DataMasterController::class,'penjaminexport'])->name('penjamin.export');
            Route::post('/penjamin/import', [DataMasterController::class,'penjaminimport'])->name('penjamin.import');

            //loket
            Route::get('/loket', [DataMasterController::class,'loket'])->name('loket.get');
            Route::post('/loket/add', [DataMasterController::class,'loketadd'])->name('loket.store');
            Route::post('/loket/update', [DataMasterController::class,'loketedit'])->name('loket.update');
            Route::post('/loket/delete', [DataMasterController::class,'loketdelete'])->name('loket.destroy');
            Route::get('/loket/export', [DataMasterController::class,'loketexport'])->name('loket.export');
            Route::post('/loket/import', [DataMasterController::class,'loketimport'])->name('loket.import');
        });
    // Menu data master medis
        Route::middleware('auth')->prefix('data-master-medis')->group(function () {
            // Menu Poli
            Route::get('/poli', [DataMasterMedisController::class,'poli'])->name('poli.get');
            Route::get('/poli/sync', [DataMasterMedisController::class,'poliadd'])->name('poli.sync');
            Route::post('/poli/delete', [DataMasterMedisController::class,'polidelete'])->name('poli.destroy');
            Route::get('/poli/export', [DataMasterMedisController::class, 'poliexport'])->name('poli.export');
            Route::post('/poli/import', [DataMasterMedisController::class, 'poliimport'])->name('poli.import');

            Route::get('/sarana', [DataMasterMedisController::class,'sarana'])->name('sarana.get');
            Route::get('/sarana/sync', [DataMasterMedisController::class,'saranaadd'])->name('sarana.sync');
            Route::post('/sarana/delete', [DataMasterMedisController::class,'saranadelete'])->name('sarana.destroy');
            Route::get('/sarana/export', [DataMasterMedisController::class, 'saranaexport'])->name('sarana.export');
            Route::post('/sarana/import', [DataMasterMedisController::class, 'saranaimport'])->name('sarana.import');

            // Menu Spesialis
            Route::get('/spesialis', [DataMasterMedisController::class,'spesialis'])->name('spesialis.get');
            Route::get('/spesialis/sync', [DataMasterMedisController::class,'spesialisadd'])->name('spesialis.sync');
            Route::post('/spesialis/delete', [DataMasterMedisController::class,'spesialisdelete'])->name('spesialis.destroy');
            Route::get('/spesialis/export', [DataMasterMedisController::class, 'spesialisexport'])->name('spesialis.export');
            Route::post('/spesialis/import', [DataMasterMedisController::class, 'spesialisimport'])->name('spesialis.import');


            Route::get('/subspesialis/{kode}', [DataMasterMedisController::class,'subspesialis'])->name('subspesialis.get');
            Route::get('/subspesialis/sync/{kode}', [DataMasterMedisController::class,'subspesialisadd'])->name('subspesialis.sync');
            Route::post('/subspesialis/delete', [DataMasterMedisController::class,'subspesialisdelete'])->name('subspesialis.destroy');

            // Kategori Perawatan
            Route::get('/katper', [DataMasterMedisController::class,'kategori_perawatan'])->name('kategori_perawatan.get');
            Route::post('/katper/add', [DataMasterMedisController::class,'kategori_perawatanadd'])->name('kategori_perawatan.store');
            Route::post('/katper/update', [DataMasterMedisController::class,'kategori_perawatanedit'])->name('kategori_perawatan.update');
            Route::post('/katper/delete', [DataMasterMedisController::class,'kategori_perawatandelete'])->name('kategori_perawatan.destroy');
            Route::get('/katper/export', [DataMasterMedisController::class,'kategori_perawatanexport'])->name('kategori_perawatan.export');
            Route::post('/katper/import', [DataMasterMedisController::class,'kategori_perawatanimport'])->name('kategori_perawatan.import');

            // Perawatan dan Tindakan
            Route::get('/perawatan-tindakan', [DataMasterMedisController::class,'perawatan_tindakan'])->name('perawatan_tindakan.get');
            Route::post('/perawatan-tindakan/add', [DataMasterMedisController::class,'perawatan_tindakanadd'])->name('perawatan_tindakan.store');
            Route::post('/perawatan-tindakan/update', [DataMasterMedisController::class,'perawatan_tindakanedit'])->name('perawatan_tindakan.update');
            Route::post('/perawatan-tindakan/delete', [DataMasterMedisController::class,'perawatan_tindakandelete'])->name('perawatan_tindakan.destroy');
            Route::get('/perawatan-tindakan/export', [DataMasterMedisController::class,'perawatan_tindakanexport'])->name('perawatan_tindakan.export');
            Route::post('/perawatan-tindakan/import', [DataMasterMedisController::class,'perawatan_tindakanimport'])->name('perawatan_tindakan.import');

            Route::get('/htt-pemeriksaan', [DataMasterMedisController::class,'htt_pemeriksaan'])->name('htt_pemeriksaan.get');
            Route::post('/htt_pemeriksaan/add', [DataMasterMedisController::class,'htt_pemeriksaanadd'])->name('htt_pemeriksaan.store');
            Route::post('/htt_pemeriksaan/update', [DataMasterMedisController::class,'htt_pemeriksaanedit'])->name('htt_pemeriksaan.update');
            Route::post('/htt_pemeriksaan/delete', [DataMasterMedisController::class,'htt_pemeriksaandelete'])->name('htt_pemeriksaan.destroy');
            Route::get('/htt_pemeriksaan/export', [DataMasterMedisController::class,'htt_pemeriksaanexport'])->name('htt_pemeriksaan.export');
            Route::post('/htt_pemeriksaan/import', [DataMasterMedisController::class,'htt_pemeriksaaneimport'])->name('htt_pemeriksaan.import');

            Route::get('/htt_sub_pemeriksaan/{kode}', [DataMasterMedisController::class,'htt_sub_pemeriksaan'])->name('htt_sub_pemeriksaan.get');
            Route::post('/htt_sub_pemeriksaan/add', [DataMasterMedisController::class,'htt_sub_pemeriksaanadd'])->name('htt_sub_pemeriksaan.store');
            Route::post('/htt_sub_pemeriksaan/update', [DataMasterMedisController::class,'htt_sub_pemeriksaanedit'])->name('htt_sub_pemeriksaan.update');
            Route::post('/htt_sub_pemeriksaan/delete', [DataMasterMedisController::class,'htt_sub_pemeriksaandelete'])->name('htt_sub_pemeriksaan.destroy');

            Route::get('/alergi', [DataMasterMedisController::class,'alergi'])->name('alergi.get');
            Route::post('/alergi/add', [DataMasterMedisController::class,'alergiadd'])->name('alergi.store');
            Route::post('/alergi/delete', [DataMasterMedisController::class,'alergidelete'])->name('alergi.destroy');

            // Menu jenis_diet
            Route::get('/jenis-diet', [DataMasterMedisController::class,'jenis_diet'])->name('jenis_diet.get');
            Route::post('/jenis-diet/add', [DataMasterMedisController::class,'jenis_dietadd'])->name('jenis_diet.store');
            Route::post('/jenis-diet/update', [DataMasterMedisController::class,'jenis_dietedit'])->name('jenis_diet.update');
            Route::post('/jenis-diet/delete', [DataMasterMedisController::class,'jenis_dietdelete'])->name('jenis_diet.destroy');
            Route::get('/jenis-diet/export', [DataMasterMedisController::class,'jenis_dietexport'])->name('jenis_diet.export');
            Route::post('/jenis-diet/import', [DataMasterMedisController::class,'jenis_dietimport'])->name('jenis_diet.import');

            // Menu nama_
            Route::get('/nama-makanan', [DataMasterMedisController::class, 'nama_makanan'])->name('nama_makanan.get');
            Route::post('/nama-makanan/add', [DataMasterMedisController::class,'nama_makananadd'])->name('nama_makanan.store');
            Route::post('/nama-makanan/update', [DataMasterMedisController::class,'nama_makananedit'])->name('nama_makanan.update');
            Route::post('/nama-makanan/delete', [DataMasterMedisController::class,'nama_makanandelete'])->name('nama_makanan.destroy');
            Route::get('/nama-makanan/export', [DataMasterMedisController::class,'nama_makananexport'])->name('nama_makanan.export');
            Route::post('/nama-makanan/import', [DataMasterMedisController::class,'nama_makananimport'])->name('nama_makanan.import');

            Route::get('/icd10', [DataMasterMedisController::class,'icd10'])->name('icd10.get');
            Route::post('/icd10/sync', [DataMasterMedisController::class,'icd10singkron'])->name('icd10.singkron');
            Route::post('/icd10/add', [DataMasterMedisController::class,'icd10add'])->name('icd10.store');
            Route::post('/icd10/update', [DataMasterMedisController::class,'icd10edit'])->name('icd10.update');
            Route::post('/icd10/delete', [DataMasterMedisController::class,'icd10delete'])->name('icd10.destroy');
            Route::get('/icd10/export', [DataMasterMedisController::class, 'icd10export'])->name('icd10.export');
            Route::post('/icd10/import', [DataMasterMedisController::class, 'icd10import'])->name('icd10.import');

            Route::get('/icd9', [DataMasterMedisController::class, 'icd9'])->name('icd9.get');
            Route::post('/icd9/add', [DataMasterMedisController::class,'icd9add'])->name('icd9.store');
            Route::post('/icd9/update', [DataMasterMedisController::class,'icd9edit'])->name('icd9.update');
            Route::post('/icd9/delete', [DataMasterMedisController::class,'icd9delete'])->name('icd9.destroy');
            Route::get('/icd9/export', [DataMasterMedisController::class,'icd9export'])->name('icd9.export');
            Route::post('/icd9/import', [DataMasterMedisController::class,'icd9import'])->name('icd9.import');

            //radiologi jenis
            Route::get('/radiologi_jenis', [DataMasterMedisController::class, 'radiologi_jenis'])->name('radiologi_jenis.get');
            Route::post('/radiologi_jenis/add', [DataMasterMedisController::class,'radiologi_jenisadd'])->name('radiologi_jenis.store');
            Route::post('/radiologi_jenis/update', [DataMasterMedisController::class,'radiologi_jenisedit'])->name('radiologi_jenis.update');
            Route::post('/radiologi_jenis/delete', [DataMasterMedisController::class,'radiologi_jenisdelete'])->name('radiologi_jenis.destroy');
            Route::get('/radiologi_jenis/export', [DataMasterMedisController::class,'radiologi_jenisexport'])->name('radiologi_jenis.export');
            Route::post('/radiologi_jenis/import', [DataMasterMedisController::class,'radiologi_jenisimport'])->name('radiologi_jenis.import');

            Route::get('/bidang-lab', [DataMasterMedisController::class, 'laboratorium_bidang'])->name('laboratorium_bidang.get');
            Route::post('/bidang-lab/add', [DataMasterMedisController::class,'laboratorium_bidangadd'])->name('laboratorium_bidang.store');
            Route::post('/bidang-lab/update', [DataMasterMedisController::class,'laboratorium_bidangedit'])->name('laboratorium_bidang.update');
            Route::post('/bidang-lab/delete', [DataMasterMedisController::class,'laboratorium_bidangdelete'])->name('laboratorium_bidang.destroy');
            Route::get('/bidang-lab/export', [DataMasterMedisController::class,'laboratorium_bidangexport'])->name('laboratorium_bidang.export');
            Route::post('/bidang-lab/import', [DataMasterMedisController::class,'laboratorium_bidangeimport'])->name('laboratorium_bidang.import');

            Route::get('/bidang-lab-sub/{kode}', [DataMasterMedisController::class,'laboratorium_bidang_sub'])->name('laboratorium_bidang_sub.get');
            Route::post('/bidang-lab-sub/add', [DataMasterMedisController::class,'laboratorium_bidang_subadd'])->name('laboratorium_bidang_sub.store');
            Route::post('/bidang-lab-sub/update', [DataMasterMedisController::class,'laboratorium_bidang_subedit'])->name('laboratorium_bidang_sub.update');
            Route::post('/bidang-lab-sub/delete', [DataMasterMedisController::class,'laboratorium_bidang_subdelete'])->name('laboratorium_bidang_sub.destroy');

            Route::get('/radiologi_pemeriksaan', [DataMasterMedisController::class, 'radiologi_pemeriksaan'])->name('radiologi_pemeriksaan.get');
            Route::post('/radiologi_pemeriksaan/add', [DataMasterMedisController::class,'radiologi_pemeriksaanadd'])->name('radiologi_pemeriksaan.store');
            Route::post('/radiologi_pemeriksaan/update', [DataMasterMedisController::class,'radiologi_pemeriksaanedit'])->name('radiologi_pemeriksaan.update');
            Route::post('/radiologi_pemeriksaan/delete', [DataMasterMedisController::class,'radiologi_pemeriksaandelete'])->name('radiologi_pemeriksaan.destroy');
            Route::get('/radiologi_pemeriksaan/export', [DataMasterMedisController::class,'radiologi_pemeriksaanexport'])->name('radiologi_pemeriksaan.export');
            Route::post('/radiologi_pemeriksaan/import', [DataMasterMedisController::class,'radiologi_pemeriksaaneimport'])->name('radiologi_pemeriksaan.import');

        });
    // Menu data master Gudang
        Route::prefix('data-master-gudang')->group(function () {
            // Menu Jenis Satuan
            Route::get('/satuan', [DataMasterGudangController::class,'satuan'])->name('satuan.get');
            Route::post('/satuan/add', [DataMasterGudangController::class,'satuanadd'])->name('satuan.store');
            Route::post('/satuan/update', [DataMasterGudangController::class,'satuanedit'])->name('satuan.update');
            Route::post('/satuan/delete', [DataMasterGudangController::class,'satuandelete'])->name('satuan.destroy');
            Route::get('/satuan/export', [DataMasterGudangController::class, 'satuanexport'])->name('satuan.export');
            Route::post('/satuan/import', [DataMasterGudangController::class, 'satuanimport'])->name('satuan.import');

            // Menu Jenis Kategori
            Route::get('/kategori', [DataMasterGudangController::class,'kategori'])->name('kategori.get');
            Route::post('/kategori/add', [DataMasterGudangController::class,'kategoriadd'])->name('kategori.store');
            Route::post('/kategori/update', [DataMasterGudangController::class,'kategoriedit'])->name('kategori.update');
            Route::post('/kategori/delete', [DataMasterGudangController::class,'kategoridelete'])->name('kategori.destroy');
            Route::get('/kategori/export', [DataMasterGudangController::class, 'kategoriexport'])->name('kategori.export');
            Route::post('/kategori/import', [DataMasterGudangController::class, 'kategoriimport'])->name('kategori.import');

            // Menu Supplier
            Route::get('/supplier-industri', [DataMasterGudangController::class,'supplier'])->name('supplier.get');
            Route::post('/supplier-industri/add', [DataMasterGudangController::class,'supplieradd'])->name('supplier.store');
            Route::post('/supplier-industri/update', [DataMasterGudangController::class,'supplieredit'])->name('supplier.update');
            Route::post('/supplier-industri/delete', [DataMasterGudangController::class,'supplierdelete'])->name('supplier.destroy');
            Route::get('/supplier-industri/export', [DataMasterGudangController::class, 'supplierexport'])->name('supplier.export');
            Route::post('/supplier-industri/import', [DataMasterGudangController::class, 'supplierimport'])->name('supplier.import');

            // Menu Setting Harga
            Route::get('/setting-harga-jual', [DataMasterGudangController::class,'setharga'])->name('setharga.get');
            Route::post('/setting-harga-jual/add', [DataMasterGudangController::class,'sethargaadd'])->name('setharga.store');
            Route::get('/setting-harga-jual/singkron/{id}', [DataMasterGudangController::class, 'sethargasingkron'])->name('setharga.singkron');
            Route::get('/harga-barang-jual', [DataMasterGudangController::class,'hargajual'])->name('hargajual.get');
            Route::get('/stok-obat-alkes', [DataMasterGudangController::class,'stokobatalkes'])->name('stokobatalkes.get');

            // Menu Satuan Inventaris
            Route::get('/satuan-inventaris', [DataMasterGudangController::class,'satuan_inventaris'])->name('satuan_inventaris.get');
            Route::post('/satuan-inventaris/add', [DataMasterGudangController::class,'satuan_inventarisadd'])->name('satuan_inventaris.store');
            Route::post('/satuan-inventaris/update', [DataMasterGudangController::class,'satuan_inventarisedit'])->name('satuan_inventaris.update');
            Route::post('/satuan-inventaris/delete', [DataMasterGudangController::class,'satuan_inventarisdelete'])->name('satuan_inventaris.destroy');
            Route::get('/satuan-inventaris/export', [DataMasterGudangController::class, 'satuan_inventarisexport'])->name('satuan_inventaris.export');
            Route::post('/satuan-inventaris/import', [DataMasterGudangController::class, 'satuan_inventarisimport'])->name('satuan_inventaris.import');

            // Menu Kategori Inventaris
            Route::get('/kategori-inventaris', [DataMasterGudangController::class,'katin'])->name('katin.get');
            Route::post('/kategori-inventaris/add', [DataMasterGudangController::class,'katinadd'])->name('katin.store');
            Route::post('/kategori-inventaris/update', [DataMasterGudangController::class,'katinedit'])->name('katin.update');
            Route::post('/kategori-inventaris/delete', [DataMasterGudangController::class,'katindelete'])->name('katin.destroy');
            Route::get('/kategori-inventaris/export', [DataMasterGudangController::class,'katinexport'])->name('katin.export');
            Route::post('/kategori-inventaris/import', [DataMasterGudangController::class,'katinimport'])->name('katin.import');

            // Menu Stok Inventaris
            Route::get('/stok-inventaris', [DataMasterGudangController::class,'stokin'])->name('stokin.get');
            Route::get('/stok-inventaris/data/{id}', [DataMasterGudangController::class,'stokin_data'])->name('stokin_data.get');
            Route::post('/stok-inventaris/data/update', [DataMasterGudangController::class,'stokin_dataedit'])->name('stokin_data.update');
            Route::post('/stok-inventaris/data/delete', [DataMasterGudangController::class,'stokin_datadelete'])->name('stokin_data.destroy');

            // Menu Request Obat Klinik Omega
            Route::get('/inventaris-request', [DataMasterGudangController::class, 'inventarisrequest'])->name('inventarisrequest.get');
            Route::post('/inventaris-request/add', [DataMasterGudangController::class, 'inventarisrequestadd'])->name('inventarisrequest.store');

            // Menu Utama Klinik Omega
            Route::get('/inventaris-utama', [DataMasterGudangController::class, 'inventarisutama'])->name('inventarisutama.get');
            Route::post('/inventaris-utama/konfirmasi', [DataMasterGudangController::class, 'inventarisutamakonfirmasi'])->name('inventarisutama.konfirmasi');

            // Menu Request Obat Klinik Omega
            Route::get('/gudang-request', [DataMasterGudangController::class, 'gudangrequest'])->name('gudangrequest.get');
            Route::post('/gudang-request/add', [DataMasterGudangController::class, 'gudangrequestadd'])->name('gudangrequest.store');

            // Menu Utama Klinik Omega
            Route::get('/gudang-utama', [DataMasterGudangController::class, 'gudangutama'])->name('gudangutama.get');
            Route::post('/gudang-utama/konfirmasi', [DataMasterGudangController::class, 'gudangutamakonfirmasi'])->name('gudangutama.konfirmasi');

        });
    // Menu data master manajemen
        Route::middleware('auth')->prefix('data-master-manajemen')->group(function () {
            // Menu Poli
            Route::get('/posker', [DataMasterManajemenController::class,'posisi_kerja'])->name('posker.get');
            Route::post('/posker/add', [DataMasterManajemenController::class,'posisi_kerjaadd'])->name('posker.store');
            Route::post('/posker/update', [DataMasterManajemenController::class,'posisi_kerjaedit'])->name('posker.update');
            Route::post('/posker/delete', [DataMasterManajemenController::class,'posisi_kerjadelete'])->name('posker.destroy');
            Route::get('/posker/export', [DataMasterManajemenController::class, 'posisi_kerjaexport'])->name('posker.export');
            Route::post('/posker/import', [DataMasterManajemenController::class, 'posisi_kerjaimport'])->name('posker.import');

        });

// Menu Setting
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
    Route::post('/user/store', [SuperadminController::class,'userstore'])->name('users.store');
    Route::post('/user/aktiva', [SuperadminController::class,'usernonaktif'])->name('user.aktiva');
    Route::post('/user/giverole', [SuperadminController::class,'usersgiverole'])->name('user.giverole');
    Route::post('/user/destroy', [SuperadminController::class,'usersdestroy'])->name('user.destroy');

    // Dashboard - Web Seting
    Route::get('/web', [SuperadminController::class,'setingweb'])->name('web.get');
    Route::post('/web/update', [WebSettingController::class, 'update'])->name('web.update');
    Route::post('/web/satusehat', [WebSettingController::class, 'set_satusehat'])->name('web.update.satusehat');
    Route::post('/web/bpjs', [WebSettingController::class, 'set_bpjs'])->name('web.update.bpjs');

});
// Route untuk panggil pasien
Route::post('/pasien/panggil/{id}', [App\Http\Controllers\SuperadminController::class, 'panggilPasien'])->name('pasien.panggil');


require __DIR__.'/auth.php';
require __DIR__.'/user.php';










