<?php

namespace App\Http\Controllers\DataMaster\medis;

use App\Http\Controllers\Controller;
use App\Exports\Radiologi_pemeriksaanExport;
use App\Imports\Radiologi_pemeriksaanImport;
use App\Models\radiologi_pemeriksaan;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class RadiologiPemeriksaanController extends Controller
{
    // pemeriksaan radiologi pemeriksaan
    public function radiologi_pemeriksaan()
    {
        $title = "Master Pemeriksaan Radiologi ";
        $radiologi_pemeriksaan = radiologi_pemeriksaan::all();
        return view('module.master-data.medis.radiologi-pemeriksaan.index', compact('title', 'radiologi_pemeriksaan'));
    }

    public function radiologi_pemeriksaanadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string',
            ]);

            $radiologi_pemeriksaan = radiologi_pemeriksaan::create([
                'nama' => $request->nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'radiologi pemeriksaan berhasil ditambahkan!',
                'data' => $radiologi_pemeriksaan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi pemeriksaan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan radiologi pemeriksaan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function radiologi_pemeriksaanedit(Request $request)
    {
        $request->validate([
            "nama_edit" => 'required|string',
        ]);

        $radiologi_pemeriksaan = radiologi_pemeriksaan::find($request->bidang_labotorium_edit);

        if (!$radiologi_pemeriksaan) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi pemeriksaan tidak ditemukan!'
            ], 404);
        }

        $radiologi_pemeriksaan->nama = $request->nama_edit;
        $radiologi_pemeriksaan->save();

        return response()->json([
            'success' => true,
            'message' => 'radiologi pemeriksaan berhasil diperbarui!'
        ]);
    }

    public function radiologi_pemeriksaandelete(Request $request)
    {

        $request->validate([
            'pemeriksaan_httid_delete' => 'required'
        ]);

        $radiologi_pemeriksaan = radiologi_pemeriksaan::find($request->pemeriksaan_httid_delete);
        if (!$radiologi_pemeriksaan) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi pemeriksaan tidak ditemukan!'
            ], 404);
        }
        $radiologi_pemeriksaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'radiologi pemeriksaan berhasil dihapus!'
        ]);
    }

    public function radiologi_pemeriksaanexport()
    {
        return Excel::download(new Radiologi_pemeriksaanExport, 'Radiologi Pemeriksaan.xlsx');
    }

    public function radiologi_pemeriksaaneimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Radiologi_pemeriksaanImport, $request->file('file'));


        return redirect()->route('radiologi_pemeriksaan.get')->with('success', 'Data berhasil diimpor!');
    }
}
