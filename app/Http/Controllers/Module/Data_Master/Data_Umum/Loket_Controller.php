<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Umum;

use App\Http\Controllers\Controller;
use App\Exports\LoketExport;
use App\Imports\LoketImport;
use App\Models\loket;
use App\Models\poli;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class Loket_Controller extends Controller
{
    public function loket()
    {
        $title = "Master Loket Antrian";
        $loket = loket::with('poli')->get();
        $poli = poli::all();
        return view('module.master-data.umum.loket.index', compact('title', 'loket', 'poli'));
    }

    public function loketadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:lokets,nama',
                "poli_id" => 'required',
            ]);

            $penjamin = loket::create([
                'nama' => $request->nama,
                'poli_id' => $request->poli_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Loket berhasil ditambahkan!',
                'data' => $penjamin
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loket Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Loket!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function loketedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'poli_edit' => 'required|string',
        ]);

        $loket = loket::find($request->loketid_edit);

        if (!$loket) {
            return response()->json([
                'success' => false,
                'message' => 'loket tidak ditemukan!'
            ], 404);
        }

        $loket->nama = $request->nama_edit;
        $loket->poli_id = $request->poli_edit;
        $loket->save();

        return response()->json([
            'success' => true,
            'message' => 'loket berhasil diperbarui!'
        ]);
    }

    public function loketdelete(Request $request)
    {

        $request->validate([
            'loketid_delete' => 'required'
        ]);

        $loket = loket::find($request->loketid_delete);
        if (!$loket) {
            return response()->json([
                'success' => false,
                'message' => 'loket tidak ditemukan!'
            ], 404);
        }
        $loket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loket berhasil dihapus!'
        ]);
    }


    public function loketexport()
    {
        return Excel::download(new LoketExport, 'loket.xlsx');
    }

    public function loketimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new LoketImport, $request->file('file'));


        return redirect()->route('loket.get')->with('success', 'Data berhasil diimpor!');
    }
}
