<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\alergi;
use App\Models\gcs_eye;
use App\Models\gcs_kesadaran;
use App\Models\gcs_motorik;
use App\Models\gcs_verbal;
use App\Models\gudang_barang;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
use App\Models\icd10;
use App\Models\icd9;
use App\Models\jenis_diet;
use App\Models\nama_makanan;
use App\Models\pelayanan;
use App\Models\pelayanan_soap_dokter;
use App\Models\pelayanan_soap_dokter_diet;
use App\Models\pelayanan_soap_dokter_icd;
use App\Models\pelayanan_soap_dokter_obat;
use App\Models\pelayanan_soap_dokter_tindakan;
use App\Models\pelayanan_soap_perawat;
use App\Models\perawatan_kategori;
use App\Models\perawatan_tindakan;
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

            $soap = pelayanan_soap_dokter::where('no_rawat', $item->nomor_register)->first();

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

        $kategori = perawatan_kategori::all();
        $tindakan = perawatan_tindakan::all();

        $jenis_diete = jenis_diet::all();
        $jenis_makanan_diet = nama_makanan::all();

        $obat = gudang_barang::all();
        return view('module.pelayanan.soap-dokter', compact('title','jenis_diete','obat','jenis_makanan_diet','tindakan','kategori','icd10','icd9','pelayanan','umur','gsc_eye','gcs_verbal','gcs_motorik','gcs_kesadaran','htt_pemeriksaan'));

    }

    public function soappelayananandd(Request $request)
    {
        $request->validate([
        'nomor_rm' => 'required|string',
        'nama' => 'required|string',
        'no_rawat' => 'required|string',
        'sex' => 'required|string',
        'penjamin' => 'required|string',
        'tanggal_lahir' => 'required|date',
        'umur' => 'nullable|string',
        'tableData' => 'nullable|string',
        'sistol' => 'nullable|numeric',
        'distol' => 'nullable|numeric',
        'tensi' => 'nullable|string',
        'suhu' => 'nullable|numeric',
        'nadi' => 'nullable|numeric',
        'rr' => 'nullable|numeric',
        'tinggi' => 'nullable|numeric',
        'berat' => 'nullable|numeric',
        'spo2' => 'nullable|numeric',
        'jenis_alergi' => 'nullable|string',
        'alergi' => 'nullable|string',
        'lingkar_perut' => 'nullable|numeric',
        'nilai_bmi' => 'nullable|numeric',
        'status_bmi' => 'nullable|string',
        'eye' => 'nullable|numeric',
        'verbal' => 'nullable|numeric',
        'motorik' => 'nullable|numeric',
        'summernote' => 'nullable|string',
        'summernote2' => 'nullable|string',
        'summernote3' => 'nullable|string',
        'summernote4' => 'nullable|string',
        'summernote5' => 'nullable|string',
        'icd10_code' => 'nullable|array',
        'icd10_name' => 'nullable|array',
        'icd10_priority' => 'nullable|array',
        'icd9_code' => 'nullable|array',
        'icd9_name' => 'nullable|array',
        'icd9_priority' => 'nullable|array',
        'diet_jenis' => 'nullable|array',
        'diet_anjuran' => 'nullable|array',
        'diet_pantangan' => 'nullable|array',
        'tindakan_nama' => 'nullable|array',
        'tindakan_pelaksana' => 'nullable|array',
        'tindakan_harga' => 'nullable|array',
        'resep_data' => 'nullable|string',
        ]);

        try {
            // Simpan data ke database
            $pemeriksaan = pelayanan_soap_dokter::create([
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
                'summernote2' => $request->summernote2,
                'summernote4' => $request->summernote4,
                'summernote5' => $request->summernote5,
            ]);

            // Simpan tindakan
            $namaTindakan = is_array($request->tindakan_nama) ? $request->tindakan_nama[0] : $request->tindakan_nama;
            $pelaksana = is_array($request->tindakan_pelaksana) ? implode(', ', $request->tindakan_pelaksana) : $request->tindakan_pelaksana;
            $harga = is_array($request->tindakan_harga) ? implode(', ', $request->tindakan_harga) : $request->tindakan_harga;

            pelayanan_soap_dokter_tindakan::create([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'Jenis_tindakan'=> $namaTindakan,
                'jenis_pelaksana'=> $pelaksana,
                'harga'=> $harga,
            ]);



            // Gabungkan array ICD-10 jadi string
            $namaIcd10 = is_array($request->icd10_name) ? implode(', ', $request->icd10_name) : null;
            $kodeIcd10 = is_array($request->icd10_code) ? implode(', ', $request->icd10_code) : null;
            $priorityIcd10 = is_array($request->icd10_priority) ? implode(', ', $request->icd10_priority) : null;

            // Gabungkan array ICD-9 jadi string
            $namaIcd9 = is_array($request->icd9_name) ? implode(', ', $request->icd9_name) : null;
            $kodeIcd9 = is_array($request->icd9_code) ? implode(', ', $request->icd9_code) : null;
            $priorityIcd9 = is_array($request->icd9_priority) ? implode(', ', $request->icd9_priority) : null;

            $pemeriksaan = pelayanan_soap_dokter_icd::create([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'nama_icd10' => $namaIcd10,
                'kode_icd10' => $kodeIcd10,
                'priority_icd10' => $priorityIcd10,
                'nama_icd9' => $namaIcd9,
                'kode_icd9' => $kodeIcd9,
                'priority_icd9' => $priorityIcd9,
            ]);

            $jenis = is_array($request->diet_jenis) ? implode(', ', $request->diet_jenis) : $request->diet_jenis;
            $anjuran = is_array($request->diet_anjuran) ? implode(', ', $request->diet_anjuran) : $request->diet_anjuran;
            $pantangan = is_array($request->diet_pantangan) ? implode(', ', $request->diet_pantangan) : $request->diet_pantangan;

            pelayanan_soap_dokter_diet::create([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'Jenis_diet' => $jenis,
                'jenis_diet_makanan' => $anjuran,
                'jenis_diet_makanan_tidak' => $pantangan,
            ]);


            pelayanan_soap_dokter_obat::create([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'Resep_obat' => $request->resep_data,
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

    public function soappelayanandata($nomor_rawat)
    {
        $data = pelayanan_soap_perawat::where('no_rawat', $nomor_rawat)->first();
        return response()->json($data);
    }

    public function dataLrawatJalan()
    {
        $title = "SOAP Rawat Jalan";
        return view('module.pelayanan.soap_rawat_jalan', compact('title'));
    }

    public function pelayana_rujukan($norawat)
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
        return view('module.pelayanan.pelayanan_rujuk', compact('title','pelayanan','umur','gsc_eye','gcs_verbal','gcs_motorik','gcs_kesadaran','htt_pemeriksaan'));
    }
}





