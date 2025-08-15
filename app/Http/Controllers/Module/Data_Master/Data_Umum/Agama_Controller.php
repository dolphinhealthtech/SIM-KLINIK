<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Umum;

use App\Exports\AgamaExport;
use App\Http\Controllers\Controller;
use App\Imports\AgamaImport;
use App\Models\agama;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class Agama_Controller extends Controller
{
    public function agama()
    {
        $title = "Master Agama";
        $agama = agama::all();
        return view('module.master-data.umum.agama.index', compact('title', 'agama'));
    }

    public function agamaadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:agamas,nama',
            ]);

            $agama = agama::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Agama berhasil ditambahkan!',
                'data' => $agama
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Agama Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Agama!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function agamaedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $agama = agama::find($request->agamaid_edit);

        if (!$agama) {
            return response()->json([
                'success' => false,
                'message' => 'Agama tidak ditemukan!'
            ], 404);
        }

        $agama->nama = $request->nama_edit;
        $agama->save();

        return response()->json([
            'success' => true,
            'message' => 'Agama berhasil diperbarui!'
        ]);
    }

    public function agamadelete(Request $request)
    {

        $request->validate([
            'agamaid_delete' => 'required'
        ]);

        $agama = agama::find($request->agamaid_delete);
        if (!$agama) {
            return response()->json([
                'success' => false,
                'message' => 'Agama tidak ditemukan!'
            ], 404);
        }
        $agama->delete();

        return response()->json([
            'success' => true,
            'message' => 'Agama berhasil dihapus!'
        ]);
    }

    public function agamaexport()
    {
        return Excel::download(new AgamaExport, 'Agama.xlsx');
    }

    public function agamaimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new AgamaImport, $request->file('file'));


        return redirect()->route('agama.get')->with('success', 'Data berhasil diimpor!');
    }
}
