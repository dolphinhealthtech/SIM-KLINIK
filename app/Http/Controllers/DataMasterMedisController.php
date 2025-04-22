<?php

namespace App\Http\Controllers;

use App\Exports\PoliExport;
use App\Exports\SpesialisExport;
use App\Exports\SubspesialisExport;
use App\Exports\Perawatan_kategoriExport;
use App\Imports\PoliImport;
use App\Imports\SpesialisImport;
use App\Imports\SubspesialisImport;
use App\Imports\Perawatan_kategoriImport;
use App\Models\poli;
use App\Models\spesialis;
use App\Models\subspesialis;
use App\Models\perawatan_kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class DataMasterMedisController extends Controller
{
    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    // data poli
    public function poli()
    {
        $title = "Master Data Poli";
        $poli = poli::all();
        return view('module.master-data-medis.poli', compact('title','poli'));
    }

    public function poliadd()
    {

        $response = $this->PcareController->get_poli_fktp_bpjs();
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data'] as $item) {
                Poli::updateOrCreate(
                    [
                        'kode' => $item['kode_poli'],
                        'nama' => $item['nama_poli'],
                        'jenis' => $item['jenis_poli']
                    ]
                );
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Poli berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Poli Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Poli!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function polidelete(Request $request)
    {

        $request->validate([
            'poliid_delete' => 'required'
        ]);

        $poli = poli::find($request->poliid_delete);

        if (!$poli) {
            return response()->json([
                'success' => false,
                'message' => 'Poli tidak ditemukan!'
            ], 404);
        }

        $poli->delete();

        return response()->json([
            'success' => true,
            'message' => 'Poli berhasil dihapus!'
        ]);
    }

    public function poliexport()
    {
        return Excel::download(new PoliExport, 'Poli.xlsx');
    }

    public function poliimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PoliImport, $request->file('file'));


        return redirect()->route('poli.get')->with('success', 'Data berhasil diimpor!');
    }


    // data poli
    public function spesialis()
    {
        $title = "Master Data Spesialis";
        $spesialis = spesialis::all();
        // spesialis
        return view('module.master-data-medis.spesialis', compact('title','spesialis'));
    }

    public function spesialisadd()
    {

        $response = $this->PcareController->get_spesialis_bpjs();
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data']['list'] as $item) {
                spesialis::updateOrCreate(
                    [
                        'kode' => $item['kdSpesialis'],
                        'nama' => $item['nmSpesialis']
                    ]
                );
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Spesialis berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialis Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Spesialis!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function spesialisdelete(Request $request)
    {

        $request->validate([
            'spesialisid_delete' => 'required'
        ]);

        $spesialis = spesialis::find($request->spesialisid_delete);

        if (!$spesialis) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialis tidak ditemukan!'
            ], 404);
        }

        $spesialis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Spesialis berhasil dihapus!'
        ]);
    }

    public function spesialisexport()
    {
        return Excel::download(new SpesialisExport, 'Spesilais.xlsx');
    }

    public function spesialisimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SpesialisImport, $request->file('file'));


        return redirect()->route('speislais.get')->with('success', 'Data berhasil diimpor!');
    }

     // data poli
     public function subspesialis($kode)
    {
        $title = "Master Data Sub Spesialis";
        $subspesialis = subspesialis::where('kode_spesialis', $kode)->get();
        // spesialis
        return view('module.master-data-medis.subspesialis', compact('title','subspesialis','kode'));
    }

    public function subspesialisadd($kode)
    {

        $response = $this->PcareController->get_sub_spesialis_bpjs($kode);
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data']['list'] as $item) {
                subspesialis::updateOrCreate(
                    [
                        'nama' => $item['nmSubSpesialis'],
                        'kode' => $item['kdSubSpesialis'],
                        'kode_rujukan' => $item['kdPoliRujuk'],
                        'kode_spesialis' => $kode
                    ]
                );
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Spesialis berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialis Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Spesialis!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function subspesialisdelete(Request $request)
    {

        $request->validate([
            'subspesialisid_delete' => 'required'
        ]);

        $subspesialis = subspesialis::find($request->subspesialisid_delete);

        if (!$subspesialis) {
            return response()->json([
                'success' => false,
                'message' => 'subspesialis tidak ditemukan!'
            ], 404);
        }

        $subspesialis->delete();

        return response()->json([
            'success' => true,
            'message' => 'subspesialis berhasil dihapus!'
        ]);
    }

    // Kategori Perawatan

    public function kategori_perawatan()
    {
         $title = "Master Kategori Perawatan";
         $kategori_perawatan = perawatan_kategori::all();
         return view('module.master-data-medis.kategori_perawatan', compact('title','kategori_perawatan'));
    }

    public function kategori_perawatanadd(Request $request)
    {
         try {
             $request->validate([
                 "nama" => 'required|string|unique:perawatan_kategoris,nama',
             ]);

             $kategori_perawatan = perawatan_kategori::create([
                 'nama' => $request->nama,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'Kategori perawatan berhasil ditambahkan!',
                 'data' => $kategori_perawatan
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Kategori perawatan Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan kategori perawatan!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function kategori_perawatanedit(Request $request)
    {
         $request->validate([
             'nama_edit' => 'required|string',
         ]);

         $kategori_perawatan = perawatan_kategori::find($request->kategori_perawatanid_edit);

         if (!$kategori_perawatan) {
             return response()->json([
                 'success' => false,
                 'message' => 'Kategori perawatan tidak ditemukan!'
             ], 404);
         }

         $kategori_perawatan->nama = $request->nama_edit;
         $kategori_perawatan->save();

         return response()->json([
             'success' => true,
             'message' => 'Kategori perawatan berhasil diperbarui!'
         ]);
    }

    public function kategori_perawatandelete(Request $request)
    {

        $request->validate([
            'kategori_perawatanid_delete' => 'required'
        ]);

        $kategori_perawatan = perawatan_kategori::find($request->kategori_perawatanid_delete);
        if (!$kategori_perawatan) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori perawatan tidak ditemukan!'
            ], 404);
        }
        $kategori_perawatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori perawatan berhasil dihapus!'
        ]);
    }

    public function kategori_perawatanexport()
    {
         return Excel::download(new Perawatan_kategoriExport, 'Kategori Perawatan.xlsx');
    }

    public function kategori_perawatanimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new Perawatan_kategoriImport, $request->file('file'));


         return redirect()->route('kategori_perawatan.get')->with('success', 'Data berhasil diimpor!');
    }

    // End Kategori Perawatan

}
