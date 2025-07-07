<?php

namespace App\Http\Controllers\DataMaster\main;

use App\Exports\BangsaExport;
use App\Http\Controllers\Controller;
use App\Imports\BangsaImport;
use App\Models\bangsa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class BangsaController extends Controller
{
    public function bangsa()
    {
        $title = "Master Bangsa";
        $bangsa = bangsa::all();
        return view('module.master-data.bangsa', compact('title', 'bangsa'));
    }

    public function bangsaadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:bangsas,nama',
            ]);

            $bangsa = bangsa::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'bangsa berhasil ditambahkan!',
                'data' => $bangsa
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bangsa Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Bangsa!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bangsaedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $bangsa = bangsa::find($request->bangsaid_edit);

        if (!$bangsa) {
            return response()->json([
                'success' => false,
                'message' => 'Bangsa tidak ditemukan!'
            ], 404);
        }

        $bangsa->nama = $request->nama_edit;
        $bangsa->save();

        return response()->json([
            'success' => true,
            'message' => 'Bangsa berhasil diperbarui!'
        ]);
    }

    public function bangsadelete(Request $request)
    {

        $request->validate([
            'bahasaid_delete' => 'required'
        ]);

        $bangsa = bangsa::find($request->bahasaid_delete);
        if (!$bangsa) {
            return response()->json([
                'success' => false,
                'message' => 'Bangsa tidak ditemukan!'
            ], 404);
        }
        $bangsa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bangsa berhasil dihapus!'
        ]);
    }

    public function bangsaexport()
    {
        return Excel::download(new BangsaExport, 'bangsa.xlsx');
    }

    public function bangsaimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new BangsaImport, $request->file('file'));


        return redirect()->route('bangsa.get')->with('success', 'Data berhasil diimpor!');
    }
}
