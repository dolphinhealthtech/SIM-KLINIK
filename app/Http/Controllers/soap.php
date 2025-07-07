<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\alergi;
use App\Models\gcs_eye;
use App\Models\gcs_kesadaran;
use App\Models\gcs_motorik;
use App\Models\gcs_verbal;
use App\Models\gudang_barang;
use App\Models\gudang_satuan;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
use App\Models\icd10;
use App\Models\icd9;
use App\Models\jenis_diet;
use App\Models\nama_makanan;
use App\Models\pelayanan;
use App\Models\pelayanan_soap_dokter;
use App\Models\pelayanan_soap_dokter_diet;
use App\Models\pelayanan_soap_dokter_icd;
use App\Models\pelayanan_soap_dokter_obat;
use App\Models\pelayanan_soap_dokter_tindakan;
use App\Models\pelayanan_soap_perawat;
use App\Models\Pendaftaran_rawat_jalan;
use App\Models\perawatan_kategori;
use App\Models\perawatan_tindakan;
use App\Models\sarana;
use App\Models\spesialis;
use App\Models\subspesialis;
use App\Models\laboratorium_bidang;
use App\Models\laboratorium_bidang_sub;
use App\Models\odontogram;
use App\Models\odontogram_details;
use App\Models\radiologi_pemeriksaan;
use App\Models\radiologi_jenis;
use App\Models\kode_surat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class soap extends Controller
{
    protected $PcareController;

    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }











}





