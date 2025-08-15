<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Inventaris\Kategori;

use App\Exports\Inventaris_kategoriExport;
use App\Http\Controllers\Controller;
use App\Imports\Inventaris_kategoriImport;
use App\Models\inventaris_kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class KategoriInventarisController  extends Controller
{
    public function katin()
    {
        $title = "Master Kategori Inventaris";
        $katin = inventaris_kategori::all();
        return view('module.master-data-gudang.kategori_inventaris', compact('title', 'katin'));
    }

    public function katinadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string'
            ]);
            // Simpan data ke database
            $kategori = inventaris_kategori::create([
                'nama' => $request->input('nama')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Jenis kategori berhasil ditambahkan!',
                'data' => $kategori
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Jenis kategori!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function katinedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string'
        ]);

        $kategori = inventaris_kategori::find($request->katinid_edit);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori tidak ditemukan!'
            ], 404);
        }

        $kategori->nama = $request->nama_edit;
        $kategori->save();

        return response()->json([
            'success' => true,
            'message' => 'Jenis kategori berhasil diperbarui!'
        ]);
    }

    public function katindelete(Request $request)
    {

        $request->validate([
            'katinid_delete' => 'required'
        ]);

        $kategori = inventaris_kategori::find($request->katinid_delete);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori tidak ditemukan!'
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis kategori berhasil dihapus!'
        ]);
    }

    public function katinexport()
    {
        return Excel::download(new Inventaris_kategoriExport, 'Jenis Kategori Inventaris.xlsx');
    }

    public function katinimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Inventaris_kategoriImport, $request->file('file'));


        return redirect()->route('katin.get')->with('success', 'Data berhasil diimpor!');
    }
}
