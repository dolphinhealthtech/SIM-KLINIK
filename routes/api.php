<?php

use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PcareController;
use App\Http\Controllers\SatusehatController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\DataMasterMedisController;
use App\Http\Controllers\DataMasterGudangController;
use App\Http\Controllers\soap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-pasien/{id}', [SuperadminController::class, 'getPasien']);
Route::post('/get-pasien-nikornoka', [SuperadminController::class, 'cariNikNoka']);
Route::post('/get-pasien-nikornama', [SuperadminController::class, 'cariNikNama']);
Route::get('/get-dokter/{id}', [SuperadminController::class, 'getDokter']);
Route::get('/get-staff/{id}', [SuperadminController::class, 'getStaff']);
Route::get('/get-dokter-all/{id}', [SuperadminController::class, 'getDokterEdit']);
Route::get('/get-staff-all/{id}', [SuperadminController::class, 'getStaffEdit']);
Route::get('/jadwal/json/{id}', [SuperadminController::class, 'dokterjadwaljson']);
Route::get('/get-dokter-by-poli/{id}', [SuperadminController::class, 'getByPoli']);
Route::get('/generate-kode-data-barang', [SuperadminController::class, 'generateKodeDataBarang'])->name('generateKodeDataBarang');
Route::get('/generate-faktur-pembelian', [SuperadminController::class, 'generateFakturPembelian'])->name('generateFakturPembelian');
Route::get('/generate-kode-inventaris', [SuperadminController::class, 'generateKodeInventaris'])->name('generateKodeInventaris');
Route::get('/generate-kode-pembelian-inventaris', [SuperadminController::class, 'generatePembelianInventaris'])->name('generatePembelianInventaris');

Route::get('/sub-pemeriksaan/{id}', [soap::class, 'getSubPemeriksaan']);
Route::get('/alergi/by-jenis/{id}', [soap::class, 'getByJenis']);
Route::get('/dokter/data/so/{norawat}', [soap::class,'soappelayanandata'])->name('pelayana_dokter_data.get');

Route::get('/get-subspesialis/{kode}', [soap::class, 'getSubSpesialis']);

Route::get('/get-pemeriksaan-laboratorium/{id}', [soap::class, 'getSubBidangLab']);

Route::get('/odontogram/load', [soap::class, 'odontogramload']);
Route::post('/odontogram/load-details', [soap::class, 'odontogramdetailsload']);


Route::prefix('lokasi')->group(function(){
    Route::get('/kabupaten', [LokasiController::class, 'getKabupaten'])->name('get.kabupaten');
    Route::get('/kecamatan', [LokasiController::class, 'getKecamatan'])->name('get.kecamatan');
    Route::get('/kelurahan', [LokasiController::class, 'getKelurahan'])->name('get.kelurahan');

});
Route::prefix('satusehat')->group(function(){
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
});

// Data Master Medis
Route::prefix('data-master-medis')->group(function(){
    Route::get('/perawatan_tindakan/getLastKode', [DataMasterMedisController::class, 'getLastKode'])->name('perawatan_tindakan.getLastKode'); // di privat fungsi nya
});

// Data Master Gudang
Route::prefix('data-master-gudang')->group(function(){
    Route::get('/supplier-industri/getLastKode', [DataMasterGudangController::class, 'getLastKode'])->name('supplier_industri.getLastKode');

    Route::get('/request/inventaris/getLastKode', [DataMasterGudangController::class, 'inventaris_request_getLastKode'])->name('inventaris.request_getLastKode');
    Route::get('/request/inventaris/getDetails/{kode_request}', [DataMasterGudangController::class, 'inventaris_getDetails'])->name('inventaris.request_getDetails');
    Route::get('/request/inventaris/detailsAprroval/{kode_request}', [DataMasterGudangController::class, 'inventaris_detailsAprroval'])->name('inventaris.request_detailsAprroval');
    Route::post('/request/inventaris/terimaData/{id}', [DataMasterGudangController::class, 'inventaris_terimaData'])->name('inventaris.request_terimaData');
    Route::post('/request/inventaris/tolakData/{id}', [DataMasterGudangController::class, 'inventaris_tolakData'])->name('inventaris.request_tolakData');

    Route::get('/utama/inventaris/getData/{kode_barang}', [DataMasterGudangController::class, 'inventaris_getData'])->name('utama.getData');
    Route::get('/utama/inventaris/getDetails/{kode_request}', [DataMasterGudangController::class, 'inventarisGetDetails'])->name('inventaris.utama_getDetails');
    Route::post('/utama/inventaris/proses-permintaan', [DataMasterGudangController::class, 'inventaris_prosesPermintaan'])->name('inventaris.utama_prosesPermintaan');
    Route::get('/pdf/inventaris/{kodeRequest}', [DataMasterGudangController::class, 'inventaris_generatePdf'])->name('inventaris.utama_pdf');

    Route::get('/request/getLastKode', [DataMasterGudangController::class, 'request_getLastKode'])->name('request.getLastKode');
    Route::get('/request/getDetails/{kode_request}', [DataMasterGudangController::class, 'getDetails'])->name('request.getDetails');
    Route::get('/request/detailsAprroval/{kode_request}', [DataMasterGudangController::class, 'detailsAprroval'])->name('request.detailsAprroval');
    Route::post('/request/terimaData/{id}', [DataMasterGudangController::class, 'terimaData'])->name('request.terimaData');
    Route::post('/request/tolakData/{id}', [DataMasterGudangController::class, 'tolakData'])->name('request.tolakData');

    Route::get('/utama/getHargaDasar/{kode_obat}', [DataMasterGudangController::class, 'getHargaDasar'])->name('utama.getHargaDasar');
    Route::get('/utama/getDetails/{kode_request}', [DataMasterGudangController::class, 'utamaGetDetails'])->name('utama.getDetails');
    Route::post('/utama/proses-permintaan', [DataMasterGudangController::class, 'prosesPermintaan'])->name('utama.prosesPermintaan');
    Route::get('/pdf/{kodeRequest}', [DataMasterGudangController::class, 'generatePdf'])->name('utama.pdf');
});

//Apotek
Route::prefix('apotek')->group(function(){
    //RESEP
    Route::post('/kodeFaktur', [SuperadminController::class, 'getKodeFaktur'])->name('apotek.getKodeFaktur');
    Route::post('/kodeObat', [SuperadminController::class, 'getKodeObat'])->name('apotek.getKodeObat');
    Route::post('/hargaBebas', [SuperadminController::class, 'hargaBebas'])->name('apotek.hargaBebas');

    //BELI BEBAS
    Route::get('/BeliBebas', [SuperadminController::class, 'getBeliBebas'])->name('apotek.getBeliBebas');
    Route::get('/KodeFakturBeliBebas', [SuperadminController::class, 'getKodeFakturBeliBebas'])->name('apotek.getKodeFakturBeliBebas');
});

//KASIR
Route::prefix('kasir')->group(function(){
    Route::post('/previewData', [SuperadminController::class, 'previewData'])->name('kasir.previewData');
    Route::get('/pdf/{kode_faktur}', [SuperadminController::class, 'generatePdf'])->name('kasir.pdf');
});


