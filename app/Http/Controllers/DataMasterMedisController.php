<?php

namespace App\Http\Controllers;

use App\Exports\Nama_MakananExport;
use App\Exports\Jenis_DietExport;
use App\Exports\Htt_pemeriksaanExport;
use App\Exports\Icd10Export;
use App\Exports\Icd9Export;
use App\Exports\Laboratorium_bidangExport;
use App\Exports\PoliExport;
use App\Exports\SpesialisExport;
use App\Exports\SubspesialisExport;
use App\Exports\Perawatan_kategoriExport;
use App\Exports\Perawatan_tindakanExport;
use App\Exports\Radiologi_pemeriksaanExport;
use App\Exports\SaranaExport;
use App\Imports\Nama_MakananImport;
use App\Imports\Jenis_DietImport;
use App\Imports\Htt_pemeriksaanImport;
use App\Imports\Icd10Import;
use App\Imports\Icd9Import;
use App\Imports\Laboratorium_bidangImport;
use App\Imports\PoliImport;
use App\Imports\SpesialisImport;
use App\Imports\SubspesialisImport;
use App\Imports\Perawatan_kategoriImport;
use App\Imports\Radiologi_JenisImport;
use App\Exports\Radiologi_JenisExport;
use App\Imports\Perawatan_tindakanImport;
use App\Imports\Radiologi_pemeriksaanImport;
use App\Imports\SaranaImport;
use App\Models\alergi;
use App\Models\jenis_diet;
use App\Models\nama_makanan;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
use App\Models\icd10;
use App\Models\icd9;
use App\Models\laboratorium_bidang;
use App\Models\laboratorium_bidang_sub;
use App\Models\poli;
use App\Models\spesialis;
use App\Models\subspesialis;
use App\Models\perawatan_kategori;
use App\Models\perawatan_tindakan;
use App\Models\radiologi_pemeriksaan;
use App\Models\sarana;
use App\Models\radiologi_jenis;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class DataMasterMedisController extends Controller
{
    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    // data poli


    // data spesialis


     // data subspesialis


    // Kategori Perawatan


    // End Kategori Perawatan

    // Kategori Perawatan



        //End

    // End Kategori Perawatan

    // pemeriksaan htt


    // data poli


    // jenis_diet Start

    // jenis_diet end


    // Nama_Makanan Start








    // Radiologi_jenis Start



    // pemeriksaan laboratorium_bidang


    // pemeriksaan laboratorium_bidang


}
