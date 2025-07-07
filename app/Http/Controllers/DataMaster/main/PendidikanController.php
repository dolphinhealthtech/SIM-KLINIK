<?php

namespace App\Http\Controllers\DataMaster\main;

use App\Exports\PendidikanExport;
use App\Http\Controllers\Controller;
use App\Imports\PendidikanImport;
use App\Models\pendidikan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class PendidikanController extends Controller
{
    public function pendidikan()
    {
        $title = "Master Pendidikan";
        $pendidikan = pendidikan::all();
        return view('module.master-data.pendidikan', compact('title', 'pendidikan'));
    }

    public function pendidikanadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:pendidikans,nama',
                "kode" => 'required|string|unique:pendidikans,kode',
                "urutan" => 'required|string|unique:pendidikans,urutan',
            ]);

            $pendidikan = pendidikan::create([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'urutan' => $request->urutan,

            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendidikan berhasil ditambahkan!',
                'data' => $pendidikan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pendidikan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Pendidikan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pendidikanedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'kode_edit' => 'required|string',
            'urutan_edit' => 'required|string',
        ]);

        $pendidikan = pendidikan::find($request->pendidikanid_edit);

        if (!$pendidikan) {
            return response()->json([
                'success' => false,
                'message' => 'pendidikan tidak ditemukan!'
            ], 404);
        }

        $pendidikan->nama = $request->nama_edit;
        $pendidikan->kode = $request->kode_edit;
        $pendidikan->urutan = $request->urutan_edit;
        $pendidikan->save();

        return response()->json([
            'success' => true,
            'message' => 'Pendidikan berhasil diperbarui!'
        ]);
    }

    public function pendidikandelete(Request $request)
    {

        $request->validate([
            'pendidikanid_delete' => 'required'
        ]);

        $pendidikan = pendidikan::find($request->pendidikanid_delete);
        if (!$pendidikan) {
            return response()->json([
                'success' => false,
                'message' => 'pendidikan tidak ditemukan!'
            ], 404);
        }
        $pendidikan->delete();

        return response()->json([
            'success' => true,
            'message' => 'pendidikan berhasil dihapus!'
        ]);
    }

    public function pendidikanexport()
    {
        return Excel::download(new PendidikanExport, 'Pendidikan.xlsx');
    }

    public function pendidikanimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PendidikanImport, $request->file('file'));


        return redirect()->route('pendidikan.get')->with('success', 'Data berhasil diimpor!');
    }
}
