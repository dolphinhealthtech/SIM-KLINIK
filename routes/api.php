<?php

use App\Http\Controllers\Brijing_Intergrasi\Mobile_Jkn_Controller;
use App\Http\Controllers\dashboard;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PcareController;
use App\Http\Controllers\SatusehatController;
use App\Http\Controllers\SuperAdmin\ApotekController;
use App\Http\Controllers\SuperAdmin\DabarController;
use App\Http\Controllers\SuperAdmin\DokterController;
use App\Http\Controllers\SuperAdmin\InventarisController;
use App\Http\Controllers\SuperAdmin\KasirController;
use App\Http\Controllers\SuperAdmin\PasienController;
use App\Http\Controllers\SuperAdmin\PembelianController;
use App\Http\Controllers\SuperAdmin\PendaftaranController;
use App\Http\Controllers\SuperAdmin\StaffController;
use App\Http\Controllers\DataMaster\gudang\GudangRequestController;
use App\Http\Controllers\DataMaster\gudang\GudangUtamaController;
use App\Http\Controllers\DataMaster\gudang\StokBarangController;
use App\Http\Controllers\DataMaster\gudang\SupplierController;
use App\Http\Controllers\DataMaster\inventaris\InventarisRequestController;
use App\Http\Controllers\DataMaster\inventaris\InventarisUtamaController;
use App\Http\Controllers\DataMaster\medis\PerawatanTindakanController;
use App\Http\Controllers\Soap\OdoController;
use App\Http\Controllers\Soap\PelayananController;
use App\Http\Controllers\Soap\RujukanController;
use App\Http\Controllers\Mobile_JknController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;
use App\Http\Controllers\Brijing_Intergrasi\Satusehat_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Gudang\Request\Gudang_Request_Api_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Gudang\Stok_Barang\Stok_Barang_Api_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Gudang\Stok_Barang\Stok_Barang_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Gudang\Stok_Barang\Stok_Barang_Utama_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Gudang\Supplier\Supplier_Api_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Gudang\Utama\Gudang_Utama_Api_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Inventaris\Request\Inventaris_Request_Api_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Inventaris\Utama\Inventaris_Utama_Api_Controller;
use App\Http\Controllers\Module\Data_Master\Data_Medis\Perawatan_Tindakan\Perawatan_Tindakan_Api_Controller;
use App\Http\Controllers\Module\Pasien\Pasien_Api_Controller;
use App\Http\Controllers\Module\Pelayanan\Pelayanan_Api_Controller;
use App\Http\Controllers\Module\Pendaftaran\Pendaftaran_Api_Controller;
use App\Http\Controllers\Module\Pendaftaran\Pendaftaran_Api_Online_Controller;
use App\Http\Controllers\Module\SDM\Dokter\Dokter_Api_Controller;
use App\Http\Controllers\Module\SDM\Staff\Staff_Api_Controller;



//pasien
Route::prefix('pasien')->group(function () {
    Route::get('/{id}', [Pasien_Api_Controller::class, 'get_pasien'])->name('search.pasien');
    Route::post('/nikornoka', [Pasien_Api_Controller::class, 'search_nik_noka'])->name('search.noka');
    Route::post('/nikornama', [Pasien_Api_Controller::class, 'search_nik_nama'])->name('search.nik');
});

//pendaftaran online
Route::prefix('pendaftaran-online')->group(function () {
    Route::get('/dokter-poli/{id}', [Pendaftaran_Api_Online_Controller::class, 'getByPoli'])->name('search.dokter.poli');
});

//pendaftaran offline
Route::prefix('pendaftaran-offline')->group(function () {
    Route::get('/dokter-poli/{id}', [Pendaftaran_Api_Controller::class, 'getByPoli'])->name('search.dokter.poli.offline');
});
//pelayanan
Route::prefix('pasien-pelayanan')->group(function () {
    //perawat
    Route::prefix('dokter')->group(function () {
        Route::get('/hadir/{norawat}', [Pelayanan_Api_Controller::class, 'soappelayananpanggil'])->name('soappelayana.hadir');
        Route::get('/alergi/{id}', [Pelayanan_Api_Controller::class, 'getByJenis'])->name('soappelayana.alerhi');
        Route::get('/sub-pemeriksaan/{id}', [Pelayanan_Api_Controller::class, 'getSubPemeriksaan'])->name('soappelayana.htt');
        Route::post('/odontogram/load-details', [Pelayanan_Api_Controller::class, 'odontogramdetailsload'])->name('soappelayana.odo');
        Route::get('/odontogram/load', [Pelayanan_Api_Controller::class, 'odontogramload'])->name('soappelayana.lod.odo');
        Route::post('/odontogram/details/add', [Pelayanan_Api_Controller::class, 'odontogramdetailsadd'])->name('soappelayana.add.deti.odo');
        Route::get('/so/{norawat}', [Pelayanan_Api_Controller::class, 'soappelayanandata'])->name('soappelayana.pelayana_data');
        Route::post('/resep/print', [Pelayanan_Api_Controller::class, 'print'])->name('soappelayana.resep.print');
    });
    Route::prefix('perawat')->group(function () {
        Route::get('/hadir/{norawat}', [Pelayanan_Api_Controller::class, 'sopelayananpanggil'])->name('sopelayana.hadir');
        Route::get('/alergi/{id}', [Pelayanan_Api_Controller::class, 'getByJenis'])->name('sopelayana.alerhi');
        Route::get('/sub-pemeriksaan/{id}', [Pelayanan_Api_Controller::class, 'getSubPemeriksaan'])->name('sopelayana.htt');
    });
});












//dokter
Route::get('/get-dokter/{id}', [Dokter_Api_Controller::class, 'getDokter']);
Route::get('/get-dokter-all/{id}', [Dokter_Api_Controller::class, 'getDokterEdit']);
Route::get('/jadwal/json/{id}', [Dokter_Api_Controller::class, 'dokterjadwaljson']);
Route::get('/sinkron-jadwal-dokter/{id}', [Dokter_Api_Controller::class, 'jadwal_dokter'])->name('jadwal.sinkron');

//staff
Route::get('/get-staff/{id}', [Staff_Api_Controller::class, 'getStaff']);
Route::get('/get-staff-all/{id}', [Staff_Api_Controller::class, 'getStaffEdit']);





//pelayanan perawat


//pelayanan dokter




Route::post('/so/odontogram/add', [Pelayanan_Api_Controller::class, 'odontogramadd'])->name('odontogram.add');

Route::get('/dokter/data/so/edit/{norawat}', [Pelayanan_Api_Controller::class, 'soappelayanandataedit'])->name('pelayana_dokter_data_edit.get');
Route::get('/dokter/data/so/icd/{norawat}', [Pelayanan_Api_Controller::class, 'soappelayanandataicd'])->name('pelayana_dokter_data_icd.get');
Route::get('/dokter/data/so/diet/{norawat}', [Pelayanan_Api_Controller::class, 'soappelayanandatadiet'])->name('pelayana_dokter_data_diet.get');
Route::get('/dokter/data/so/obat/{norawat}', [Pelayanan_Api_Controller::class, 'soappelayanandataobat'])->name('pelayana_dokter_data_obat.get');
Route::get('/dokter/data/so/tindakan/{norawat}', [Pelayanan_Api_Controller::class, 'soappelayanandatatindakan'])->name('pelayana_dokter_data_tindakan.get');
Route::get('/get-subspesialis/{kode}', [Pelayanan_Api_Controller::class, 'getSubSpesialis']);
Route::get('/rujukan/cetak/{no_rawat}', [Pelayanan_Api_Controller::class, 'cetakSuratRujukan'])->name('rujukan.cetak');






Route::get('/generate-kode-data-barang', [DabarController::class, 'generateKodeDataBarang'])->name('generateKodeDataBarang');
Route::get('/generate-kode-data-barang-utama', [DabarController::class, 'generateKodeDataBarangUtama'])->name('generateKodeDataBarangUtama');
Route::get('/generate-faktur-pembelian', [PembelianController::class, 'generateFakturPembelian'])->name('generateFakturPembelian');
Route::get('/generate-kode-inventaris', [InventarisController::class, 'generateKodeInventaris'])->name('generateKodeInventaris');
Route::get('/generate-kode-pembelian-inventaris', [InventarisController::class, 'generatePembelianInventaris'])->name('generatePembelianInventaris');







Route::get('/get-pemeriksaan-laboratorium/{id}', [Pelayanan_Api_Controller::class, 'getSubBidangLab']);





Route::prefix('lokasi')->group(function () {
    Route::get('/kabupaten', [LokasiController::class, 'getKabupaten'])->name('get.kabupaten');
    Route::get('/kecamatan', [LokasiController::class, 'getKecamatan'])->name('get.kecamatan');
    Route::get('/kelurahan', [LokasiController::class, 'getKelurahan'])->name('get.kelurahan');
});
Route::prefix('satusehat')->group(function () {
    Route::get('/token', [Satusehat_Controller::class, 'get_token'])->name('satusehat.token'); // di privat fungsi nya
    Route::get('/nik/{nomor}', [Satusehat_Controller::class, 'get_nik_satusehat'])->name('satusehat.nik'); // di privat fungsi nya
    Route::get('/nik-practitioner/{nomor}', [Satusehat_Controller::class, 'get_nik_practitioner_satusehat'])->name('satusehat.nik_practitione'); // di privat fungsi nya
    Route::get('/kfa/{nama}', [Satusehat_Controller::class, 'get_kfa_satusehat'])->name('satusehat.kfa'); // di privat fungsi nya
});
Route::prefix('pcare')->group(function () {
    // pcare
    Route::get('/token', [Pcare_Controller::class, 'get_token'])->name('pcare.token'); // di privat fungsi nya
    Route::get('/noka/{nomor}', [Pcare_Controller::class, 'get_noka_bpjs'])->name('pcare.noka');
    Route::get('/nik/{nomor}', [Pcare_Controller::class, 'get_nik_bpjs'])->name('pcare.nik');
    Route::get('/poli', [Pcare_Controller::class, 'get_poli_fktp_bpjs'])->name('pcare.poli');
    Route::get('/dokter', [Pcare_Controller::class, 'get_dokter_bpjs'])->name('pcare.dokter');
    Route::get('/spesialis', [Pcare_Controller::class, 'get_spesialis_bpjs'])->name('pcare.spesialis');
    Route::get('/sub-spesialis/{nama}', [Pcare_Controller::class, 'get_sub_spesialis_bpjs'])->name('pcare.subspesialis');
    Route::get('/diagnosis/{nama}', [Pcare_Controller::class, 'get_diagnosis_bpjs'])->name('pcare.diagnosis');
    Route::get('/statpul/{nama}', [Pcare_Controller::class, 'get_statpul_bpjs'])->name('pcare.statpul');
    Route::get('/kesadaran', [Pcare_Controller::class, 'get_kesadaran_bpjs'])->name('pcare.kesadaran');
    Route::get('/provider', [Pcare_Controller::class, 'get_provider_bpjs'])->name('pcare.provider');
    Route::get('/khusus', [Pcare_Controller::class, 'get_khusus_bpjs'])->name('pcare.khusus');
    Route::get('/dpho/{nama}', [Pcare_Controller::class, 'get_dphoobat_bpjs'])->name('pcare.dpho');
    Route::get('/prognosa', [Pcare_Controller::class, 'get_prognosa_bpjs'])->name('pcare.prognosa');
    Route::get('/alergi/{kode}', [Pcare_Controller::class, 'get_alergi_bpjs'])->name('pcare.alergi');
    Route::get('/sarana', [Pcare_Controller::class, 'get_sarana_bpjs'])->name('pcare.sarana');
    Route::get('/provide_rujuk/{spesialis}/{sarana}/{tanggal}', [Pcare_Controller::class, 'get_rujukan_spesialis_bpjs'])->name('pcare.provide_rujuk');
    Route::get('/provide_rujuk_husus/{spesialis}/{noKartu}/{tanggal}', [Pcare_Controller::class, 'get_rujukan_husus_bpjs'])->name('pcare.provide_rujuk_husus');
    Route::get('/provide_rujuk_husus_subspesialis/{husus}/{spesialis}/{noKartu}/{tanggal}', [Pcare_Controller::class, 'get_rujukan_husus_subspesialis_bpjs'])->name('pcare.provide_rujuk_husus_subspesialis');
    Route::get('/jadwal/{kodepoli}/{tanggal}', [Pcare_Controller::class, 'get_jadwal_dokter_bpjs'])->name('pcare.jadwal');
    Route::get('/poli/{tanggal}', [Pcare_Controller::class, 'get_ws_poli_bpjs'])->name('pcare.poli_ws');
    Route::get('/get-dekrip-bpjs', [Pcare_Controller::class, 'bpjs_dekrip'])->name('pcare.dekrip_bpjs');
    Route::get('/get-pendaftaran-nomor/{nomor}/{tanggal}', [Pcare_Controller::class, 'get_pendaftaran_nomor'])->name('pcare.pendaftaran_nomor');
    Route::get('/get-pendaftaran-provide/{tanggal}', [Pcare_Controller::class, 'get_pendaftaran_provide'])->name('pcare.pendaftaran_provide');
});

// Data Master Medis
Route::prefix('data-master-medis')->group(function () {
    Route::get('/perawatan_tindakan/getLastKode', [Perawatan_Tindakan_Api_Controller::class, 'getLastKode'])->name('perawatan_tindakan.getLastKode'); // di privat fungsi nya
});

// Data Master Gudang
Route::prefix('data-master-gudang')->group(function () {
    Route::get('/supplier-industri/getLastKode', [Supplier_Api_Controller::class, 'getLastKode'])->name('supplier_industri.getLastKode');

    Route::get('/request/inventaris/getLastKode', [Inventaris_Request_Api_Controller::class, 'inventaris_request_getLastKode'])->name('inventaris.request_getLastKode');
    Route::get('/request/inventaris/getDetails/{kode_request}', [Inventaris_Request_Api_Controller::class, 'inventaris_getDetails'])->name('inventaris.request_getDetails');
    Route::get('/request/inventaris/detailsAprroval/{kode_request}', [Inventaris_Request_Api_Controller::class, 'inventaris_detailsAprroval'])->name('inventaris.request_detailsAprroval');
    Route::post('/request/inventaris/terimaData/{id}', [Inventaris_Request_Api_Controller::class, 'inventaris_terimaData'])->name('inventaris.request_terimaData');
    Route::post('/request/inventaris/tolakData/{id}', [Inventaris_Request_Api_Controller::class, 'inventaris_tolakData'])->name('inventaris.request_tolakData');

    Route::get('/utama/inventaris/getData/{kode_barang}', [Inventaris_Utama_Api_Controller::class, 'inventaris_getData'])->name('utama.getData');
    Route::get('/utama/inventaris/getDetails/{kode_request}', [Inventaris_Utama_Api_Controller::class, 'inventarisGetDetails'])->name('inventaris.utama_getDetails');
    Route::post('/utama/inventaris/proses-permintaan', [Inventaris_Utama_Api_Controller::class, 'inventaris_prosesPermintaan'])->name('inventaris.utama_prosesPermintaan');
    Route::get('/pdf/inventaris/{kodeRequest}', [Inventaris_Utama_Api_Controller::class, 'inventaris_generatePdf'])->name('inventaris.utama_pdf');

    Route::get('/request/getLastKode', [Gudang_Request_Api_Controller::class, 'request_getLastKode'])->name('request.getLastKode');
    Route::get('/request/getDetails/{kode_request}', [Gudang_Request_Api_Controller::class, 'getDetails'])->name('request.getDetails');
    Route::get('/request/detailsAprroval/{kode_request}', [Gudang_Request_Api_Controller::class, 'detailsAprroval'])->name('request.detailsAprroval');
    Route::post('/request/terimaData/{id}', [Gudang_Request_Api_Controller::class, 'terimaData'])->name('request.terimaData');
    Route::post('/request/tolakData/{id}', [Gudang_Request_Api_Controller::class, 'tolakData'])->name('request.tolakData');

    Route::get('/utama/getHargaDasar/{kode_obat}', [Gudang_Utama_Api_Controller::class, 'getHargaDasar'])->name('utama.getHargaDasar');
    Route::get('/utama/getDetails/{kode_request}', [Gudang_Utama_Api_Controller::class, 'utamaGetDetails'])->name('utama.getDetails');
    Route::post('/utama/proses-permintaan', [Gudang_Utama_Api_Controller::class, 'prosesPermintaan'])->name('utama.prosesPermintaan');
    Route::get('/pdf/{kodeRequest}', [Gudang_Utama_Api_Controller::class, 'generatePdf'])->name('utama.pdf');


    Route::get('/kartu-stok-masuk', [Stok_Barang_Api_Controller::class, 'getKartuStokMasuk'])->name('getKartuStokMasuk');
    Route::get('/kartu-stok-masuk-utama', [Stok_Barang_Utama_Controller::class, 'getKartuStokMasukUtama'])->name('getKartuStokMasukUtama');
    Route::get('/kartu-stok-keluar', [Stok_Barang_Api_Controller::class, 'getKartuStokKeluar'])->name('getKartuStokKeluar');
    Route::get('/kartu-stok-keluar-utama', [Stok_Barang_Utama_Controller::class, 'getKartuStokKeluarUtama'])->name('getKartuStokKeluarUtama');
});


//Apotek
Route::prefix('apotek')->group(function () {
    //RESEP
    Route::post('/kodeFaktur', [ApotekController::class, 'getKodeFaktur'])->name('apotek.getKodeFaktur');
    Route::post('/kodeObat', [ApotekController::class, 'getKodeObat'])->name('apotek.getKodeObat');
    Route::post('/hargaBebas', [ApotekController::class, 'hargaBebas'])->name('apotek.hargaBebas');

    //BELI BEBAS
    Route::get('/BeliBebas', [ApotekController::class, 'getBeliBebas'])->name('apotek.getBeliBebas');
    Route::get('/KodeFakturBeliBebas', [ApotekController::class, 'getKodeFakturBeliBebas'])->name('apotek.getKodeFakturBeliBebas');
});

//KASIR
Route::prefix('kasir')->group(function () {
    Route::post('/previewData', [KasirController::class, 'previewData'])->name('kasir.previewData');
    Route::get('/pdf/{kode_faktur}', [KasirController::class, 'generatePdf'])->name('kasir.pdf');
    Route::get('/ambil-kode/{no_rawat}/{method}', [KasirController::class, 'getKodePenjamin'])->name('kasir.kode');
});


Route::get('/kunjungan-harian', [dashboard::class, 'kunjunganHarian']);
Route::get('/kunjungan-per-poli', [dashboard::class, 'kunjunganPerPoli']);

Route::get('/pendapatan-hari-ini', [dashboard::class, 'getPendapatanHariIni']);
Route::get('/pendapatan-bulanan', [dashboard::class, 'getPendapatanBulanan']);
Route::get('/pendapatan-detail', [dashboard::class, 'getPendapatanDetail']);

// Dashboard Administrasi (dipakai oleh view module/dashboard/administrasi)
Route::prefix('admin-dashboard')->group(function () {
    Route::get('/summary', [dashboard::class, 'ringkasanAdministrasi']);
    Route::get('/schedule-today', [dashboard::class, 'jadwalKunjunganHariIni']);
    Route::get('/payment-status', [dashboard::class, 'statusPembayaranHariIni']);
    Route::get('/incomplete-data', [dashboard::class, 'dataBelumLengkap']);
});

// Dashboard Dokter
Route::prefix('dokter-dashboard')->group(function () {
    Route::get('/ringkasan', [dashboard::class, 'ringkasanDokter']);
    Route::get('/jadwal-hari-ini', [dashboard::class, 'jadwalDokterHariIni']);
    Route::get('/antrian-hari-ini', [dashboard::class, 'antrianDokterHariIni']);
    Route::get('/rme-terbaru', [dashboard::class, 'rmeTerbaruDokter']);
    Route::get('/rujukan-terbaru', [dashboard::class, 'rujukanTerbaruDokter']);
});

// Dashboard Apoteker
Route::prefix('apoteker-dashboard')->group(function () {
    Route::get('/ringkasan', [dashboard::class, 'ringkasanApoteker']);
    Route::get('/penjualan-bulanan', [dashboard::class, 'penjualanBulananApoteker']);
    Route::get('/top-obat-hari-ini', [dashboard::class, 'topObatHariIni']);
    Route::get('/resep-menunggu', [dashboard::class, 'resepMenungguApotek']);
});

// Dashboard Gudang
Route::prefix('gudang-dashboard')->group(function () {
    Route::get('/ringkasan', [dashboard::class, 'ringkasanGudang']);
    Route::get('/pergerakan-hari-ini', [dashboard::class, 'pergerakanGudangHariIni']);
    Route::get('/low-stock', [dashboard::class, 'gudangLowStockTop']);
    Route::get('/permintaan-terbaru', [dashboard::class, 'gudangPermintaanTerbaru']);
});

// Dashboard Gudang Utama
Route::prefix('gudang-utama-dashboard')->group(function () {
    Route::get('/ringkasan', [dashboard::class, 'ringkasanGudangUtama']);
    Route::get('/pergerakan-hari-ini', [dashboard::class, 'pergerakanGudangUtamaHariIni']);
    Route::get('/low-stock', [dashboard::class, 'gudangUtamaLowStockTop']);
    Route::get('/pengiriman-terbaru', [dashboard::class, 'gudangUtamaPengirimanTerbaru']);
});

// Dashboard Pasien
Route::prefix('pasien-dashboard')->group(function () {
    Route::get('/ringkasan', [dashboard::class, 'ringkasanPasien']);
    Route::get('/distribusi-kelamin', [dashboard::class, 'distribusiKelaminPasien']);
    Route::get('/pasien-baru-bulanan', [dashboard::class, 'pasienBaruBulanan']);
    Route::get('/terbaru', [dashboard::class, 'pasienTerbaru']);
});

// Dashboard Manajemen
Route::prefix('manajemen-dashboard')->group(function () {
    Route::get('/ringkasan', [dashboard::class, 'ringkasanManajemen']);
    Route::get('/pendapatan-bulanan', [dashboard::class, 'pendapatanBulananManajemen']);
    Route::get('/komposisi-hari-ini', [dashboard::class, 'komposisiPendapatanHariIni']);
    Route::get('/kunjungan-per-poli', [dashboard::class, 'kunjunganPerPoli']);
    Route::get('/top-dokter-30-hari', [dashboard::class, 'topDokter30Hari']);
});

// Dashboard Kasir
Route::prefix('kasir-dashboard')->group(function () {
    Route::get('/summary', [dashboard::class, 'ringkasanKasir']);
    Route::get('/komposisi-hari-ini', [dashboard::class, 'komposisiPendapatanHariIni']);
    Route::get('/bulanan', [dashboard::class, 'pendapatanBulananKasir']);
    Route::get('/transaksi-terbaru', [dashboard::class, 'transaksiTerbaruKasir']);
});

// Dashboard Registrasi
Route::prefix('registrasi-dashboard')->group(function () {
    Route::get('/summary', [dashboard::class, 'ringkasanRegistrasi']);
    Route::get('/jadwal-hari-ini', [dashboard::class, 'jadwalKunjunganHariIni']);
    Route::get('/kunjungan-per-poli', [dashboard::class, 'kunjunganPerPoli']);
    Route::get('/registrasi-terbaru', [dashboard::class, 'registrasiTerbaru']);
});

// Dashboard Perawat
Route::prefix('perawat-dashboard')->group(function () {
    Route::get('/ringkasan', [dashboard::class, 'ringkasanPerawat']);
    Route::get('/antrian-hari-ini', [dashboard::class, 'antrianPerawatHariIni']);
    Route::get('/soap-terbaru', [dashboard::class, 'soapPerawatTerbaru']);
    Route::get('/jadwal-hari-ini', [dashboard::class, 'jadwalKunjunganHariIni']);
});

// Dashboard Personalia
Route::prefix('personalia-dashboard')->group(function () {
    Route::get('/ringkasan', [dashboard::class, 'ringkasanPersonalia']);
    Route::get('/distribusi-status', [dashboard::class, 'komposisiStatusPegawai']);
    Route::get('/rekrut-bulanan', [dashboard::class, 'rekrutBulananStaff']);
    Route::get('/staf-terbaru', [dashboard::class, 'staffTerbaru']);
});

Route::prefix('m_jkn')->group(function () {
    Route::get('/token', [Mobile_Jkn_Controller::class, 'get_token'])->name('get_token.m_jkn');
    Route::post('/get_antrian', [Mobile_Jkn_Controller::class, 'get_antrian'])->name('get_antrian.m_jkn');
    Route::get('/status_antrian/{kode_poli}/{tgl}', [Mobile_Jkn_Controller::class, 'get_status_antrian'])->name('get_status_antrian.m_jkn');
    Route::get('/sisa_antrian/{noka}/{kode_poli}/{tgl_periksa}', [Mobile_Jkn_Controller::class, 'get_sisa_antrian'])->name('get_sisa_antrian.m_jkn');
    Route::put('/batalkan_antrian', [Mobile_Jkn_Controller::class, 'batalkan_antrian'])->name('batalkan_antrian.m_jkn');
    Route::post('/set_pasien_baru', [Mobile_Jkn_Controller::class, 'set_pasien_baru'])->name('pasien_baru.m_jkn');
});
