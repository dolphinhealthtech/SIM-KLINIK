<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Medis\Kategori_Perawatan;

use App\Exports\Perawatan_kategoriExport;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;
use App\Http\Controllers\Controller;
use App\Imports\Perawatan_kategoriImport;
use App\Models\perawatan_kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class Kategori_Perawatan_Controller extends Controller
{
    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function kategori_perawatan()
    {
        $title = "Master Kategori Perawatan";
        $kategori_perawatan = perawatan_kategori::all();
        return view('module.master-data.medis.kategori-perawatan.index', compact('title', 'kategori_perawatan'));
    }

    public function kategori_perawatanadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:perawatan_kategoris,nama',
            ]);

            $kategori_perawatan = perawatan_kategori::create([
                'nama' => $request->nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori perawatan berhasil ditambahkan!',
                'data' => $kategori_perawatan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori perawatan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan kategori perawatan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function kategori_perawatanedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $kategori_perawatan = perawatan_kategori::find($request->kategori_perawatanid_edit);

        if (!$kategori_perawatan) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori perawatan tidak ditemukan!'
            ], 404);
        }

        $kategori_perawatan->nama = $request->nama_edit;
        $kategori_perawatan->save();

        return response()->json([
            'success' => true,
            'message' => 'Kategori perawatan berhasil diperbarui!'
        ]);
    }

    public function kategori_perawatandelete(Request $request)
    {

        $request->validate([
            'kategori_perawatanid_delete' => 'required'
        ]);

        $kategori_perawatan = perawatan_kategori::find($request->kategori_perawatanid_delete);
        if (!$kategori_perawatan) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori perawatan tidak ditemukan!'
            ], 404);
        }
        $kategori_perawatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori perawatan berhasil dihapus!'
        ]);
    }

    public function kategori_perawatanexport()
    {
        return Excel::download(new Perawatan_kategoriExport, 'Kategori Perawatan.xlsx');
    }

    public function kategori_perawatanimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Perawatan_kategoriImport, $request->file('file'));


        return redirect()->route('kategori_perawatan.get')->with('success', 'Data berhasil diimpor!');
    }
}
