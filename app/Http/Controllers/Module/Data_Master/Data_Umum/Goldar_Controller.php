<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Umum;

use App\Exports\GoldarExport;
use App\Http\Controllers\Controller;
use App\Imports\GoldarImport;
use App\Models\goldar;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;


class Goldar_Controller extends Controller
{
    public function darah()
    {
        $title = "Master Gologan Darah";
        $goldar = goldar::all();
        return view('module.master-data.umum.goldar.index', compact('title', 'goldar'));
    }

    public function darahadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'rhesus' => 'string|nullable'
            ]);
            $resus = $request->input('rhesus');

            // Jika dikirim string kosong atau 'null', simpan sebagai NULL
            $resus = ($resus === '' || $resus === 'null') ? null : $resus;

            $goldar = goldar::create([
                'nama' => $request->input('nama'),
                'resus' => $resus
            ]);


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Goldar berhasil ditambahkan!',
                'data' => $goldar
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Golongan darah Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Golongan darah!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function darahedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'rhesus_edit' => 'string|nullable'
        ]);

        $goldar = goldar::find($request->goldarid_edit);

        if (!$goldar) {
            return response()->json([
                'success' => false,
                'message' => 'Golongan darah tidak ditemukan!'
            ], 404);
        }

        $goldar->nama = $request->nama_edit;
        $goldar->resus = $request->rhesus_edit;
        $goldar->save();

        return response()->json([
            'success' => true,
            'message' => 'Golongan darah berhasil diperbarui!'
        ]);
    }

    public function darahdelete(Request $request)
    {

        $request->validate([
            'goldarid_delete' => 'required'
        ]);

        $goldar = goldar::find($request->goldarid_delete);

        if (!$goldar) {
            return response()->json([
                'success' => false,
                'message' => 'Golongan darah tidak ditemukan!'
            ], 404);
        }

        $goldar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Golongan darah berhasil dihapus!'
        ]);
    }

    public function darahexport()
    {
        return Excel::download(new GoldarExport, 'goldar.xlsx');
    }

    public function darahimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new GoldarImport, $request->file('file'));


        return redirect()->route('goldar.get')->with('success', 'Data berhasil diimpor!');
    }
}
