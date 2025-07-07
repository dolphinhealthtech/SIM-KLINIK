<?php

namespace App\Http\Controllers\DataMaster\medis;

use App\Http\Controllers\Controller;
use App\Exports\radiologi_jenisExport;
use App\Imports\radiologi_jenisImport;
use App\Models\radiologi_jenis;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class RadiologiJenisController extends Controller
{
    public function radiologi_jenis()
    {
        $title = "Master radiologi jenis";
        $radiologi_jenis = radiologi_jenis::all();
        return view('module.master-data-medis.radiologi_jenis', compact('title', 'radiologi_jenis'));
    }

    public function radiologi_jenisadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:radiologi_jenis,nama', // perhatikan nama tabel
            ]);

            $radiologi_jenis = radiologi_jenis::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'radiologi jenis berhasil ditambahkan!',
                'data' => $radiologi_jenis
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi jenis Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {


            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan radiologi jenis!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function radiologi_jenisedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $radiologi_jenis = radiologi_jenis::find($request->radiologi_jenisid_edit);

        if (!$radiologi_jenis) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi jenis tidak ditemukan!'
            ], 404);
        }

        $radiologi_jenis->nama = $request->nama_edit;
        $radiologi_jenis->save();

        return response()->json([
            'success' => true,
            'message' => 'radiologi jenis berhasil diperbarui!'
        ]);
    }

    public function radiologi_jenisdelete(Request $request)
    {

        $request->validate([
            'radiologi_jenisid_delete' => 'required'
        ]);

        $radiologi_jenis = radiologi_jenis::find($request->radiologi_jenisid_delete);
        if (!$radiologi_jenis) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi jenis tidak ditemukan!'
            ], 404);
        }
        $radiologi_jenis->delete();

        return response()->json([
            'success' => true,
            'message' => 'radiologi jenis berhasil dihapus!'
        ]);
    }

    public function radiologi_jenisexport()
    {
        return Excel::download(new radiologi_jenisExport, 'Radiologi jenis.xlsx');
    }

    public function radiologi_jenisimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new radiologi_jenisImport, $request->file('file'));


        return redirect()->route('radiologi_jenis.get')->with('success', 'Data berhasil diimpor!');
    }
}
