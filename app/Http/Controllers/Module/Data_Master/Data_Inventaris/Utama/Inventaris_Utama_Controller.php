<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Inventaris\Utama;

use App\Http\Controllers\Controller;
use App\Models\inventaris_data_barang;
use App\Models\inventaris_request;
use App\Models\inventaris_request_detail;
use App\Models\inventaris_stok;
use App\Models\inventaris_utama_keluar;
use App\Models\inventaris_data_barang_utama;
use App\Models\inventaris_stok_utama;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class Inventaris_Utama_Controller extends Controller
{
    public function inventarisutama()
    {
        $title = "Inventaris Gudang Utama";
        $request = inventaris_request::with('details')->get();
        $inventaris = inventaris_data_barang_utama::all();

        return view('module.master-data-gudang.utama_inventaris', compact('title', 'request', 'inventaris'));
    }
}
