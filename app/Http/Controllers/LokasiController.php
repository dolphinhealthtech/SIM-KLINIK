<?php

namespace App\Http\Controllers;

use App\Models\desa;
use App\Models\kabupaten;
use App\Models\kecamatan;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function getKabupaten(Request $request)
    {
        $kabupaten = kabupaten::where('kode_provinsi', $request->provinsi_id)->get();
        return response()->json($kabupaten);
    }

    public function getKecamatan(Request $request)
    {
        $kecamatan = kecamatan::where('kode_kabupaten', $request->kabupaten_id)->get();
        return response()->json($kecamatan);
    }

    public function getKelurahan(Request $request)
    {
        $kelurahan = desa::where('kode_kecamatan', $request->kecamatan_id)->get();
        return response()->json($kelurahan);
    }
}
