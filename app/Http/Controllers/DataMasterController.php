<?php

namespace App\Http\Controllers;

use App\Exports\BangsaExport;
use App\Exports\GoldarExport;
use App\Exports\SukuExport;
use App\Imports\BangsaImport;
use App\Imports\GoldarImport;
use App\Imports\SukuImport;
use App\Models\bangsa;
use App\Models\goldar;
use App\Models\suku;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class DataMasterController extends Controller
{
    // Golongan darah Start
    public function darah()
    {
        $title = "Master Gologan Darah";
        $goldar = goldar::all();
        return view('module.master-data.goldar', compact('title','goldar'));
    }

    public function darahadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'rhesus' =>'string|nullable'
            ]);
            // Simpan data ke database
            $goldar = Goldar::create([
                'nama' => $request->input('nama'),   // Ambil data dari request
                'resus' => $request->input('rhesus')
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
                'message' => 'Goldar Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Goldar!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function darahedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'rhesus_edit' =>'string|nullable'
        ]);

        $goldar = goldar::find($request->goldarid_edit);

        if (!$goldar) {
            return response()->json([
                'success' => false,
                'message' => 'Goldar tidak ditemukan!'
            ], 404);
        }

        $goldar->nama = $request->nama_edit;
        $goldar->resus = $request->rhesus_edit;
        $goldar->save();

        return response()->json([
            'success' => true,
            'message' => 'Goldar berhasil diperbarui!'
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
                'message' => 'Goldar tidak ditemukan!'
            ], 404);
        }

        $goldar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Goldar berhasil dihapus!'
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
    // Golongan darah end

    // Suku Start
    public function suku()
    {
        $title = "Master Suku";
        $suku = suku::all();
        return view('module.master-data.suku', compact('title','suku'));
    }

    public function sukuadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:sukus,nama',
            ]);

            $suku = suku::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Suku berhasil ditambahkan!',
                'data' => $suku
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Suku Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Suku!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sukuedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $suku = suku::find($request->sukuid_edit);

        if (!$suku) {
            return response()->json([
                'success' => false,
                'message' => 'Suku tidak ditemukan!'
            ], 404);
        }

        $suku->nama = $request->nama_edit;
        $suku->save();

        return response()->json([
            'success' => true,
            'message' => 'suku berhasil diperbarui!'
        ]);
    }

    public function sukudelete(Request $request)
    {

        $request->validate([
            'sukuid_delete' => 'required'
        ]);

        $suku = suku::find($request->sukuid_delete);
        if (!$suku) {
            return response()->json([
                'success' => false,
                'message' => 'Suku tidak ditemukan!'
            ], 404);
        }
        $suku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Suku berhasil dihapus!'
        ]);
    }

    public function sukuexport()
    {
        return Excel::download(new SukuExport, 'suku.xlsx');
    }

    public function sukuimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SukuImport, $request->file('file'));


        return redirect()->route('suku.get')->with('success', 'Data berhasil diimpor!');
    }
    // Suku End

    // Bangsa Start
    public function bangsa()
    {
        $title = "Master Bangsa";
        $bangsa = bangsa::all();
        return view('module.master-data.bangsa', compact('title','bangsa'));
    }

    public function bangsaadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:bangsas,nama',
            ]);

            $bangsa = bangsa::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Suku berhasil ditambahkan!',
                'data' => $bangsa
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Suku Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Suku!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bangsaedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $bangsa = bangsa::find($request->bangsaid_edit);

        if (!$bangsa) {
            return response()->json([
                'success' => false,
                'message' => 'Bangsa tidak ditemukan!'
            ], 404);
        }

        $bangsa->nama = $request->nama_edit;
        $bangsa->save();

        return response()->json([
            'success' => true,
            'message' => 'Bangsa berhasil diperbarui!'
        ]);
    }

    public function bangsadelete(Request $request)
    {

        $request->validate([
            'bangsaid_delete' => 'required'
        ]);

        $bangsa = bangsa::find($request->bangsaid_delete);
        if (!$bangsa) {
            return response()->json([
                'success' => false,
                'message' => 'Bangsa tidak ditemukan!'
            ], 404);
        }
        $bangsa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bangsa berhasil dihapus!'
        ]);
    }

    public function bangsaexport()
    {
        return Excel::download(new BangsaExport, 'bangsa.xlsx');
    }

    public function bangsaimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new BangsaImport, $request->file('file'));


        return redirect()->route('bangsa.get')->with('success', 'Data berhasil diimpor!');
    }
    // Bangsa end
}
