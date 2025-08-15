<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Medis\Icd9;

use App\Http\Controllers\Controller;
use App\Exports\Icd9Export;
use App\Imports\Icd9Import;
use App\Models\icd9;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class Icd9_Controller extends Controller
{
    public function icd9()
    {
        $title = "Master ICD 9";
        $icd9 = icd9::all();
        return view('module.master-data.medis.icd9.index', compact('title', 'icd9'));
    }

    public function icd9add(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'kode' => 'string|nullable'
            ]);
            // Simpan data ke database
            $goldar = icd9::create([
                'nama_icd9' => $request->input('nama'),   // Ambil data dari request
                'kode_icd9' => $request->input('kode')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'icd9 berhasil ditambahkan!',
                'data' => $goldar
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'icd9 Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan icd9!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function icd9edit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'kode_edit' => 'string|nullable'
        ]);

        $icd9 = icd9::find($request->icd9id_edit);

        if (!$icd9) {
            return response()->json([
                'success' => false,
                'message' => 'icd9 tidak ditemukan!'
            ], 404);
        }

        $icd9->nama_icd9 = $request->nama_edit;
        $icd9->kode_icd9 = $request->kode_edit;
        $icd9->save();

        return response()->json([
            'success' => true,
            'message' => 'icd9 berhasil diperbarui!'
        ]);
    }

    public function icd9delete(Request $request)
    {

        $request->validate([
            'icd9id_delete' => 'required'
        ]);

        $icd9 = icd9::find($request->icd9id_delete);

        if (!$icd9) {
            return response()->json([
                'success' => false,
                'message' => 'icd9 tidak ditemukan!'
            ], 404);
        }

        $icd9->delete();

        return response()->json([
            'success' => true,
            'message' => 'icd9 berhasil dihapus!'
        ]);
    }

    public function icd9export()
    {
        return Excel::download(new Icd9Export, 'Macam-macam ICD9.xlsx');
    }

    public function icd9import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Icd9Import, $request->file('file'));
        return redirect()->route('icd9.get')->with('success', 'Data berhasil diimpor!');
    }
}
