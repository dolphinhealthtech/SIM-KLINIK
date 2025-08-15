<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Medis\Nama_Makanan;

use App\Http\Controllers\Controller;
use App\Exports\nama_makananExport;
use App\Imports\nama_makananImport;
use App\Models\nama_makanan;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class Nama_Makanan_Controller extends Controller
{
    public function nama_makanan()
    {
        $title = "Master nama_makanan";
        $nama_makanan = nama_makanan::all();
        return view('module.master-data.medis.nama-makanan.index', compact('title', 'nama_makanan'));
    }

    public function nama_makananadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:nama_makanans,nama', // perhatikan nama tabel
            ]);

            $nama_makanan = nama_makanan::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'nama makanan berhasil ditambahkan!',
                'data' => $nama_makanan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'nama makanan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {


            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan nama makanan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function nama_makananedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $nama_makanan = nama_makanan::find($request->nama_makananid_edit);

        if (!$nama_makanan) {
            return response()->json([
                'success' => false,
                'message' => 'nama makanan tidak ditemukan!'
            ], 404);
        }

        $nama_makanan->nama = $request->nama_edit;
        $nama_makanan->save();

        return response()->json([
            'success' => true,
            'message' => 'nama makanan berhasil diperbarui!'
        ]);
    }

    public function nama_makanandelete(Request $request)
    {

        $request->validate([
            'nama_makananid_delete' => 'required'
        ]);

        $nama_makanan = nama_makanan::find($request->nama_makananid_delete);
        if (!$nama_makanan) {
            return response()->json([
                'success' => false,
                'message' => 'nama makanan tidak ditemukan!'
            ], 404);
        }
        $nama_makanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'nama makanan berhasil dihapus!'
        ]);
    }

    public function nama_makananexport()
    {
        return Excel::download(new nama_makananExport, 'nama_makanan.xlsx');
    }

    public function nama_makananimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new nama_makananImport, $request->file('file'));


        return redirect()->route('nama_makanan.get')->with('success', 'Data berhasil diimpor!');
    }
}
