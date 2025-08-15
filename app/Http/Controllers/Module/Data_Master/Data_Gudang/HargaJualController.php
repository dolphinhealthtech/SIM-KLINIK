<?php

namespace App\Http\Controllers\DataMaster\gudang;

use App\Http\Controllers\Controller;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_harga_utama;
use Illuminate\Http\Request;


class HargaJualController extends Controller
{
    public function hargajual()
    {
        $title = "Master Harga Jual Obat / Alkes";
        $harga_jual = gudang_barang_harga::all();

        return view('module.master-data-gudang.harga_jual', compact('title','harga_jual'));
    }

    public function hargajualutama()
    {
        $title = "Master Utama Harga Jual Obat / Alkes";
        $harga_jual = gudang_barang_harga_utama::all();

        return view('module.master-data-gudang.harga_jual_utama', compact('title','harga_jual'));
    }
}
