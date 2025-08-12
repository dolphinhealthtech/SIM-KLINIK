<?php

namespace App\Http\Controllers\Soap;

use App\Http\Controllers\Controller;
use App\Models\pelayanan;
use App\Models\sarana;
use App\Models\spesialis;
use App\Models\subspesialis;
use App\Models\pelayanan_soap_dokter_icd;
use App\Models\gcs_kesadaran;
use App\Models\pelayanan_soap_dokter;
use App\Models\pelayanan_rujukan;
use App\Http\Controllers\PcareController;
use App\Models\WebSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


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

    public function getSubSpesialis($kode)
    {
        $subSpesialis = subspesialis::where('kode_spesialis', $kode)->get();
        return response()->json($subSpesialis);
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
        $kdSadar = gcs_kesadaran::where('skor', $totalSkor)->value('kode') ?? '01';

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

    // Fungsi cetak surat rujukan PDF
    public function cetakSuratRujukan($no_rawat)
    {
        $pelayanan = pelayanan::with(['poli', 'dokter.namauser', 'pasien.kelamin', 'pendaftaran.penjamin'])
            ->where('nomor_register', $no_rawat)
            ->first();
        $websetting = WebSetting::find(1);
        $rujukan = pelayanan_rujukan::where('no_rawat', $no_rawat)->first(); // hanya satu baris data
        $data_rujukan = json_decode($rujukan->rujukan_lanjut, true); // decode kolom rujukan_lanjut yang isinya JSON

        $kdSubSpesialis = data_get($data_rujukan, 'subSpesialis.kdSubSpesialis1');

        $kepada = subspesialis::where('kode', $kdSubSpesialis)->first();

        if (!$pelayanan) {
            abort(404, 'Data tidak ditemukan');
        }
        $diagnosa = pelayanan_soap_dokter_icd::where('no_rawat', $no_rawat)->first();
        $data = [
            'nomor_registrasi' => $pelayanan->nomor_register ?? '-',
            'fktp'             => $websetting->nama ?? '-',
            'nama_pasien'      => $pelayanan->pasien->nama ?? '-',
            'nomor_rm'         => $pelayanan->nomor_rm ?? '-',
            'tanggal_lahir'    => $pelayanan->pasien->tanggal_lahir ?? '-',
            'jenis_kelamin'    => $pelayanan->pasien->kelamin->nama ?? '-',
            'penjamin'         => $pelayanan->pendaftaran->penjamin->nama ?? '-',
            'dokter_pengirim'  => $pelayanan->dokter->namauser->name ?? '-',
            'diagnosa'         => $diagnosa ?? '-',
            'tanggal_rujukan'  => $data_rujukan['tglEstRujuk'],
            'keterangan'       => 'Rujukan untuk pemeriksaan lebih lanjut',
            'no_rujukan'       => $pelayanan->kunjungan ?? '-',
            'no_bpjs'          => $pelayanan->pasien->no_bpjs ?? '-',
            'subspesialis'     => $kepada->nama,
            'lokasi'           => $data_rujukan['kdppk'] ?? null,
        ];
        $pdf = Pdf::loadView('pdf.rujukan', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('rujukan-'.$pelayanan->nomor_rm.'.pdf');
    }
}
