<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Gudang\Supplier;

use App\Http\Controllers\Controller;
use App\Exports\Gudang_supplier_industriExport;
use App\Imports\Gudang_supplier_industriImport;
use App\Models\gudang_supplier_industri;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class Supplier_Api_Controller extends Controller
{

    // API Get Kode Supplier Industri

    public function getLastKode()
    {
        $last = gudang_supplier_industri::orderBy('id', 'desc')->first();

        return response()->json([
            'kode' => $last ? $last->kode : null
        ]);
    }
}
