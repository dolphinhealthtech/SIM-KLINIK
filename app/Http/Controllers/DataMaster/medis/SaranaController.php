<?php

namespace App\Http\Controllers\DataMaster\medis;

use App\Http\Controllers\Controller;
use App\Exports\SaranaExport;
use App\Imports\SaranaImport;
use App\Models\sarana;
use App\Http\Controllers\PcareController;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class SaranaController extends Controller
{
    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function sarana()
    {
        $title = "Master Data Sarana";
        $poli = sarana::all();
        return view('module.master-data-medis.sarana', compact('title', 'poli'));
    }

    public function saranaadd()
    {

        $response = $this->PcareController->get_sarana_bpjs();
        $data = json_decode($response->getContent(), true);
        try {
            // Validasi struktur data
            if (!isset($data['data']['list']) || !is_array($data['data']['list'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dari BPJS tidak valid atau kosong.',
                ], 400);
            }

            // Siapkan data untuk upsert
            $dataInsert = collect($data['data']['list'])->map(function ($item) {
                return [
                    'kode' => $item['kdSarana'],
                    'nama' => $item['nmSarana'],
                ];
            })->toArray();

            // Simpan sekaligus: insert baru atau update jika sudah ada berdasarkan 'kode'
            sarana::upsert($dataInsert, ['kode'], ['nama']);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'sarana berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'sarana Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan sarana!',
                'data' => $data,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function saranadelete(Request $request)
    {

        $request->validate([
            'poliid_delete' => 'required'
        ]);

        $poli = sarana::find($request->poliid_delete);

        if (!$poli) {
            return response()->json([
                'success' => false,
                'message' => 'sarana tidak ditemukan!'
            ], 404);
        }

        $poli->delete();

        return response()->json([
            'success' => true,
            'message' => 'sarana berhasil dihapus!'
        ]);
    }

    public function saranaexport()
    {
        return Excel::download(new SaranaExport, 'Sarana.xlsx');
    }

    public function saranaimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SaranaImport, $request->file('file'));


        return redirect()->route('sarana.get')->with('success', 'Data berhasil diimpor!');
    }
}
