<?php

namespace App\Http\Controllers\Soap;

use App\Http\Controllers\Controller;
use App\Models\alergi;
use App\Models\pelayanan;
use App\Models\pelayanan_soap_perawat;
use App\Models\gcs_eye;
use App\Models\gcs_verbal;
use App\Models\gcs_motorik;
use App\Models\gcs_kesadaran;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
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
use App\Models\Pendaftaran_rawat_jalan;
use App\Http\Controllers\PcareController;
use App\Models\dokter;
use App\Models\laboratorium_bidang;
use App\Models\laboratorium_bidang_sub;
use App\Models\radiologi_pemeriksaan;
use App\Models\radiologi_jenis;
use App\Models\kode_surat;
use App\Models\WebSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;


class PelayananController extends Controller
{
    protected $PcareController;

    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }

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
            ->whereDate('created_at', Carbon::today()) // Filter hanya hari ini
            ->get();


        foreach ($pelayanan as $item) {
            $status = $item->pendaftaran->status->status_panggil ?? 0;

            $soap = pelayanan_soap_perawat::where('no_rawat', $item->nomor_register)->first();

            if ($status == 0) {
                $item->tindakan_button = 'panggil';
            } elseif ($status == 1 && !$soap) {
                $item->tindakan_button = 'soap';
            } elseif ($status == 1 && $soap) {
                $item->tindakan_button = 'edit';
            } elseif ($status == 2) {
                $item->tindakan_button = 'Complete';
            } else {
                $item->tindakan_button = 'Complete';
            }
        }
        return view('module.pelayanan.pelayanan', compact('title', 'pelayanan'));
    }

    public function sopelayanan($norawat)
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
        return view('module.pelayanan.so-perawat', compact('title', 'pelayanan', 'umur', 'gsc_eye', 'gcs_verbal', 'gcs_motorik', 'gcs_kesadaran', 'htt_pemeriksaan'));
    }

    public function pendaftaranupdokter(Request $request)
    {
        try {
            $pelayanan = Pelayanan::with('pendaftaran')->find($request->rubahdokter_id);

            if (!$pelayanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pelayanan tidak ditemukan.'
                ], 404);
            }

            // Pastikan relasi pendaftaran ada
            if ($pelayanan->pendaftaran) {
                $pelayanan->pendaftaran->dokter_id = $request->dokter_id_update;
                $pelayanan->pendaftaran->save();
            }

            // Update di tabel pelayanan juga
            $pelayanan->dokter_id = $request->dokter_id_update;
            $pelayanan->save();

            return response()->json([
                'success' => true,
                'message' => 'Data dokter berhasil diupdate.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function sopelayananedit($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Pelayanan";
        $pelayanan = pelayanan::with('poli', 'dokter.namauser', 'pasien.kelamin', 'pendaftaran.penjamin')->where('nomor_register', $nomor_rawat)->first();
        $pelayanan_soap = pelayanan_soap_perawat::where('no_rawat', $nomor_rawat)->first();

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
        return view('module.pelayanan.so-perawat_edit', compact('title', 'pelayanan', 'pelayanan_soap', 'umur', 'gsc_eye', 'gcs_verbal', 'gcs_motorik', 'gcs_kesadaran', 'htt_pemeriksaan'));
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
            $pemeriksaan = pelayanan_soap_perawat::updateOrCreate([
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
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'pelayanan soap perawat berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'pelayanan soap perawat Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pelayanan soap perawat!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sopelayananupdate(Request $request)
    {
        try {
            // Simpan data ke database
            $pemeriksaan = pelayanan_soap_perawat::where('no_rawat', $request->no_rawat)->update([
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
                'summernote' => $request->summernote,
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'pelayanan soap perawat berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'pelayanan soap perawat Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pelayanan soap perawat!',
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

        // Tambahkan filter jika user adalah dokter
        if ($user->hasRole('Dokter')) {
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
        return view('module.pelayanan.pelayanan_dokter', compact('title', 'pelayanan'));
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

            $pelayanan->kunjungan = $noKunjungan;
            $pelayanan->save();

            return response()->json([
                'success' => true,
                'message' => 'Status panggil berhasil diperbarui.',
                'data' => $noKunjungan
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
            'anamnesa' => 'nullable|string',
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
                'anamnesa' => $request->anamnesa,
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

    public function soappelayanandata($nomor_rawat)
    {
        $data = pelayanan_soap_perawat::where('no_rawat', $nomor_rawat)->first();
        return response()->json($data);
    }

    public function soappelayanandataedit($nomor_rawat)
    {
        $data = pelayanan_soap_dokter::where('no_rawat', $nomor_rawat)->first();
        return response()->json($data);
    }

    public function soappelayanandataicd($nomor_rawat)
    {
        $data = pelayanan_soap_dokter_icd::where('no_rawat', $nomor_rawat)->get();
        return response()->json($data);
    }
    public function soappelayanandatadiet($nomor_rawat)
    {
        $data = pelayanan_soap_dokter_diet::where('no_rawat', $nomor_rawat)->get();
        return response()->json($data);
    }
    public function soappelayanandataobat($nomor_rawat)
    {
        $data = pelayanan_soap_dokter_obat::where('no_rawat', $nomor_rawat)->get();
        return response()->json($data);
    }
    public function soappelayanandatatindakan($nomor_rawat)
    {
        $data = pelayanan_soap_dokter_tindakan::where('no_rawat', $nomor_rawat)->get();
        return response()->json($data);
    }

    public function pelayana_rme($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Data RME";
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




        return view('module.pelayanan.pelayanan_rme', compact('title', 'pelayanan', 'umur', 'timeline'));
    }

    public function print(Request $request)
    {
        $resepList = json_decode($request->input('resep_data'), true);
        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.resep', [
            'resepList' => $resepList,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        return $pdf->download('resep-obat.pdf'); // akan dibuka lewat blob di JS
    }

    public function permintaanSakitPrint(Request $request)
    {
        $diagnosis_utama = $request->diagnosis_utama;
        $diagnosis_penyerta_1 = $request->diagnosis_penyerta_1;
        $diagnosis_penyerta_2 = $request->diagnosis_penyerta_2;
        $diagnosis_penyerta_3 = $request->diagnosis_penyerta_3;
        $komplikasi_1 = $request->komplikasi_1;
        $komplikasi_2 = $request->komplikasi_2;
        $komplikasi_3 = $request->komplikasi_3;
        $lama_istirahat = $request->lama_istirahat;
        $terhitung_mulai = $request->terhitung_mulai;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $umur = $request->umur;
        $now = Carbon::now();

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = PDF::loadView('pdf.permintaan_sakit', [
            'diagnosis_utama' => $diagnosis_utama,
            'diagnosis_penyerta_1' => $diagnosis_penyerta_1,
            'diagnosis_penyerta_2' => $diagnosis_penyerta_2,
            'diagnosis_penyerta_3' => $diagnosis_penyerta_3,
            'komplikasi_1' => $komplikasi_1,
            'komplikasi_2' => $komplikasi_2,
            'komplikasi_3' => $komplikasi_3,
            'lama_istirahat' => $lama_istirahat,
            'terhitung_mulai' => $terhitung_mulai,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'umur' => $umur,
            'now' => $now,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_sakit_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename);
    }

    public function permintaanSehatPrint(Request $request)
    {
        $tgl_periksa = $request->tgl_periksa_sehat;
        $sistole = $request->sistole;
        $diastole = $request->diastole;
        $suhu = $request->suhu;
        $berat = $request->berat;
        $respiratory_rate = $request->respiratory_rate;
        $nadi = $request->nadi;
        $tinggi = $request->tinggi;
        $buta_warna_status = $request->buta_warna_status;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $umur = $request->umur;
        $now = Carbon::now();

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = PDF::loadView('pdf.permintaan_sehat', [
            'tgl_periksa' => $tgl_periksa,
            'sistole' => $sistole,
            'diastole' => $diastole,
            'suhu' => $suhu,
            'berat' => $berat,
            'respiratory_rate' => $respiratory_rate,
            'nadi' => $nadi,
            'tinggi' => $tinggi,
            'buta_warna_status' => $buta_warna_status,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'umur' => $umur,
            'now' => $now,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_sehat_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename);
    }

    public function permintaanKematianPrint(Request $request)
    {
        $tgl_periksa = $request->tgl_periksa_kematian;
        $dokter_kematian = $request->dokter_kematian;
        $penandatangan = $request->penandatangan;
        $tanggal_meninggal = $request->tanggal_meninggal;
        $jam_meninggal = $request->jam_meninggal;
        $ref_tgl_jam = $request->ref_tgl_jam;
        $penyebab_kematian = $request->penyebab_kematian;
        $penyebab_lainnya = $request->penyebab_lainnya;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $umur = $request->umur;
        $now = Carbon::now();

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = PDF::loadView('pdf.permintaan_kematian', [
            'tgl_periksa' => $tgl_periksa,
            'dokter_kematian' => $dokter_kematian,
            'penandatangan' => $penandatangan,
            'tanggal_meninggal' => $tanggal_meninggal,
            'jam_meninggal' => $jam_meninggal,
            'ref_tgl_jam' => $ref_tgl_jam,
            'penyebab_kematian' => $penyebab_kematian,
            'penyebab_lainnya' => $penyebab_lainnya,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'umur' => $umur,
            'now' => $now,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_kematian_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename);
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

        return view('module.pelayanan.pelayanan_permintaan', compact('title', 'kodeSurat', 'pelayanan', 'umur', 'data_icd9', 'data_lab', 'radiologi_pemeriksaan', 'radiologi_jenis'));
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

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

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
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
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

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

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
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_radiologi_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function skdPrint(Request $request)
    {
        $tgl_pemeriksaan = $request->tgl_pemeriksaan_skd;
        $kode_surat = $request->kode_surat_skd;
        $tgl_awal = $request->tgl_awal_skd;
        $tgl_akhir = $request->tgl_akhir_skd;
        $diagnosa = $request->diagnosa_skd;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $umur = $request->umur;
        $no_bpjs = $request->no_bpjs;
        $now = Carbon::now();

        $tgl_awal_proses = Carbon::parse($request->tgl_awal_skd);
        $tgl_akhir_proses = Carbon::parse($request->tgl_akhir_skd);

        $jumlah_hari = $tgl_awal_proses->diffInDays($tgl_akhir_proses) + 1;

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        kode_surat::create([
            'kode_surat_skd' => $kode_surat,
            'user_input_id' => Auth::user()->id,
            'user_input_name' => Auth::user()->name,
        ]);

        $pdf = PDF::loadView('pdf.permintaan_skd', [
            'tgl_pemeriksaan' => $tgl_pemeriksaan,
            'kode_surat' => $kode_surat,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'diagnosa' => $diagnosa,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'umur' => $umur,
            'no_bpjs' => $no_bpjs,
            'now' => $now,
            'jumlah_hari' => $jumlah_hari,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'surat_keterangan_dokter_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function pelayana_selesai()
    {
        $title = "list_pasien";
        $pelayanan = pelayanan::with('poli', 'dokter.namauser', 'pasien', 'pendaftaran.status')->whereHas('pelayanan_so')->get();


        return view('module.pelayanan.list_pasien_selesai', compact('title', 'pelayanan'));
    }

    public function pelayana_rme_selesai($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Data RME selesai";
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
            $jenisAlergi = match ($step2->jenis_alergi) {
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
            $jenisAlergi = match ($step3->jenis_alergi) {
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
            $message .= "<b>expertise:</b><br><br>" . $step3->expertise . '<br><br>';
            $message .= "<b>evaluasi:</b><br><br>" . $step3->evaluasi . '<br><br>';
            $message .= "<b>plan:</b><br><br>" . $step3->plan . '<br><br>';


            $timeline[] = [
                'date' => $step3->created_at->format('d M Y'),
                'time' => $step3->created_at->format('H:i'),
                'icon' => 'fas fa-user-nurse',
                'bg' => 'bg-green',
                'title' => 'Pemeriksaan Awal oleh Perawat',
                'message' => $message
            ];
        }



        return view('module.pelayanan.list_pasien_selesai_rme', compact('title', 'pelayanan', 'umur', 'timeline'));
    }
}
