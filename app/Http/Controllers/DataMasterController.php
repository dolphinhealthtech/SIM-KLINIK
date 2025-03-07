<?php

namespace App\Http\Controllers;

use App\Exports\agamaExport;
use App\Exports\BahasaExport;
use App\Exports\BangsaExport;
use App\Exports\GoldarExport;
use App\Exports\KelaminExport;
use App\Exports\PendidikanExport;
use App\Exports\SukuExport;
use App\Imports\AgamaImport;
use App\Imports\BahasaImport;
use App\Imports\BangsaImport;
use App\Imports\GoldarImport;
use App\Imports\KelaminImport;
use App\Imports\PendidikanImport;
use App\Imports\SukuImport;
use App\Models\agama;
use App\Models\bahasa;
use App\Models\bangsa;
use App\Models\goldar;
use App\Models\kelamin;
use App\Models\pendidikan;
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


    // Bahasa Start
    public function bahasa()
    {
        $title = "Master Bahasa";
        $bahasa = bahasa::all();
        return view('module.master-data.bahasa', compact('title','bahasa'));
    }

    public function bahasaadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:bahasas,nama',
            ]);

            $bahasa = bahasa::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bahasa berhasil ditambahkan!',
                'data' => $bahasa
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bahasa Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Bahasa!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bahasaedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $bahasa = bahasa::find($request->bahasaid_edit);

        if (!$bahasa) {
            return response()->json([
                'success' => false,
                'message' => 'Bahasa tidak ditemukan!'
            ], 404);
        }

        $bahasa->nama = $request->nama_edit;
        $bahasa->save();

        return response()->json([
            'success' => true,
            'message' => 'Bahasa berhasil diperbarui!'
        ]);
    }

    public function bahasadelete(Request $request)
    {

        $request->validate([
            'bahasaid_delete' => 'required'
        ]);

        $bahasa = bahasa::find($request->bahasaid_delete);
        if (!$bahasa) {
            return response()->json([
                'success' => false,
                'message' => 'Bahasa tidak ditemukan!'
            ], 404);
        }
        $bahasa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bahasa berhasil dihapus!'
        ]);
    }

    public function bahasaexport()
    {
        return Excel::download(new BahasaExport, 'bahasa.xlsx');
    }

    public function bahasaimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new BahasaImport, $request->file('file'));


        return redirect()->route('bahasa.get')->with('success', 'Data berhasil diimpor!');
    }
    // Bahasa end

    // Agama start
    public function agama()
    {
        $title = "Master Agama";
        $agama = agama::all();
        return view('module.master-data.agama', compact('title','agama'));
    }

    public function agamaadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:agamas,nama',
            ]);

            $agama = agama::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Agama berhasil ditambahkan!',
                'data' => $agama
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Agama Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Agama!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function agamaedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $agama = agama::find($request->agamaid_edit);

        if (!$agama) {
            return response()->json([
                'success' => false,
                'message' => 'Agama tidak ditemukan!'
            ], 404);
        }

        $agama->nama = $request->nama_edit;
        $agama->save();

        return response()->json([
            'success' => true,
            'message' => 'Agama berhasil diperbarui!'
        ]);
    }

    public function agamadelete(Request $request)
    {

        $request->validate([
            'agamaid_delete' => 'required'
        ]);

        $agama = agama::find($request->agamaid_delete);
        if (!$agama) {
            return response()->json([
                'success' => false,
                'message' => 'Agama tidak ditemukan!'
            ], 404);
        }
        $agama->delete();

        return response()->json([
            'success' => true,
            'message' => 'Agama berhasil dihapus!'
        ]);
    }

    public function agamaexport()
    {
        return Excel::download(new AgamaExport, 'Agama.xlsx');
    }

    public function agamaimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new AgamaImport, $request->file('file'));


        return redirect()->route('agama.get')->with('success', 'Data berhasil diimpor!');
    }
    // Agama End

    // Pendidikan Start
    public function pendidikan()
    {
        $title = "Master Pendidikan";
        $pendidikan = pendidikan::all();
        return view('module.master-data.pendidikan', compact('title','pendidikan'));
    }

    public function pendidikanadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:pendidikans,nama',
                "kode" => 'required|string|unique:pendidikans,kode',
            ]);

            $pendidikan = pendidikan::create([
                'nama' => $request->nama,
                'kode' => $request->kode
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendidikan berhasil ditambahkan!',
                'data' => $pendidikan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pendidikan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Pendidikan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pendidikanedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'kode_edit' => 'required|string',
        ]);

        $pendidikan = pendidikan::find($request->pendidikanid_edit);

        if (!$pendidikan) {
            return response()->json([
                'success' => false,
                'message' => 'Bahasa tidak ditemukan!'
            ], 404);
        }

        $pendidikan->nama = $request->nama_edit;
        $pendidikan->kode = $request->kode_edit;
        $pendidikan->save();

        return response()->json([
            'success' => true,
            'message' => 'Pendidikan berhasil diperbarui!'
        ]);
    }

    public function pendidikandelete(Request $request)
    {

        $request->validate([
            'pendidikanid_delete' => 'required'
        ]);

        $pendidikan = pendidikan::find($request->pendidikanid_delete);
        if (!$pendidikan) {
            return response()->json([
                'success' => false,
                'message' => 'pendidikan tidak ditemukan!'
            ], 404);
        }
        $pendidikan->delete();

        return response()->json([
            'success' => true,
            'message' => 'pendidikan berhasil dihapus!'
        ]);
    }

    public function pendidikanexport()
    {
        return Excel::download(new PendidikanExport, 'Pendidikan.xlsx');
    }

    public function pendidikanimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PendidikanImport, $request->file('file'));


        return redirect()->route('pendidikan.get')->with('success', 'Data berhasil diimpor!');
    }
    // Pendidikan End

    // Jenis Kelamin Start
    public function kelamin()
    {
        $title = "Master Jenis Kelamin";
        $kelamin = kelamin::all();
        return view('module.master-data.kelamin', compact('title','kelamin'));
    }

    public function kelaminadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:kelamins,nama',
                "kode" => 'required|string|unique:kelamins,kode',
            ]);

            $kelamin = kelamin::create([
                'nama' => $request->nama,
                'kode' => $request->kode
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kelamin berhasil ditambahkan!',
                'data' => $kelamin
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kelamin Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Kelamin!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function kelaminedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'kode_edit' => 'required|string',
        ]);

        $kelamin = kelamin::find($request->kelaminid_edit);

        if (!$kelamin) {
            return response()->json([
                'success' => false,
                'message' => 'Kelamin tidak ditemukan!'
            ], 404);
        }

        $kelamin->nama = $request->nama_edit;
        $kelamin->kode = $request->kode_edit;
        $kelamin->save();

        return response()->json([
            'success' => true,
            'message' => 'Kelamin berhasil diperbarui!'
        ]);
    }

    public function kelamindelete(Request $request)
    {

        $request->validate([
            'kelaminid_delete' => 'required'
        ]);

        $kelamin = kelamin::find($request->kelaminid_delete);
        if (!$kelamin) {
            return response()->json([
                'success' => false,
                'message' => 'Kelamin tidak ditemukan!'
            ], 404);
        }
        $kelamin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelamin berhasil dihapus!'
        ]);
    }

    public function kelaminexport()
    {
        return Excel::download(new KelaminExport, 'kelamin.xlsx');
    }

    public function kelaminimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new KelaminImport, $request->file('file'));


        return redirect()->route('kelamin.get')->with('success', 'Data berhasil diimpor!');
    }
    // Pendidikan End
}
