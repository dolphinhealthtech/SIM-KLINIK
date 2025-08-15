<?php

namespace App\Http\Controllers\DataMaster\medis;

use App\Http\Controllers\Controller;
use App\Models\alergi;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;
use Illuminate\Http\Request;


class AlergiController extends Controller
{

    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function alergi()
    {
        $title = "Master Data Alergi";
        $alergi = alergi::all();
        return view('module.master-data.medis.alergi.index', compact('title', 'alergi'));
    }

    public function alergiadd(Request $request)
    {

        $response = $this->PcareController->get_alergi_bpjs($request->jenis_alergi);
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data']['list'] as $item) {
                if ($item['kdAlergi'] != '00') {
                    alergi::updateOrCreate(
                        [
                            'kode_jenis_alergi' => $request->jenis_alergi,
                            'kode_alergi' => $item['kdAlergi'],
                            'nama_jenis_alergi' => $item['nmAlergi']
                        ]
                    );
                }
            }
            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Alergi berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Alergi Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Alergi!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function alergidelete(Request $request)
    {

        $request->validate([
            'alergiid_delete' => 'required'
        ]);

        $alergi = alergi::find($request->alergiid_delete);

        if (!$alergi) {
            return response()->json([
                'success' => false,
                'message' => 'alergi tidak ditemukan!'
            ], 404);
        }

        $alergi->delete();

        return response()->json([
            'success' => true,
            'message' => 'alergi berhasil dihapus!'
        ]);
    }
}
