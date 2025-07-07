<?php

namespace App\Http\Controllers\DataMaster\main;

use App\Http\Controllers\Controller;
use App\Exports\PenjaminExport;
use App\Imports\PenjaminImport;
use App\Models\penjamin;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class PenjaminController extends Controller
{
    public function penjamin()
    {
         $title = "Master Penjamin";
         $penjamin = penjamin::all();
         return view('module.master-data.penjamin', compact('title','penjamin'));
    }

    public function penjaminadd(Request $request)
    {
         try {
             $request->validate([
                 "nama" => 'required|string|unique:penjamins,nama',
             ]);

             $penjamin = penjamin::create([
                 'nama' => $request->nama,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'Penjamin berhasil ditambahkan!',
                 'data' => $penjamin
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Penjamin Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan Penjamin!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function penjaminedit(Request $request)
    {
         $request->validate([
             'nama_edit' => 'required|string',
         ]);

         $penjamin = penjamin::find($request->penjaminid_edit);

         if (!$penjamin) {
             return response()->json([
                 'success' => false,
                 'message' => 'Penjamin tidak ditemukan!'
             ], 404);
         }

         $penjamin->nama = $request->nama_edit;
         $penjamin->save();

         return response()->json([
             'success' => true,
             'message' => 'Penjamin berhasil diperbarui!'
         ]);
    }

    public function penjamindelete(Request $request)
    {

        $request->validate([
            'penjaminid_delete' => 'required'
        ]);

        $penjamin = penjamin::find($request->penjaminid_delete);
        if (!$penjamin) {
            return response()->json([
                'success' => false,
                'message' => 'Penjamin tidak ditemukan!'
            ], 404);
        }
        $penjamin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penjamin berhasil dihapus!'
        ]);
    }

    public function penjaminexport()
    {
         return Excel::download(new PenjaminExport, 'penjamin.xlsx');
    }

    public function penjaminimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new PenjaminImport, $request->file('file'));


         return redirect()->route('penjamin.get')->with('success', 'Data berhasil diimpor!');
    }
}
