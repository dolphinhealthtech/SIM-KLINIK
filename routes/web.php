<?php

use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\DataMasterManajemenController;
use App\Http\Controllers\DataMasterMedisController;
use App\Http\Controllers\DataMasterGudangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\soap;
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

// Route untuk Kasir
Route::get('/kasir', [SuperadminController::class, 'kasir'])->name('kasir');
Route::get('/kasir_detail', [App\Http\Controllers\SuperadminController::class, 'kasirDetail'])->name('kasir.detail');

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

// Menu apotek
Route::get('/apotek', [SuperadminController::class, 'apotek'])->middleware(['auth'])->name('apotek.index');
Route::post('/apotek/add', [SuperadminController::class, 'apotekadd'])->name('apotek.store');

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
    Route::get('/dokter', [soap::class,'pelayana_dokter'])->name('pelayana_dokter.get');
    Route::get('/dokter/so/{norawat}', [soap::class,'soappelayanan'])->name('pelayana_dokter.get');
    Route::post('/dokter/so/add', [soap::class,'soappelayananandd'])->name('pelayana_dokter.add');
    Route::get('/dokter/so/hadir/{norawat}', [soap::class,'soappelayananpanggil'])->name('pelayana_dokter.hadir');
    Route::get('/rujuk/{norawat}', [soap::class,'pelayana_rujukan'])->name('pelayana_rujuk.get');
    // Menu Pasien
    Route::get('/perawat', [soap::class,'pelayana'])->name('pelayana.get');
    Route::get('/perawat/so/{norawat}', [soap::class,'sopelayanan'])->name('sopelayana.get');
    Route::post('/perawat/so/add', [soap::class,'sopelayanandd'])->name('sopelayana.add');
    Route::get('/perawat/so/hadir/{norawat}', [soap::class,'sopelayananpanggil'])->name('sopelayana.hadir');

});

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

            // Route untuk menampilkan SOAP Rawat Jalan
            Route::get('/pelayanan/soap-rawat-jalan', [soap::class, 'dataLrawatJalan'])->name('soap.rawat.jalan');

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



require __DIR__.'/auth.php';
require __DIR__.'/user.php';









