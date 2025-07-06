<?php

namespace App\Http\Controllers;

use App\Exports\PoskerExport;
use App\Http\Controllers\Controller;
use App\Imports\PoskerImport;
use App\Models\posker;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class DataMasterManajemenController extends Controller
{
    public function posisi_kerja()
    {
        $title = "Master Posisi Kerja";
        $posker = posker::all();
        return view('module.master-data-manajemen.posker', compact('title','posker'));
    }

    public function posisi_kerjaadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
            ]);
            // Simpan data ke database
            $posker = posker::create([
                'nama' => $request->input('nama'),   // Ambil data dari request
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'posisi Kerja berhasil ditambahkan!',
                'data' => $posker
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'posisi Kerja Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan posisi Kerja!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function posisi_kerjaedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string'
        ]);

        $posker = posker::find($request->poskerid_edit);

        if (!$posker) {
            return response()->json([
                'success' => false,
                'message' => 'Posisi Kerja tidak ditemukan!'
            ], 404);
        }

        $posker->nama = $request->nama_edit;
        $posker->save();

        return response()->json([
            'success' => true,
            'message' => 'Posisi Kerja berhasil diperbarui!'
        ]);
    }


    public function posisi_kerjadelete(Request $request)
    {

        $request->validate([
            'poskerid_delete' => 'required'
        ]);

        $posker = posker::find($request->poskerid_delete);

        if (!$posker) {
            return response()->json([
                'success' => false,
                'message' => 'Posisi Kerja tidak ditemukan!'
            ], 404);
        }

        $posker->delete();

        return response()->json([
            'success' => true,
            'message' => 'Posisi Kerja berhasil dihapus!'
        ]);
    }

    public function posisi_kerjaexport()
    {
        return Excel::download(new PoskerExport, 'Posisi_kerja.xlsx');
    }

    public function posisi_kerjaimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PoskerImport, $request->file('file'));


        return redirect()->route('posker.get')->with('success', 'Data berhasil diimpor!');
    }
}
