<?php

namespace App\Http\Controllers\DataMaster\main;

use App\Exports\BahasaExport;
use App\Http\Controllers\Controller;
use App\Imports\BahasaImport;
use App\Models\bahasa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class BahasaController extends Controller
{
    public function bahasa()
    {
        $title = "Master Bahasa";
        $bahasa = bahasa::all();
        return view('module.master-data.bahasa', compact('title', 'bahasa'));
    }

    public function bahasaadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:bahasas,nama',
            ]);

            $bahasa = bahasa::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bahasa berhasil ditambahkan!',
                'data' => $bahasa
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bahasa Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Bahasa!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bahasaedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $bahasa = bahasa::find($request->bahasaid_edit);

        if (!$bahasa) {
            return response()->json([
                'success' => false,
                'message' => 'Bahasa tidak ditemukan!'
            ], 404);
        }

        $bahasa->nama = $request->nama_edit;
        $bahasa->save();

        return response()->json([
            'success' => true,
            'message' => 'Bahasa berhasil diperbarui!'
        ]);
    }

    public function bahasadelete(Request $request)
    {

        $request->validate([
            'bahasaid_delete' => 'required'
        ]);

        $bahasa = bahasa::find($request->bahasaid_delete);
        if (!$bahasa) {
            return response()->json([
                'success' => false,
                'message' => 'Bahasa tidak ditemukan!'
            ], 404);
        }
        $bahasa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bahasa berhasil dihapus!'
        ]);
    }

    public function bahasaexport()
    {
        return Excel::download(new BahasaExport, 'bahasa.xlsx');
    }

    public function bahasaimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new BahasaImport, $request->file('file'));


        return redirect()->route('bahasa.get')->with('success', 'Data berhasil diimpor!');
    }
}
