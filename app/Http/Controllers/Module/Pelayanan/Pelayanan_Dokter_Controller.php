<?php

namespace App\Http\Controllers\Module\Pelayanan;

use App\Http\Controllers\Controller;
use App\Models\pelayanan;
use App\Models\gcs_eye;
use App\Models\gcs_verbal;
use App\Models\gcs_motorik;
use App\Models\gcs_kesadaran;
use App\Models\htt_pemeriksaan;
use App\Models\pelayanan_soap_dokter;
use App\Models\pelayanan_soap_dokter_icd;
use App\Models\pelayanan_soap_dokter_obat;
use App\Models\pelayanan_soap_dokter_diet;
use App\Models\pelayanan_soap_dokter_tindakan;
use App\Models\icd10;
use App\Models\icd9;
use App\Models\perawatan_kategori;
use App\Models\perawatan_tindakan;
use App\Models\gudang_barang;
use App\Models\gudang_satuan;
use App\Models\jenis_diet;
use App\Models\nama_makanan;
use App\Http\Controllers\PcareController;
use App\Models\dokter;
use App\Models\laboratorium_bidang;
use App\Models\radiologi_pemeriksaan;
use App\Models\radiologi_jenis;
use App\Models\kode_surat;
use App\Models\pelayanan_rujukan;
use App\Models\sarana;
use App\Models\spesialis;
use App\Models\subspesialis;
use App\Models\WebSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;

class Pelayanan_Dokter_Controller extends Controller
{
    protected $PcareController;

    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function pelayana_dokter()
    {
        $title = "Pelayanan";

        $user = Auth::user(); // Ambil user yang login
        $pelayananQuery = Pelayanan::with([
            'poli',
            'dokter.namauser',
            'pasien',
            'pendaftaran.status'
        ])
            ->whereHas('pelayanan_so') // Pastikan ada SOAP
            ->whereHas('pendaftaran.status')
            ->whereDate('created_at', Carbon::today());


        if (isset($user->role) && $user->role === 'Dokter') {
            $dokterId = Dokter::where('users', $user->id)->value('id');

            if ($dokterId) {
                $pelayananQuery->where('dokter_id', $dokterId);
            }
        }

        $pelayanan = $pelayananQuery->get();


        foreach ($pelayanan as $item) {
            $status = $item->pendaftaran->status->status_panggil ?? 0;

            $soap = pelayanan_soap_dokter::where('no_rawat', $item->nomor_register)->first();

            if ($status == 1) {
                $item->tindakan_button = 'panggil';
            } elseif ($status == 2 && !$soap) {
                $item->tindakan_button = 'soap';
            } elseif ($status == 2 && $soap) {
                $item->tindakan_button = 'edit';
            } elseif ($status == 3) {
                $item->tindakan_button = 'Complete';
            }
        }
        return view('module.pelayanan.pelayanan-dokter.index', compact('title', 'pelayanan'));
    }



    public function soappelayananselesai($norawat)
    {
        $nomor_rawat = base64_decode($norawat);

        $pelayanan = Pelayanan::with('pendaftaran.status')->where('nomor_register', $nomor_rawat)->first();

        if (!$pelayanan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelayanan tidak ditemukan.'
            ], 404);
        }
        $soap = pelayanan_soap_dokter::where('no_rawat', $pelayanan->nomor_register)->first();

        // Jika penjamin adalah BPJS
        if ($soap->penjamin === "BPJS") {

            if (!$soap) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data SOAP tidak ditemukan.'
                ], 404);
            }

            // Proses keluhan
            $output = 'Keluhan tidak tersedia';
            if (!empty($soap->tableData)) {
                $array = json_decode($soap->tableData, true);
                if (is_array($array)) {
                    $hasil = [];
                    foreach ($array as $item) {
                        $hasil[] = "{$item['penyakit']} {$item['durasi']} " . strtolower($item['waktu']);
                    }
                    $output = implode(', ', $hasil);
                }
            }

            // GCS Kesadaran
            $totalSkor = (int) $soap->eye + (int) $soap->verbal + (int) $soap->motorik;
            $kesadaran = gcs_kesadaran::where('skor', $totalSkor)->first();
            $kdSadar = $kesadaran?->kode ?? '01'; // fallback ke '01' jika tidak ditemukan

            // Diagnosa (ICD)
            $icds = pelayanan_soap_dokter_icd::where('no_rawat', $soap->no_rawat)
                ->where('nomor_rm', $soap->nomor_rm)
                ->pluck('kode_icd10')
                ->toArray();


            // Gabungkan semua kode ICD menjadi satu string, lalu pisahkan per koma
            $allCodes = implode(',', $icds);
            $diagnosa = array_slice(array_map('trim', explode(',', $allCodes)), 0, 3);

            $dataDiag = [];
            foreach ($diagnosa as $i => $kode) {
                $dataDiag["kdDiag" . ($i + 1)] = $kode;
            }

            if (empty($dataDiag)) {
                $dataDiag['kdDiag1'] = 'Z00.0'; // Diagnosa default
            }

            // Bangun payload
            $kunjunganPayload = array_merge([
                "noKunjungan" => null,
                "noKartu" => $pelayanan->pasien->no_bpjs ?? '',
                "tglDaftar" => now()->format('d-m-Y'),
                "kdPoli" => $pelayanan->poli->kode ?? '',
                "keluhan" => $output,
                "kdSadar" => $kdSadar,
                "sistole" => $soap->sistol,
                "diastole" => $soap->distol,
                "beratBadan" => $soap->berat,
                "tinggiBadan" => $soap->tinggi,
                "respRate" => $soap->rr,
                "heartRate" => $soap->nadi,
                "lingkarPerut" => $soap->lingkar_perut,
                "kdStatusPulang" => "3",
                "tglPulang" => now()->format('d-m-Y'),
                "kdDokter" => $pelayanan->dokter->kode ?? '',
                "kdPoliRujukInternal" => null,
                "rujukLanjut" => null,
                "kdTacc" => 0,
                "alasanTacc" => null,
                "anamnesa" => $output,
                "alergiMakan" => $pelayanan->alergi_makanan ?? '00',
                "alergiUdara" => $pelayanan->alergi_udara ?? '00',
                "alergiObat" => $pelayanan->alergi_obat ?? '00',
                "kdPrognosa" => "01",
                "terapiObat" => $pelayanan->terapi_obat ?? "tidak ada",
                "terapiNonObat" => $pelayanan->terapi_nonobat ?? "tidak ada",
                "bmhp" => $soap->bmhp ?? '',
                "suhu" => $soap->suhu ?? " 0",
            ], $dataDiag);

            try {
                $response = $this->PcareController->post_kunjungan_bpjs($kunjunganPayload);

                // Ambil content dan decode JSON
                $content = $response->getContent();
                $data = json_decode($content, true);

                // Ambil nomor kunjungan sesuai struktur response BPJS
                if (isset($data['data']) && is_array($data['data']) && count($data['data']) > 0) {
                    $noKunjungan = $data['data'][0]['message'] ?? null;
                } else {
                    $noKunjungan = null;
                }

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal kirim kunjungan ke BPJS.',
                    'error' => $e->getMessage()
                ], 500);
            }

        }


        // Simpan status selesai dan nomor kunjungan ke database
        if ($pelayanan->pendaftaran && $pelayanan->pendaftaran->status) {
            $pelayanan->pendaftaran->status->status_panggil = 3;
            $pelayanan->pendaftaran->status->save();
            if($soap->penjamin === "BPJS")
            {
                $pelayanan->kunjungan = $noKunjungan;
                $pelayanan->save();
            }else{
                $pelayanan->kunjungan = "Umum";
                $pelayanan->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Status panggil berhasil diperbarui.',
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
        $pelayanan = pelayanan::with('poli', 'dokter.namauser', 'pasien.kelamin', 'pendaftaran.penjamin')->where('nomor_register', $nomor_rawat)->first();

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

        return view('module.pelayanan.soap-dokter', compact('title', 'satuan', 'jenis_diete', 'obat', 'jenis_makanan_diet', 'tindakan', 'kategori', 'icd10', 'icd9', 'pelayanan', 'umur', 'gsc_eye', 'gcs_verbal', 'gcs_motorik', 'gcs_kesadaran', 'htt_pemeriksaan'));
    }

    public function soappelayananedit($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Pelayanan";
        $pelayanan = pelayanan::with('poli', 'dokter.namauser', 'pasien.kelamin', 'pendaftaran.penjamin')->where('nomor_register', $nomor_rawat)->first();

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


        $pelayana_data = Pelayanan::where('nomor_rm', $pelayanan->nomor_rm)->get();
        // dd($pelayana_data);
        return view('module.pelayanan.soap-dokter_edit', compact('title', 'pelayana_data', 'satuan', 'jenis_diete', 'obat', 'jenis_makanan_diet', 'tindakan', 'kategori', 'icd10', 'icd9', 'pelayanan', 'umur', 'gsc_eye', 'gcs_verbal', 'gcs_motorik', 'gcs_kesadaran', 'htt_pemeriksaan'));
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
                'Jenis_tindakan' => $namaTindakan,
                'jenis_pelaksana' => $pelaksana,
                'harga' => $harga,
                'status_kasir' => 0,
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
                'message' => 'Pelayanan soap dokter berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pelayanan soap dokter Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pelayanan soap dokter!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function soappelayananupdate(Request $request)
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
            $pemeriksaan = pelayanan_soap_dokter::where('no_rawat', $request->no_rawat)->update([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'umur' => $request->umur,
                'tableData' => json_encode($request->tableData),
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

            pelayanan_soap_dokter_tindakan::where('no_rawat', $request->no_rawat)->update([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'Jenis_tindakan' => $namaTindakan,
                'jenis_pelaksana' => $pelaksana,
                'harga' => $harga,
                'status_kasir' => 0,
            ]);



            // Gabungkan array ICD-10 jadi string
            $namaIcd10 = is_array($request->icd10_name) ? implode(', ', $request->icd10_name) : null;
            $kodeIcd10 = is_array($request->icd10_code) ? implode(', ', $request->icd10_code) : null;
            $priorityIcd10 = is_array($request->icd10_priority) ? implode(', ', $request->icd10_priority) : null;

            // Gabungkan array ICD-9 jadi string
            $namaIcd9 = is_array($request->icd9_name) ? implode(', ', $request->icd9_name) : null;
            $kodeIcd9 = is_array($request->icd9_code) ? implode(', ', $request->icd9_code) : null;

            $pemeriksaan = pelayanan_soap_dokter_icd::where('no_rawat', $request->no_rawat)->update([
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

            pelayanan_soap_dokter_diet::where('no_rawat', $request->no_rawat)->update([
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


            pelayanan_soap_dokter_obat::where('no_rawat', $request->no_rawat)->update([
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
                'message' => 'Pelayanan soap dokter berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pelayanan soap dokter Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pelayanan soap dokter!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pelayana_permintaan($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Permintaan Pengecekan";
        $pelayanan = pelayanan::with('poli', 'dokter.namauser', 'pasien.kelamin', 'pendaftaran.penjamin', 'icd')->where('nomor_register', $nomor_rawat)->first();

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

        $now = Carbon::now();
        $bulan = $now->format('m');
        $tahun = $now->format('Y');

        // Hitung jumlah surat yang sudah ada bulan ini
        $jumlah = kode_surat::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // Nomor urut berikutnya (increment)
        $nomorUrut = str_pad($jumlah + 1, 4, '0', STR_PAD_LEFT); // 0001, 0002, dst

        $kodeKlinik = WebSetting::value('kode_klinik');

        // Buat format kode surat
        $kodeSurat = "$kodeKlinik/{$nomorUrut}/SKD/{$bulan}/{$tahun}";

        return view('module.pelayanan.pelayanan-dokter-surat.pelayanan_permintaan', compact('title', 'kodeSurat', 'pelayanan', 'umur', 'data_icd9', 'data_lab', 'radiologi_pemeriksaan', 'radiologi_jenis'));
    }

    public function pelayana_rujukan($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Pelayanan";
        $pelayanan = pelayanan::with('poli', 'dokter.namauser', 'pasien.kelamin', 'pendaftaran.penjamin')->where('nomor_register', $nomor_rawat)->first();

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



        // Ambil ICD dengan priority Primary untuk nomor rawat ini
        $alasanComplication = pelayanan_soap_dokter_icd::where('no_rawat', $nomor_rawat)
            ->where('priority_icd10', 'Primary')
            ->get()
            ->map(function ($item) {
                return $item->kode_icd10 . ' - ' . $item->nama_icd10;
            })
            ->toArray();

        $Ref_TACC = [
            [
                "kdTacc" => "-1",
                "nmTacc" => "Tanpa TACC",
                "alasanTacc" => []
            ],
            [
                "kdTacc" => "1",
                "nmTacc" => "Time",
                "alasanTacc" => ["< 3 Hari", ">= 3 - 7 Hari", ">= 7 Hari"]
            ],
            [
                "kdTacc" => "2",
                "nmTacc" => "Age",
                "alasanTacc" => [
                    "< 1 Bulan",
                    ">= 1 Bulan s/d < 12 Bulan",
                    ">= 1 Tahun s/d < 5 Tahun",
                    ">= 5 Tahun s/d < 12 Tahun",
                    ">= 12 Tahun s/d < 55 Tahun",
                    ">= 55 Tahun"
                ]
            ],
            [
                "kdTacc" => "3",
                "nmTacc" => "Complication",
                "alasanTacc" => $alasanComplication
            ],
            [
                "kdTacc" => "4",
                "nmTacc" => "Comorbidity",
                "alasanTacc" => ["< 3 Hari", ">= 3 - 7 Hari", ">= 7 Hari"]
            ]
        ];

        return view('module.pelayanan.pelayanan_rujuk', compact('title', 'Ref_TACC','pelayanan', 'umur', 'sarana', 'spesialis', 'subspesialis'));
    }

    public function pelayana_rujukan_add(Request $request)
    {
        $validated = $request->validate([
            'nomor_rm' => 'required|string',
            'no_rawat' => 'required|string',
            'penjamin' => 'required|string',
            'tujuan_rujukan' => 'required|string',
            'opsi_rujukan' => 'required|string',
        ]);

        $pelayanan = Pelayanan::with('poli', 'pelayanan_so', 'pelayanan_soap', 'dokter.namauser', 'pasien', 'pendaftaran.status')
            ->where('nomor_rm', $validated['nomor_rm'])
            ->where('nomor_register', $validated['no_rawat'])
            ->first();

        if (!$pelayanan) {
            return response()->json(['success' => false, 'message' => 'Data pelayanan tidak ditemukan'], 404);
        }

        // Penjamin UMUM
        if ($validated['penjamin'] === 'UMUM') {
            // Simpan ke DB
            pelayanan_rujukan::create([
                'nomor_rm' => $validated['nomor_rm'],
                'no_rawat' => $validated['no_rawat'],
                'penjamin' => $validated['penjamin'],
                'tujuan_rujukan' => $validated['tujuan_rujukan'],
                'opsi_rujukan' => $validated['opsi_rujukan'],
                'tanggal_rujukan' => now()->format('Y-m-d'),
            ]);

            return response()->json([
                'message' => 'Data rujukan berhasil disimpan (UMUM)',
                'data' => $pelayanan
            ]);
        }

        // Penjamin selain BPJS ditolak
        if ($validated['penjamin'] !== 'BPJS') {
            return response()->json(['success' => false, 'message' => 'Penjamin tidak valid'], 400);
        }

        // Ambil data SOAP
        $soap = pelayanan_soap_dokter::where('no_rawat', $pelayanan->nomor_register)->first();
        if (!$soap) {
            return response()->json(['success' => false, 'message' => 'Data SOAP tidak ditemukan'], 404);
        }

        // Proses keluhan
        $output = 'Keluhan tidak tersedia';
        if (!empty($soap->tableData)) {
            $array = json_decode($soap->tableData, true);
            if (is_array($array)) {
                $hasil = array_map(fn($item) => "{$item['penyakit']} {$item['durasi']} " . strtolower($item['waktu']), $array);
                $output = implode(', ', $hasil);
            }
        }

        // GCS
        $totalSkor = (int) $soap->eye + (int) $soap->verbal + (int) $soap->motorik;
        $kdSadar = gcs_kesadaran::where('skor', $totalSkor)->value('kode') ?? '1';
        $kdSadar = str_pad($kdSadar, 2, '0', STR_PAD_LEFT);

        // Diagnosa ICD
        $icds = pelayanan_soap_dokter_icd::where('no_rawat', $soap->no_rawat)
            ->where('nomor_rm', $soap->nomor_rm)
            ->pluck('kode_icd10')
            ->toArray();

            $diagnosa = array_slice($icds, 0, 3);
            $dataDiag = [];
            for ($i = 0; $i < 3; $i++) {
                $dataDiag["kdDiag" . ($i + 1)] = isset($diagnosa[$i]) ? $diagnosa[$i] : null;
            }
            // Jika tidak ada diagnosa sama sekali, isi default
            if (empty($dataDiag['kdDiag1'])) {
                $dataDiag['kdDiag1'] = 'Z00.0';
            }

            // Payload kunjungan dengan posisi kdDiag setelah kdDokter
            $suhu = str_replace(',', '.', $soap->suhu); // Ubah koma ke titik agar bisa dibaca sebagai float

            $kunjunganPayload = array_merge([
                "noKunjungan" => null,
                "noKartu" => $pelayanan->pasien->no_bpjs,
                "tglDaftar" => now()->format('d-m-Y'),
                "kdPoli" => $pelayanan->poli->kode,
                "keluhan" => $output,
                "kdSadar" => $kdSadar,
                "sistole" => (int)$soap->sistol,
                "diastole" => (int)$soap->distol,
                "beratBadan" => (int)$soap->berat,
                "tinggiBadan" => (int)$soap->tinggi,
                "respRate" => (int)$soap->rr,
                "heartRate" => (int)$soap->nadi,
                "lingkarPerut" => (int)$soap->lingkar_perut,
                "kdStatusPulang" => "4",
                "tglPulang" => now()->format('d-m-Y'),
                "kdDokter" => $pelayanan->dokter->kode,
            ] + $dataDiag + [
                "kdPoliRujukInternal" => null,
                "rujukLanjut" => [],
                "kdTacc" => (int) $request->input('kategori_rujukan', -1),
                "alasanTacc" => $request->input('alasanTacc', null),
                "anamnesa" => $output,
                "alergiMakan" => $pelayanan->alergi_makanan ?? "00",
                "alergiUdara" => $pelayanan->alergi_udara ?? "00",
                "alergiObat" => $pelayanan->alergi_obat ?? "00",
                "kdPrognosa" => '01',
                "terapiObat" => $pelayanan->terapi_obat ?? "tidak ada",
                "terapiNonObat" => $pelayanan->terapi_nonobat ?? "tidak ada",
                "bmhp" => $request->input('bmhp') ?? null,
                "suhu" => $suhu !== null ? (float)$suhu : null,
            ]);



        // Tambahkan Rujukan Khusus atau Spesialis
        if ($validated['opsi_rujukan'] === 'rujukan_khusus') {
            $kunjunganPayload['rujukLanjut'] = [
                "kdppk" => $request->input('tujuan_rujukan_khusus'),
                "tglEstRujuk" => Carbon::parse($request->input('tanggal_rujukan_khusus'))->format('d-m-Y'),
                "subSpesialis" => null,
                "khusus" => [
                    "kdKhusus" => $request->input('igd_rujukan_khusus'),
                    "kdSubSpesialis" => $request->input('subspesialis_khusus') !== "0" ? $request->input('subspesialis_khusus') : null,
                    "catatan" => null
                ]
            ];
        } elseif ($validated['opsi_rujukan'] === 'spesialis') {
            $kunjunganPayload['rujukLanjut'] = [
                "kdppk" => $request->input('tujuan_rujukan_spesialis'),
                "tglEstRujuk" => Carbon::parse($request->input('tanggal_rujukan'))->format('d-m-Y'),
                "subSpesialis" => [
                    "kdSubSpesialis1" => $request->input('sub_spesialis'),
                    "kdSarana" => $request->input('sarana') !== "0" ? $request->input('sarana') : null,
                ],
                "khusus" => null
            ];
        } else {
            return response()->json(['success' => false, 'message' => 'Opsi rujukan tidak valid'], 400);
        }

        // Kirim ke BPJS
        try {
            $response = $this->PcareController->post_kunjungan_bpjs($kunjunganPayload);

            // Ambil content dan decode JSON
            $content = $response->getContent();
            $data = json_decode($content, true);

            // Ambil nomor kunjungan sesuai struktur response BPJS
            if (isset($data['data']) && is_array($data['data']) && count($data['data']) > 0) {
                $noKunjungan = $data['data'][0]['message'] ?? null;
            } else {
                $noKunjungan = null;
            }

            // Simpan ke DB
            pelayanan_rujukan::create([
                'nomor_rm' => $validated['nomor_rm'],
                'no_rawat' => $validated['no_rawat'],
                'penjamin' => $validated['penjamin'],
                'tujuan_rujukan' => $validated['tujuan_rujukan'],
                'opsi_rujukan' => $validated['opsi_rujukan'],
                'tanggal_rujukan' => $request->input('tanggal_rujukan') ?? $request->input('tanggal_rujukan_khusus'),
                'sarana' => $request->input('sarana'),
                'rujukan_lanjut' => json_encode($kunjunganPayload['rujukLanjut']),
                'sub_spesialis' => $request->input('sub_spesialis') ?? $request->input('subspesialis_khusus'),
            ]);

             // Simpan status selesai dan nomor kunjungan ke database
            if ($pelayanan->pendaftaran && $pelayanan->pendaftaran->status) {
                $pelayanan->pendaftaran->status->status_panggil = 3;
                $pelayanan->pendaftaran->status->save();

                $pelayanan->kunjungan = $noKunjungan;
                $pelayanan->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Data rujukan berhasil disimpan & dikirim ke BPJS.',
                    'data' => $noKunjungan
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data status tidak ditemukan.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal kirim kunjungan ke BPJS.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
