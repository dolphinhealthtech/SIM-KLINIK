<?php

namespace App\Http\Controllers\DataMaster\main;

use App\Http\Controllers\Controller;
use App\Exports\KelaminExport;
use App\Imports\KelaminImport;
use App\Models\kelamin;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class KelaminController extends Controller
{
    public function kelamin()
    {
        $title = "Master Jenis Kelamin";
        $kelamin = kelamin::all();
        return view('module.master-data.kelamin', compact('title','kelamin'));
    }

    public function kelaminadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:kelamins,nama',
                "urutan" => 'required|string|unique:kelamins,kode',
            ]);

            $kelamin = kelamin::create([
                'nama' => $request->nama,
                'kode' => $request->kode
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kelamin berhasil ditambahkan!',
                'data' => $kelamin
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kelamin Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Kelamin!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function kelaminedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'kode_edit' => 'required|string',
        ]);

        $kelamin = kelamin::find($request->kelaminid_edit);

        if (!$kelamin) {
            return response()->json([
                'success' => false,
                'message' => 'Kelamin tidak ditemukan!'
            ], 404);
        }

        $kelamin->nama = $request->nama_edit;
        $kelamin->kode = $request->kode_edit;
        $kelamin->save();

        return response()->json([
            'success' => true,
            'message' => 'Kelamin berhasil diperbarui!'
        ]);
    }

    public function kelamindelete(Request $request)
    {

        $request->validate([
            'kelaminid_delete' => 'required'
        ]);

        $kelamin = kelamin::find($request->kelaminid_delete);
        if (!$kelamin) {
            return response()->json([
                'success' => false,
                'message' => 'Kelamin tidak ditemukan!'
            ], 404);
        }
        $kelamin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelamin berhasil dihapus!'
        ]);
    }

    public function kelaminexport()
    {
        return Excel::download(new KelaminExport, 'kelamin.xlsx');
    }

    public function kelaminimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new KelaminImport, $request->file('file'));


        return redirect()->route('kelamin.get')->with('success', 'Data berhasil diimpor!');
    }
}
