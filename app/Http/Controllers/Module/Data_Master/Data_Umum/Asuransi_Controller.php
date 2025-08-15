<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Umum;

use App\Http\Controllers\Controller;
use App\Exports\AsuransiExport;
use App\Imports\AsuransiImport;
use App\Models\asuransi;
use App\Models\bank;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class Asuransi_Controller extends Controller
{
    public function asuransi()
    {
        $title = "Master Asuransi";
        $asuransi = asuransi::all();
        $bank = bank::all();
        return view('module.master-data.umum.asuransi.index', compact('title', 'asuransi', 'bank'));
    }

    public function asuransiadd(Request $request)
    {
        try {
            $request->validate([
                'nama'              => 'required|string|unique:asuransis,nama',
                'kode'              => 'required|string|unique:asuransis,kode',
                'jenis'             => 'nullable|string',
                'verifikasi'        => 'nullable|string',
                'filter_obat'       => 'nullable|string',
                'tgl_mulai'         => 'nullable|date',
                'tgl_akhir'         => 'nullable|date',
                'alamat'            => 'nullable|string',
                'no_telp_asuransi'  => 'nullable|string',
                'faksimil_asuransi' => 'nullable|string',
                'pic'               => 'nullable|string',
                'no_telp_pic'       => 'nullable|string',
                'jabatan_pic'       => 'nullable|string',
                'bank'              => 'nullable|string',
                'no_rekening'       => 'nullable|string',
            ]);

            // Simpan data ke DB
            $asuransi = asuransi::create([
                'nama'              => $request->nama,
                'kode'              => $request->kode,
                'jenis_asuransi'    => $request->jenis,
                'verif_pasien'      => $request->verifikasi,
                'filter_obat'       => $request->filter_obat,
                'tanggal_mulai'     => $request->tgl_mulai,
                'tanggal_akhir'     => $request->tgl_akhir,
                'alamat_asuransi'   => $request->alamat,
                'no_telp_asuransi'  => $request->no_telp_asuransi,
                'faksimil'          => $request->faksimil_asuransi,
                'pic'               => $request->pic,
                'no_telp_pic'       => $request->no_telp_pic,
                'jabatan_pic'       => $request->jabatan_pic,
                'bank'              => $request->bank,
                'no_rekening'       => $request->no_rekening,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asuransi berhasil ditambahkan!',
                'data' => $asuransi
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Asuransi sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan asuransi!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function asuransiedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'kode_edit' => 'required|string',
        ]);

        $asuransi = asuransi::find($request->asuransiid_edit);

        if (!$asuransi) {
            return response()->json([
                'success' => false,
                'message' => 'asuransi tidak ditemukan!'
            ], 404);
        }

        $asuransi->update([
            'nama'              => $request->nama_edit,
            'kode'              => $request->kode_edit,
            'jenis_asuransi'    => $request->jenis_edit,
            'verif_pasien'      => $request->verifikasi_edit,
            'filter_obat'       => $request->filter_obat_edit,
            'tanggal_mulai'     => $request->tgl_mulai_edit,
            'tanggal_akhir'     => $request->tgl_akhir_edit,
            'alamat_asuransi'   => $request->alamat_edit,
            'no_telp_asuransi'  => $request->no_telp_asuransi_edit,
            'faksimil'          => $request->faksimil_asuransi_edit,
            'pic'               => $request->pic_edit,
            'no_telp_pic'       => $request->no_telp_pic_edit,
            'jabatan_pic'       => $request->jabatan_pic_edit,
            'bank'              => $request->bank_edit,
            'no_rekening'       => $request->no_rekening_edit,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'asuransi berhasil diperbarui!'
        ]);
    }

    public function asuransidelete(Request $request)
    {

        $request->validate([
            'asuransiid_delete' => 'required'
        ]);

        $asuransi = asuransi::find($request->asuransiid_delete);
        if (!$asuransi) {
            return response()->json([
                'success' => false,
                'message' => 'asuransi tidak ditemukan!'
            ], 404);
        }
        $asuransi->delete();

        return response()->json([
            'success' => true,
            'message' => 'asuransi berhasil dihapus!'
        ]);
    }

    public function asuransiexport()
    {
        return Excel::download(new AsuransiExport, 'Data_Asuransi.xlsx');
    }

    public function asuransiimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new AsuransiImport, $request->file('file'));


        return redirect()->route('asuransi.get')->with('success', 'Data berhasil diimpor!');
    }
}
