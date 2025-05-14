<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\alergi;
use App\Models\gcs_eye;
use App\Models\gcs_kesadaran;
use App\Models\gcs_motorik;
use App\Models\gcs_verbal;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
use App\Models\icd10;
use App\Models\icd9;
use App\Models\pelayanan;
use App\Models\pelayanan_soap_perawat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class soap extends Controller
{
    public function pelayana()
    {
        $title = "Pelayanan";
        $pelayanan = pelayanan::with('poli','dokter.namauser', 'pasien','pendaftaran.status')->get();

        foreach ($pelayanan as $item) {
            $status = $item->pendaftaran->status->status_panggil ?? 0;

            $soap = pelayanan_soap_perawat::where('no_rawat', $item->nomor_register)->first();

            if ($status == 0) {
                $item->tindakan_button = 'panggil';
            } elseif ($status == 1 && !$soap) {
                $item->tindakan_button = 'soap';
            } elseif ($status == 1 && $soap) {
                $item->tindakan_button = 'edit';
            } elseif ($status == 2){
                $item->tindakan_button = 'Complete';
            }
        }
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

    public function getByJenis($kode)
    {
        $data = alergi::where('kode_jenis_alergi', $kode)->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function sopelayanandd(Request $request)
    {
        $request->validate([
            'nomor_rm' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
            'no_rawat' => 'required|string|max:50',
            'sex' => 'required',
            'penjamin' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'umur' => 'required|string|max:50',
            'tableData' => 'required|json', // Pastikan tableData berformat JSON
            'sistol' => 'nullable|string|max:10',
            'distol' => 'nullable|string|max:10',
            'tensi' => 'nullable|string|max:10',
            'suhu' => 'nullable|string|max:10',
            'nadi' => 'nullable|string|max:10',
            'rr' => 'nullable|string|max:10',
            'tinggi' => 'nullable|string|max:10',
            'berat' => 'nullable|string|max:10',
            'spo2' => 'nullable|string|max:10',
            'lingkar_perut' => 'nullable|string|max:10',
            'nilai_bmi' => 'nullable|string|max:10',
            'status_bmi' => 'nullable|string|max:50',
            'jenis_alergi' => 'nullable|string|max:2',
            'alergi' => 'nullable|string|max:2',
            'eye' => 'nullable|integer',
            'verbal' => 'nullable|integer',
            'motorik' => 'nullable|integer',
            'summernote' => 'nullable|string',
        ]);

        try {
            // Simpan data ke database
            $pemeriksaan = pelayanan_soap_perawat::create([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'umur' => $request->umur,
                'tableData' => $request->tableData,
                'sistol' => $request->sistol,
                'distol' => $request->distol,
                'tensi' => $request->tensi,
                'suhu' => $request->suhu,
                'nadi' => $request->nadi,
                'rr' => $request->rr,
                'tinggi' => $request->tinggi,
                'berat' => $request->berat,
                'spo2' => $request->spo2,
                'lingkar_perut' => $request->lingkar_perut,
                'nilai_bmi' => $request->nilai_bmi,
                'status_bmi' => $request->status_bmi,
                'jenis_alergi' => $request->jenis_alergi,
                'alergi' => $request->alergi,
                'eye' => $request->eye,
                'verbal' => $request->verbal,
                'motorik' => $request->motorik,
                'summernote' => $request->summernote,
            ]);


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

    public function sopelayananpanggil($norawat)
    {
        $nomor_rawat = base64_decode($norawat);

        $pelayanan = Pelayanan::with('pendaftaran.status')->where('nomor_register', $nomor_rawat)->first();

        if ($pelayanan && $pelayanan->pendaftaran && $pelayanan->pendaftaran->status) {
            $pelayanan->pendaftaran->status->status_panggil = 1;
            $pelayanan->pendaftaran->status->save();

            return response()->json([
                'success' => true,
                'message' => 'Status panggil berhasil diperbarui.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data status tidak ditemukan.'
            ], 404);
        }


        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan atau belum memiliki status.'
        ], 404);
    }

    public function pelayana_dokter()
    {
        $title = "Pelayanan";
        $pelayanan = pelayanan::with('poli','dokter.namauser', 'pasien','pendaftaran.status')->whereHas('pelayanan_so')->get();

        foreach ($pelayanan as $item) {
            $status = $item->pendaftaran->status->status_panggil ?? 0;

            $soap = pelayanan_soap_perawat::where('no_rawat', $item->nomor_register)->first();

            if ($status == 1) {
                $item->tindakan_button = 'panggil';
            } elseif ($status == 2 && !$soap) {
                $item->tindakan_button = 'soap';
            } elseif ($status == 2 && $soap) {
                $item->tindakan_button = 'edit';
            }
        }
        return view('module.pelayanan.pelayanan_dokter', compact('title','pelayanan'));
    }

    public function soappelayananpanggil($norawat)
    {
        $nomor_rawat = base64_decode($norawat);

        $pelayanan = Pelayanan::with('pendaftaran.status')->where('nomor_register', $nomor_rawat)->first();

        if ($pelayanan && $pelayanan->pendaftaran && $pelayanan->pendaftaran->status) {
            $pelayanan->pendaftaran->status->status_panggil = 2;
            $pelayanan->pendaftaran->status->save();

            return response()->json([
                'success' => true,
                'message' => 'Status panggil berhasil diperbarui.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data status tidak ditemukan.'
            ], 404);
        }


        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan atau belum memiliki status.'
        ], 404);
    }

    public function soappelayanan($norawat)
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
        $icd10 = icd10::all();
        $icd9 = icd9::all();
        return view('module.pelayanan.soap-dokter', compact('title','icd10','icd9','pelayanan','umur','gsc_eye','gcs_verbal','gcs_motorik','gcs_kesadaran','htt_pemeriksaan'));

    }

    public function soappelayananandd(Request $request)
    {
        $request->validate([
            'nomor_rm' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
            'no_rawat' => 'required|string|max:50',
            'sex' => 'required',
            'penjamin' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'umur' => 'required|string|max:50',
            'tableData' => 'required|json', // Pastikan tableData berformat JSON
            'sistol' => 'nullable|string|max:10',
            'distol' => 'nullable|string|max:10',
            'tensi' => 'nullable|string|max:10',
            'suhu' => 'nullable|string|max:10',
            'nadi' => 'nullable|string|max:10',
            'rr' => 'nullable|string|max:10',
            'tinggi' => 'nullable|string|max:10',
            'berat' => 'nullable|string|max:10',
            'spo2' => 'nullable|string|max:10',
            'lingkar_perut' => 'nullable|string|max:10',
            'nilai_bmi' => 'nullable|string|max:10',
            'status_bmi' => 'nullable|string|max:50',
            'jenis_alergi' => 'nullable|string|max:2',
            'alergi' => 'nullable|string|max:2',
            'eye' => 'nullable|integer',
            'verbal' => 'nullable|integer',
            'motorik' => 'nullable|integer',
            'summernote' => 'nullable|string',
        ]);

        try {
            // Simpan data ke database
            $pemeriksaan = pelayanan_soap_perawat::create([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'umur' => $request->umur,
                'tableData' => $request->tableData,
                'sistol' => $request->sistol,
                'distol' => $request->distol,
                'tensi' => $request->tensi,
                'suhu' => $request->suhu,
                'nadi' => $request->nadi,
                'rr' => $request->rr,
                'tinggi' => $request->tinggi,
                'berat' => $request->berat,
                'spo2' => $request->spo2,
                'lingkar_perut' => $request->lingkar_perut,
                'nilai_bmi' => $request->nilai_bmi,
                'status_bmi' => $request->status_bmi,
                'jenis_alergi' => $request->jenis_alergi,
                'alergi' => $request->alergi,
                'eye' => $request->eye,
                'verbal' => $request->verbal,
                'motorik' => $request->motorik,
                'summernote' => $request->summernote,
            ]);


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
}
