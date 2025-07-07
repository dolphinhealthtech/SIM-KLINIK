<?php

namespace App\Http\Controllers\DataMaster\main;

use App\Http\Controllers\Controller;
use App\Exports\PekerjaanExport;
use App\Imports\PekerjaanImport;
use App\Models\pekerjaan;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class PekerjaanController extends Controller
{
    public function pekerjaan()
    {
         $title = "Master Pekerjaan";
         $pekerjaan = pekerjaan::all();
         return view('module.master-data.pekerjaan', compact('title','pekerjaan'));
    }

    public function pekerjaanadd(Request $request)
    {
         try {
             $request->validate([
                 "nama" => 'required|string|unique:pekerjaans,nama',
             ]);

             $pekerjaan = pekerjaan::create([
                 'nama' => $request->nama,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'pekerjaan berhasil ditambahkan!',
                 'data' => $pekerjaan
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'pekerjaan Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan pekerjaan!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function pekerjaanedit(Request $request)
    {
         $request->validate([
             'nama_edit' => 'required|string',
         ]);

         $pekerjaan = pekerjaan::find($request->pekerjaanid_edit);

         if (!$pekerjaan) {
             return response()->json([
                 'success' => false,
                 'message' => 'pekerjaan tidak ditemukan!'
             ], 404);
         }

         $pekerjaan->nama = $request->nama_edit;
         $pekerjaan->save();

         return response()->json([
             'success' => true,
             'message' => 'pekerjaan berhasil diperbarui!'
         ]);
    }

     public function pekerjaandelete(Request $request)
     {

         $request->validate([
             'pekerjaanid_delete' => 'required'
         ]);

         $pekerjaan = pekerjaan::find($request->pekerjaanid_delete);
         if (!$pekerjaan) {
             return response()->json([
                 'success' => false,
                 'message' => 'pekerjaan tidak ditemukan!'
             ], 404);
         }
         $pekerjaan->delete();

         return response()->json([
             'success' => true,
             'message' => 'pekerjaan berhasil dihapus!'
         ]);
     }

    public function pekerjaanexport()
    {
         return Excel::download(new PekerjaanExport, 'Pekerjaan.xlsx');
    }

    public function pekerjaanimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new PekerjaanImport, $request->file('file'));


         return redirect()->route('pekerjaan.get')->with('success', 'Data berhasil diimpor!');
    }
}
