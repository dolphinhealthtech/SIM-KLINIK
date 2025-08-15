<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Gudang\Harga_Jual;

use App\Http\Controllers\Controller;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_harga_utama;
use Illuminate\Http\Request;


class Harga_Jual_Utama_Controller extends Controller
{
    public function hargajualutama()
    {
        $title = "Master Utama Harga Jual Obat / Alkes";
        $harga_jual = gudang_barang_harga_utama::all();

        return view('module.master-data-gudang.harga_jual_utama', compact('title', 'harga_jual'));
    }
}
