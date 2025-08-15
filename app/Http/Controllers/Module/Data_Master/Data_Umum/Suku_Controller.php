<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Umum;

use App\Exports\SukuExport;
use App\Http\Controllers\Controller;
use App\Imports\SukuImport;
use App\Models\suku;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class Suku_Controller extends Controller
{
    public function suku()
    {
        $title = "Master Suku";
        $suku = suku::all();
        return view('module.master-data.suku', compact('title', 'suku'));
    }

    public function sukuadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:sukus,nama',
            ]);

            $suku = suku::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Suku berhasil ditambahkan!',
                'data' => $suku
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Suku Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Suku!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sukuedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $suku = suku::find($request->sukuid_edit);

        if (!$suku) {
            return response()->json([
                'success' => false,
                'message' => 'Suku tidak ditemukan!'
            ], 404);
        }

        $suku->nama = $request->nama_edit;
        $suku->save();

        return response()->json([
            'success' => true,
            'message' => 'suku berhasil diperbarui!'
        ]);
    }

    public function sukudelete(Request $request)
    {

        $request->validate([
            'sukuid_delete' => 'required'
        ]);

        $suku = suku::find($request->sukuid_delete);
        if (!$suku) {
            return response()->json([
                'success' => false,
                'message' => 'Suku tidak ditemukan!'
            ], 404);
        }
        $suku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Suku berhasil dihapus!'
        ]);
    }

    public function sukuexport()
    {
        return Excel::download(new SukuExport, 'suku.xlsx');
    }

    public function sukuimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SukuImport, $request->file('file'));


        return redirect()->route('suku.get')->with('success', 'Data berhasil diimpor!');
    }
}
