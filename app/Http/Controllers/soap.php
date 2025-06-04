<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\alergi;
use App\Models\gcs_eye;
use App\Models\gcs_kesadaran;
use App\Models\gcs_motorik;
use App\Models\gcs_verbal;
use App\Models\gudang_barang;
use App\Models\gudang_satuan;
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
use App\Models\Pendaftaran_rawat_jalan;
use App\Models\perawatan_kategori;
use App\Models\perawatan_tindakan;
use App\Models\sarana;
use App\Models\spesialis;
use App\Models\subspesialis;
use App\Models\laboratorium_bidang;
use App\Models\laboratorium_bidang_sub;
use App\Models\radiologi_pemeriksaan;
use App\Models\radiologi_jenis;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class soap extends Controller
{
    public function pelayana()
    {
        $title = "Pelayanan";
        $pelayanan = Pelayanan::with([
        'poli',
        'dokter.namauser',
        'pasien',
        'pendaftaran.status',
        'pelayanan_soap'
    ])
    ->get()
    ->filter(function ($item) {
        $statusPanggil = $item->pendaftaran->status->status_panggil ?? null;
        $soapExists = $item->pelayanan_soap && $item->pelayanan_soap->isNotEmpty();

        return !($statusPanggil == 2 && $soapExists); // sembunyikan jika memenuhi syarat
    });


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
        // $pelayanan = pelayanan::with('poli','dokter.namauser', 'pasien','pendaftaran.status')->whereHas('pelayanan_so')->get();
        $pelayanan = Pelayanan::with('poli', 'dokter.namauser', 'pasien', 'pendaftaran.status')
        ->whereHas('pelayanan_so')
        ->whereHas('pendaftaran.status', function ($query) {
            $query->where('status_panggil', '!=', 3);
        })
        ->get();


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

    public function soappelayananselesai($norawat)
    {
        $nomor_rawat = base64_decode($norawat);

        $pelayanan = Pelayanan::with('pendaftaran.status')->where('nomor_register', $nomor_rawat)->first();

        if ($pelayanan && $pelayanan->pendaftaran && $pelayanan->pendaftaran->status) {
            $pelayanan->pendaftaran->status->status_panggil = 3;
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
        $satuan = gudang_satuan::all();
        return view('module.pelayanan.soap-dokter', compact('title','satuan','jenis_diete','obat','jenis_makanan_diet','tindakan','kategori','icd10','icd9','pelayanan','umur','gsc_eye','gcs_verbal','gcs_motorik','gcs_kesadaran','htt_pemeriksaan'));

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
                'htt' => $request->summernote,
                'assesmen' => $request->summernote2,
                'evaluasi' => $request->summernote3,
                'plan' => $request->summernote4,
                'expertise' => $request->summernote5,
                'status_apotek' => '0',
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
                'status_kasir'=> 0,
            ]);



            // Gabungkan array ICD-10 jadi string
            $namaIcd10 = is_array($request->icd10_name) ? implode(', ', $request->icd10_name) : null;
            $kodeIcd10 = is_array($request->icd10_code) ? implode(', ', $request->icd10_code) : null;
            $priorityIcd10 = is_array($request->icd10_priority) ? implode(', ', $request->icd10_priority) : null;

            // Gabungkan array ICD-9 jadi string
            $namaIcd9 = is_array($request->icd9_name) ? implode(', ', $request->icd9_name) : null;
            $kodeIcd9 = is_array($request->icd9_code) ? implode(', ', $request->icd9_code) : null;

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

    public function pelayana_rme($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Data RME";
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


        $step1 = Pendaftaran_rawat_jalan::with([
            'status',
            'poli',
            'dokter',
            'pasien',
            'penjamin',
            'soap_dokter',
            'apotek'
        ])->where('nomor_register', $nomor_rawat)->first();

        $timeline = [];

        // Step 1: Registrasi Rawat Jalan
        if ($step1) {
            $timeline[] = [
                'date' => $step1->created_at->format('d M Y'),
                'time' => $step1->created_at->format('H:i'),
                'icon' => 'fas fa-hospital-user',
                'bg' => 'bg-blue',
                'title' => 'Pasien Registrasi ke Rawat Jalan',
                'message' => 'Pasien dengan No. Rawat: ' . $step1->nomor_register .
                            ' atas nama ' . $step1->pasien->nama .
                            ' telah terdaftar di Rawat Jalan ke Poli ' . $step1->poli->nama
            ];
        }

        // Step 2: Pemeriksaan oleh Perawat
        $step2 = Pelayanan_soap_perawat::with([
            'alergi_keterangan',
            'gcs_eye',
            'gcs_verbal',
            'gcs_motorik',
        ])->where('no_rawat', $nomor_rawat)->first();

        if ($step2) {
            // Handle tableData (gejala)
            $tableData = json_decode($step2->tableData, true);
            $gejalaList = [];

            if (is_array($tableData)) {
                foreach ($tableData as $item) {
                    $penyakit = $item['penyakit'] ?? '-';
                    $durasi = $item['durasi'] ?? '-';
                    $waktu = $item['waktu'] ?? '';
                    $gejalaList[] = "Sakit {$penyakit} sejak {$durasi} {$waktu}";
                }
            }

            // Alergi
            $jenisAlergi = match($step2->jenis_alergi) {
                '00' => 'Tidak ada',
                '01' => 'Makanan',
                '02' => 'Obat',
                '03' => 'Udara',
                default => 'Tidak diketahui'
            };

            $namaAlergi = $step2->alergi_keterangan->nama_jenis_alergi ?? '-';
            $alergiText = $jenisAlergi === 'Tidak ada' ? 'Pasien tidak memiliki riwayat alergi.' :
                "Pasien memiliki alergi jenis <b>{$jenisAlergi}</b> terhadap <b>{$namaAlergi}</b>.";

            // Pemeriksaan tanda vital
            $pemeriksaan = [
                "Tensi: {$step2->tensi} mmHg",
                "Suhu: {$step2->suhu} °C",
                "Nadi: {$step2->nadi} bpm",
                "RR: {$step2->rr} x/menit",
                "Berat: {$step2->berat} kg",
                "Tinggi: {$step2->tinggi} cm",
                "Lingkar Perut: {$step2->lingkar_perut} cm",
                "Spo2: {$step2->spo2} %",
                "BMI: {$step2->nilai_bmi} ({$step2->status_bmi})"
            ];

            // GCS
            $eye = $step2->eye;
            $verbal = $step2->verbal;
            $motorik = $step2->motorik;
            $totalGCS = (int)$eye + (int)$verbal + (int)$motorik;

            $eyeDesc = $step2->gcs_eye->nama ?? '-';
            $verbalDesc = $step2->gcs_verbal->nama ?? '-';
            $motorikDesc = $step2->gcs_motorik->nama ?? '-';

            $kesadaran = gcs_kesadaran::where('skor', $totalGCS)->first()?->nama ?? '-';

            $gcsText = "GCS (E{$eye} V{$verbal} M{$motorik}) = {$totalGCS}<br>
                - Eye: {$eyeDesc}<br>
                - Verbal: {$verbalDesc}<br>
                - Motorik: {$motorikDesc}<br>
                - Kesadaran: {$kesadaran}";

            // Combine semua ke message
            $message = "";
            if (!empty($gejalaList)) {
                $message .= "<b>Keluhan Utama:</b><br>" . implode('<br>', $gejalaList) . "<br><br>";
            }

            $message .= "<b>Pemeriksaan Fisik:</b><br>" . implode('<br>', $pemeriksaan) . "<br><br>";
            $message .= "<b>GCS:</b><br>{$gcsText}<br><br>";
            $message .= "<b>Alergi:</b><br>{$alergiText}<br><br>";
            $message .= "<b>Head To Toe:</b><br>" . $step2->summernote;

            $timeline[] = [
                'date' => $step2->created_at->format('d M Y'),
                'time' => $step2->created_at->format('H:i'),
                'icon' => 'fas fa-user-nurse',
                'bg' => 'bg-green',
                'title' => 'Pemeriksaan Awal oleh Perawat',
                'message' => $message
            ];
        }


         $step3 = pelayanan_soap_dokter::with([
            'alergi_keterangan',
            'gcs_eye',
            'gcs_verbal',
            'gcs_motorik',
            'icd',
        ])->where('no_rawat', $nomor_rawat)->first();

        if ($step3) {
            // Handle tableData (gejala)
            $tableData = json_decode($step3->tableData, true);
            $gejalaList = [];

            if (is_array($tableData)) {
                foreach ($tableData as $item) {
                    $penyakit = $item['penyakit'] ?? '-';
                    $durasi = $item['durasi'] ?? '-';
                    $waktu = $item['waktu'] ?? '';
                    $gejalaList[] = "Sakit {$penyakit} sejak {$durasi} {$waktu}";
                }
            }

            // Alergi
            $jenisAlergi = match($step3->jenis_alergi) {
                '00' => 'Tidak ada',
                '01' => 'Makanan',
                '02' => 'Obat',
                '03' => 'Udara',
                default => 'Tidak diketahui'
            };

            $namaAlergi = $step3->alergi_keterangan->nama_jenis_alergi ?? '-';
            $alergiText = $jenisAlergi === 'Tidak ada' ? 'Pasien tidak memiliki riwayat alergi.' :
                "Pasien memiliki alergi jenis <b>{$jenisAlergi}</b> terhadap <b>{$namaAlergi}</b>.";

            // Pemeriksaan tanda vital
            $pemeriksaan = [
                "Tensi: {$step3->tensi} mmHg",
                "Suhu: {$step3->suhu} °C",
                "Nadi: {$step3->nadi} bpm",
                "RR: {$step3->rr} x/menit",
                "Berat: {$step3->berat} kg",
                "Tinggi: {$step3->tinggi} cm",
                "Lingkar Perut: {$step3->lingkar_perut} cm",
                "Spo2: {$step3->spo2} %",
                "BMI: {$step3->nilai_bmi} ({$step3->status_bmi})"
            ];

            // GCS
            $eye = $step3->eye;
            $verbal = $step3->verbal;
            $motorik = $step3->motorik;
            $totalGCS = (int)$eye + (int)$verbal + (int)$motorik;

            $eyeDesc = $step3->gcs_eye->nama ?? '-';
            $verbalDesc = $step3->gcs_verbal->nama ?? '-';
            $motorikDesc = $step3->gcs_motorik->nama ?? '-';

            $kesadaran = gcs_kesadaran::where('skor', $totalGCS)->first()?->nama ?? '-';

            $gcsText = "GCS (E{$eye} V{$verbal} M{$motorik}) = {$totalGCS}<br>
                - Eye: {$eyeDesc}<br>
                - Verbal: {$verbalDesc}<br>
                - Motorik: {$motorikDesc}<br>
                - Kesadaran: {$kesadaran}";

            // Combine semua ke message
            $message = "";
            if (!empty($gejalaList)) {
                $message .= "<b>Keluhan Utama:</b><br>" . implode('<br>', $gejalaList) . "<br><br>";
            }

            $message .= "<b>Pemeriksaan Fisik:</b><br>" . implode('<br>', $pemeriksaan) . "<br><br>";
            $message .= "<b>GCS:</b><br>{$gcsText}<br><br>";
            $message .= "<b>Alergi:</b><br>{$alergiText}<br><br>";
            $message .= "<b>Head To Toe:</b>" . $step3->htt;
            $message .= "<b>Assesmen:</b>" . $step3->assesmen;
            // $message .= "<b>ICD</b>" . $step3->icd;
            if ($step3->icd && $step3->icd->count() > 0) {
                $message .= "<b>ICD:</b><br>";
                foreach ($step3->icd as $icd) {
                    if (!$icd->kode_icd10 && !$icd->kode_icd9) {
                        continue; // skip data kosong
                    }
                    $message .= '<b>- Kode ICD-10:</b> ' . ($icd->kode_icd10 ?? '-') . ' - ' . ($icd->nama_icd10 ?? '-');
                    $message .= ' | <b>Prioritas:</b> ' . ($icd->priority_icd10 ?? '-') . '<br>';
                    $message .= '<b>- Kode ICD-9:</b> ' . ($icd->kode_icd9 ?? '-') . ' - ' . ($icd->nama_icd9 ?? '-') . '<br>';
                }
            }
            $message .= "<b>expertise:</b><br><br>" . $step3->expertise.'<br><br>';
            $message .= "<b>evaluasi:</b><br><br>" . $step3->evaluasi.'<br><br>';
            $message .= "<b>plan:</b><br><br>" . $step3->plan.'<br><br>';


            $timeline[] = [
                'date' => $step3->created_at->format('d M Y'),
                'time' => $step3->created_at->format('H:i'),
                'icon' => 'fas fa-user-nurse',
                'bg' => 'bg-green',
                'title' => 'Pemeriksaan Awal oleh Perawat',
                'message' => $message
            ];
        }



        return view('module.pelayanan.pelayanan_rme', compact('title','pelayanan','umur','timeline'));
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

        $sarana = sarana::all();
        $spesialis = spesialis::all();
        $subspesialis = subspesialis::all();

        return view('module.pelayanan.pelayanan_rujuk', compact('title','pelayanan','umur','sarana','spesialis','subspesialis'));
    }
    public function getSubSpesialis($kode)
    {
        $subSpesialis = subspesialis::where('kode_spesialis', $kode)->get();
        return response()->json($subSpesialis);
    }

    public function print(Request $request)
    {
        $resepList = json_decode($request->input('resep_data'), true);
        $pdf = Pdf::loadView('pdf.resep', ['resepList' => $resepList])->setPaper('a6', 'portrait');
        return $pdf->download('resep-obat.pdf'); // akan dibuka lewat blob di JS
    }

    public function pelayana_permintaan($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Permintaan Pengecekan";
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

        $data_icd9 = icd9::all();

        $data_lab = laboratorium_bidang::all();

        $radiologi_pemeriksaan = radiologi_pemeriksaan::all();

        $radiologi_jenis = radiologi_jenis::all();

        return view('module.pelayanan.pelayanan_permintaan', compact('title','pelayanan','umur','data_icd9','data_lab','radiologi_pemeriksaan','radiologi_jenis'));
    }

    public function getSubBidangLab($id)
    {
        if ($id === 'all') {
            $data = laboratorium_bidang_sub::all();
        } else {
            $data = laboratorium_bidang_sub::where('laboratorium_bidang_id', $id)->get();
        }

        return response()->json($data);
    }

    public function laboratoriumPrint(Request $request)
    {
        $labData = json_decode($request->lab_table_hidden, true);
        $diagnosa = $request->diagnosa_laboratorium;
        $tanggal = $request->tanggal_periksa_laboratorium;
        $catatan = $request->catatan_dokter_laboratorium;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $poli = $request->poli;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $penjamin = $request->penjamin;

        $pdf = PDF::loadView('pdf.permintaan_laboratorium', [
            'labData' => $labData,
            'diagnosa' => $diagnosa,
            'tanggal' => $tanggal,
            'catatan' => $catatan,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'poli' => $poli,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'penjamin' => $penjamin,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_laboratorium_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function radiologiPrint(Request $request)
    {
        $radData = json_decode($request->rad_table_hidden, true);
        $diagnosa = $request->diagnosa_radiologi;
        $tanggal = $request->tanggal_periksa_radiologi;
        $catatan = $request->catatan_dokter_radiologi;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $poli = $request->poli;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $penjamin = $request->penjamin;

        $pdf = PDF::loadView('pdf.permintaan_radiologi', [
            'radData' => $radData,
            'diagnosa' => $diagnosa,
            'tanggal' => $tanggal,
            'catatan' => $catatan,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'poli' => $poli,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'penjamin' => $penjamin,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_radiologi_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    //list pasien selesai
    public function pelayana_selesai()
    {
        $title = "list_pasien";
        $pelayanan = pelayanan::with('poli','dokter.namauser', 'pasien','pendaftaran.status')->whereHas('pelayanan_so')->get();


        return view('module.pelayanan.list_pasien_selesai', compact('title','pelayanan'));
    }

    //rme pasien selesai
    public function pelayana_rme_selesai($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Data RME selesai";
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


        $step1 = Pendaftaran_rawat_jalan::with([
            'status',
            'poli',
            'dokter',
            'pasien',
            'penjamin',
            'soap_dokter',
            'apotek'
        ])->where('nomor_register', $nomor_rawat)->first();

        $timeline = [];

        // Step 1: Registrasi Rawat Jalan
        if ($step1) {
            $timeline[] = [
                'date' => $step1->created_at->format('d M Y'),
                'time' => $step1->created_at->format('H:i'),
                'icon' => 'fas fa-hospital-user',
                'bg' => 'bg-blue',
                'title' => 'Pasien Registrasi ke Rawat Jalan',
                'message' => 'Pasien dengan No. Rawat: ' . $step1->nomor_register .
                            ' atas nama ' . $step1->pasien->nama .
                            ' telah terdaftar di Rawat Jalan ke Poli ' . $step1->poli->nama
            ];
        }

        // Step 2: Pemeriksaan oleh Perawat
        $step2 = Pelayanan_soap_perawat::with([
            'alergi_keterangan',
            'gcs_eye',
            'gcs_verbal',
            'gcs_motorik',
        ])->where('no_rawat', $nomor_rawat)->first();

        if ($step2) {
            // Handle tableData (gejala)
            $tableData = json_decode($step2->tableData, true);
            $gejalaList = [];

            if (is_array($tableData)) {
                foreach ($tableData as $item) {
                    $penyakit = $item['penyakit'] ?? '-';
                    $durasi = $item['durasi'] ?? '-';
                    $waktu = $item['waktu'] ?? '';
                    $gejalaList[] = "Sakit {$penyakit} sejak {$durasi} {$waktu}";
                }
            }

            // Alergi
            $jenisAlergi = match($step2->jenis_alergi) {
                '00' => 'Tidak ada',
                '01' => 'Makanan',
                '02' => 'Obat',
                '03' => 'Udara',
                default => 'Tidak diketahui'
            };

            $namaAlergi = $step2->alergi_keterangan->nama_jenis_alergi ?? '-';
            $alergiText = $jenisAlergi === 'Tidak ada' ? 'Pasien tidak memiliki riwayat alergi.' :
                "Pasien memiliki alergi jenis <b>{$jenisAlergi}</b> terhadap <b>{$namaAlergi}</b>.";

            // Pemeriksaan tanda vital
            $pemeriksaan = [
                "Tensi: {$step2->tensi} mmHg",
                "Suhu: {$step2->suhu} °C",
                "Nadi: {$step2->nadi} bpm",
                "RR: {$step2->rr} x/menit",
                "Berat: {$step2->berat} kg",
                "Tinggi: {$step2->tinggi} cm",
                "Lingkar Perut: {$step2->lingkar_perut} cm",
                "Spo2: {$step2->spo2} %",
                "BMI: {$step2->nilai_bmi} ({$step2->status_bmi})"
            ];

            // GCS
            $eye = $step2->eye;
            $verbal = $step2->verbal;
            $motorik = $step2->motorik;
            $totalGCS = (int)$eye + (int)$verbal + (int)$motorik;

            $eyeDesc = $step2->gcs_eye->nama ?? '-';
            $verbalDesc = $step2->gcs_verbal->nama ?? '-';
            $motorikDesc = $step2->gcs_motorik->nama ?? '-';

            $kesadaran = gcs_kesadaran::where('skor', $totalGCS)->first()?->nama ?? '-';

            $gcsText = "GCS (E{$eye} V{$verbal} M{$motorik}) = {$totalGCS}<br>
                - Eye: {$eyeDesc}<br>
                - Verbal: {$verbalDesc}<br>
                - Motorik: {$motorikDesc}<br>
                - Kesadaran: {$kesadaran}";

            // Combine semua ke message
            $message = "";
            if (!empty($gejalaList)) {
                $message .= "<b>Keluhan Utama:</b><br>" . implode('<br>', $gejalaList) . "<br><br>";
            }

            $message .= "<b>Pemeriksaan Fisik:</b><br>" . implode('<br>', $pemeriksaan) . "<br><br>";
            $message .= "<b>GCS:</b><br>{$gcsText}<br><br>";
            $message .= "<b>Alergi:</b><br>{$alergiText}<br><br>";
            $message .= "<b>Head To Toe:</b><br>" . $step2->summernote;

            $timeline[] = [
                'date' => $step2->created_at->format('d M Y'),
                'time' => $step2->created_at->format('H:i'),
                'icon' => 'fas fa-user-nurse',
                'bg' => 'bg-green',
                'title' => 'Pemeriksaan Awal oleh Perawat',
                'message' => $message
            ];
        }


         $step3 = pelayanan_soap_dokter::with([
            'alergi_keterangan',
            'gcs_eye',
            'gcs_verbal',
            'gcs_motorik',
            'icd',
        ])->where('no_rawat', $nomor_rawat)->first();

        if ($step3) {
            // Handle tableData (gejala)
            $tableData = json_decode($step3->tableData, true);
            $gejalaList = [];

            if (is_array($tableData)) {
                foreach ($tableData as $item) {
                    $penyakit = $item['penyakit'] ?? '-';
                    $durasi = $item['durasi'] ?? '-';
                    $waktu = $item['waktu'] ?? '';
                    $gejalaList[] = "Sakit {$penyakit} sejak {$durasi} {$waktu}";
                }
            }

            // Alergi
            $jenisAlergi = match($step3->jenis_alergi) {
                '00' => 'Tidak ada',
                '01' => 'Makanan',
                '02' => 'Obat',
                '03' => 'Udara',
                default => 'Tidak diketahui'
            };

            $namaAlergi = $step3->alergi_keterangan->nama_jenis_alergi ?? '-';
            $alergiText = $jenisAlergi === 'Tidak ada' ? 'Pasien tidak memiliki riwayat alergi.' :
                "Pasien memiliki alergi jenis <b>{$jenisAlergi}</b> terhadap <b>{$namaAlergi}</b>.";

            // Pemeriksaan tanda vital
            $pemeriksaan = [
                "Tensi: {$step3->tensi} mmHg",
                "Suhu: {$step3->suhu} °C",
                "Nadi: {$step3->nadi} bpm",
                "RR: {$step3->rr} x/menit",
                "Berat: {$step3->berat} kg",
                "Tinggi: {$step3->tinggi} cm",
                "Lingkar Perut: {$step3->lingkar_perut} cm",
                "Spo2: {$step3->spo2} %",
                "BMI: {$step3->nilai_bmi} ({$step3->status_bmi})"
            ];

            // GCS
            $eye = $step3->eye;
            $verbal = $step3->verbal;
            $motorik = $step3->motorik;
            $totalGCS = (int)$eye + (int)$verbal + (int)$motorik;

            $eyeDesc = $step3->gcs_eye->nama ?? '-';
            $verbalDesc = $step3->gcs_verbal->nama ?? '-';
            $motorikDesc = $step3->gcs_motorik->nama ?? '-';

            $kesadaran = gcs_kesadaran::where('skor', $totalGCS)->first()?->nama ?? '-';

            $gcsText = "GCS (E{$eye} V{$verbal} M{$motorik}) = {$totalGCS}<br>
                - Eye: {$eyeDesc}<br>
                - Verbal: {$verbalDesc}<br>
                - Motorik: {$motorikDesc}<br>
                - Kesadaran: {$kesadaran}";

            // Combine semua ke message
            $message = "";
            if (!empty($gejalaList)) {
                $message .= "<b>Keluhan Utama:</b><br>" . implode('<br>', $gejalaList) . "<br><br>";
            }

            $message .= "<b>Pemeriksaan Fisik:</b><br>" . implode('<br>', $pemeriksaan) . "<br><br>";
            $message .= "<b>GCS:</b><br>{$gcsText}<br><br>";
            $message .= "<b>Alergi:</b><br>{$alergiText}<br><br>";
            $message .= "<b>Head To Toe:</b>" . $step3->htt;
            $message .= "<b>Assesmen:</b>" . $step3->assesmen;
            // $message .= "<b>ICD</b>" . $step3->icd;
            if ($step3->icd && $step3->icd->count() > 0) {
                $message .= "<b>ICD:</b><br>";
                foreach ($step3->icd as $icd) {
                    if (!$icd->kode_icd10 && !$icd->kode_icd9) {
                        continue; // skip data kosong
                    }
                    $message .= '<b>- Kode ICD-10:</b> ' . ($icd->kode_icd10 ?? '-') . ' - ' . ($icd->nama_icd10 ?? '-');
                    $message .= ' | <b>Prioritas:</b> ' . ($icd->priority_icd10 ?? '-') . '<br>';
                    $message .= '<b>- Kode ICD-9:</b> ' . ($icd->kode_icd9 ?? '-') . ' - ' . ($icd->nama_icd9 ?? '-') . '<br>';
                }
            }
            $message .= "<b>expertise:</b><br><br>" . $step3->expertise.'<br><br>';
            $message .= "<b>evaluasi:</b><br><br>" . $step3->evaluasi.'<br><br>';
            $message .= "<b>plan:</b><br><br>" . $step3->plan.'<br><br>';


            $timeline[] = [
                'date' => $step3->created_at->format('d M Y'),
                'time' => $step3->created_at->format('H:i'),
                'icon' => 'fas fa-user-nurse',
                'bg' => 'bg-green',
                'title' => 'Pemeriksaan Awal oleh Perawat',
                'message' => $message
            ];
        }



        return view('module.pelayanan.list_pasien_selesai_rme', compact('title','pelayanan','umur','timeline'));
    }

}





