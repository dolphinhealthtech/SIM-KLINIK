<?php

namespace App\Http\Controllers\Module\Pasien;

use App\Http\Controllers\Controller;
use App\Models\pasien;
use App\Models\pasien_antrian;
use Carbon\Carbon;
use Illuminate\Http\Request;


class Pasien_Api_Controller extends Controller
{
    /**
    * Mencari Pasien via Nomor Kartu BPJS Atau Nomor NIK
    */
    public function search_nik_noka(Request $request)
    {
        $nikNoka = $request->input('nikNoka');

        $data = Pasien::where('nik', $nikNoka)
            ->orWhere('no_bpjs', $nikNoka)
            ->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'nama' => $data->nama
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    /**
    * Mencari Pasien via Nama Atau Nomor NIK
    */
    public function search_nik_nama(Request $request)
    {
        $nikNama = $request->input('nikNama');
        $data = Pasien::where('nik', $nikNama)
            ->orWhere('nama', $nikNama)
            ->first();

        if ($data) {
            return response()->json([
                'success' => true,
                'nama' => $data->nama
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    /**
    * Nembuat nomor RM
    */
    public function create_no_rm()
    {
        $lastNoRM = pasien::max('no_rm');
        if ($lastNoRM) {
            $newNoRM = str_pad((int)$lastNoRM + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNoRM = '000001';
        }
        return $newNoRM;
    }
    /**
    * Nembuat nomor antrian
    */
    public function create_no_antrian()
    {
        $prefix = 'A-';
        $today = Carbon::today();
        $lastAntrian = pasien_antrian::whereDate('created_at', $today)
            ->orderBy('nomor_antrian', 'desc')
            ->first();
        if ($lastAntrian) {
            $lastNumber = (int) str_replace($prefix, '', $lastAntrian->nomor_antrian);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $nomorAntrian = $prefix . $nextNumber;
        return $nomorAntrian;
    }
    /**
    * Mencari Pasien By id
    */
    public function get_pasien($id)
    {
        $pasien = Pasien::with(['getnama'])->find($id);
        return response()->json($pasien);
    }
    /**
    * Memangil Pasien Untuk merubah status Pasien
    */
    public function call_pasien($id)
    {
        try {
            $antrian = pasien_antrian::where('pasien_id', $id)
                ->where('status_panggil', '0')
                ->orderBy('created_at', 'desc')
                ->first();
            if ($antrian) {
                $antrian->status_panggil = '1';
                $antrian->save();
                $pasien = pasien::find($id);
                return response()->json([
                    'success' => true,
                    'message' => 'Pasien ' . $pasien->nama . ' berhasil dipanggil.',
                    'data' => $antrian
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data antrian pasien tidak ditemukan atau sudah dipanggil.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
