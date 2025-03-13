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
use App\Models\menu;
use App\Models\pekerjaan;
use App\Models\pendidikan;
use App\Models\pernikahan;
use App\Models\provinsi;
use App\Models\suku;


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
        return view('dashboard.pasien', compact('title','pasiens','provinsi','kelamin','goldar','agama','pernikahan','suku','bangsa','bahasa','pendidikan','pekerjaan'));
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
            "kota_kabupaten" => 'required',
            "kecamatan" => 'required',
            "desa" => 'required',
            "rt" => 'required',
            "rw" => 'required',
            "kode_pos" => 'required',
            "alamat" => 'required|string|max:255',
            "noka" => 'nullable',
            "kode_ihs" => 'required',
            "jenis_kartu" => 'nullable|string',
            "kelas" => 'nullable|string',
            "provide" => 'nullable|string',
            "tgl_exp_bpjs" => 'nullable|date',
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
                'users' => $request->userinput ,
                'user_id_input' => $request->userinputid,
                'user_name_input' => $request->userinput,
            ]);

            // Logging perubahan data
            Log::info('Pasien berhasil diupdate', [
                'no_rm' => $pasien->no_rm,
                'user_input' => $pasien->user_name_input,
                'waktu' => now()
            ]);

        } else {
            Log::error('Gagal update, pasien tidak ditemukan', [
                'no_rm' => $request->nomor_rm,
                'user_input' => $request->userinput,
                'waktu' => now()
            ]);
        }

    }
}
