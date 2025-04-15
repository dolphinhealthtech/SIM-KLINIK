<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Models\goldar;
use App\Models\kelamin;
use App\Models\pasien;
use App\Models\Set_Bpjs;
use App\Models\Set_Sehat;
use App\Models\User;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\SatusehatController;
use App\Models\agama;
use App\Models\bahasa;
use App\Models\bangsa;
use App\Models\bank;
use App\Models\dokter;
use App\Models\dokter_jadwal;
use App\Models\dokter_pelatihan;
use App\Models\dokter_pendidikan;
use App\Models\dokter_pendidikan_spesialis;
use App\Models\dokter_verifikasi;
use App\Models\menu;
use App\Models\pekerjaan;
use App\Models\pendidikan;
use App\Models\pernikahan;
use App\Models\poli;
use App\Models\posker;
use App\Models\provinsi;
use App\Models\suku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SuperadminController extends Controller
{


    protected $SatusehatController;

    public function __construct(SatusehatController $SatusehatController)
    {
        $this->SatusehatController = $SatusehatController;
    }


    public function rolecreate()
    {
        $title = "Role Manajemen";

        $role = Role::with('permissions')->get();
        $permission = Permission::all();

        return view('module.permission-role.role', compact('title', 'role', 'permission'));
    }

    public function rolestore(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'rolename' => 'required|string|unique:roles,name'
            ]);

            // Simpan data ke database
            $role = Role::create([
                'name' => $request->rolename
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil ditambahkan!',
                'data' => $role
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan role!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function rolesupdate(Request $request)
    {
        $request->validate([
            'rolename_update' => 'required|string|unique:roles,name,' . $request->roleid_update,
        ]);

        $role = Role::find($request->roleid_update);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan!'
            ], 404);
        }

        $role->name = $request->rolename_update;
        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui!'
        ]);
    }

    public function rolesdestroy(Request $request)
    {
        $id = $request->input('roleids');
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan!'
            ], 404);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus!'
        ]);
    }

    public function givePermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::find($request->role_id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan'
            ], 404);
        }

        // Update permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil diperbarui untuk role ini !'
        ]);
    }

    public function permissioncreate()
    {
        $title = "Permission Manajemen";

        $permission = Permission::all();

        return view('module.permission-role.permission', compact('title', 'permission'));
    }

    public function permissiontore(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'permissionname' => 'required|string|unique:permissions,name'
            ]);

            // Simpan data ke database
            $permission = Permission::create([
                'name' => $request->permissionname
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil ditambahkan!',
                'data' => $permission
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan role!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function permissionupdate(Request $request)
    {
        $request->validate([
            'permissionname_update' => 'required|string|unique:permissions,name,' . $request->permissionid_update,
        ]);

        $permission = Permission::find($request->permissionid_update);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission tidak ditemukan!'
            ], 404);
        }

        $permission->name = $request->permissionname_update;
        $permission->save();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui!'
        ]);
    }

    public function permissiondestroy(Request $request)
    {
        $id = $request->input('permissionids');
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission tidak ditemukan!'
            ], 404);
        }

        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus!'
        ]);
    }

    public function usercreate()
    {
        $title = "User Manajemen";

        $users = $users = User::with(['roles', 'permissions'])->get();
        $role = Role::all();
        return view('module.permission-role.user', compact('title', 'users', 'role'));
    }

    public function userstore(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile' => 'default.png',
                'is_active' => 0,
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Users berhasil Di Tambahkan !',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menonaktifkan Users!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function usernonaktif(Request $request)
    {
        try {
            // Validasi input
            $user = User::find($request->usersids);


            // Toggle status: jika 1 jadi 0, jika 0 jadi 1
            $user->is_active = $user->is_active ? 0 : 1;
            $user->save();

            // Menentukan pesan berdasarkan status user
            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Users berhasil Di' . $status . '!',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menonaktifkan Users!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function usersgiverole(Request $request)
    {
        $request->validate([
            'userid_give' => 'required|exists:users,id',
            'role-give' => 'required|array',
            'role-give.*' => 'exists:roles,name'
        ]);

        try {
            // Validasi input
            $user = User::find($request->usersids);


            $user = User::findOrFail($request->userid_give);

            // Sinkronisasi peran pengguna
            $user->syncRoles($request->input('role-give'));
            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Users berhasil Di Berikan Role!',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memberikan Role Users!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function usersdestroy(Request $request)
    {
        $request->validate([
            'usersid_delete' => 'required|exists:users,id',
        ]);
        $user = User::find($request->usersid_delete);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Hapus semua role sebelum menghapus user
        $user->syncRoles([]);

        // Hapus user
        $user->delete();


        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus!'
        ]);
    }


    public function setingweb()
    {
        $title = "Seting Websaite";
        $setting = WebSetting::first(); // Ambil data pertama jika ada
        $set_bpjs = Set_Bpjs::all(); // Ambil data pertama jika ada
        $set_Sehat = Set_Sehat::all(); // Ambil data pertama jika ada


        return view('dashboard.webset', compact('title', 'setting', 'set_bpjs', 'set_Sehat'));
    }

    public function monitor()
    {
        $title = "Pelayanan tiket";
        $goldar = goldar::all();
        $kelamin = kelamin::all();

        return view('monitor.index', compact('title','goldar','kelamin'));
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
        return view('dashboard.pasien', compact('title','pasiens','provinsi','kelamin','goldar','agama','pernikahan','suku','bangsa','bahasa','pendidikan','pekerjaan','pasiennoverif','pasienall','pasienallnewnow','pasienallold'));
    }

    public function getPasien($id)
    {
        $pasien = Pasien::find($id); // Ambil data pasien dari database
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
            $kodeihs = $this->SatusehatController->get_nik_satusehat($request->nik)->getData(true); // Konversi ke array
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

            return response()->json([
                'message' => 'Data pasien berhasil disimpan.',
                'data'    => $pasiens
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
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
            "noihs" => 'required',
            "jenis_kartu" => 'nullable|string',
            "kelas" => 'nullable|string',
            "provide" => 'nullable|string',
            "tgl_exp_bpjs" => 'nullable|date',
            "kodeprovide" =>'nullable',
            "hubungan_keluarga" =>'nullable',
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
            "email" =>'nullable',
            "username" =>'nullable',
            "password" =>'nullable',
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
            ]);



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
            "noihs_edit" => 'required',
            "jenis_kartu_edit" => 'nullable|string',
            "kelas_edit" => 'nullable|string',
            "provide_edit" => 'nullable|string',
            "tgl_exp_bpjs_edit" => 'nullable|date',
            //"kodeprovide_edit" =>'nullable',
            //"hubungan_keluarga_edit" =>'nullable',
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
            "email_edit" =>'nullable',
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


    public function dokter()
    {
        $title = "Dokter";

        $user = User::all();
        $poli = poli::all();
        $posker = posker::all();
        $dokter = dokter::with('namauser','namapoli','namastatuspegawai')->get();
        $provinsi = provinsi::all();
        $kelamin = kelamin::all();
        $goldar = goldar::all();
        $agama = agama::all();
        $pernikahan = pernikahan::all();
        $suku = suku::all();
        $bangsa = bangsa::all();
        $pendidikan = pendidikan::orderBy('urutan')->get();
        $bahasa = bahasa::all();
        $bank= bank::all();
        $dokternoverif = dokter::where('verifikasi', 1)->count();
        $dokterall = dokter::count();

        return view('module.staff-manajemen.dokter', compact('title','user','poli','posker','dokter','provinsi','kelamin','goldar','agama','pernikahan','suku','bangsa','bahasa','pendidikan','bank','dokternoverif','dokterall'));

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
        $jadwal = dokter_jadwal::create([
            'dokter_id' => $request->dokter_id,
            'title'     => $request->title,
            'start'     => $request->start,
            'end'       => $request->end,
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
}
