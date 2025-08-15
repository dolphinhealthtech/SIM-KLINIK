<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Medis\Pemeriksaan_Htt;

use App\Exports\Htt_pemeriksaanExport;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;
use App\Http\Controllers\Controller;
use App\Imports\Htt_pemeriksaanImport;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class Pemeriksaan_Htt_Sub_Controller extends Controller
{
    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function htt_sub_pemeriksaan($kode)
    {
        $title = "Master Data Sub Spesialis";
        $htt_sub_pemeriksaan = htt_sub_pemeriksaan::where('htt_pemeriksaan_id', $kode)->get();
        $htt_pemeriksaan = htt_pemeriksaan::find($kode);

        // spesialis
        return view('module.master-data.medis.htt-sub-pemeriksaan.index', compact('title', 'htt_sub_pemeriksaan', 'htt_pemeriksaan'));
    }

    public function htt_sub_pemeriksaanadd(Request $request)
    {
        try {
            $request->validate([
                "htt_sub_pemeriksaan_id" => 'required',
                "nama_sub_pemeriksaan" => 'required|string',
                "nama" => 'required|string',
            ]);

            $htt_sub_pemeriksaan = htt_sub_pemeriksaan::create([
                'htt_pemeriksaan_id' => $request->htt_sub_pemeriksaan_id,
                'nama_pemeriksaan' => $request->nama_sub_pemeriksaan,
                'nama_subpemeriksaan' => $request->nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'sub pemeriksaan berhasil ditambahkan!',
                'data' => $htt_sub_pemeriksaan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'sub pemeriksaan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan sub pemeriksaan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function htt_sub_pemeriksaanedit(Request $request)
    {
        $request->validate([
            "htt_sub_pemeriksaanid_edit" => 'required',
            "htt_sub_pemeriksaan_id_edit" => 'required',
            "nama_pemeriksaan_edit" => 'required|string',
            "nama_edit" => 'required|string',
        ]);

        $htt_sub_pemeriksaan = htt_sub_pemeriksaan::find($request->htt_sub_pemeriksaanid_edit);

        if (!$htt_sub_pemeriksaan) {
            return response()->json([
                'success' => false,
                'message' => 'sub pemeriksaan tidak ditemukan!'
            ], 404);
        }

        $htt_sub_pemeriksaan->htt_pemeriksaan_id = $request->htt_sub_pemeriksaan_id_edit;
        $htt_sub_pemeriksaan->nama_pemeriksaan = $request->nama_pemeriksaan_edit;
        $htt_sub_pemeriksaan->nama_subpemeriksaan = $request->nama_edit;
        $htt_sub_pemeriksaan->save();

        return response()->json([
            'success' => true,
            'message' => 'sub pemeriksaan berhasil diperbarui!'
        ]);
    }

    public function htt_sub_pemeriksaandelete(Request $request)
    {

        $request->validate([
            'htt_sub_pemeriksaanid_delete' => 'required'
        ]);

        $htt_sub_pemeriksaan = htt_sub_pemeriksaan::find($request->htt_sub_pemeriksaanid_delete);
        if (!$htt_sub_pemeriksaan) {
            return response()->json([
                'success' => false,
                'message' => 'sub pemeriksaan tidak ditemukan!'
            ], 404);
        }
        $htt_sub_pemeriksaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'sub pemeriksaan berhasil dihapus!'
        ]);
    }
}
