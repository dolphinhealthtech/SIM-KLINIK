<?php

namespace App\Http\Controllers;

use App\Exports\Gudang_satuanExport;
use App\Exports\Gudang_kategoriExport;
use App\Exports\Gudang_supplier_industriExport;
use App\Exports\Inventaris_kategoriExport;
use App\Exports\Inventaris_satuanExport;
use App\Imports\Gudang_satuanImport;
use App\Imports\Gudang_kategoriImport;
use App\Imports\Gudang_supplier_industriImport;
use App\Imports\Inventaris_kategoriImport;
use App\Imports\Inventaris_satuanImport;
use App\Models\gudang_satuan;
use App\Models\gudang_kategori;
use App\Models\gudang_supplier_industri;
use App\Models\gudang_barang;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_stok;
use App\Models\gudang_setting_harga;
use App\Models\external_database;
use App\Models\gudang_klinik_request;
use App\Models\gudang_klinik_request_details;
use App\Models\gudang_utama_keluar;
use App\Models\inventaris_kategori;
use App\Models\inventaris_stok;
use App\Models\inventaris_data_barang;
use App\Models\inventaris_request;
use App\Models\inventaris_utama_keluar;
use App\Models\inventaris_satuan;
use App\Models\inventaris_request_detail;
use App\Models\apotek_prebayar;
use App\Models\gudang_penyesuaian_masuk;
use App\Models\gudang_penyesuaian_keluar;
use App\Models\gudang_stok_opname;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Connectors\ConnectionFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DataMasterGudangController extends Controller
{
    // Jenis Satuan



    // Jenis satuan end

    // Jenis Kategori



    // Jenis Kategori end

    // Supplier Industri



    // Supplier Industri end

    // Setting Harga



    // End Setting Harga

    // Harga Jual Barang



    //End Harga Jual Barang

    // Stok Barang (Obat / Alkes)

    // End Stok Barang (Obat / Alkes)

    // Penyesuaian / Stok Opname



    // Jenis Kategori Inventaris

    // Jenis Kategori Inventaris end

    // Jenis Satuan Inventaris

    // Jenis satuan inventaris end

    // Stok Inventaris

    // End Stok Inventaris

    // Inventaris Klinik Request (OMEGA)

    // End Inventaris Klinik Request (OMEGA)

    // Inventaris Gudang Utama (OMEGA)


    // End Inventaris Gudang Utama (OMEGA)

    // Gudang Klinik Request (OMEGA)


    // END Gudang Klinik Request (OMEGA)

    // Gudang Utama (OMEGA)




    // END Gudang Utama (OMEGA)
}




