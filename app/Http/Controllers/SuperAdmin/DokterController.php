<?php

namespace App\Http\Controllers\SuperAdmin;

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
use App\Http\Controllers\PcareController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;


class DokterController extends Controller
{
    protected $PcareController;

    public function __construct(PcareController $PcareController)
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

        return view('module.staff-manajemen.dokter', compact('title', 'user', 'poli', 'posker', 'dokter', 'provinsi', 'kelamin', 'goldar', 'agama', 'pernikahan', 'suku', 'bangsa', 'bahasa', 'pendidikan', 'bank', 'dokternoverif', 'dokterall'));
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

    public function getDokter($id)
    {
        // Ambil data dokter
        $dokter = Dokter::findOrFail($id);

        // Ambil data pendidikan dari SD hingga ke tingkat terakhir yang dimiliki dokter
        $pendidikan = DB::table('pendidikans')
            ->where('urutan', '<=', function ($query) use ($dokter) {
                $query->select('urutan')
                    ->from('pendidikans')
                    ->where('kode', $dokter->pendidikan);
            })
            ->orderBy('urutan')
            ->get();

        // Return ke view modal atau response JSON
        return response()->json([
            'dokter' => $dokter,
            'pendidikans' => $pendidikan
        ]);
    }

    public function getDokterEdit($id)
    {
        $dokter = dokter::with([
            'namauser',
            'namapoli',
            'namastatuspegawai',
            'verifikasi.pendidikan',
            'verifikasi.spesialis',
            'verifikasi.pelatihan'
        ])->findOrFail($id);


        // Return ke view modal atau response JSON
        return response()->json([
            'dokter' => $dokter
        ]);
    }

    public function dokterverifikasi(Request $request)
    {
        try {

            // Simpan data utama
            $verifikasi = dokter_verifikasi::create([
                'dokter_id'   => $request->dokterid_verifikasi,
                'nama_bank'   => $request->nama_bank,
                'norek'       => $request->norek,
                'cabang_bank' => $request->cabang_bank,
            ]);

            // Simpan pendidikan
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

            // Simpan spesialis
            foreach ($request->spesialis as $item) {
                $ijasahPath = $item['ijasah'] ?? null;
                if (is_file($ijasahPath)) {
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

            // Simpan pelatihan
            foreach ($request->pelatihan as $item) {
                $sertifikatPath = $item['sertifikat'] ?? null;
                if (is_file($sertifikatPath)) {
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

            $dokter = Dokter::find($request->dokterid_verifikasi);

            if ($dokter && $dokter->verifikasi == 1) {
                $dokter->verifikasi = 2;
                $dokter->save();
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


    public function dokterjadwal(Request $request)
    {
        $date = new DateTime($request->start);
        // Ubah ke timezone lokal (Asia/Jakarta)
        $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
        $waktuLocal = $date->format('Y-m-d H:i:s');

        $date1 = new DateTime($request->end);
        // Ubah ke timezone lokal (Asia/Jakarta)
        $date1->setTimezone(new DateTimeZone('Asia/Jakarta'));
        $waktuLocal1 = $date1->format('Y-m-d H:i:s');

        $jadwal = dokter_jadwal::create([
            'dokter_id' => $request->dokter_id,
            'title'     => $request->title,
            'start'     => $waktuLocal,
            'end'       => $waktuLocal1,
        ]);

        return response()->json($jadwal);
    }

    public function dokterjadwaljson($id)
    {
        return dokter_jadwal::where('dokter_id', $id)->get(['id', 'title', 'start', 'end']);
    }

    public function dokterjadwalhapus($id)
    {
        $jadwal = dokter_jadwal::find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan.'
            ], 404);
        }

        // Menghapus jadwal
        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus.'
        ]);
    }

    public function jadwal_dokter($id)
    {
        $dokter = Dokter::findOrFail($id);
        $kodepoli = $dokter->namapoli->kode;
        $tanggal = date('Y-m-d');

        $listDokter = $this->PcareController->get_jadwal_dokter_bpjs($kodepoli, $tanggal)->getData(true); // Ambil data sebagai array

        if (!$listDokter) {
            Log::error("Tidak ada data dokter dari BPJS untuk poli {$kodepoli} tanggal {$tanggal}");
            return;
        }


        foreach ($listDokter['data'] as $dokterData) {
            $kodeDokter = $dokterData['kodedokter'];
            $jamPraktek = $dokterData['jampraktek']; // contoh: "08:00-23:00"
            $kapasitas = $dokterData['kapasitas'];

            $dokter = dokter::where('kode', $kodeDokter)->first();

            if (!$dokter) {
                Log::warning("Dokter dengan kodedokter {$kodeDokter} tidak ditemukan.");
                continue;
            }

            if (strpos($jamPraktek, '-') !== false) {
                list($jamMulai, $jamSelesai) = explode('-', $jamPraktek);

                $jamMulaiFull = $tanggal . ' ' . $jamMulai . ':00';
                $jamSelesaiFull = $tanggal . ' ' . $jamSelesai . ':00';

                // Shift malam: jika jam selesai lebih kecil dari jam mulai, tambahkan 1 hari
                if ($jamSelesai < $jamMulai) {
                    $jamSelesaiFull = date('Y-m-d H:i:s', strtotime($jamSelesaiFull . ' +1 day'));
                }

                // Simpan ke tabel dokter_jadwal
                dokter_jadwal::updateOrCreate(
                    [
                        'dokter_id' => $dokter->id,
                        'title' => "Jadwal Masuk",
                        'start' => $jamMulaiFull,
                        'end' => $jamSelesaiFull
                    ]
                );
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Data pembelian berhasil ditambahkan!'
        ], 201);
    }
}
