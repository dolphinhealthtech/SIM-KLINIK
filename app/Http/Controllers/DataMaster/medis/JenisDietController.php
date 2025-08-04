<?php

namespace App\Http\Controllers\DataMaster\medis;

use App\Http\Controllers\Controller;
use App\Exports\Jenis_DietExport;
use App\Imports\Jenis_DietImport;
use App\Models\jenis_diet;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class JenisDietController extends Controller
{
    public function jenis_diet()
    {
        $title = "Master jenis_diet";
        $jenis_diet = jenis_diet::all();
        return view('module.master-data-medis.jenis_diet', compact('title', 'jenis_diet'));
    }

    public function jenis_dietadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:jenis_diets,nama',
            ]);

            $jenis_diet = jenis_diet::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'jenis diet berhasil ditambahkan!',
                'data' => $jenis_diet,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'jenis diet Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan jenis diet!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function jenis_dietedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $jenis_diet = jenis_diet::find($request->jenis_dietid_edit);

        if (!$jenis_diet) {
            return response()->json([
                'success' => false,
                'message' => 'jenis diet tidak ditemukan!'
            ], 404);
        }

        $jenis_diet->nama = $request->nama_edit;
        $jenis_diet->save();

        return response()->json([
            'success' => true,
            'message' => 'jenis diet berhasil diperbarui!'
        ]);
    }

    public function jenis_dietdelete(Request $request)
    {

        $request->validate([
            'jenis_dietid_delete' => 'required'
        ]);

        $jenis_diet = jenis_diet::find($request->jenis_dietid_delete);
        if (!$jenis_diet) {
            return response()->json([
                'success' => false,
                'message' => 'jenis diet tidak ditemukan!'
            ], 404);
        }
        $jenis_diet->delete();

        return response()->json([
            'success' => true,
            'message' => 'jenis diet berhasil dihapus!'
        ]);
    }

    public function jenis_dietexport()
    {
        return Excel::download(new Jenis_DietExport, 'jenis_diet.xlsx');
    }

    public function jenis_dietimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Jenis_DietImport, $request->file('file'));


        return redirect()->route('jenis_diet.get')->with('success', 'Data berhasil diimpor!');
    }
}
