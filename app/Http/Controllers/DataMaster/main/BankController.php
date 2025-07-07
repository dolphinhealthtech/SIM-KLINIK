<?php

namespace App\Http\Controllers\DataMaster\main;

use App\Http\Controllers\Controller;
use App\Exports\BankExport;
use App\Imports\BankImport;
use App\Models\bank;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class BankController extends Controller
{
    public function bank()
    {
         $title = "Master Bnak";
         $bank = bank::all();
         return view('module.master-data.bank', compact('title','bank'));
    }

    public function bankadd(Request $request)
    {
         try {
             $request->validate([
                 "nama" => 'required|string|unique:banks,nama',
                 "kode" => 'required|string|unique:banks,kode',
             ]);

             $bank = bank::create([
                 'nama' => $request->nama,
                 'kode' => $request->kode,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'bank berhasil ditambahkan!',
                 'data' => $bank
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'bank Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan bank!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function bankedit(Request $request)
    {
         $request->validate([
             'nama_edit' => 'required|string',
             'kode_edit' => 'required|string',
         ]);

         $bank = bank::find($request->bankid_edit);

         if (!$bank) {
             return response()->json([
                 'success' => false,
                 'message' => 'bank tidak ditemukan!'
             ], 404);
         }

         $bank->nama = $request->nama_edit;
         $bank->kode = $request->kode_edit;
         $bank->save();

         return response()->json([
             'success' => true,
             'message' => 'bank berhasil diperbarui!'
         ]);
    }

    public function bankdelete(Request $request)
    {

        $request->validate([
            'bankid_delete' => 'required'
        ]);

        $bank = bank::find($request->bankid_delete);
        if (!$bank) {
            return response()->json([
                'success' => false,
                'message' => 'bank tidak ditemukan!'
            ], 404);
        }
        $bank->delete();

        return response()->json([
            'success' => true,
            'message' => 'bank berhasil dihapus!'
        ]);
    }

    public function bankexport()
    {
         return Excel::download(new BankExport, 'bank.xlsx');
    }

    public function bankimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new BankImport, $request->file('file'));


         return redirect()->route('bank.get')->with('success', 'Data berhasil diimpor!');
    }
}
