<?php

namespace App\Http\Controllers\DataMaster\medis;

use App\Exports\Perawatan_tindakanExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PcareController;
use App\Imports\Perawatan_tindakanImport;
use App\Models\perawatan_kategori;
use App\Models\perawatan_tindakan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class PerawatanTindakanController extends Controller
{
    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }

        public function perawatan_tindakan()
    {
         $title = "Master Kategori Perawatan";
         $perawatan_tindakan = perawatan_tindakan::with('perawatan_kategori')->get();
         $kategori = perawatan_kategori::all();
         return view('module.master-data-medis.perawatan_tindakan', compact('title','perawatan_tindakan','kategori'));
    }

    public function perawatan_tindakanadd(Request $request)
    {
         try {
             $request->validate([
                 "kode" => 'required|string',
                 "nama" => 'required|string',
                 "kategori" => 'required|string',
                 "tarif_dokter" => 'required|string',
                 "tarif_perawat" => 'required|string',
                 "tarif_total" => 'required|string',
             ]);

             $perawatan_tindakan = perawatan_tindakan::create([
                 'kode' => $request->kode,
                 'nama' => $request->nama,
                 'perawatan_kategori_id' => $request->kategori,
                 'tarif_dokter' => $request->tarif_dokter,
                 'tarif_perawat' => $request->tarif_perawat,
                 'tarif_total' => $request->tarif_total,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'Perawatan tindakan berhasil ditambahkan!',
                 'data' => $perawatan_tindakan
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Perawatan tindakan Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan perawatan tindakan!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function perawatan_tindakanedit(Request $request)
    {
         $request->validate([
            "nama_edit" => 'required|string',
            "kategori_edit" => 'required|string',
            "tarif_dokter_edit" => 'required|string',
            "tarif_perawat_edit" => 'required|string',
            "tarif_total_edit" => 'required|string',
            "tarif_all_edit" => 'required|string',
         ]);

         $perawatan_tindakan = perawatan_tindakan::find($request->perawatan_tindakanid_edit);

         if (!$perawatan_tindakan) {
             return response()->json([
                 'success' => false,
                 'message' => 'Perawatan tindakan tidak ditemukan!'
             ], 404);
         }

         $perawatan_tindakan->nama = $request->nama_edit;
         $perawatan_tindakan->perawatan_kategori_id = $request->kategori_edit;
         $perawatan_tindakan->tarif_dokter = $request->tarif_dokter_edit;
         $perawatan_tindakan->tarif_perawat = $request->tarif_perawat_edit;
         $perawatan_tindakan->tarif_total = $request->tarif_total_edit;
         $perawatan_tindakan->tarif_all = $request->tarif_all_edit;
         $perawatan_tindakan->save();

         return response()->json([
             'success' => true,
             'message' => 'Perawatan tindakan berhasil diperbarui!'
         ]);
    }

    public function perawatan_tindakandelete(Request $request)
    {

        $request->validate([
            'perawatan_tindakanid_delete' => 'required'
        ]);

        $perawatan_tindakan = perawatan_tindakan::find($request->perawatan_tindakanid_delete);
        if (!$perawatan_tindakan) {
            return response()->json([
                'success' => false,
                'message' => 'Perawatan tindakan tidak ditemukan!'
            ], 404);
        }
        $perawatan_tindakan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perawatan tindakan berhasil dihapus!'
        ]);
    }

    public function perawatan_tindakanexport()
    {
         return Excel::download(new Perawatan_tindakanExport, 'Macam-macam perawatan tindakan.xlsx');
    }

    public function perawatan_tindakanimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new Perawatan_tindakanImport, $request->file('file'));


         return redirect()->route('perawatan_tindakan.get')->with('success', 'Data berhasil diimpor!');
    }

        // API Get Kode Kategori Perawatan

        public function getLastKode()
        {
            $last = perawatan_tindakan::orderBy('id', 'desc')->first();

            return response()->json([
                'kode' => $last ? $last->kode : null
            ]);
        }
}
