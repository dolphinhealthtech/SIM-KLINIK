<?php

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-pasien/{id}', [PasienController::class, 'getPasien']);
Route::post('/get-pasien-nikornoka', [PasienController::class, 'cariNikNoka']);
Route::post('/get-pasien-nikornama', [PasienController::class, 'cariNikNama']);
Route::get('/get-dokter/{id}', [DokterController::class, 'getDokter']);
Route::get('/get-staff/{id}', [StaffController::class, 'getStaff']);
Route::get('/get-dokter-all/{id}', [DokterController::class, 'getDokterEdit']);
Route::get('/get-staff-all/{id}', [StaffController::class, 'getStaffEdit']);
Route::get('/jadwal/json/{id}', [DokterController::class, 'dokterjadwaljson']);
Route::get('/get-dokter-by-poli/{id}', [PendaftaranController::class, 'getByPoli']);
Route::get('/generate-kode-data-barang', [DabarController::class, 'generateKodeDataBarang'])->name('generateKodeDataBarang');
Route::get('/generate-faktur-pembelian', [PembelianController::class, 'generateFakturPembelian'])->name('generateFakturPembelian');
Route::get('/generate-kode-inventaris', [InventarisController::class, 'generateKodeInventaris'])->name('generateKodeInventaris');
Route::get('/generate-kode-pembelian-inventaris', [InventarisController::class, 'generatePembelianInventaris'])->name('generatePembelianInventaris');

Route::get('/sub-pemeriksaan/{id}', [PelayananController::class, 'getSubPemeriksaan']);
Route::get('/alergi/by-jenis/{id}', [PelayananController::class, 'getByJenis']);
Route::get('/dokter/data/so/{norawat}', [PelayananController::class, 'soappelayanandata'])->name('pelayana_dokter_data.get');

Route::get('/get-subspesialis/{kode}', [RujukanController::class, 'getSubSpesialis']);

Route::get('/get-pemeriksaan-laboratorium/{id}', [PelayananController::class, 'getSubBidangLab']);

Route::get('/odontogram/load', [OdoController::class, 'odontogramload']);
Route::post('/odontogram/load-details', [OdoController::class, 'odontogramdetailsload']);


Route::prefix('lokasi')->group(function () {
    Route::get('/kabupaten', [LokasiController::class, 'getKabupaten'])->name('get.kabupaten');
    Route::get('/kecamatan', [LokasiController::class, 'getKecamatan'])->name('get.kecamatan');
    Route::get('/kelurahan', [LokasiController::class, 'getKelurahan'])->name('get.kelurahan');
});
Route::prefix('satusehat')->group(function () {
    Route::get('/token', [SatusehatController::class, 'get_token'])->name('satusehat.token'); // di privat fungsi nya
    Route::get('/nik/{nomor}', [SatusehatController::class, 'get_nik_satusehat'])->name('satusehat.nik'); // di privat fungsi nya
    Route::get('/nik-practitioner/{nomor}', [SatusehatController::class, 'get_nik_practitioner_satusehat'])->name('satusehat.nik_practitione'); // di privat fungsi nya
    Route::get('/kfa/{nama}', [SatusehatController::class, 'get_kfa_satusehat'])->name('satusehat.kfa'); // di privat fungsi nya
});
Route::prefix('pcare')->group(function () {
    // pcare
    Route::get('/token', [PcareController::class, 'get_token'])->name('pcare.token'); // di privat fungsi nya
    Route::get('/noka/{nomor}', [PcareController::class, 'get_noka_bpjs'])->name('pcare.noka');
    Route::get('/nik/{nomor}', [PcareController::class, 'get_nik_bpjs'])->name('pcare.nik');
    Route::get('/poli', [PcareController::class, 'get_poli_fktp_bpjs'])->name('pcare.poli');
    Route::get('/dokter', [PcareController::class, 'get_dokter_bpjs'])->name('pcare.dokter');
    Route::get('/spesialis', [PcareController::class, 'get_spesialis_bpjs'])->name('pcare.spesialis');
    Route::get('/sub-spesialis/{nama}', [PcareController::class, 'get_sub_spesialis_bpjs'])->name('pcare.subspesialis');
    Route::get('/diagnosis/{nama}', [PcareController::class, 'get_diagnosis_bpjs'])->name('pcare.diagnosis');
    Route::get('/statpul/{nama}', [PcareController::class, 'get_statpul_bpjs'])->name('pcare.statpul');
    Route::get('/kesadaran', [PcareController::class, 'get_kesadaran_bpjs'])->name('pcare.kesadaran');
    Route::get('/provider', [PcareController::class, 'get_provider_bpjs'])->name('pcare.provider');
    Route::get('/khusus', [PcareController::class, 'get_khusus_bpjs'])->name('pcare.khusus');
    Route::get('/dpho/{nama}', [PcareController::class, 'get_dphoobat_bpjs'])->name('pcare.dpho');
    Route::get('/prognosa', [PcareController::class, 'get_prognosa_bpjs'])->name('pcare.prognosa');
    Route::get('/alergi/{kode}', [PcareController::class, 'get_alergi_bpjs'])->name('pcare.alergi');
    Route::get('/sarana', [PcareController::class, 'get_sarana_bpjs'])->name('pcare.sarana');
    Route::get('/provide_rujuk/{spesialis}/{sarana}/{tanggal}', [PcareController::class, 'get_rujukan_spesialis_bpjs'])->name('pcare.provide_rujuk');
    Route::get('/provide_rujuk_husus/{spesialis}/{noKartu}/{tanggal}', [PcareController::class, 'get_rujukan_husus_bpjs'])->name('pcare.provide_rujuk_husus');
    Route::get('/provide_rujuk_husus_subspesialis/{husus}/{spesialis}/{noKartu}/{tanggal}', [PcareController::class, 'get_rujukan_husus_subspesialis_bpjs'])->name('pcare.provide_rujuk_husus_subspesialis');
    Route::get('/jadwal/{kodepoli}/{tanggal}', [PcareController::class, 'get_jadwal_dokter_bpjs'])->name('pcare.jadwal');
    Route::get('/poli/{tanggal}', [PcareController::class, 'get_ws_poli_bpjs'])->name('pcare.poli_ws');
    Route::get('/get-dekrip-bpjs', [PcareController::class, 'bpjs_dekrip'])->name('pcare.dekrip_bpjs');
});

// Data Master Medis
Route::prefix('data-master-medis')->group(function () {
    Route::get('/perawatan_tindakan/getLastKode', [PerawatanTindakanController::class, 'getLastKode'])->name('perawatan_tindakan.getLastKode'); // di privat fungsi nya
});

// Data Master Gudang
Route::prefix('data-master-gudang')->group(function () {
    Route::get('/supplier-industri/getLastKode', [SupplierController::class, 'getLastKode'])->name('supplier_industri.getLastKode');

    Route::get('/request/inventaris/getLastKode', [InventarisRequestController::class, 'inventaris_request_getLastKode'])->name('inventaris.request_getLastKode');
    Route::get('/request/inventaris/getDetails/{kode_request}', [InventarisRequestController::class, 'inventaris_getDetails'])->name('inventaris.request_getDetails');
    Route::get('/request/inventaris/detailsAprroval/{kode_request}', [InventarisRequestController::class, 'inventaris_detailsAprroval'])->name('inventaris.request_detailsAprroval');
    Route::post('/request/inventaris/terimaData/{id}', [InventarisRequestController::class, 'inventaris_terimaData'])->name('inventaris.request_terimaData');
    Route::post('/request/inventaris/tolakData/{id}', [InventarisRequestController::class, 'inventaris_tolakData'])->name('inventaris.request_tolakData');

    Route::get('/utama/inventaris/getData/{kode_barang}', [InventarisUtamaController::class, 'inventaris_getData'])->name('utama.getData');
    Route::get('/utama/inventaris/getDetails/{kode_request}', [InventarisUtamaController::class, 'inventarisGetDetails'])->name('inventaris.utama_getDetails');
    Route::post('/utama/inventaris/proses-permintaan', [InventarisUtamaController::class, 'inventaris_prosesPermintaan'])->name('inventaris.utama_prosesPermintaan');
    Route::get('/pdf/inventaris/{kodeRequest}', [InventarisUtamaController::class, 'inventaris_generatePdf'])->name('inventaris.utama_pdf');

    Route::get('/request/getLastKode', [GudangRequestController::class, 'request_getLastKode'])->name('request.getLastKode');
    Route::get('/request/getDetails/{kode_request}', [GudangRequestController::class, 'getDetails'])->name('request.getDetails');
    Route::get('/request/detailsAprroval/{kode_request}', [GudangRequestController::class, 'detailsAprroval'])->name('request.detailsAprroval');
    Route::post('/request/terimaData/{id}', [GudangRequestController::class, 'terimaData'])->name('request.terimaData');
    Route::post('/request/tolakData/{id}', [GudangRequestController::class, 'tolakData'])->name('request.tolakData');

    Route::get('/utama/getHargaDasar/{kode_obat}', [GudangUtamaController::class, 'getHargaDasar'])->name('utama.getHargaDasar');
    Route::get('/utama/getDetails/{kode_request}', [GudangUtamaController::class, 'utamaGetDetails'])->name('utama.getDetails');
    Route::post('/utama/proses-permintaan', [GudangUtamaController::class, 'prosesPermintaan'])->name('utama.prosesPermintaan');
    Route::get('/pdf/{kodeRequest}', [GudangUtamaController::class, 'generatePdf'])->name('utama.pdf');


    Route::get('/kartu-stok-masuk', [StokBarangController::class, 'getKartuStokMasuk'])->name('getKartuStokMasuk');
    Route::get('/kartu-stok-keluar', [StokBarangController::class, 'getKartuStokKeluar'])->name('getKartuStokKeluar');
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
});


Route::get('/kunjungan-harian', [dashboard::class, 'kunjunganHarian']);
Route::get('/kunjungan-per-poli', [dashboard::class, 'kunjunganPerPoli']);

Route::get('/pendapatan-hari-ini', [dashboard::class, 'getPendapatanHariIni']);
Route::get('/pendapatan-bulanan', [dashboard::class, 'getPendapatanBulanan']);
Route::get('/pendapatan-detail', [dashboard::class, 'getPendapatanDetail']);

Route::prefix('m_jkn')->group(function () {
    Route::get('/token', [Mobile_JknController::class, 'get_token'])->name('get_token.m_jkn');
    Route::post('/get_antrian', [Mobile_JknController::class, 'get_antrian'])->name('get_antrian.m_jkn');
    Route::get('/status_antrian/{kode_poli}/{tgl}', [Mobile_JknController::class, 'get_status_antrian'])->name('get_status_antrian.m_jkn');
    Route::get('/sisa_antrian/{noka}/{kode_poli}/{tgl_periksa}', [Mobile_JknController::class, 'get_sisa_antrian'])->name('get_sisa_antrian.m_jkn');
    Route::put('/batalkan_antrian', [Mobile_JknController::class, 'batalkan_antrian'])->name('batalkan_antrian.m_jkn');
});
