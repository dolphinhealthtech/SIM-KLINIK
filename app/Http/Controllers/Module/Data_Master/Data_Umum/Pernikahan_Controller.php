<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Umum;

use App\Http\Controllers\Controller;
use App\Exports\PernikahanExport;
use App\Imports\PernikahanImport;
use App\Models\pernikahan;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class Pernikahan_Controller extends Controller
{
    public function pernikahan()
    {
        $title = "Master Pernikahan";
        $pernikahan = pernikahan::all();
        return view('module.master-data.umum.pernikahan.index', compact('title','pernikahan'));
    }

    public function pernikahanadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:pernikahans,nama',
            ]);

            $pernikahan = pernikahan::create([
                'nama' => $request->nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'pernikahan berhasil ditambahkan!',
                'data' => $pernikahan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'pernikahan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pernikahan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pernikahanedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $pernikahan = pernikahan::find($request->pernikahanid_edit);

        if (!$pernikahan) {
            return response()->json([
                'success' => false,
                'message' => 'pernikahan tidak ditemukan!'
            ], 404);
        }

        $pernikahan->nama = $request->nama_edit;
        $pernikahan->save();

        return response()->json([
            'success' => true,
            'message' => 'pernikahan berhasil diperbarui!'
        ]);
    }

    public function pernikahandelete(Request $request)
    {

        $request->validate([
            'pernikahanid_delete' => 'required'
        ]);

        $pernikahan = pernikahan::find($request->pernikahanid_delete);
        if (!$pernikahan) {
            return response()->json([
                'success' => false,
                'message' => 'pernikahan tidak ditemukan!'
            ], 404);
        }
        $pernikahan->delete();

        return response()->json([
            'success' => true,
            'message' => 'pernikahan berhasil dihapus!'
        ]);
    }

    public function pernikahanexport()
    {
        return Excel::download(new PernikahanExport, 'Pernikahan.xlsx');
    }

    public function pernikahanimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PernikahanImport, $request->file('file'));


        return redirect()->route('pernikahan.get')->with('success', 'Data berhasil diimpor!');
    }
}
