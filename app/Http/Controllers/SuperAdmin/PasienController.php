<?php

namespace App\Http\Controllers\SuperAdmin;

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
use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SatusehatController;
use App\Models\penjamin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PasienController extends Controller
{
    protected $SatusehatController;

    public function __construct(SatusehatController $SatusehatController)
    {
        $this->SatusehatController = $SatusehatController;
    }

    public function cariNikNoka(Request $request)
    {
        $nikNoka = $request->input('nikNoka');
        $data = Pasien::where('nik', $nikNoka)
            ->orWhere('no_bpjs', $nikNoka)
            ->first();

        if ($data) {
            return response()->json([
                'success' => true,
                'nama' => $data->nama
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function search()
{
    try {
        $pasiens = DB::table('pasiens')
            ->select('id', 'nama', 'telepon')
            ->whereNotNull('telepon')
            ->where('telepon', '!=', '')
            ->orderBy('nama')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pasiens
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function queueMessage(Request $request)
    {
        try {
            $telepon = $request->telepon;

            // Cari pasien berdasarkan telepon
            $pasien = DB::table('pasiens')
                ->where('telepon', $telepon)
                ->first();

            if (!$pasien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pasien tidak ditemukan'
                ]);
            }

            // Cari antrian terbaru pasien
            $antrian = DB::table('pasien_antrians')
                ->where('pasien_id', $pasien->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($antrian) {
                $message = "Halo {$pasien->nama}, nomor antrian Anda adalah {$antrian->nomor_antrian}. Status: " .
                          ($antrian->status_panggil == 0 ? 'Menunggu' : 'Sudah Dipanggil') . ". Terima kasih.";
            } else {
                $message = "Halo {$pasien->nama}, terima kasih telah menggunakan layanan klinik kami. Silakan tunggu informasi lebih lanjut.";
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cariNikNama(Request $request)
    {
        $nikNama = $request->input('nikNama');
        $data = Pasien::where('nik', $nikNama)
            ->orWhere('nama', $nikNama)
            ->first();

        if ($data) {
            return response()->json([
                'success' => true,
                'nama' => $data->nama
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function creatnorm()
    {
        // Ambil No RM terbesar di database
        $lastNoRM = pasien::max('no_rm');

        if ($lastNoRM) {
            // Jika ada data, tambahkan 1 ke No RM terakhir
            $newNoRM = str_pad((int)$lastNoRM + 1, 6, '0', STR_PAD_LEFT);
        } else {
            // Jika tidak ada data pasien, mulai dari 000001
            $newNoRM = '000001';
        }

        return $newNoRM;
    }

    public function createNomorAntrian()
    {
        // Prefix untuk nomor antrian
        $prefix = 'A-';

        // Ambil tanggal hari ini
        $today = Carbon::today();

        // Cari nomor antrian terbesar untuk hari ini
        $lastAntrian = pasien_antrian::whereDate('created_at', $today)
            ->orderBy('nomor_antrian', 'desc')
            ->first();

        if ($lastAntrian) {
            // Jika sudah ada antrian hari ini
            // Ekstrak angka dari nomor antrian terakhir (format: A-xx)
            $lastNumber = (int) str_replace($prefix, '', $lastAntrian->nomor_antrian);

            // Tambahkan 1 untuk nomor berikutnya
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika belum ada antrian hari ini, mulai dari 1
            $nextNumber = 1;
        }

        // Format nomor antrian: A-xx
        $nomorAntrian = $prefix . $nextNumber;

        return $nomorAntrian;
    }

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

        return view('dashboard.pasien', compact('title', 'pejamin','kodefasyankes', 'pasiens', 'provinsi', 'kelamin', 'goldar', 'agama', 'pernikahan', 'suku', 'bangsa', 'bahasa', 'pendidikan', 'pekerjaan', 'pasiennoverif', 'pasienall', 'pasienallnewnow', 'pasienallold', 'asuransi'));
    }

    public function getPasien($id)
    {
        // $pasien = Pasien::find($id); // Ambil data pasien dari database
        $pasien = Pasien::with(['getnama:id,email'])->find($id); // Ambil pasien + relasi email saja

        return response()->json($pasien);
    }

    public function pasiensadd(Request $request)
    {
        try {

            $request->validate([
                'patientName'   => 'required|string|max:255',
                'tanggallahir'  => 'required|date',
                'gender'        => 'required',
                'phoneNumber'   => 'required|string|min:10|max:15',
                'email'         => 'required|email|max:255|unique:users,email',
                'address'       => 'required|string|max:500',
                'bloodType'     => 'required',
                'maritalStatus' => 'required',
                'nik'           => 'digits:16|unique:pasiens,nik',
                'noka'          => 'nullable|digits:13|unique:pasiens,no_bpjs',
            ]);

            $noRM = $this->creatnorm();
            // $kodeihs = $this->SatusehatController->get_nik_satusehat($request->nik)->getData(true); // Konversi ke array
            try {
                $kodeihs = $this->SatusehatController->get_nik_satusehat($request->nik)->getData(true);
            } catch (\Exception $e) {
                $kodeihs = null;
            }
            $pasiens = pasien::create([
                'no_rm' => $noRM,
                'kode_ihs' => $kodeihs['data'] ?? "--",
                'nik' => $request->nik,
                'no_bpjs' => $request->noka ?? "--",
                'goldar' => $request->bloodType,
                'pernikahan' => $request->maritalStatus,
                'nama' => $request->patientName,
                'tanggal_lahir' => $request->tanggallahir,
                'seks' => $request->gender,
                'telepon' => $request->phoneNumber,
                'alamat' => $request->address,
                'kewarganegaraan' => "indonesia",
                'verifikasi' => "1",
            ]);

            $NomorAntrian = $this->createNomorAntrian();
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
     * Mengubah status panggil pasien dari 0 menjadi 1
     */
    public function panggilPasien($id)
    {
        try {
            // Cari data antrian pasien
            $antrian = pasien_antrian::where('pasien_id', $id)
                ->where('status_panggil', '0')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($antrian) {
                // Update status panggil menjadi 1
                $antrian->status_panggil = '1';
                $antrian->save();

                // Ambil data pasien
                $pasien = pasien::find($id);

                return response()->json([
                    'success' => true,
                    'message' => 'Pasien ' . $pasien->nama . ' berhasil dipanggil.',
                    'data' => $antrian
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data antrian pasien tidak ditemukan atau sudah dipanggil.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

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

        $fotoName = null;

        if ($request->hasFile('profile_image')) {
            // Get the original file name
            $fotoName = $request->file('profile_image')->getClientOriginalName();

            // Define the directory path to save in the public folder
            $destinationPath = public_path('uploads/patient_photos');

            // Move the uploaded file to the public directory
            $request->file('profile_image')->move($destinationPath, $fotoName);
        }

        // Handle null profile photo if needed
        if (is_null($fotoName)) {
            // Set a default photo name or handle as required
            $fotoName = 'default.jpg';  // Example default image
        }


        $pasien = Pasien::where('no_rm', $request->nomor_rm)->first();
        if ($pasien) {
            $pass = $request->tgllahir;
            $passformad = date('dmY', strtotime($pass));

            $users = User::create([
                'name' => $request->nama,
                'username' => $request->nik,
                'email' => $request->email,
                'password' => Hash::make($passformad),
                'profile' => $fotoName,
                'is_active' => 1
            ]);
            // Update data pasien
            $pasien->update([
                'no_rm' => $request->nomor_rm,
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

            // Update status panggil menjadi 2 (selesai)
            $antrian = pasien_antrian::where('pasien_id', $pasien->id)
                ->whereDate('created_at', now()->toDateString())
                ->first();

            if ($antrian) {
                $antrian->status_panggil = '2'; // 2 = selesai
                $antrian->save();

                Log::info('Status panggil pasien diubah menjadi selesai', [
                    'pasien_id' => $pasien->id,
                    'no_rm' => $pasien->no_rm,
                    'status_panggil' => $antrian->status_panggil,
                    'waktu' => now()
                ]);
            }


            // Logging perubahan data
            Log::info('Pasien berhasil diupdate', [
                'no_rm' => $pasien->no_rm,
                'user_input' => $pasien->user_name_input,
                'waktu' => now()
            ]);

            return redirect()->route('pasien.get')->with('success', 'Data Pasien Behasil Dilengkapi');
        } else {
            Log::error('Gagal update, pasien tidak ditemukan', [
                'no_rm' => $request->nomor_rm,
                'user_input' => $request->userinput,
                'waktu' => now()
            ]);

            return redirect()->route('pasien.get')->with('error', 'Data Pasien Gagal Dilengkapi');
        }
    }

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
            "email_edit" => 'nullable',
            "penjamin_2_info_edit" => 'nullable',
            "penjamin_3_info_edit" => 'nullable',
            "penjamin_2_edit" => 'nullable',
            "penjamin_3_edit" => 'nullable',
        ]);

        $pasien = Pasien::where('no_rm', $request->nomor_rm_edit)->first();
        if ($pasien) {
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

            // Logging perubahan data
            Log::info('Pasien berhasil diupdate', [
                'no_rm' => $pasien->no_rm,
                'user_input' => $pasien->user_name_input,
                'waktu' => now()
            ]);

            return redirect()->route('pasien.get')->with('success', 'Data Pasien Berhasil Diperbarui');
        } else {
            Log::error('Gagal update, pasien tidak ditemukan', [
                'no_rm' => $request->nomor_rm,
                'user_input' => $request->userinput,
                'waktu' => now()
            ]);

            return redirect()->route('pasien.get')->with('error', 'Data Pasien Gagal Diperbarui');
        }
    }
}
