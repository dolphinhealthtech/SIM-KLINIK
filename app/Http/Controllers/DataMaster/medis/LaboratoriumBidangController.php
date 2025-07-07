<?php

namespace App\Http\Controllers\DataMaster\medis;

use App\Http\Controllers\Controller;
use App\Exports\Laboratorium_bidangExport;
use App\Imports\Laboratorium_bidangImport;
use App\Models\laboratorium_bidang;
use App\Models\laboratorium_bidang_sub;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class LaboratoriumBidangController extends Controller
{
    // pemeriksaan laboratorium_bidang

    public function laboratorium_bidang()
    {
        $title = "Master Bidang Laboratorium ";
        $laboratorium_bidang = laboratorium_bidang::all();
        return view('module.master-data-medis.laboratorium_bidang', compact('title', 'laboratorium_bidang'));
    }

    public function laboratorium_bidangadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string',
            ]);

            $laboratorium_bidang = laboratorium_bidang::create([
                'nama' => $request->nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bidang Labotorium berhasil ditambahkan!',
                'data' => $laboratorium_bidang
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang Labotorium Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Bidang Labotorium!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function laboratorium_bidangedit(Request $request)
    {
        $request->validate([
            "nama_edit" => 'required|string',
        ]);

        $laboratorium_bidang = laboratorium_bidang::find($request->bidang_labotorium_edit);

        if (!$laboratorium_bidang) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang Labotorium tidak ditemukan!'
            ], 404);
        }

        $laboratorium_bidang->nama = $request->nama_edit;
        $laboratorium_bidang->save();

        return response()->json([
            'success' => true,
            'message' => 'Bidang Labotorium berhasil diperbarui!'
        ]);
    }

    public function laboratorium_bidangdelete(Request $request)
    {

        $request->validate([
            'pemeriksaan_httid_delete' => 'required'
        ]);

        $laboratorium_bidang = laboratorium_bidang::find($request->pemeriksaan_httid_delete);
        if (!$laboratorium_bidang) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang Labotorium tidak ditemukan!'
            ], 404);
        }
        $laboratorium_bidang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bidang Labotorium berhasil dihapus!'
        ]);
    }

    public function laboratorium_bidangexport()
    {
        return Excel::download(new Laboratorium_bidangExport, 'labotorium Bidang.xlsx');
    }

    public function laboratorium_bidangeimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Laboratorium_bidangImport, $request->file('file'));


        return redirect()->route('laboratorium_bidang.get')->with('success', 'Data berhasil diimpor!');
    }

    public function laboratorium_bidang_sub($kode)
    {
        $title = "Master Data Sub Spesialis";
        $laboratorium_bidang_sub = laboratorium_bidang_sub::where('laboratorium_bidang_id', $kode)->get();
        $laboratorium_bidang = laboratorium_bidang::find($kode);

        // spesialis
        return view('module.master-data-medis.laboratorium_bidang_sub', compact('title', 'laboratorium_bidang_sub', 'laboratorium_bidang'));
    }

    public function laboratorium_bidang_subadd(Request $request)
    {
        try {
            $request->validate([
                "laboratorium_bidang_sub_id" => 'required',
                "nama_sub_pemeriksaan" => 'required|string',
                "nama" => 'required|string',
            ]);

            $laboratorium_bidang_sub = laboratorium_bidang_sub::create([
                'laboratorium_bidang_id' => $request->laboratorium_bidang_sub_id,
                'nama_laboratorium_bidang' => $request->nama_sub_pemeriksaan,
                'nama_sublaboratorium_bidang' => $request->nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bidang Laboratorium berhasil ditambahkan!',
                'data' => $laboratorium_bidang_sub
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang Laboratorium Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Bidang Laboratorium!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function laboratorium_bidang_subedit(Request $request)
    {
        $request->validate([
            "laboratorium_bidang_subid_edit" => 'required',
            "laboratorium_bidang_sub_id_edit" => 'required',
            "nama_pemeriksaan_edit" => 'required|string',
            "nama_edit" => 'required|string',
        ]);

        $laboratorium_bidang_sub = laboratorium_bidang_sub::find($request->laboratorium_bidang_subid_edit);

        if (!$laboratorium_bidang_sub) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang Laboratorium tidak ditemukan!'
            ], 404);
        }

        $laboratorium_bidang_sub->laboratorium_bidang_id = $request->laboratorium_bidang_sub_id_edit;
        $laboratorium_bidang_sub->nama_laboratorium_bidang = $request->nama_pemeriksaan_edit;
        $laboratorium_bidang_sub->nama_sublaboratorium_bidang = $request->nama_edit;
        $laboratorium_bidang_sub->save();

        return response()->json([
            'success' => true,
            'message' => 'Bidang Laboratorium berhasil diperbarui!'
        ]);
    }

    public function laboratorium_bidang_subdelete(Request $request)
    {

        $request->validate([
            'laboratorium_bidang_subid_delete' => 'required'
        ]);

        $laboratorium_bidang_sub = laboratorium_bidang_sub::find($request->laboratorium_bidang_subid_delete);
        if (!$laboratorium_bidang_sub) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang Laboratorium tidak ditemukan!'
            ], 404);
        }
        $laboratorium_bidang_sub->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bidang Laboratorium berhasil dihapus!'
        ]);
    }
}
