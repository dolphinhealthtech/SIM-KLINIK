<?php

namespace App\Http\Controllers;

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
        return view('dashboard.pasien', compact('title','pasiens','provinsi','kelamin','goldar','agama','pernikahan','suku','bangsa','bahasa','pendidikan'));
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
        $data = $request->validate([
            "nomor_rm" => 'required',
            "nik" => 'required|unique:pasiens',
            "kode_ihs" => 'required|unique:pasiens',
            "nama" => 'required',
            "tempat_lahir" => 'required',
            "tanggal_lahir" => 'required',
            "Alamat" => 'required|string|max:255',
            "rt" => 'required',
            "rw" => 'required',
            "kode_pos" => 'required',
            "kewarganegaraan" => 'required',
            "provinsi" => 'required',
            "kota_kabupaten" => 'required',
            "kecamatan" => 'required',
            "desa" => 'required',
            "seks" => 'required',
            "agama" => 'required',
            "pendidikan" => 'required',
            "goldar" => 'required',
            "pernikahan" => 'required',
            "pekerjaan" => 'required',
            "suku" => 'required',
            "bangsa" => 'required',
            "bahasa" => 'required',
            "telepon" => 'required|string',
        ],[
            'nomor_rm.required' => 'Nomor RM harus diisi.',
            'nik.required' => 'NIK harus diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'kode_ihs.required' => 'Kode IHS harus diisi.',
            'kode_ihs.unique' => 'Kode IHS sudah terdaftar.',
            'nama.required' => 'Nama harus diisi.',
            'tempat_lahir.required' => 'Tempat lahir harus diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'Alamat.required' => 'Alamat harus diisi.',
            'rt.required' => 'RT harus diisi.',
            'rw.required' => 'RW harus diisi.',
            'kode_pos.required' => 'Kode pos harus diisi.',
            'kewarganegaraan.required' => 'Kewarganegaraan harus diisi.',
            'provinsi.required' => 'Provinsi harus diisi.',
            'kota_kabupaten.required' => 'Kota/Kabupaten harus diisi.',
            'kecamatan.required' => 'Kecamatan harus diisi.',
            'desa.required' => 'Desa harus diisi.',
            'seks.required' => 'Seks harus diisi.',
            'agama.required' => 'Agama harus diisi.',
            'pendidikan.required' => 'Pendidikan harus diisi.',
            'goldar.required' => 'Golongan darah harus diisi.',
            'pernikahan.required' => 'Status pernikahan harus diisi.',
            'pekerjaan.required' => 'Pekerjaan harus diisi.',
            'telepon.required' => 'Telepon harus diisi.',
        ]);

        $fotoName = null;

        if ($request->hasFile('foto')) {
            // Get the original file name
            $fotoName = $request->file('foto')->getClientOriginalName();

            // Define the directory path to save in the public folder
            $destinationPath = public_path('uploads/patient_photos');

            // Move the uploaded file to the public directory
            $request->file('foto')->move($destinationPath, $fotoName);
        }

        // Handle null profile photo if needed
        if (is_null($fotoName)) {
            // Set a default photo name or handle as required
            $fotoName = 'default.jpg';  // Example default image
        }


        $pasien = new Pasien();
            $pasien->no_rm = $data['nomor_rm'];
            $pasien->nik = $data['nik'];
            $pasien->nama = $data['nama'];
            $pasien->kode_ihs = $data['kode_ihs'];
            $pasien->tempat_lahir = $data['tempat_lahir'];
            $pasien->tanggal_lahir = $data['tanggal_lahir'];
            $pasien->no_bpjs = $request->no_bpjs;
            $pasien->tgl_exp_bpjs = $request->tgl_akhir;
            $pasien->kelas_bpjs = $request->kelbpjs;
            $pasien->jenis_Kartu_bpjs = $request->jenper;
            $pasien->provide = $request->provide;
            $pasien->kodeprovide = $request->kodeprovide    ;
            $pasien->hubungan_keluarga = $request->hubka;
            $pasien->Alamat = $data['Alamat'];
            $pasien->rt = $data['rt'];
            $pasien->rw = $data['rw'];
            $pasien->kode_pos = $data['kode_pos'];
            $pasien->kewarganegaraan = $data['kewarganegaraan'];
            $pasien->seks = $data['seks'];
            $pasien->agama = $data['agama'];
            $pasien->pendidikan = $data['pendidikan'];
            $pasien->goldar = $data['goldar'];
            $pasien->pernikahan = $data['pernikahan'];
            $pasien->pekerjaan = $data['pekerjaan'];
            $pasien->telepon = $data['telepon'];
            $pasien->provinsi_kode = $data['provinsi'];
            $pasien->kabupaten_kode = $data['kota_kabupaten'];
            $pasien->kecamatan_kode = $data['kecamatan'];
            $pasien->desa_kode = $data['desa'];
            $pasien->suku = $data['suku'];
            $pasien->bangsa = $data['bangsa'];
            $pasien->bahasa = $data['bahasa'];
            $pasien->verifikasi = 2;
            $pasien->users = $request->userinput;
            $pasien->user_id_input = $request->userinputid;
            $pasien->user_name_input = $request->userinput;
            $pasien->save();
    }
}
