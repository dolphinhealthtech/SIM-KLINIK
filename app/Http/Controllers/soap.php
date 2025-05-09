<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\gcs_eye;
use App\Models\gcs_kesadaran;
use App\Models\gcs_motorik;
use App\Models\gcs_verbal;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
use App\Models\pelayanan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class soap extends Controller
{
    public function pelayana()
    {
        $title = "Pelayanan";
        $pelayanan = pelayanan::with('poli','dokter.namauser', 'pasien')->get();
        return view('module.pelayanan.pelayanan', compact('title','pelayanan'));
    }

    public function sopelayanan($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Pelayanan";
        $pelayanan = pelayanan::with('poli','dokter.namauser', 'pasien.kelamin','pendaftaran.penjamin')->where('nomor_register', $nomor_rawat)->first();

        $tgl_lahir = Carbon::createFromFormat('Y-m-d', $pelayanan->pasien->tanggal_lahir);
        $diff = $tgl_lahir->diff(Carbon::now());

        $umurTahun = $diff->y;
        $umurBulan = $diff->m;
        $umurHari = $diff->d;

        $umur = '';
        if ($umurTahun > 0) {
            $umur .= $umurTahun . ' Tahun ';
        }
        if ($umurBulan > 0 || $umurTahun > 0) {
            $umur .= $umurBulan . ' Bulan ';
        }
        $umur .= $umurHari . ' Hari';

        $gsc_eye = gcs_eye::all();
        $gcs_verbal = gcs_verbal::all();
        $gcs_motorik = gcs_motorik::all();
        $gcs_kesadaran = gcs_kesadaran::all();

        $htt_pemeriksaan = htt_pemeriksaan::all();
        return view('module.pelayanan.so-perawat', compact('title','pelayanan','umur','gsc_eye','gcs_verbal','gcs_motorik','gcs_kesadaran','htt_pemeriksaan'));

    }

    public function getSubPemeriksaan($id)
    {
        $sub = htt_sub_pemeriksaan::where('htt_pemeriksaan_id', $id)->get();
        return response()->json($sub);
    }

}
