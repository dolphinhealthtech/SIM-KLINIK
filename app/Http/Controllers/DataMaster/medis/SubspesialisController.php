<?php

namespace App\Http\Controllers\DataMaster\medis;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PcareController;
use App\Models\subspesialis;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubspesialisController extends Controller
{
    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }

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
                'message' => 'subspesialis berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'subspesialis Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan subspesialis!',
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
}
