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
        foreach ($diagnosa as $i => $kode) {
            $dataDiag["kdDiag" . ($i + 1)] = $kode;
        }
        if (empty($dataDiag)) $dataDiag['kdDiag1'] = 'Z00.0';

        // Payload Dasar
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
            "rujukLanjut" => [],
            "kdTacc" => $request->input('kategori_rujukan', '0'),
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

        // Tambahkan Rujukan Khusus atau Spesialis
        if ($validated['opsi_rujukan'] === 'rujukan_khusus') {
            $kunjunganPayload['rujukLanjut'] = [
                "kdppk" => $request->input('tujuan_rujukan_khusus'),
                "tglEstRujuk" => $request->input('tanggal_rujukan_khusus'),
                "subSpesialis" => null,
                "khusus" => [
                    "kdKhusus" => $request->input('igd_rujukan_khusus'),
                    "kdSubSpesialis" => $request->input('subspesialis_khusus') !== "0" ? $request->input('subspesialis_khusus') : null,
                    "catatan" => null
                ]
            ];
        } elseif ($validated['opsi_rujukan'] === 'spesialis') {
            $kunjunganPayload['rujukLanjut'] = [
                "kdppk" => $request->input('tujuan_rujukan'),
                "tglEstRujuk" => $request->input('tanggal_rujukan'),
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
            $noKunjungan = $response->response->message ?? null;

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

            pelayanan::where('nomor_register', $pelayanan->nomor_register)
                ->update(['kunjungan' => $noKunjungan]);

            $pelayanan->pendaftaran->status->status_panggil = 3;
            $pelayanan->pendaftaran->status->save();

            return response()->json([
                'success' => true,
                'message' => 'Data rujukan berhasil disimpan & dikirim ke BPJS',
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
    }
}
