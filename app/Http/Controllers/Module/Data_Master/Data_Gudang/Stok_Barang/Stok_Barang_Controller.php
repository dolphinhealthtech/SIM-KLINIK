<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Gudang\Stok_Barang;

use App\Http\Controllers\Controller;
use App\Models\gudang_barang_stok;
use App\Models\gudang_barang;
use Illuminate\Http\Request;



class Stok_Barang_Controller extends Controller
{
    // Web View untuk Stok Barang (Obat / Alkes) Cabang

    public function stokobatalkes()
    {
        $title = "Master Stok Obat / Alkes";
        $stok = gudang_barang_stok::all();

        return view('module.master-data-gudang.stok', compact('title','stok'));
    }

    // Web View untuk Penyesuaian / Stok Opname Cabang
    public function stok_penyesuaian()
    {
        $title = "Master Stok Penyesuaian / Stok Opname";
        $stok = gudang_barang_stok::all();
        $obat = gudang_barang::all();

        return view('module.master-data-gudang.stok_penyesuaian_opname', compact('title','stok','obat'));
    }

    // Web View untuk Kartu Stok Cabang
    public function kartu_stok()
    {
        $title = "Kartu Stok";

        $data = gudang_barang::all();

        return view('module.master-data-gudang.kartu_stok', compact('title', 'data'));
    }
}

