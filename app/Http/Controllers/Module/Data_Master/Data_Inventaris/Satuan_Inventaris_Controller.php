<?php

namespace App\Http\Controllers\Data_Master\Data_Inventaris;

use App\Exports\Inventaris_satuanExport;
use App\Http\Controllers\Controller;
use App\Imports\Inventaris_satuanImport;
use App\Models\inventaris_satuan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class SatuanInventarisController extends Controller
{
    public function satuan_inventaris()
    {
        $title = "Master Jenis Satuan Inventaris";
        $satuan_inventaris = inventaris_satuan::all();
        return view('module.master-data-gudang.satuan_inventaris', compact('title', 'satuan_inventaris'));
    }

    public function satuan_inventarisadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string'
            ]);
            // Simpan data ke database
            $satuan = inventaris_satuan::create([
                'nama' => $request->input('nama')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Jenis satuan berhasil ditambahkan!',
                'data' => $satuan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis satuan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Jenis satuan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function satuan_inventarisedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string'
        ]);

        $satuan = inventaris_satuan::find($request->satuan_inventarisid_edit);

        if (!$satuan) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis satuan tidak ditemukan!'
            ], 404);
        }

        $satuan->nama = $request->nama_edit;
        $satuan->save();

        return response()->json([
            'success' => true,
            'message' => 'Jenis satuan berhasil diperbarui!'
        ]);
    }

    public function satuan_inventarisdelete(Request $request)
    {

        $request->validate([
            'satuan_inventarisid_delete' => 'required'
        ]);

        $satuan = inventaris_satuan::find($request->satuan_inventarisid_delete);

        if (!$satuan) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis satuan tidak ditemukan!'
            ], 404);
        }

        $satuan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis satuan berhasil dihapus!'
        ]);
    }

    public function satuan_inventarisexport()
    {
        return Excel::download(new Inventaris_satuanExport, 'Jenis Satuan Inventaris.xlsx');
    }

    public function satuan_inventarisimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Inventaris_satuanImport, $request->file('file'));


        return redirect()->route('satuan_inventaris.get')->with('success', 'Data berhasil diimpor!');
    }
}
