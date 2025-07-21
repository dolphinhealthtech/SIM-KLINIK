<?php

namespace App\Http\Controllers\DataMaster\main;

use App\Http\Controllers\Controller;
use App\Exports\AsuransiExport;
use App\Imports\AsuransiImport;
use App\Models\asuransi;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class AsuransiController extends Controller
{
    public function asuransi()
    {
         $title = "Master Asuransi";
         $asuransi = asuransi::all();
         return view('module.master-data.asuransi', compact('title','asuransi'));
    }

    public function asuransiadd(Request $request)
    {
         try {
             $request->validate([
                 "nama" => 'required|string|unique:asuransis,nama',
                 "kode" => 'required|string|unique:asuransis,kode',
             ]);

             $asuransi = asuransi::create([
                 'nama' => $request->nama,
                 'kode' => $request->kode,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'Asuransi berhasil ditambahkan!',
                 'data' => $asuransi
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Asuransi sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan asuransi!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function asuransiedit(Request $request)
    {
         $request->validate([
             'nama_edit' => 'required|string',
             'kode_edit' => 'required|string',
         ]);

         $asuransi = asuransi::find($request->asuransiid_edit);

         if (!$asuransi) {
             return response()->json([
                 'success' => false,
                 'message' => 'asuransi tidak ditemukan!'
             ], 404);
         }

         $asuransi->nama = $request->nama_edit;
         $asuransi->kode = $request->kode_edit;
         $asuransi->save();

         return response()->json([
             'success' => true,
             'message' => 'asuransi berhasil diperbarui!'
         ]);
    }

    public function asuransidelete(Request $request)
    {

        $request->validate([
            'asuransiid_delete' => 'required'
        ]);

        $asuransi = asuransi::find($request->asuransiid_delete);
        if (!$asuransi) {
            return response()->json([
                'success' => false,
                'message' => 'asuransi tidak ditemukan!'
            ], 404);
        }
        $asuransi->delete();

        return response()->json([
            'success' => true,
            'message' => 'asuransi berhasil dihapus!'
        ]);
    }

    public function asuransiexport()
    {
         return Excel::download(new AsuransiExport, 'Data_Asuransi.xlsx');
    }

    public function asuransiimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new AsuransiImport, $request->file('file'));


         return redirect()->route('asuransi.get')->with('success', 'Data berhasil diimpor!');
    }
}
