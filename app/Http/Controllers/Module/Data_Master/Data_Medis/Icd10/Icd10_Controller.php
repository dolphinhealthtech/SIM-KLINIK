<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Medis\Icd10;

use App\Http\Controllers\Controller;
use App\Exports\Icd10Export;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;
use App\Imports\Icd10Import;
use App\Models\icd10;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class Icd10_Controller extends Controller
{

    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function icd10()
    {
        $title = "Master ICD 10";
        $icd10 = icd10::all();
        return view('module.master-data.medis.icd10.index', compact('title', 'icd10'));
    }

    public function icd10add(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'kode' => 'string|nullable'
            ]);
            // Simpan data ke database
            $goldar = icd10::create([
                'nama_icd10' => $request->input('nama'),   // Ambil data dari request
                'kode_icd10' => $request->input('kode')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'icd10 berhasil ditambahkan!',
                'data' => $goldar
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'icd10 Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan icd10!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function icd10edit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'kode_edit' => 'string|nullable'
        ]);

        $icd10 = icd10::find($request->icd10id_edit);

        if (!$icd10) {
            return response()->json([
                'success' => false,
                'message' => 'icd10 tidak ditemukan!'
            ], 404);
        }

        $icd10->nama_icd10 = $request->nama_edit;
        $icd10->kode_icd10 = $request->kode_edit;
        $icd10->save();

        return response()->json([
            'success' => true,
            'message' => 'icd10 berhasil diperbarui!'
        ]);
    }
    public function icd10delete(Request $request)
    {

        $request->validate([
            'icd10id_delete' => 'required'
        ]);

        $icd10 = icd10::find($request->icd10id_delete);

        if (!$icd10) {
            return response()->json([
                'success' => false,
                'message' => 'icd10 tidak ditemukan!'
            ], 404);
        }

        $icd10->delete();

        return response()->json([
            'success' => true,
            'message' => 'icd10 berhasil dihapus!'
        ]);
    }

    public function icd10singkron(Request $request)
    {
        $response = $this->PcareController->get_diagnosis_bpjs($request->kode_icd);
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data']['list'] as $item) {
                icd10::updateOrCreate(
                    [
                        'kode_icd10' => $item['kdDiag'],
                        'nama_icd10' => $item['nmDiag']
                    ]
                );
            }
            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'icd10 berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'icd10 Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan icd10!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // nama_makanan end
    public function icd10export()
    {
        return Excel::download(new Icd10Export, 'Macam-macam ICD10.xlsx');
    }

    public function icd10import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Icd10Import, $request->file('file'));
        return redirect()->route('icd10.get')->with('success', 'Data berhasil diimpor!');
    }
}
