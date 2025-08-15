<?php

namespace App\Http\Controllers\Module\SDM\Dokter;

use App\Http\Controllers\Controller;
use App\Models\agama;
use App\Models\bahasa;
use App\Models\bangsa;
use App\Models\bank;
use App\Models\dokter;
use App\Models\dokter_jadwal;
use App\Models\dokter_pendidikan;
use App\Models\dokter_pendidikan_spesialis;
use App\Models\dokter_pelatihan;
use App\Models\dokter_verifikasi;
use App\Models\kelamin;
use App\Models\poli;
use App\Models\posker;
use App\Models\provinsi;
use App\Models\suku;
use App\Models\User;
use App\Models\goldar;
use App\Models\pernikahan;
use App\Models\pendidikan;
use App\Models\WebSetting;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;


class Dokter_Controller extends Controller
{
    protected $PcareController;

    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function dokter()
    {
        $title = "Dokter";

        $user = User::all();
        $poli = poli::all();
        $posker = posker::all();
        $dokter = dokter::with('namauser', 'namapoli', 'namastatuspegawai')->get();
        $provinsi = provinsi::all();
        $kelamin = kelamin::all();
        $goldar = goldar::all();
        $agama = agama::all();
        $pernikahan = pernikahan::all();
        $suku = suku::all();
        $bangsa = bangsa::all();
        $pendidikan = pendidikan::orderBy('urutan')->get();
        $bahasa = bahasa::all();
        $bank = bank::all();
        $dokternoverif = dokter::where('verifikasi', 1)->count();
        $dokterall = dokter::count();
        $webSetting = WebSetting::first();
        $is_bpjs_active = $webSetting ? $webSetting->is_bpjs_active : false;
        return view('module.sdm.dokter.index', compact('title', 'user', 'poli', 'posker', 'dokter', 'provinsi', 'kelamin', 'goldar', 'agama', 'pernikahan', 'suku', 'bangsa', 'bahasa', 'pendidikan', 'bank', 'dokternoverif', 'dokterall', 'is_bpjs_active'));
    }

    public function dokteradd(Request $request)
    {
        try {

            $request->validate([
                'nama'              => 'required',
                'kode'              => 'required',
                'poli'              => 'required',
                'nik'               => 'required',
                'npwp'              => 'required',
                'kode_satu'         => 'required',
                'str'               => 'required',
                'expstr'            => 'required',
                'sip'               => 'required',
                'expspri'           => 'required',
                'tgl_masuk'         => 'required',
                'provinsi'          => 'required',
                'kabupaten'         => 'required',
                'kecamatan'         => 'required',
                'desa'              => 'required',
                'rt'                => 'required',
                'rw'                => 'required',
                'kode_pos'          => 'required',
                'alamat'            => 'required',
                'seks'              => 'required',
                'goldar'            => 'required',
                'pernikahan'        => 'required',
                'kewarganegaraan'   => 'required',
                'agama'             => 'required',
                'pendidikan'        => 'required',
                'telepon'           => 'required',
                'suku'              => 'required',
                'bangsa'            => 'required',
                'bahasa'            => 'required',
                'tempat_lahir'      => 'required',
                'tgl_lahir'         => 'required',
                'posker'            => 'required',
                'userinput'         => 'required',
                'userinputid'       => 'required',
            ]);


            dokter::create([
                'nik'               => $request->nik,
                'poli'              => $request->poli,
                'npwp'              => $request->npwp,
                'kode'              => $request->kode,
                'kode_satu'         => $request->kode_satu,
                'tgl_masuk'         => $request->tgl_masuk,
                'status_pegawaian'  => $request->posker,
                'sip'               => $request->sip,
                'exp_spri'          => $request->expspri,
                'str'               => $request->str,
                'exp_str'           => $request->expstr,
                'tempat_lahir'      => $request->tempat_lahir,
                'tanggal_lahir'     => $request->tgl_lahir,
                'alamat'            => $request->alamat,
                'rt'                => $request->rt,
                'rw'                => $request->rw,
                'kode_pos'          => $request->kode_pos,
                'kewarganegaraan'   => $request->kewarganegaraan,
                'seks'              => $request->sex,
                'agama'             => $request->agama,
                'pendidikan'        => $request->pendidikan,
                'goldar'            => $request->goldar,
                'pernikahan'        => $request->pernikahan,
                'telepon'           => $request->telepon,
                'provinsi_kode'     => $request->provinsi,
                'kabupaten_kode'    => $request->kabupaten,
                'kecamatan_kode'    => $request->kecamatan,
                'desa_kode'         => $request->desa,
                'suku'              => $request->suku,
                'bahasa'            => $request->bangsa,
                'bangsa'            => $request->bahasa,
                'verifikasi'        => 1,
                'users'             => $request->nama,
                'user_id_input'     => $request->userinputid,
                'user_name_input'   => $request->userinput,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function dokterdelete(Request $request)
    {

        $request->validate([
            'dokterid_delete' => 'required'
        ]);

        $dokter = dokter::find($request->dokterid_delete);
        if (!$dokter) {
            return response()->json([
                'success' => false,
                'message' => 'dokter tidak ditemukan!'
            ], 404);
        }
        $dokter->delete();

        return response()->json([
            'success' => true,
            'message' => 'dokter berhasil dihapus!'
        ]);
    }


    public function dokterverifikasi(Request $request)
    {
        // ✅ VALIDASI
        $request->validate([
            'dokterid_verifikasi' => 'required|exists:dokters,id',
            'nama_bank'           => 'required|string|max:100',
            'norek'               => 'required|string|max:50',
            'cabang_bank'         => 'required|string|max:100',

            'pendidikan'          => 'required|array|min:1',
            'pendidikan.*.kode'         => 'required|string',
            'pendidikan.*.nama_sekolah' => 'required|string',
            'pendidikan.*.tahun_lulus'  => 'required',
            'pendidikan.*.ijasah'       => 'nullable|file|mimes:pdf,jpg,jpeg,png',

            'spesialis'           => 'nullable|array',
            'spesialis.*.nama'       => 'required|string',
            'spesialis.*.institusi'  => 'required|string',
            'spesialis.*.tahun_lulus' => 'required',
            'spesialis.*.ijasah'     => 'nullable|file|mimes:pdf,jpg,jpeg,png',

            'pelatihan'           => 'nullable|array',
            'pelatihan.*.nama'         => 'required|string',
            'pelatihan.*.penyelenggara' => 'required|string',
            'pelatihan.*.tahun'        => 'required',
            'pelatihan.*.sertifikat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        try {
            // ✅ SIMPAN DATA UTAMA
            $verifikasi = dokter_verifikasi::updateOrCreate(
                ['dokter_id' => $request->dokterid_verifikasi],
                [
                    'nama_bank'   => $request->nama_bank,
                    'norek'       => $request->norek,
                    'cabang_bank' => $request->cabang_bank,
                ]
            );

            // ✅ SIMPAN PENDIDIKAN
            foreach ($request->pendidikan as $item) {
                $ijasahPath = null;
                if (isset($item['ijasah']) && $item['ijasah'] instanceof \Illuminate\Http\UploadedFile) {
                    $ijasahPath = $item['ijasah']->store('ijasah', 'public');
                }

                dokter_pendidikan::updateOrCreate(
                    [
                        'dokter_verifikasi_id' => $verifikasi->id,
                        'kode' => $item['kode'],
                    ],
                    [
                        'nama_sekolah' => $item['nama_sekolah'],
                        'tahun_lulus'  => $item['tahun_lulus'],
                        'ijasah'       => $ijasahPath,
                    ]
                );
            }

            // ✅ SIMPAN SPESIALIS (Opsional)
            if (!empty($request->spesialis)) {
                foreach ($request->spesialis as $item) {
                    $ijasahPath = null;
                    if (isset($item['ijasah']) && $item['ijasah'] instanceof \Illuminate\Http\UploadedFile) {
                        $ijasahPath = $item['ijasah']->store('spesialis', 'public');
                    }

                    dokter_pendidikan_spesialis::updateOrCreate(
                        [
                            'dokter_verifikasi_id' => $verifikasi->id,
                            'nama' => $item['nama'],
                            'institusi' => $item['institusi'],
                        ],
                        [
                            'tahun_lulus' => $item['tahun_lulus'],
                            'ijasah'      => $ijasahPath,
                        ]
                    );
                }
            }

            // ✅ SIMPAN PELATIHAN (Opsional)
            if (!empty($request->pelatihan)) {
                foreach ($request->pelatihan as $item) {
                    $sertifikatPath = null;
                    if (isset($item['sertifikat']) && $item['sertifikat'] instanceof \Illuminate\Http\UploadedFile) {
                        $sertifikatPath = $item['sertifikat']->store('pelatihan', 'public');
                    }

                    dokter_pelatihan::updateOrCreate(
                        [
                            'dokter_verifikasi_id' => $verifikasi->id,
                            'nama' => $item['nama'],
                            'penyelenggara' => $item['penyelenggara'],
                        ],
                        [
                            'tahun'      => $item['tahun'],
                            'sertifikat' => $sertifikatPath,
                        ]
                    );
                }
            }

            // ✅ UPDATE STATUS DOKTER
            $dokter = dokter::find($request->dokterid_verifikasi);
            if ($dokter && $dokter->verifikasi == 1) {
                $dokter->verifikasi = 2;
                $dokter->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data dokter berhasil dilengkapi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function dokteredit(Request $request)
    {
        try {

            $dokter = Dokter::findOrFail($request->dokterid_update);

            $dokter->update([
                'nik'               => $request->nik_edit,
                'poli'              => $request->poli_edit,
                'npwp'              => $request->npwp_edit,
                'kode'              => $request->kode_edit,
                'kode_satu'         => $request->kode_satu_edit,
                'tgl_masuk'         => $request->tgl_masuk_edit,
                'status_pegawaian'  => $request->posker_edit,
                'sip'               => $request->sip_edit,
                'exp_spri'          => $request->expspri_edit,
                'str'               => $request->str_edit,
                'exp_str'           => $request->expstr_edit,
                'tempat_lahir'      => $request->tempat_lahir_edit,
                'tanggal_lahir'     => $request->tgl_lahir_edit,
                'alamat'            => $request->alamat_edit,
                'rt'                => $request->rt_edit,
                'rw'                => $request->rw_edit,
                'kode_pos'          => $request->kode_pos_edit,
                'kewarganegaraan'   => $request->kewarganegaraan_edit,
                'seks'              => $request->sex_edit,
                'agama'             => $request->agama_edit,
                'pendidikan'        => $request->pendidikan_edit,
                'goldar'            => $request->goldar_edit,
                'pernikahan'        => $request->pernikahan_edit,
                'telepon'           => $request->telepon_edit,
                'provinsi_kode'     => $request->provinsi_edit,
                'kabupaten_kode'    => $request->kabupaten_edit,
                'kecamatan_kode'    => $request->kecamatan_edit,
                'desa_kode'         => $request->desa_edit,
                'suku'              => $request->suku_edit,
                'bahasa'            => $request->bangsa_edit,
                'bangsa'            => $request->bahasa_edit,
            ]);

            // Update data utama
            $verifikasi = dokter_verifikasi::where('dokter_id', $request->dokterid_update)->first();
            if ($verifikasi) {
                $verifikasi->update([
                    'nama_bank'   => $request->nama_bank,
                    'norek'       => $request->norek,
                    'cabang_bank' => $request->cabang_bank,
                ]);
            }
            // Hapus data lama (pendidikan, spesialis, pelatihan)
            dokter_pendidikan::where('dokter_verifikasi_id', $verifikasi->id)->delete();
            dokter_pendidikan_spesialis::where('dokter_verifikasi_id', $verifikasi->id)->delete();
            dokter_pelatihan::where('dokter_verifikasi_id', $verifikasi->id)->delete();

            // Simpan ulang pendidikan
            if (!empty($request->pendidikan) && is_array($request->pendidikan)) {
                foreach ($request->pendidikan as $item) {
                    $ijasahPath = $item['ijasah'] ?? null;
                    if (is_file($ijasahPath)) {
                        $ijasahPath = $item['ijasah']->store('ijasah', 'public');
                    }

                    dokter_pendidikan::create([
                        'dokter_verifikasi_id' => $verifikasi->id,
                        'kode' => $item['kode'],
                        'nama_sekolah' => $item['nama_sekolah'],
                        'tahun_lulus' => $item['tahun_lulus'],
                        'ijasah' => $ijasahPath,
                    ]);
                }
            }

            // Simpan ulang spesialis
            if (!empty($request->spesialis) && is_array($request->spesialis)) {
                foreach ($request->spesialis as $item) {
                    $ijasahPath = null;

                    if (!empty($item['ijasah']) && is_file($item['ijasah'])) {
                        $ijasahPath = $item['ijasah']->store('spesialis', 'public');
                    }

                    dokter_pendidikan_spesialis::create([
                        'dokter_verifikasi_id' => $verifikasi->id,
                        'nama' => $item['nama'],
                        'institusi' => $item['institusi'],
                        'tahun_lulus' => $item['tahun_lulus'],
                        'ijasah' => $ijasahPath,
                    ]);
                }
            }

            // Simpan ulang pelatihan
            if (!empty($request->pelatihan) && is_array($request->pelatihan)) {
                foreach ($request->pelatihan as $item) {
                    $sertifikatPath = null;

                    if (!empty($item['sertifikat']) && is_file($item['sertifikat'])) {
                        $sertifikatPath = $item['sertifikat']->store('pelatihan', 'public');
                    }

                    dokter_pelatihan::create([
                        'dokter_verifikasi_id' => $verifikasi->id,
                        'nama' => $item['nama'],
                        'penyelenggara' => $item['penyelenggara'],
                        'tahun' => $item['tahun'],
                        'sertifikat' => $sertifikatPath,
                    ]);
                }
            }



            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }
}
