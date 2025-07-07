<?php

namespace App\Http\Controllers;

use App\Exports\AgamaExport;
use App\Exports\BahasaExport;
use App\Exports\BangsaExport;
use App\Exports\BankExport;
use App\Exports\GoldarExport;
use App\Exports\KelaminExport;
use App\Exports\LoketExport;
use App\Exports\PekerjaanExport;
use App\Exports\PendidikanExport;
use App\Exports\PernikahanExport;
use App\Exports\SukuExport;
use App\Exports\PenjaminExport;
use App\Imports\AgamaImport;
use App\Imports\BahasaImport;
use App\Imports\BangsaImport;
use App\Imports\BankImport;
use App\Imports\GoldarImport;
use App\Imports\KelaminImport;
use App\Imports\LoketImport;
use App\Imports\PekerjaanImport;
use App\Imports\PendidikanImport;
use App\Imports\PernikahanImport;
use App\Imports\SukuImport;
use App\Imports\PenjaminImport;
use App\Models\agama;
use App\Models\bahasa;
use App\Models\bangsa;
use App\Models\bank;
use App\Models\goldar;
use App\Models\kelamin;
use App\Models\loket;
use App\Models\pekerjaan;
use App\Models\pendidikan;
use App\Models\pernikahan;
use App\Models\suku;
use App\Models\penjamin;
use App\Models\poli;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class DataMasterController extends Controller
{
    // Golongan darah Start

    // Golongan darah end

    // Suku Start

    // Suku End

    // Bangsa Start

    // Bangsa end


    // Bahasa Start

    // Bahasa end

    // Agama start

    // Agama End

    // Pendidikan Start

    // Pendidikan End

    // Jenis Kelamin Start
    public function kelamin()
    {
        $title = "Master Jenis Kelamin";
        $kelamin = kelamin::all();
        return view('module.master-data.kelamin', compact('title', 'kelamin'));
    }

    public function kelaminadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:kelamins,nama',
                "urutan" => 'required|string|unique:kelamins,kode',
            ]);

            $kelamin = kelamin::create([
                'nama' => $request->nama,
                'kode' => $request->kode
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis Kelamin berhasil ditambahkan!',
                'data' => $kelamin
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis Kelamin Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Jenis Kelamin!',
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
            'message' => 'Jenis Kelamin berhasil diperbarui!'
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
                'message' => 'Jenis Kelamin tidak ditemukan!'
            ], 404);
        }
        $kelamin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis Kelamin berhasil dihapus!'
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
    // Kelamin End

    // Pernikahan Start
    public function pernikahan()
    {
        $title = "Master Pernikahan";
        $pernikahan = pernikahan::all();
        return view('module.master-data.pernikahan', compact('title', 'pernikahan'));
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
    // Pernikahan End


    // Pernikahan Start
    public function pekerjaan()
    {
        $title = "Master Pekerjaan";
        $pekerjaan = pekerjaan::all();
        return view('module.master-data.pekerjaan', compact('title', 'pekerjaan'));
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
    // Pernikahan End


    public function bank()
    {
        $title = "Master Bnak";
        $bank = bank::all();
        return view('module.master-data.bank', compact('title', 'bank'));
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

    // Penjamin

    public function penjamin()
    {
        $title = "Master Penjamin";
        $penjamin = penjamin::all();
        return view('module.master-data.penjamin', compact('title', 'penjamin'));
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

    // End Penjamin


    // antrian
    public function loket()
    {
        $title = "Master Loket Antrian";
        $loket = loket::with('poli')->get();
        $poli = poli::all();
        return view('module.master-data.loket', compact('title', 'loket', 'poli'));
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
