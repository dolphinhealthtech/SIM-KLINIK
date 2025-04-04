<?php

namespace App\Http\Controllers;

use App\Exports\PoliExport;
use App\Exports\SpesialisExport;
use App\Exports\SubspesialisExport;
use App\Imports\PoliImport;
use App\Imports\SpesialisImport;
use App\Imports\SubspesialisImport;
use App\Models\poli;
use App\Models\spesialis;
use App\Models\subspesialis;
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


    public function subspesialisexport($kode)
    {
        return Excel::download(new SubspesialisExport($kode), 'Subspesialis-' . $kode . '.xlsx');
    }


    public function subspesialisimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SubspesialisImport, $request->file('file'));
        $kode = $request->input('kode');

        return redirect()->route('subspesialis.get', ['kode' => $kode])->with('success', 'Data berhasil diimpor!');
    }
}
