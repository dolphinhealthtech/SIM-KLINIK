<?php

namespace App\Http\Controllers\DataMaster\soap;

use App\Http\Controllers\Controller;
use App\Models\pelayanan;
use App\Models\sarana;
use App\Models\spesialis;
use App\Models\subspesialis;
use App\Models\pelayanan_soap_dokter_icd;
use App\Models\gcs_kesadaran;
use App\Models\pelayanan_soap_dokter;
use App\Http\Controllers\PcareController;
use Carbon\Carbon;
use Illuminate\Http\Request;


class RujukanController extends Controller
{
    protected $PcareController;

    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
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

        return view('module.pelayanan.pelayanan_rujuk', compact('title', 'pelayanan', 'umur', 'sarana', 'spesialis', 'subspesialis'));
    }

    public function getSubSpesialis($kode)
    {
        $subSpesialis = subspesialis::where('kode_spesialis', $kode)->get();
        return response()->json($subSpesialis);
    }

    public function pelayana_rujukanadd(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required|string',
        ]);

        try {
            $pelayanan_dokter = pelayanan_soap_dokter::with('pendaftaran', 'pasien')->where('no_rawat', $request->no_rawat)->first();

            $data = json_decode($pelayanan_dokter->tableData, true);

            $result = '';
            foreach ($data as $item) {
                $penyakit = $item['penyakit'];
                $durasi = $item['durasi'];
                $waktu = $item['waktu']; // Tetap pakai huruf besar jika ingin tampilkan seperti aslinya

                $result .= "$penyakit sejak $durasi $waktu ";
            }

            $result = trim($result);

            if ($request->opsi_rujukan == 'spesialis') {
                $data["rujukLanjut"] = [
                    "kdppk" => $request->tujuan_rujukan_spesialis,
                    "tglEstRujuk" => $request->tanggal_rujukan,
                    "subSpesialis" => [
                        "kdSubSpesialis1" => $request->sub_spesialis ?: null, // Bisa NULL
                        "kdSarana" => $request->sarana !== "0" ? $request->sarana : null, // Pastikan bisa NULL jika "0"
                    ],
                    "khusus" => null
                ];
            }

            $databpjs = [
                'noKunjungan' => null,
                'noKartu' => $pelayanan_dokter->pasien->nomor_kartu,
                'tglDaftar' => date('d-m-Y'),
                'kdPoli' => $pelayanan_dokter->pendaftaran->poli->kode_poli,
                'keluhan' => $result,
                'kdSadar' => '04',
                'sistole' => $pelayanan_dokter->sistol,
                'diastole' => $pelayanan_dokter->distol,
                'beratBadan' => $pelayanan_dokter->berat,
                'tinggiBadan' => $pelayanan_dokter->tinggi,
                'respRate' => $pelayanan_dokter->rr,
                'heartRate' => $pelayanan_dokter->nadi,
                'lingkarPerut' => $pelayanan_dokter->lingkar_perut,
                'kdStatusPulang' => '4',
                'tglPulang' => date('d-m-Y'),
                'kdDokter' => $pelayanan_dokter->pendaftaran->dokter->kode_dokter,
                'kdDiag1' => 'O82',
                'kdDiag2' => null,
                'kdDiag3' => null,
                'kdPoliRujukInternal' => null,
                'kdTacc' => $request->kategori_rujukan,
                'alasanTacc' => $request->alasan_rujukan ?? null,
                'anamnesa' => 'test anamnesa',
                'alergiMakan' => '00',
                'alergiUdara' => '00',
                'alergiObat' => '00',
                'kdPrognosa' => '01',
                'terapiObat' => 'test terapi obat',
                'terapiNonObat' => 'test terapi nonobat',
                'bmhp' => null,
                'suhu' => $pelayanan_dokter->suhu,
            ];


            // $rujuk = $this->PcareController->post_kunjungan_bpjs($databpjs);

            return response()->json([
                'success' => true,
                'message' => 'Rujukan berhasil Di buat!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan rujukan!',
                'error' => $e->getMessage()
            ], 500);
        }
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


        $data = [
            "noKunjungan" => null,
            "noKartu" => $pelayanan->no_bpjs,

        ];

        if ($validated['penjamin'] === 'UMUM') {
            return response()->json([
                'message' => 'Data rujukan berhasil disimpan',
                'data' => $pelayanan
            ]);
        } elseif ($validated['penjamin'] === 'BPJS') {

            if ($validated['opsi_rujukan'] === 'rujukan_khusus') {
                $soap = pelayanan_soap_dokter::where('no_rawat', $pelayanan->nomor_register)->first();

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

                $diagnosa = array_slice($icds, 0, 3);
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
                    "kdStatusPulang" => "4",
                    "tglPulang" => now()->format('d-m-Y'),
                    "kdDokter" => $pelayanan->dokter->kode ?? '',
                    "kdPoliRujukInternal" => null,
                    "rujukLanjut" => [
                        "kdppk" => $validated['tujuan_rujukan_khusus'],
                        "tglEstRujuk" => $validated['tanggal_rujukan_khusus'],
                        "subSpesialis" => null,
                        "khusus" =>  [
                            "kdKhusus" => $validated['igd_rujukan_khusus'] ?? null,
                            "kdSubSpesialis" => $validated['subspesialis_khusus'] !== "0" ? $validated['subspesialis_khusus'] : null,
                            "catatan" =>  null
                        ]
                    ],
                    "kdTacc" => '0',
                    "alasanTacc" => null,
                    "anamnesa" => null,
                    "alergiMakan" => $pelayanan->alergi_makanan ?? '00',
                    "alergiUdara" => $pelayanan->alergi_udara ?? '00',
                    "alergiObat" => $pelayanan->alergi_obat ?? '00',
                    "kdPrognosa" => null,
                    "terapiObat" => $pelayanan->terapi_obat ?? null,
                    "terapiNonObat" => $pelayanan->terapi_nonobat ?? null,
                    "bmhp" => $soap->bmhp ?? null,
                    "suhu" => $soap->suhu ?? "36.4"
                ], $dataDiag);

                // Kirim ke BPJS
                try {
                    $response = $this->PcareController->post_kunjungan_bpjs($kunjunganPayload);
                    $noKunjungan = $response->response->message ?? null;
                    pelayanan::where('nomor_register', $pelayanan->nomor_register)->update(['kunjungan' => $noKunjungan]);
                    $pelayanan->pendaftaran->status->status_panggil = 3;
                    $pelayanan->pendaftaran->status->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'Data rujukan berhasil disimpan',
                        'no_kunjungan' => $noKunjungan,
                        'data' => $pelayanan
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal kirim kunjungan ke BPJS.',
                        'error' => $e->getMessage()
                    ], 500);
                }
            } elseif ($validated['opsi_rujukan'] === 'spesialis') {

                $soap = pelayanan_soap_dokter::where('no_rawat', $pelayanan->nomor_register)->first();

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

                $diagnosa = array_slice($icds, 0, 3);
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
                    "kdStatusPulang" => "4",
                    "tglPulang" => now()->format('d-m-Y'),
                    "kdDokter" => $pelayanan->dokter->kode ?? '',
                    "kdPoliRujukInternal" => null,
                    "rujukLanjut" => [
                        "kdppk" => $request['tujuan_rujukan_spesialis'],
                        "tglEstRujuk" => $request['tanggal_rujukan'],
                        "subSpesialis" => [
                            "kdSubSpesialis1" => $request['sub_spesialis'] ?? null,
                            "kdSarana" => $request['sarana'] !== "0" ? $request['sarana'] : null
                        ],
                        "khusus" => null
                    ],
                    "kdTacc" => $request['kategori_rujukan'] ?? '-1',
                    "alasanTacc" => null,
                    "anamnesa" => null,
                    "alergiMakan" => $pelayanan->alergi_makanan ?? '00',
                    "alergiUdara" => $pelayanan->alergi_udara ?? '00',
                    "alergiObat" => $pelayanan->alergi_obat ?? '00',
                    "kdPrognosa" => null,
                    "terapiObat" => $pelayanan->terapi_obat ?? null,
                    "terapiNonObat" => $pelayanan->terapi_nonobat ?? null,
                    "bmhp" => $soap->bmhp ?? null,
                    "suhu" => $soap->suhu ?? "36.4"
                ], $dataDiag);

                // Kirim ke BPJS
                try {
                    $response = $this->PcareController->post_kunjungan_bpjs($kunjunganPayload);
                    $noKunjungan = $response->response->message ?? null;
                    pelayanan::where('nomor_register', $pelayanan->nomor_register)->update(['kunjungan' => $noKunjungan]);
                    $pelayanan->pendaftaran->status->status_panggil = 3;
                    $pelayanan->pendaftaran->status->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'Data rujukan berhasil disimpan',
                        'no_kunjungan' => $noKunjungan,
                        'data' => $pelayanan
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal kirim kunjungan ke BPJS.',
                        'error' => $e->getMessage()
                    ], 500);
                }
            } else {
                return response()->json(['message' => 'Opsi rujukan tidak valid'], 400);
            }
        }

        // return response()->json(['message' => 'Data rujukan berhasil disimpan']);
    }
}
