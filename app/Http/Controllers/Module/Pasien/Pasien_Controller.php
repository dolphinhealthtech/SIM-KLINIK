<?php

namespace App\Http\Controllers\Module\Pasien;

use App\Http\Controllers\Controller;
use App\Models\agama;
use App\Models\bahasa;
use App\Models\bangsa;
use App\Models\goldar;
use App\Models\kelamin;
use App\Models\pasien;
use App\Models\pasien_antrian;
use App\Models\pendidikan;
use App\Models\pernikahan;
use App\Models\Set_Bpjs;
use App\Models\pekerjaan;
use App\Models\provinsi;
use App\Models\suku;
use App\Models\User;
use App\Models\asuransi;
use App\Models\penjamin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Http\Controllers\SatusehatController;
use App\Http\Controllers\Module\Pasien\Pasien_Api_Controller;
use App\Http\Controllers\PcareController;


class Pasien_Controller extends Controller
{
    protected $SatusehatController;
    protected $pcare;
    protected $pasien_api;

    public function __construct(SatusehatController $SatusehatController, Pasien_Api_Controller $pasien_api, PcareController $pcare)
    {
        $this->SatusehatController = $SatusehatController;
        $this->pasien_api = $pasien_api;
        $this->pcare = $pcare;

    }
    /**
    * Tampilan Time Line Pasien
    */
    public function pasiens_time_line($norm)
    {
        $title = "Time Line";
        $normAsli = base64_decode($norm);
        $datapasien = pasien::where('no_rm', $normAsli)->first();

        return view('dashboard.pasien-time-line', compact('title', 'datapasien'));
    }
    /**
    * Tampilan Data Pasien
    */
    public function pasiens()
    {
        $title = "Pasien";
        $pasiens = pasien::all();
        $provinsi = provinsi::all();
        $kelamin = kelamin::all();
        $goldar = goldar::all();
        $agama = agama::all();
        $pernikahan = pernikahan::all();
        $suku = suku::all();
        $bangsa = bangsa::all();
        $pendidikan = pendidikan::all();
        $bahasa = bahasa::all();
        $pekerjaan = pekerjaan::all();
        $pasiennoverif = Pasien::where('verifikasi', 1)->count();
        $pasienallold = Pasien::where('created_at', '<', now()->subDays(30))->count();
        $pasienall = Pasien::count();
        $pasienallnewnow = Pasien::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        $kodefasyankes = Set_Bpjs::first();

        $pejamin = penjamin::all();

        $asuransi = asuransi::all();

        return view('module.pasien.index', compact('title', 'pejamin','kodefasyankes', 'pasiens', 'provinsi', 'kelamin', 'goldar', 'agama', 'pernikahan', 'suku', 'bangsa', 'bahasa', 'pendidikan', 'pekerjaan', 'pasiennoverif', 'pasienall', 'pasienallnewnow', 'pasienallold', 'asuransi'));
    }
    /**
    * Menambahkan Data Pasien
    */
    public function pasiensadd(Request $request)
    {
        try {

            $request->validate([
                'patientName'   => 'required|string|max:255',
                'tanggallahir'  => 'required|date',
                'gender'        => 'required',
                'phoneNumber'   => 'required|string|min:10|max:15',
                'address'       => 'required|string|max:500',
                'bloodType'     => 'required',
                'maritalStatus' => 'required',
                'nik'           => 'nullable|digits:16|unique:pasiens,nik',
                'noka'          => 'nullable|digits:13|unique:pasiens,no_bpjs',
            ]);

            $noRM = $this->pasien_api->create_no_rm();

            try {
                $kodeihs = $this->SatusehatController->get_nik_satusehat($request->nik)->getData(true);
            } catch (\Exception $e) {
                $kodeihs = null;
            }

            if (empty($request->nik)) {
                $response = $this->pcare->get_noka_bpjs($request->noka);
                $data = is_array($response) ? $response : json_decode($response, true);
                $noNik = $data['status'] === 'success' && !empty($data['data']['noKTP'])
                    ? $data['data']['noKTP']
                    : null;
            } else {
                $noNik = $request->nik;
            }

            // Ambil noKartu
            if (empty($request->noka)) {
                $response = $this->pcare->get_noka_bpjs($request->nik);
                $data = is_array($response) ? $response : json_decode($response, true);
                $noKartu = $data['status'] === 'success' && !empty($data['data']['noKartu'])
                    ? $data['data']['noKartu']
                    : null;
            } else {
                $noKartu = $request->noka;
            }

            $pasiens = pasien::create([
                'no_rm' => $noRM,
                'kode_ihs' => $kodeihs['data'] ?? "--",
                'nik'     => $noNik ?? '--',
                'no_bpjs' => $noKartu ?? '--',
                'goldar' => $request->bloodType,
                'pernikahan' => $request->maritalStatus,
                'nama' => $request->patientName,
                'tanggal_lahir' => $request->tanggallahir,
                'seks' => $request->gender,
                'telepon' => $request->phoneNumber,
                'alamat' => $request->address,
                'kewarganegaraan' => "WNI",
                'verifikasi' => "1",
            ]);

            $NomorAntrian = $this->pasien_api->create_no_antrian();
            pasien_antrian::create([
                'pasien_id' => $pasiens->id,
                'nomor_antrian' => $NomorAntrian,
                'status_panggil' => '0', // 0 = belum panggil, 1 = sedang dipanggil, 2 = selesai
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.',
                'data'    => $pasiens,
                'noantrian' => $NomorAntrian
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }
    /**
    * Melengkapi Data Pasien
    */
    public function pasienvefiv(Request $request)
    {
        $request->validate([
            "nomor_rm" => 'required',
            "nama" => 'required',
            "nik" => 'required',
            "tempat_lahir" => 'required',
            "tgllahir" => 'required|date',
            "provinsi" => 'required',
            "kabupaten" => 'required',
            "kecamatan" => 'required',
            "desa" => 'required',
            "rt" => 'required',
            "rw" => 'required',
            "kode_pos" => 'required',
            "alamat" => 'required|string|max:255',
            "noka" => 'nullable',
            "noihs" => 'nullable',
            "jenis_kartu" => 'nullable|string',
            "kelas" => 'nullable|string',
            "provide" => 'nullable|string',
            "tgl_exp_bpjs" => 'nullable|date',
            "kodeprovide" => 'nullable',
            "hubungan_keluarga" => 'nullable',
            "seks" => 'required',
            "goldar" => 'required',
            "pernikahan" => 'required',
            "kewarganegaraan" => 'required',
            "agama" => 'required',
            "pendidikan" => 'required',
            "status_kerja" => 'required',
            "telepon" => 'required|string',
            "suku" => 'required',
            "bangsa" => 'required',
            "bahasa" => 'required',
            "email" => 'nullable',
            "username" => 'nullable',
            "password" => 'nullable',
            'penjamin_2' => 'nullable',
            'penjamin_3' => 'nullable',
            'penjamin_2_info' => 'nullable|string|max:255',
            'penjamin_3_info' => 'nullable|string|max:255',
        ]);

        try {
            $pasien = Pasien::where('no_rm', $request->nomor_rm)->firstOrFail();

            // Handle file foto
            $fotoName = $request->hasFile('profile_image')
                ? $request->file('profile_image')->getClientOriginalName()
                : 'default.png';

            if ($request->hasFile('profile_image')) {
                $request->file('profile_image')->move(public_path('profile'), $fotoName);
            }

            // Buat user jika perlu
            $passformad = date('dmY', strtotime($request->tgllahir));
            $users = User::firstOrCreate(
                ['username' => $request->nik], // cek apakah sudah ada
                [
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($passformad),
                    'profile' => $fotoName,
                    'is_active' => 1
                ]
            );

            $users->assignRole('pasien'); // assign role jika belum ada


            // Update data pasien
            $pasien->update([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tgllahir,
                'provinsi_kode' => $request->provinsi,
                'kabupaten_kode' => $request->kabupaten,
                'kecamatan_kode' => $request->kecamatan,
                'desa_kode' => $request->desa,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'kode_pos' => $request->kode_pos,
                'alamat' => $request->alamat,
                'no_bpjs' => $request->noka,
                'kode_ihs' => $request->noihs,
                'jenis_Kartu_bpjs' => $request->jenis_kartu,
                'kelas_bpjs' => $request->kelas,
                'provide' => $request->provide,
                'kodeprovide' => $request->kodeprovide,
                'hubungan_keluarga' => $request->hubungan_keluarga,
                'tgl_exp_bpjs' => $request->tgl_exp_bpjs,
                'seks' => $request->seks,
                'goldar' => $request->goldar,
                'pernikahan' => $request->pernikahan,
                'kewarganegaraan' => $request->kewarganegaraan,
                'agama' => $request->agama,
                'pendidikan' => $request->pendidikan,
                'pekerjaan' => $request->status_kerja,
                'telepon' => $request->telepon,
                'suku' => $request->suku,
                'bangsa' => $request->bangsa,
                'bahasa' => $request->bahasa,
                'verifikasi' => 2,
                'users' => $users->id,
                'user_id_input' => $request->userinputid,
                'user_name_input' => $request->userinput,
                'penjamin_2_nama' => $request->penjamin_2,
                'penjamin_3_nama' => $request->penjamin_3,
                'penjamin_2_no' => $request->penjamin_2_info,
                'penjamin_3_no' => $request->penjamin_3_info,
            ]);

            // Update status panggil
            $antrian = pasien_antrian::where('pasien_id', $pasien->id)
                ->whereDate('created_at', now()->toDateString())
                ->first();

            if ($antrian) {
                $antrian->status_panggil = '2'; // selesai
                $antrian->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil dilengkapi.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
    * Nerubah Data Pasien
    */
    public function pasienupdate(Request $request)
    {
        $request->validate([
            "nomor_rm_edit" => 'required',
            "nama_edit" => 'required',
            "nik_edit" => 'required',
            "tempat_lahir_edit" => 'required',
            "tgllahir_edit" => 'required|date',
            "provinsi_edit" => 'required',
            "kabupaten_edit" => 'required',
            "kecamatan_edit" => 'required',
            "desa_edit" => 'required',
            "rt_edit" => 'required',
            "rw_edit" => 'required',
            "kode_pos_edit" => 'required',
            "alamat_edit" => 'required|string|max:255',
            "noka_edit" => 'nullable',
            "noihs_edit" => 'nullable',
            "jenis_kartu_edit" => 'nullable|string',
            "kelas_edit" => 'nullable|string',
            "provide_edit" => 'nullable|string',
            "tgl_exp_bpjs_edit" => 'nullable|date',
            "kodeprovide_edit" => 'nullable',
            "hubungan_keluarga_edit" => 'nullable',
            "seks_edit" => 'required',
            "goldar_edit" => 'required',
            "pernikahan_edit" => 'required',
            "kewarganegaraan_edit" => 'required',
            "agama_edit" => 'required',
            "pendidikan_edit" => 'required',
            "status_kerja_edit" => 'required',
            "telepon_edit" => 'required|string',
            "suku_edit" => 'required',
            "bangsa_edit" => 'required',
            "bahasa_edit" => 'required',
            "email_edit" => 'nullable|email',
            "penjamin_2_info_edit" => 'nullable',
            "penjamin_3_info_edit" => 'nullable',
            "penjamin_2_edit" => 'nullable',
            "penjamin_3_edit" => 'nullable',
            "profile_image_edit" => 'nullable|image|mimes:jpg,jpeg,png',
            "user_edit" => 'required|exists:users,id',
        ]);

        $pasien = Pasien::where('no_rm', $request->nomor_rm_edit)->first();

        if (!$pasien) {
            Log::error('Gagal update, pasien tidak ditemukan', [
                'no_rm' => $request->nomor_rm_edit,
                'user_input' => $request->userinput,
                'waktu' => now()
            ]);

            return redirect()->route('pasien.get')->with('error', 'Data Pasien Gagal Diperbarui');
        }

        // Update data pasien
        $pasien->update([
            'no_rm' => $request->nomor_rm_edit,
            'nama' => $request->nama_edit,
            'nik' => $request->nik_edit,
            'tempat_lahir' => $request->tempat_lahir_edit,
            'tanggal_lahir' => $request->tgllahir_edit,
            'provinsi_kode' => $request->provinsi_edit,
            'kabupaten_kode' => $request->kabupaten_edit,
            'kecamatan_kode' => $request->kecamatan_edit,
            'desa_kode' => $request->desa_edit,
            'rt' => $request->rt_edit,
            'rw' => $request->rw_edit,
            'kode_pos' => $request->kode_pos_edit,
            'alamat' => $request->alamat_edit,
            'no_bpjs' => $request->noka_edit,
            'kode_ihs' => $request->noihs_edit,
            'jenis_Kartu_bpjs' => $request->jenis_kartu_edit,
            'kelas_bpjs' => $request->kelas_edit,
            'provide' => $request->provide_edit,
            'kodeprovide' => $request->kodeprovide_edit,
            'hubungan_keluarga' => $request->hubungan_keluarga_edit,
            'tgl_exp_bpjs' => $request->tgl_exp_bpjs_edit,
            'seks' => $request->seks_edit,
            'goldar' => $request->goldar_edit,
            'pernikahan' => $request->pernikahan_edit,
            'kewarganegaraan' => $request->kewarganegaraan_edit,
            'agama' => $request->agama_edit,
            'pendidikan' => $request->pendidikan_edit,
            'pekerjaan' => $request->status_kerja_edit,
            'telepon' => $request->telepon_edit,
            'suku' => $request->suku_edit,
            'bangsa' => $request->bangsa_edit,
            'bahasa' => $request->bahasa_edit,
            'penjamin_2_nama' => $request->penjamin_2_edit,
            'penjamin_3_nama' => $request->penjamin_3_edit,
            'penjamin_2_no' => $request->penjamin_2_info_edit,
            'penjamin_3_no' => $request->penjamin_3_info_edit,
        ]);

        // Update User (foto profile)
        $user = User::find($request->user_edit);

        if ($request->hasFile('profile_image_edit')) {
            // Buat nama file unik
            $fotoName = uniqid('user_') . '.' . $request->file('profile_image_edit')->getClientOriginalExtension();

            // Simpan ke folder public/profile
            $request->file('profile_image_edit')->move(public_path('profile'), $fotoName);

            // Update hanya jika ada foto
            $user->profile = $fotoName;
        }

        $user->save();

        // Logging
        Log::info('Pasien berhasil diupdate', [
            'no_rm' => $pasien->no_rm,
            'user_input' => $pasien->user_name_input,
            'waktu' => now()
        ]);

        return redirect()->route('pasien.get')->with('success', 'Data Pasien Berhasil Diperbarui');
    }

}
