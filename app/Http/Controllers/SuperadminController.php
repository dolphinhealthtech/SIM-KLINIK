<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Models\goldar;
use App\Models\kelamin;
use App\Models\pasien;
use App\Models\Set_Bpjs;
use App\Models\Set_Sehat;
use App\Models\User;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\SatusehatController;
use App\Models\agama;
use App\Models\bahasa;
use App\Models\bangsa;
use App\Models\bank;
use App\Models\dokter;
use App\Models\dokter_jadwal;
use App\Models\dokter_pelatihan;
use App\Models\dokter_pendidikan;
use App\Models\dokter_pendidikan_spesialis;
use App\Models\dokter_verifikasi;
use App\Models\loket;
use App\Models\menu;
use App\Models\pekerjaan;
use App\Models\Pendaftaran_rawat_jalan;
use App\Models\Pendaftaran_rawat_jalan_status;
use App\Models\pendidikan;
use App\Models\penjamin;
use App\Models\pernikahan;
use App\Models\poli;
use App\Models\posker;
use App\Models\provinsi;
use App\Models\suku;
use App\Models\pelayanan;
use App\Models\gudang_satuan;
use App\Models\gudang_kategori;
use App\Models\gudang_supplier_industri;
use App\Models\gudang_barang;
use App\Models\pembelian;
use App\Models\pembelian_details;
use App\Models\gudang_setting_harga;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_stok;
use App\Models\pelayanan_soap_dokter;
use App\Models\pelayanan_soap_dokter_tindakan;
use App\Models\perawatan_kategori;
use App\Models\apotek;
use App\Models\apotek_prebayar;
use App\Exports\Gudang_barangExport;
use App\Imports\Gudang_barangImport;
use App\Models\external_database;
use App\Models\staff;
use App\Models\staff_pelatihan;
use App\Models\staff_pendidikan;
use App\Models\staff_verifikasi;
use App\Models\kasir;
use App\Models\kasir_detail_lunas;
use App\Models\kasir_apotek_lunas;
use App\Models\kasir_tindakan_lunas;
use App\Models\kasir_diskon;
use App\Models\pasien_antrian;
use App\Models\inventaris_kategori;
use App\Models\inventaris_data_barang;
use App\Models\inventaris_pembelian;
use App\Models\inventaris_pembelian_detail;
use App\Models\inventaris_stok;
use App\Models\inventaris_satuan;
use App\Models\gudang_penyesuaian_masuk;
use App\Models\gudang_penyesuaian_keluar;
use App\Models\gudang_stok_opname;
use App\Exports\Inventaris_data_barangExport;
use App\Imports\Inventaris_data_barangImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Connectors\ConnectionFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use DateTimeZone;

class SuperadminController extends Controller
{


    protected $SatusehatController;
    protected $PcareController;

    public function __construct(SatusehatController $SatusehatController, PcareController $PcareController)
    {
        $this->SatusehatController = $SatusehatController;
        $this->PcareController = $PcareController;
    }

    // Role

    // Permission

    // User

    // Monitor




    //Dokter





    // Data Barang



    // Data Barang end


    // Pembelian



    // Pembelian end

    // Kasir




    // End Kasir

    // Data Lunas Kasir

    // End Data Lunas Kasir

    // Data Lunas Detail



    // End Data Lunas Detail

    // Data Lunas Apotek



    // End Data Lunas Apotek

    // Data Lunas Tindakan



    // End Data Lunas Tindakan

    // Data Diskon



    // End Data Diskon

    // Apotek



    // Apotek End


    // Staff




    // PENDATAAN



    // END PENDATAAN

    // INVENTARIS




    // END INVENTARIS

    // PEMBELIAN INVENTARIS


    // END PEMBELIAN INVENTARIS




}
