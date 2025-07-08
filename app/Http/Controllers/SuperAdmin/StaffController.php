<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\agama;
use App\Models\bahasa;
use App\Models\bangsa;
use App\Models\bank;
use App\Models\goldar;
use App\Models\kelamin;
use App\Models\pendidikan;
use App\Models\pernikahan;
use App\Models\poli;
use App\Models\provinsi;
use App\Models\suku;
use App\Models\staff;
use App\Models\staff_pendidikan;
use App\Models\staff_pelatihan;
use App\Models\staff_verifikasi;
use App\Models\User;
use App\Models\posker;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class StaffController extends Controller
{
    public function staff()
    {
        $title = "Dokter";

        $user = User::all();
        $poli = poli::all();
        $posker = posker::all();
        $dokter = staff::with('namauser', 'namastatuspegawai')->get();
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
        $dokternoverif = staff::where('verifikasi', 1)->count();
        $dokterall = staff::count();

        return view('module.staff-manajemen.staff', compact('title', 'user', 'poli', 'posker', 'dokter', 'provinsi', 'kelamin', 'goldar', 'agama', 'pernikahan', 'suku', 'bangsa', 'bahasa', 'pendidikan', 'bank', 'dokternoverif', 'dokterall'));
    }

    public function staffadd(Request $request)
    {
        try {

            $request->validate([
                'nama'              => 'required',
                'nik'               => 'required',
                'npwp'              => 'required',
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


            staff::create([
                'nik'               => $request->nik,
                'npwp'              => $request->npwp,
                'tgl_masuk'         => $request->tgl_masuk,
                'status_pegawaian'  => $request->posker,
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

    public function staffdelete(Request $request)
    {

        $request->validate([
            'dokterid_delete' => 'required'
        ]);

        $dokter = staff::find($request->dokterid_delete);
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

    public function getStaff($id)
    {
        // Ambil data dokter
        $dokter = staff::findOrFail($id);

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
            'staff' => $dokter,
            'pendidikans' => $pendidikan
        ]);
    }

    public function staffverifikasi(Request $request)
    {
        try {

            // Simpan data utama
            $verifikasi = staff_verifikasi::create([
                'staff_id'   => $request->dokterid_verifikasi,
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

                staff_pendidikan::create([
                    'staff_verifikasi_id' => $verifikasi->id,
                    'kode' => $item['kode'],
                    'nama_sekolah' => $item['nama_sekolah'],
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

                staff_pelatihan::create([
                    'staff_verifikasi_id' => $verifikasi->id,
                    'nama' => $item['nama'],
                    'penyelenggara' => $item['penyelenggara'],
                    'tahun' => $item['tahun'],
                    'sertifikat' => $sertifikatPath,
                ]);
            }

            $dokter = staff::find($request->dokterid_verifikasi);

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

    public function getStaffEdit($id)
    {
        $dokter = staff::with([
            'namauser',
            'namastatuspegawai',
            'verifikasi.pendidikan',
            'verifikasi.pelatihan'
        ])->findOrFail($id);


        // Return ke view modal atau response JSON
        return response()->json([
            'dokter' => $dokter
        ]);
    }

    public function staffedit(Request $request)
    {
        try {

            $dokter = staff::findOrFail($request->dokterid_update);

            $dokter->update([
                'nik'               => $request->nik_edit,
                'npwp'              => $request->npwp_edit,
                'tgl_masuk'         => $request->tgl_masuk_edit,
                'status_pegawaian'  => $request->posker_edit,
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
            $verifikasi = staff_verifikasi::where('staff_id', $request->dokterid_update)->first();
            if ($verifikasi) {
                $verifikasi->update([
                    'nama_bank'   => $request->nama_bank,
                    'norek'       => $request->norek,
                    'cabang_bank' => $request->cabang_bank,
                ]);
            }
            // Hapus data lama (pendidikan, spesialis, pelatihan)
            staff_pendidikan::where('staff_verifikasi_id', $verifikasi->id)->delete();
            staff_pelatihan::where('staff_verifikasi_id', $verifikasi->id)->delete();

            // Simpan ulang pendidikan
            if (!empty($request->pendidikan) && is_array($request->pendidikan)) {
                foreach ($request->pendidikan as $item) {
                    $ijasahPath = $item['ijasah'] ?? null;
                    if (is_file($ijasahPath)) {
                        $ijasahPath = $item['ijasah']->store('ijasah', 'public');
                    }

                    staff_pendidikan::create([
                        'staff_verifikasi_id' => $verifikasi->id,
                        'kode' => $item['kode'],
                        'nama_sekolah' => $item['nama_sekolah'],
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

                    staff_pelatihan::create([
                        'staff_verifikasi_id' => $verifikasi->id,
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
