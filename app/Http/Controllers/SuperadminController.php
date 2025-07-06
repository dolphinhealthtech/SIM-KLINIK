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
use App\Models\loket;
use App\Models\menu;
use App\Models\pekerjaan;
use App\Models\Pendaftaran_rawat_jalan;
use App\Models\Pendaftaran_rawat_jalan_status;
use App\Models\pendidikan;
use App\Models\penjamin;
use App\Models\pernikahan;
use App\Models\poli;
use App\Models\posker;
use App\Models\provinsi;
use App\Models\suku;
use App\Models\pelayanan;
use App\Models\gudang_satuan;
use App\Models\gudang_kategori;
use App\Models\gudang_supplier_industri;
use App\Models\gudang_barang;
use App\Models\pembelian;
use App\Models\pembelian_details;
use App\Models\gudang_setting_harga;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_stok;
use App\Models\pelayanan_soap_dokter;
use App\Models\pelayanan_soap_dokter_tindakan;
use App\Models\perawatan_kategori;
use App\Models\apotek;
use App\Models\apotek_prebayar;
use App\Exports\Gudang_barangExport;
use App\Imports\Gudang_barangImport;
use App\Models\external_database;
use App\Models\staff;
use App\Models\staff_pelatihan;
use App\Models\staff_pendidikan;
use App\Models\staff_verifikasi;
use App\Models\kasir;
use App\Models\kasir_detail_lunas;
use App\Models\kasir_apotek_lunas;
use App\Models\kasir_tindakan_lunas;
use App\Models\kasir_diskon;
use App\Models\pasien_antrian;
use App\Models\inventaris_kategori;
use App\Models\inventaris_data_barang;
use App\Models\inventaris_pembelian;
use App\Models\inventaris_pembelian_detail;
use App\Models\inventaris_stok;
use App\Models\inventaris_satuan;
use App\Exports\Inventaris_data_barangExport;
use App\Imports\Inventaris_data_barangImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Connectors\ConnectionFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use DateTimeZone;

class SuperadminController extends Controller
{


    protected $SatusehatController;
    protected $PcareController;

    public function __construct(SatusehatController $SatusehatController, PcareController $PcareController)
    {
        $this->SatusehatController = $SatusehatController;
        $this->PcareController = $PcareController;
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

            $user->email_verified_at = $user->is_active ? now() : null; // Set email_verified_at jika diaktifkan

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
        $poli = poli::all();
        $pernikaha = pernikahan::all();

        return view('monitor.index', compact('title','poli','goldar','kelamin','pernikaha'));
    }

    public function monitor_bpjs(Request $request)
    {
        try {


            $data = $request->validate([
                'nikNokaInput' => 'required',
                'tanggal_kunjungan' => 'required',
                'poli_id' => 'required',
                'dokter_id' => 'required'
            ]);

            $antrian = Loket::where('poli_id', $request->poli_id)->first();


            $pasien = Pasien::where('nik', $request->nikNokaInput)
            ->orWhere('no_bpjs', $request->nikNokaInput)
            ->first();

            $penjamin = penjamin::where('nama',"BPJS")->first();
            // Ambil tanggal kunjungan dan ubah jadi format yyDDD
            $tanggal = Carbon::parse($request->tanggal_kunjungan);
            $tanggalKode = $tanggal->format('y') . str_pad($tanggal->dayOfYear, 3, '0', STR_PAD_LEFT); // Contoh: 25113

            // Angka acak 4 digit
            $angkaAcak = mt_rand(1000, 9999); // Contoh: 1234

            // Gabungkan format akhir: 1234-25113
            $no_registrasi = $angkaAcak . '-' . $tanggalKode;

            // Ambil tanggal hari ini (tanpa jam)
            $tanggalHariIni = Carbon::today();

            // Cari antrian terakhir HANYA untuk hari ini berdasarkan kode poli
            $last = Pendaftaran_rawat_jalan::where('antrian', 'like', $antrian->nama . '-%')
                ->whereDate('created_at', $tanggalHariIni)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last) {
                // Ambil angka terakhir dan increment
                $lastNumber = (int) str_replace($antrian->nama . '-', '', $last->antrian);
                $nextNumber = $lastNumber + 1;
            } else {
                // Jika belum ada antrian hari ini, mulai dari 1
                $nextNumber = 1;
            }

            $antrianBaru = $antrian->nama . '-' . $nextNumber;


            $pendaftaran = Pendaftaran_rawat_jalan::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id' => $pasien->id,
                'poli_id' => $request->poli_id,
                'tanggal_kujungan' => $request->tanggal_kunjungan,
                'dokter_id' => $request->dokter_id,
                'Penjamin' => $penjamin->id,
                'nomor_register'=> $no_registrasi,
                'antrian'=> $antrianBaru,
            ]);

            Pendaftaran_rawat_jalan_status::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id'=> $pasien->id,
                'nomor_register' => $no_registrasi,
                'tanggal_kujungan' => $request->tanggal_kunjungan,
                'register_id'=> $pendaftaran->id,
                'status_panggil'=> 0, // 0 = pendaftaran , 1 = perawat, 2 = dokter
                'status_pendaftaran' => 1, // 0 = batal, 1 = pendaftaran , 2 = hadir
                'Status_aplikasi' => 2 , // 1 = app manual , 2 = app Onlain ,3 = bpjs
            ]);

            $poli = poli::find($request->poli_id)->first();

            // Ambil info dokter dan jadwal berdasarkan tanggal kunjungan
            $tanggalKunjungan = Carbon::parse($request->tanggal_kunjungan)->format('Y-m-d');

            $dokter = Dokter::with(['namauser', 'jadwal' => function ($query) use ($tanggalKunjungan) {
                $query->whereDate('start', $tanggalKunjungan);
            }])->find($request->dokter_id);

            $jadwal = $dokter->jadwal->first();
            $jamPraktek = $jadwal
                ? Carbon::parse($jadwal->start)->format('H:i') . '-' . Carbon::parse($jadwal->end)->format('H:i')
                : '-';

            $databpjs = [
                "nomorkartu"=> $pasien->no_bpjs,
                "nik"=> $pasien->nik,
                "nohp"=>  $pasien->telepon,
                "kodepoli"=> $poli->kode,
                "namapoli"=> $poli->nama,
                "norm"=> $pasien->no_rm,
                "tanggalperiksa"=> $tanggalKunjungan,
                "kodedokter"=> $dokter->kode,
                "namadokter"=> $dokter->namauser->name,
                "jampraktek"=> $jamPraktek,
                "nomorantrean"=> $antrianBaru,
                "angkaantrean"=> $nextNumber,
                "keterangan"=> "",
            ];

            $response = $this->PcareController->post_ws_antria_bpjs($databpjs);

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.',
                'noantrian' => $antrianBaru,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }

    }

    public function monitor_nobpjs(Request $request)
    {
        try {


            $data = $request->validate([
                'nikNnamaInput' => 'required',
                'tanggal_kunjungan_no' => 'required',
                'poli_id_no' => 'required',
                'dokter_id_no' => 'required'
            ]);

            $antrian = Loket::where('poli_id', $request->poli_id_no)->first();


            $pasien = Pasien::where('nik', $request->nikNnamaInput)
            ->orWhere('nama', $request->nikNnamaInput)
            ->first();

            $penjamin = penjamin::where('nama',"UMUM")->first();
            // Ambil tanggal kunjungan dan ubah jadi format yyDDD
            $tanggal = Carbon::parse($request->tanggal_kunjungan_no);
            $tanggalKode = $tanggal->format('y') . str_pad($tanggal->dayOfYear, 3, '0', STR_PAD_LEFT); // Contoh: 25113

            // Angka acak 4 digit
            $angkaAcak = mt_rand(1000, 9999); // Contoh: 1234

            // Gabungkan format akhir: 1234-25113
            $no_registrasi = $angkaAcak . '-' . $tanggalKode;

            // Ambil tanggal hari ini (tanpa jam)
            $tanggalHariIni = Carbon::today();

            // Cari antrian terakhir HANYA untuk hari ini berdasarkan kode poli
            $last = Pendaftaran_rawat_jalan::where('antrian', 'like', $antrian->nama . '-%')
                ->whereDate('created_at', $tanggalHariIni)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last) {
                // Ambil angka terakhir dan increment
                $lastNumber = (int) str_replace($antrian->nama . '-', '', $last->antrian);
                $nextNumber = $lastNumber + 1;
            } else {
                // Jika belum ada antrian hari ini, mulai dari 1
                $nextNumber = 1;
            }

            $antrianBaru = $antrian->nama . '-' . $nextNumber;


            $pendaftaran = Pendaftaran_rawat_jalan::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id' => $pasien->id,
                'poli_id' => $request->poli_id_no,
                'tanggal_kujungan' => $request->tanggal_kunjungan_no,
                'dokter_id' => $request->dokter_id_no,
                'Penjamin' => $penjamin->id,
                'nomor_register'=> $no_registrasi,
                'antrian'=> $antrianBaru,
            ]);

            Pendaftaran_rawat_jalan_status::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id'=> $pasien->id,
                'nomor_register' => $no_registrasi,
                'tanggal_kujungan' => $request->tanggal_kunjungan_no,
                'register_id'=> $pendaftaran->id,
                'status_panggil'=> 0, // 0 = pendaftaran , 1 = perawat, 2 = dokter
                'status_pendaftaran' => 1, // 0 = batal, 1 = pendaftaran , 2 = hadir
                'Status_aplikasi' => 2 , // 1 = app manual , 2 = app Onlain ,3 = bpjs
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.',
                'noantrian' => $antrianBaru,

            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }

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

        return view('dashboard.pasien', compact('title','kodefasyankes','pasiens','provinsi','kelamin','goldar','agama','pernikahan','suku','bangsa','bahasa','pendidikan','pekerjaan','pasiennoverif','pasienall','pasienallnewnow','pasienallold'));
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
            "noihs_edit" => 'required',
            "jenis_kartu_edit" => 'nullable|string',
            "kelas_edit" => 'nullable|string',
            "provide_edit" => 'nullable|string',
            "tgl_exp_bpjs_edit" => 'nullable|date',
            "kodeprovide_edit" =>'nullable',
            "hubungan_keluarga_edit" =>'nullable',
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


    public function pendaftaran()
    {
        $title = "Pasien";
        $pasiens = pasien::all();
        $poli = poli::all();

        $today = Carbon::today(); // atau now()->startOfDay()

        $pendaftaran = Pendaftaran_rawat_jalan::with('status', 'poli', 'dokter.namauser', 'pasien' ,'penjamin')
        ->whereHas('status', function ($query) {
            $query->whereIn('status_pendaftaran', ['1', '2']);
        })
        ->whereDate('tanggal_kujungan', '=', $today)
        ->whereDoesntHave('apotek') // Filter: yang belum ada di tabel apotek
        ->get();


        $pasienallold = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', '=', $today)
        ->count();
        $pasienallnewnow = Pendaftaran_rawat_jalan::with('status')
        ->whereHas('status', function ($query) {
            $query->whereIn('status_pendaftaran', ['3']);
        })
        ->count();

        $penjamin = penjamin::all();

        $rekapPerPoliDokter = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', $today)
        ->whereHas('dokter.jadwal', function ($query) use ($today) {
            $query->whereDate('start', '=', $today);
        })
        ->select('poli_id', 'dokter_id', DB::raw('count(*) as jumlah'))
        ->groupBy('poli_id', 'dokter_id')
        ->with(['poli', 'dokter'])
        ->get();

        $jumlahDokter = $rekapPerPoliDokter->count(); // Banyaknya dokter unik
        $totalPasien = $rekapPerPoliDokter->sum('jumlah'); // Total pasien dari semua dokter

        // dd($rekapPerPoliDokter,$jumlahDokter,$totalPasien);

        return view('module.pendaftaran.daftar', compact('title','jumlahDokter','totalPasien','rekapPerPoliDokter','pendaftaran','pasiens','penjamin','poli','pasienallnewnow','pasienallold'));
    }

    public function getByPoli($id, Request $request)
    {
        $datetime = $request->input('datetime'); // ex: 2025-04-16 00:30:00

        $dokter = Dokter::where('poli', $id)
            ->whereHas('jadwal', function ($query) use ($datetime) {
                $query->where('start', '<=', $datetime)
                    ->where('end', '>=', $datetime);
            })
            ->with('namauser', 'namapoli', 'namastatuspegawai')
            ->get();

        return response()->json($dokter);
    }

    public function pendaftaranadd(Request $request)
    {
        try {

            $data = $request->validate([
                'pasien' => 'required',
                'poli_id' => 'required',
                'tanggal_kunjungan' => 'required',
                'dokter_id' => 'required',
                'penjamin_id' => 'required',
            ]);

            $pasien = Pasien::find($request->pasien);
            // Ambil tanggal kunjungan dan ubah jadi format yyDDD
            $tanggal = Carbon::parse($request->tanggal_kunjungan);
            $tanggalKode = $tanggal->format('y') . str_pad($tanggal->dayOfYear, 3, '0', STR_PAD_LEFT); // Contoh: 25113

            // Angka acak 4 digit
            $angkaAcak = mt_rand(1000, 9999); // Contoh: 1234

            // Gabungkan format akhir: 1234-25113
            $no_registrasi = $angkaAcak . '-' . $tanggalKode;

            $antrian = Loket::where('poli_id', $request->poli_id)->first();

            // Cari antrian terakhir berdasarkan kode poli
            $last = Pendaftaran_rawat_jalan::where('antrian', 'like', $antrian->nama . '-%')
            ->orderBy('created_at', 'desc')
            ->first();

            if ($last) {
                // Ambil angka terakhir dan increment
                $lastNumber = (int) str_replace($antrian->nama . '-', '', $last->antrian);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $antrianBaru = $antrian->nama . '-' . $nextNumber;


            $pendaftaran = Pendaftaran_rawat_jalan::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id' => $request->pasien,
                'poli_id' => $request->poli_id,
                'tanggal_kujungan' => $request->tanggal_kunjungan,
                'dokter_id' => $request->dokter_id,
                'Penjamin' => $request->penjamin_id,
                'nomor_register'=> $no_registrasi,
                'antrian' => $antrianBaru,
            ]);

            Pendaftaran_rawat_jalan_status::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id'=> $request->pasien,
                'nomor_register' => $no_registrasi,
                'tanggal_kujungan' => $request->tanggal_kunjungan,
                'register_id'=> $pendaftaran->id,
                'status_panggil'=> 0, // 0 = pendaftaran , 1 = perawat, 2 = dokter ,3 = selesai
                'status_pendaftaran' => 1, // 0 = batal, 1 = pendaftaran , 2 = hadir
                'Status_aplikasi' => 1 , // 1 = app manual , 2 = app Onlain ,3 = bpjs
                ]);

            $penjamin = penjamin::find($request->penjamin_id);
            if ($penjamin->nama == 'BPJS') {

                $poli = poli::find($request->poli_id)->first();

                // Ambil info dokter dan jadwal berdasarkan tanggal kunjungan
                $tanggalKunjungan = Carbon::parse($request->tanggal_kunjungan)->format('Y-m-d');

                $dokter = Dokter::with(['namauser', 'jadwal' => function ($query) use ($tanggalKunjungan) {
                    $query->whereDate('start', $tanggalKunjungan);
                }])->find($request->dokter_id);

                $jadwal = $dokter->jadwal->first();
                $jamPraktek = $jadwal
                    ? Carbon::parse($jadwal->start)->format('H:i') . '-' . Carbon::parse($jadwal->end)->format('H:i')
                    : '-';

                $databpjs = [
                    "nomorkartu"=> $pasien->no_bpjs,
                    "nik"=> $pasien->nik,
                    "nohp"=>  $pasien->telepon,
                    "kodepoli"=> $poli->kode,
                    "namapoli"=> $poli->nama,
                    "norm"=> $pasien->no_rm,
                    "tanggalperiksa"=> $tanggalKunjungan,
                    "kodedokter"=> $dokter->kode,
                    "namadokter"=> $dokter->namauser->name,
                    "jampraktek"=> $jamPraktek,
                    "nomorantrean"=> $antrianBaru,
                    "angkaantrean"=> $nextNumber,
                    "keterangan"=> "",
                ];

                $this->PcareController->post_ws_antria_bpjs($databpjs);
            }
            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil didaftarkan.',
                'noantrian' => $antrianBaru,
                'data' => $data
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }


    public function pendaftaranbatal(Request $request)
    {
        try {

            $pendaftaran = Pendaftaran_rawat_jalan_status::find($request->batalid_delete);

            // Pastikan data ditemukan
            if (!$pendaftaran) {
                return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan.');
            }


            $datapendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $pendaftaran->nomor_register)
                                ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
                                ->first();

            $penjamin = penjamin::find($datapendaftaran->Penjamin);
            if ($penjamin->nama == 'BPJS') {

                $poli = poli::find($datapendaftaran->poli_id)->first();

                $databpjs = [
                    "tanggalperiksa"=> Carbon::parse($pendaftaran->tanggal_kunjungan)->format('Y-m-d'),
                    "kodepoli"=> $poli->kode,
                    "nomorkartu"=> $datapendaftaran->pasien->no_bpjs,
                    "alasan"=> $request->alasanpembatalan,
                ];

                $this->PcareController->delete_ws_antria_bpjs($databpjs);
            }


            // Perbarui status_pendaftaran menjadi 0 (batal)
            $pendaftaran->status_pendaftaran = 0;
            $pendaftaran->save();

            $pemeriksaan = pelayanan::where('nomor_register', $pendaftaran->nomor_register)
            ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
            ->where('pasien_id', $pendaftaran->pasien_id)
            ->first();

            if ($pemeriksaan) {
                $pemeriksaan->delete();
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

    public function pendaftaranupdokter(Request $request)
    {
        try {

            $pendaftaran = Pendaftaran_rawat_jalan::find($request->rubahdokter_id);

            // Pastikan data ditemukan
            if (!$pendaftaran) {
                return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan.');
            }

            // Perbarui status_pendaftaran menjadi 0 (batal)
            $pendaftaran->dokter_id = $request->dokter_id_update;
            $pendaftaran->save();

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

    public function pendaftaranhadir(Request $request)
    {
        try {

            $pendaftaran = Pendaftaran_rawat_jalan_status::find($request->hadirid_delete);

            // Pastikan data ditemukan
            if (!$pendaftaran) {
                return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan.');
            }

            $datapendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $pendaftaran->nomor_register)
            ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
            ->first();


            pelayanan::updateOrCreate([
                'nomor_rm' => $datapendaftaran->nomor_rm ,
                'pasien_id' => $datapendaftaran->pasien_id ,
                'nomor_register' => $datapendaftaran->nomor_register ,
                'tanggal_kujungan' => $datapendaftaran->tanggal_kujungan ,
                'poli_id' => $datapendaftaran->poli_id ,
                'dokter_id'=> $datapendaftaran->dokter_id,
            ]);

            $penjamin = penjamin::find($datapendaftaran->Penjamin);

            if ($penjamin->nama == 'BPJS') {

                $poli = poli::find($datapendaftaran->poli_id)->first();

                date_default_timezone_set('UTC');
                $Timestamp = strval(time() - strtotime('1970-01-01 00:00:00'));
                $newTimestamp = $Timestamp * 1000;

                $databpjs = [
                    "tanggalperiksa"=> Carbon::parse($pendaftaran->tanggal_kunjungan, 'Asia/Jakarta')->format('Y-m-d'),
                    "kodepoli"=> $poli->kode,
                    "nomorkartu"=> $datapendaftaran->pasien->no_bpjs,
                    "status"=> 1,
                    "waktu"=> $newTimestamp,
                ];

                $this->PcareController->update_ws_antria_bpjs($databpjs);

                $pendaftaranpcare = [
                    "kdProviderPeserta"=> $datapendaftaran->pasien->kodeprovide,
                    "tglDaftar"=> Carbon::parse($pendaftaran->tanggal_kunjungan, 'Asia/Jakarta')->format('d-m-Y'),
                    "noKartu"=> $datapendaftaran->pasien->no_bpjs,
                    "kdPoli"=> $poli->kode,
                    "keluhan"=> null,
                    "kunjSakit"=> true,
                    "sistole"=> 0,
                    "diastole"=> 0,
                    "beratBadan"=> 0,
                    "tinggiBadan"=> 0,
                    "respRate"=> 0,
                    "lingkarPerut"=> 0,
                    "heartRate"=> 0,
                    "rujukBalik"=> 0,
                    "kdTkp"=> "10",
                ];

                $nourut = $this->PcareController->post_pendaftaran_bpjs($pendaftaranpcare);
                $data = json_decode($nourut->getContent(), true);
                $pendaftaran_nourut = Pendaftaran_rawat_jalan::where('nomor_register', $pendaftaran->nomor_register)
                    ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
                    ->first();
                if ($pendaftaran_nourut) {
                    $pendaftaran_nourut->no_urut = $data['data']['message'];
                    $pendaftaran_nourut->save();
                }
            }
            // Perbarui status_pendaftaran menjadi 0 (batal)
            $pendaftaran->status_pendaftaran = 2;
            $pendaftaran->save();

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

    // Data Barang

    public function dabar()
    {
        $title = "Data Barang";
        $dabar = gudang_barang::all();
        $satuan = gudang_satuan::all();
        $kategori = gudang_kategori::all();
        $singkron = external_database::all();
        return view('dashboard.dabar', compact('title','dabar','satuan','kategori','singkron'));
    }

    public function dabaradd(Request $request)
    {
        try {
            $request->validate([
                'kode_barang'           => 'required|string',
                'nama_barang'           => 'required|string',
                'kfa_kode'              => 'required|string',
                'jenis_formularium'     => 'required|string',
                'nama_industri_barang'  => 'required|string',
                'jenis_obat'            => 'required|string',
                'jenis_generik'         => 'required|string',
                'satuan_kecil'          => 'required|string',
                'nilai_satuan_kecil'    => 'required|string',
                'satuan_sedang'         => 'required|string',
                'nilai_satuan_sedang'   => 'required|string',
                'satuan_besar'          => 'required|string',
                'tempat_penyimpanan'    => 'required|string',
                'barcode'               => 'required|string',
                'barang_kategori'       => 'required|string',
                'bentuk_sediaan'        => 'required|string',
            ], [
                // 👇 Custom attribute names
                'kode_barang' => 'Kode Barang',
                'nama_barang' => 'Nama Barang',
                'kfa_kode' => 'Kode KFA',
                'jenis_formularium' => 'Jenis Formularium',
                'nama_industri_barang' => 'Industri Barang',
                'jenis_obat' => 'Jenis Obat',
                'jenis_generik' => 'Jenis Generik',
                'satuan_kecil' => 'Satuan Kecil',
                'nilai_satuan_kecil' => 'Nilai Satuan Kecil',
                'satuan_sedang' => 'Satuan Sedang',
                'nilai_satuan_sedang' => 'Nilai Satuan Sedang',
                'satuan_besar' => 'Satuan Besar',
                'tempat_penyimpanan' => 'Tempat Penyimpanan',
                'barcode' => 'Barcode',
                'barang_kategori' => 'Barang Kategori',
                'bentuk_sediaan' => 'Bentuk Sediaan',
            ]);

            // Simpan data ke database
            $satuan = gudang_barang::create([
                'kode_barang' => $request->input('kode_barang'),
                'nama_barang' => $request->input('nama_barang'),
                'kfa_kode' => $request->input('kfa_kode'),
                'jenis_formularium' => $request->input('jenis_formularium'),
                'nama_industri_barang' => $request->input('nama_industri_barang'),
                'jenis_obat' => $request->input('jenis_obat'),
                'jenis_generik' => $request->input('jenis_generik'),
                'satuan_kecil' => $request->input('satuan_kecil'),
                'nilai_satuan_kecil' => $request->input('nilai_satuan_kecil'),
                'satuan_sedang' => $request->input('satuan_sedang'),
                'nilai_satuan_sedang' => $request->input('nilai_satuan_sedang'),
                'satuan_besar' => $request->input('satuan_besar'),
                'nilai_satuan_besar' => 1,
                'tempat_penyimpanan' => $request->input('tempat_penyimpanan'),
                'barcode' => $request->input('barcode'),
                'gudang_kategori' => $request->input('barang_kategori'),
                'bentuk_sediaan' => $request->input('bentuk_sediaan'),
                'user_input_id' => Auth::user()->id,
                'user_input_nama' => Auth::user()->name,
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data Barang berhasil ditambahkan!',
                'data' => $satuan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data Barang Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Data Barang!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function dabaredit(Request $request)
    {
        $request->validate([
            'nama_barang_edit'           => 'required|string',
            'kode_kfa_edit'              => 'required|string',
            'jenis_formularium_edit'     => 'required|string',
            'industri_barang_edit'       => 'required|string',
            'jenis_obat_edit'            => 'required|string',
            'jenis_generik_edit'         => 'required|string',
            'satuan_kecil_edit'          => 'required|string',
            'nilai_satuan_kecil_edit'    => 'required|string',
            'satuan_sedang_edit'         => 'required|string',
            'nilai_satuan_sedang_edit'   => 'required|string',
            'satuan_besar_edit'          => 'required|string',
            'tempat_penyimpanan_edit'    => 'required|string',
            'barcode_edit'               => 'required|string',
            'barang_kategori_edit'       => 'required|string',
            'bentuk_sediaan_edit'        => 'required|string',
        ], [
            'nama_barang_edit'           => 'Masukan Data Nama Barang',
            'kode_kfa_edit'              => 'Kode KFA',
            'jenis_formularium_edit'     => 'Pilih Jenis Formularium',
            'industri_barang_edit'       => 'Industri Barang',
            'jenis_obat_edit'            => 'Pilih Jenis Obat',
            'jenis_generik_edit'         => 'Masukan Jenis Generik Barang',
            'satuan_kecil_edit'          => 'Pilih Satuan Kecil',
            'nilai_satuan_kecil_edit'    => 'Masukan Satuan Kecil',
            'satuan_sedang_edit'         => 'Pilih Satuan Sedang',
            'nilai_satuan_sedang_edit'   => 'Masukan Satuan Sedang',
            'satuan_besar_edit'          => 'Pilih Satuan Besar',
            'tempat_penyimpanan_edit'    => 'Masukan Tempat Penyimpanan',
            'barcode_edit'               => 'Masukan No Barcode',
            'barang_kategori_edit'       => 'Pilih Kategori Barang',
            'bentuk_sediaan_edit'        => 'Pilih Bentuk Sediaan Barang',
        ]);

        $dabar = gudang_barang::find($request->dabarid_edit);

        if (!$dabar) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan!'
            ], 404);
        }

        $dabar->nama_barang = $request->nama_barang_edit;
        $dabar->kfa_kode = $request->kode_kfa_edit;
        $dabar->jenis_formularium = $request->jenis_formularium_edit;
        $dabar->nama_industri_barang = $request->industri_barang_edit;
        $dabar->satuan_kecil = $request->satuan_kecil_edit;
        $dabar->satuan_sedang = $request->satuan_sedang_edit;
        $dabar->satuan_besar = $request->satuan_besar_edit;
        $dabar->nilai_satuan_kecil = $request->nilai_satuan_kecil_edit;
        $dabar->nilai_satuan_sedang = $request->nilai_satuan_sedang_edit;
        $dabar->tempat_penyimpanan = $request->tempat_penyimpanan_edit;
        $dabar->barcode = $request->barcode_edit;
        $dabar->gudang_kategori = $request->barang_kategori_edit;
        $dabar->jenis_obat = $request->jenis_obat_edit;
        $dabar->jenis_generik = $request->jenis_generik_edit;
        $dabar->bentuk_sediaan = $request->bentuk_sediaan_edit;
        $dabar->user_input_id = Auth::user()->id;
        $dabar->user_input_nama = Auth::user()->name;
        $dabar->save();

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil diperbarui!'
        ]);
    }

    public function dabardelete(Request $request)
    {

        $request->validate([
            'dabarid_delete' => 'required'
        ]);

        $dabar = gudang_barang::find($request->dabarid_delete);

        if (!$dabar) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan!'
            ], 404);
        }

        $dabar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil dihapus!'
        ]);
    }

    public function dabarexport()
    {
        return Excel::download(new Gudang_barangExport, 'Data Gudang Barang.xlsx');
    }

    public function dabarimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Gudang_barangImport, $request->file('file'));


        return redirect()->route('dabar.get')->with('success', 'Data berhasil diimpor!');
    }

    // Koneksi antar database
    public function dabarsingkron($id)
    {
        $externalDb = external_database::findOrFail($id);

        $config = [
            'driver' => 'mysql',
            'host' => $externalDb->host,
            'database' => $externalDb->database,
            'username' => $externalDb->username,
            'password' => $externalDb->password,
            'port' => $externalDb->port ?? 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        $factory = app(ConnectionFactory::class);
        $connection = $factory->make($config, $externalDb->name);

        // Gunakan koneksi ini untuk query
        $data = $connection->table('gudang_barangs')->get();

        $response = response()->json($data)->getData();

        try {
            // Simpan data ke database
            foreach ($response  as $item) {
                gudang_barang::updateOrCreate(
                    [
                        'kode_barang' => $item->kode_barang,
                        'nama_barang' => $item->nama_barang,
                        'kfa_kode' => $item->kfa_kode,
                        'jenis_formularium' => $item->jenis_formularium,
                        'nama_industri_barang' => $item->nama_industri_barang,
                        'satuan_kecil' => $item->satuan_kecil,
                        'satuan_sedang' => $item->satuan_sedang,
                        'satuan_besar' => $item->satuan_besar,
                        'nilai_satuan_kecil' => $item->nilai_satuan_kecil,
                        'nilai_satuan_sedang' => $item->nilai_satuan_sedang,
                        'nilai_satuan_besar' => $item->nilai_satuan_besar,
                        'tempat_penyimpanan' => $item->tempat_penyimpanan,
                        'barcode' => $item->barcode,
                        'gudang_kategori' => $item->gudang_kategori,
                        'jenis_obat' => $item->jenis_obat,
                        'jenis_generik' => $item->jenis_generik,
                        'bentuk_sediaan' => $item->bentuk_sediaan,
                        'user_input_id' => Auth::user()->id,
                        'user_input_nama' => Auth::user()->name,
                    ]
                );
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Data barang!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        //Generate Kode Barang Otomatis
        public function generateKodeDataBarang()
        {
            // Mengambil data barang terakhir dari tabel 'gudang_barang'
            $last = gudang_barang::orderBy('id', 'desc')->first();

            // Jika tidak ada data barang sebelumnya atau kode barang tidak sesuai format 'KBR-xxxx'
            if (!$last || !preg_match('/^KBR-(\d{4})$/', $last->kode_barang, $match)) {
                $nextNumber = 1;  // Mulai dengan nomor 1 jika tidak ada data atau format kode salah
            } else {
                // Jika ada data sebelumnya, ambil angka terakhir dan tambah 1
                $nextNumber = (int)$match[1] + 1;
            }

            // Membuat kode barang baru dengan format 'KBR-xxxx' (dengan padding 0 di depan)
            $kode = 'KBR-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Mengembalikan response dalam format JSON
            return response()->json([
                'success' => true,
                'kode_barang' => $kode
            ]);
        }

    // Data Barang end


    // Pembelian

    public function pembelian()
    {
        $title = "Pembelian";
        $supplier = gudang_supplier_industri::all();
        $dabar = gudang_barang::all();
        $user = User::all();
        $settingHarga = gudang_setting_harga::first();

        return view('dashboard.pembelian', compact('title','supplier','dabar','user','settingHarga'));
    }

    public function pembelianadd(Request $request)
    {
        try {
            $request->validate([
                'data_json_tabel' => 'required|string',
                'nomor_faktur' => 'required|string',
                'supplier_select' => 'nullable|string',
                'supplier_input' => 'nullable|string',
                'no_po_sp' => 'required|string',
                'no_faktur_supplier' => 'required|string',
                'tanggal_terima_barang' => 'required|string',
                'tanggal_faktur' => 'required|string',
                'tanggal_jatuh_tempo' => 'required|string',
                'pajak_ppn' => 'required|string',
                'metode_hna' => 'required|string',
                'sub_total_keseluruhan_input' => 'required|string',
                'diskon_total_keseluruhan_input' => 'required|string',
                'ppn_total_keseluruhan_input' => 'required|string',
                'total_keseluruhan_input' => 'required|string',
                'materai' => 'required|string',
                'koreksi' => 'required|string',
                'penerima_barang' => 'required|string',
            ], [
                // Custom attribute names
                'data_json_tabel' => 'Data JSON Tabel',
                'nomor_faktur' => 'Nomor Faktur',
                'supplier_select' => 'Supplier Select',
                'supplier_input' => 'Supplier Input',
                'no_po_sp' => 'Nomor PO/SP',
                'no_faktur_supplier' => 'Nomor Faktur Supplier',
                'tanggal_terima_barang' => 'Tanggal Terima Barang',
                'tanggal_faktur' => 'Tanggal Faktur',
                'tanggal_jatuh_tempo' => 'Tanggal Jatuh Tempo',
                'pajak_ppn' => 'Pajak PPN',
                'metode_hna' => 'Metode HNA',
                'sub_total_keseluruhan_input' => 'Sub Total Keseluruhan',
                'diskon_total_keseluruhan_input' => 'Diskon Total Keseluruhan',
                'ppn_total_keseluruhan_input' => 'PPN Total Keseluruhan',
                'total_keseluruhan_input' => 'Total Keseluruhan',
                'materai' => 'Materai',
                'koreksi' => 'Koreksi',
                'penerima_barang' => 'Penerima Barang',
            ]);

            // Simpan data ke database (1)
            $pembelian = pembelian::create([
                'nomor_faktur' => $request->input('nomor_faktur'),
                'supplier' => $request->input('supplier_select') ?: $request->input('supplier_input'),
                'no_po_sp' => $request->input('no_po_sp'),
                'no_faktur_supplier' => $request->input('no_faktur_supplier'),
                'tanggal_terima_barang' => $request->input('tanggal_terima_barang'),
                'tanggal_faktur' => $request->input('tanggal_faktur'),
                'tanggal_jatuh_tempo' => $request->input('tanggal_jatuh_tempo'),
                'pajak_ppn' => $request->input('pajak_ppn'),
                'metode_hna' => $request->input('metode_hna'),
                'sub_total' => $request->input('sub_total_keseluruhan_input'),
                'total_diskon' => $request->input('diskon_total_keseluruhan_input'),
                'ppn_total' => $request->input('ppn_total_keseluruhan_input'),
                'materai' => $request->input('materai'),
                'koreksi' => $request->input('koreksi'),
                'total' => $request->input('total_keseluruhan_input'),
                'penerima_barang' => $request->input('penerima_barang'),
                'user_input_id' => Auth::user()->id,
                'user_input_nama' => Auth::user()->name,
            ]);

            // Simpan detail pembelian
            $dataDetail = json_decode($request->data_json_tabel, true);

            foreach ($dataDetail as $detail) {
                // Ambil metode
                $metode = $request->metode_hna;

                // Ambil dan konversi subtotal ke float
                $subTotalRaw = $detail['hargaSatuan'];
                $subTotal = (float) str_replace(['Rp', '.', ' '], '', $subTotalRaw);

                // Ambil dan konversi diskon
                $diskon = $detail['disc'];
                $diskonPersen = 0;
                $diskonRupiah = 0;

                if (strpos($diskon, '%') !== false) {
                    // Jika diskon dalam persen (misal: "10%")
                    $diskonPersen = (float) str_replace('%', '', $diskon);
                } else {
                    // Jika diskon dalam rupiah
                    $diskonRupiah = (float) str_replace(['Rp', '.', ' '], '', $diskon);
                }

                // Ambil dan konversi PPN ke float
                $ppn = (float) str_replace('%', '', $request->pajak_ppn);

                $PPNbarang = 0;
                $Diskonbarang = 0;
                $hargaDiskon_4 = 0;
                $hargaDasar = $subTotal;

                if ($metode == '1') {
                    // Metode 1: Hanya subtotal
                    $hargaDasar = $subTotal;
                } elseif ($metode == '2') {
                    // Metode 2: Subtotal + PPN
                    $PPNbarang = $subTotal * ($ppn / 100);
                    $hargaDasar = $subTotal + $PPNbarang;
                } elseif ($metode == '3') {
                    // Metode 3: Subtotal - Diskon
                    if ($diskonPersen > 0) {
                        $Diskonbarang = $subTotal * ($diskonPersen / 100);
                        $hargaDasar = $subTotal - $Diskonbarang;
                    } else {
                        $Diskonbarang = $diskonRupiah;
                        $hargaDasar = $subTotal - $Diskonbarang;
                    }
                } elseif ($metode == '4') {
                    // Metode 4: Subtotal + PPN - Diskon
                    if ($diskonPersen > 0) {
                        $hargaDiskon_4 = $subTotal * ($diskonPersen / 100);
                        $Diskonbarang = $hargaDiskon_4;
                    } else {
                        $hargaDiskon_4 = $diskonRupiah;
                        $Diskonbarang = $hargaDiskon_4;
                    }

                    $hargaSetelahDiskon = $subTotal - $hargaDiskon_4;
                    $PPNbarang = $hargaSetelahDiskon * ($ppn / 100);
                    $hargaDasar = $hargaSetelahDiskon + $PPNbarang;
                }

                $setting = gudang_setting_harga::first(); // atau where('some_column', ...)

                $hargaJual1 = $hargaDasar * (1 + ($setting->harga_jual_1 / 100));
                $hargaJual2 = $hargaDasar * (1 + ($setting->harga_jual_2 / 100));
                $hargaJual3 = $hargaDasar * (1 + ($setting->harga_jual_3 / 100));

                // Simpan ke gudang
                gudang_barang_harga::create([
                    'kode_obat_alkes' => $detail['kodeBarang'],
                    'nama_obat_alkes' => $detail['nama'],
                    'harga_dasar' => $hargaDasar,
                    'harga_jual_1' => $hargaJual1,
                    'harga_jual_2' => $hargaJual2,
                    'harga_jual_3' => $hargaJual3,
                    'diskon' => $Diskonbarang,
                    'ppn' => $PPNbarang,
                    'tanggal_obat_masuk' => $request->input('tanggal_terima_barang'),
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);

                // Simpan ke detail pembelian
                pembelian_details::create([
                    'nomor_faktur' => $request->input('nomor_faktur'),
                    'nama_obat_alkes' => $detail['nama'],
                    'kode_obat_alkes' => $detail['kodeBarang'],
                    'qty' => $detail['qty'],
                    'harga_satuan' => $detail['hargaSatuan'],
                    'diskon' => $detail['disc'],
                    'exp' => $detail['exp'],
                    'batch' => $detail['batch'],
                    'sub_total' => $detail['subTotal'],
                ]);

                gudang_barang_stok::create([
                    'kode_obat_alkes' => $detail['kodeBarang'],
                    'nama_obat_alkes' => $detail['nama'],
                    'qty' => $detail['qty'],
                    'tanggal_terima_obat' => $request->input('tanggal_terima_barang'),
                    'expired' => $detail['exp'],
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data pembelian berhasil ditambahkan!',
                'data' => $pembelian
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembelian Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Data pembelian!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //GENERATE NO FAKTUR
        public function generateFakturPembelian()
        {
            try {
                // Ambil tanggal hari ini dalam format Ymd (tanpa tanda -)
                $today = date('Ymd'); // Format menjadi YYYYMMDD

                // Cari nomor faktur terakhir untuk tanggal yang sama
                $lastPembelian = pembelian::whereDate('created_at', '=', date('Y-m-d'))  // filter by actual date
                                            ->latest('nomor_faktur')
                                            ->first();

                // Format dasar nomor faktur 'INV-YYYYMMDD-'
                $prefix = 'INV-' . $today . '-';

                // Jika ada nomor faktur terakhir, ambil angka di akhir nomor faktur dan tambahkan 1
                if ($lastPembelian) {
                    preg_match('/(\d+)$/', $lastPembelian->nomor_faktur, $matches);
                    $nextNumber = isset($matches[0]) ? (int) $matches[0] + 1 : 1;
                } else {
                    // Jika tidak ada nomor faktur sebelumnya, mulai dari 1
                    $nextNumber = 1;
                }

                // Format nomor faktur dengan padding 5 digit
                $nextNomorFaktur = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

                return response()->json([
                    'success' => true,
                    'kode_faktur' => $nextNomorFaktur
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghasilkan nomor faktur.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

    //CETAK PDF
        public function cetakPembelianPdf($nomor_faktur)
        {
            // Ambil data pembelian
            $pembelian = pembelian::where('nomor_faktur', $nomor_faktur)->first();

            // Ambil detail pembelian
            $details = pembelian_details::where('nomor_faktur', $nomor_faktur)->get();

            // Pastikan data numerik dikonversi dengan benar
            foreach ($details as $detail) {
                // Bersihkan format mata uang jika ada
                $detail->harga_satuan = is_numeric($detail->harga_satuan) ? $detail->harga_satuan :
                    floatval(str_replace(['Rp', '.', ','], ['', '', '.'], $detail->harga_satuan));

                // Hitung diskon
                $diskonValue = 0;
                if (strpos($detail->diskon, '%') !== false) {
                    // Jika diskon dalam persen
                    $diskonPersen = floatval(str_replace('%', '', $detail->diskon));
                    $diskonValue = ($detail->harga_satuan * $detail->qty) * ($diskonPersen / 100);
                } elseif (strpos($detail->diskon, 'Rp') !== false) {
                    // Jika diskon dalam rupiah
                    $diskonValue = floatval(str_replace(['Rp', '.', ','], ['', '', '.'], $detail->diskon));
                }

                // Hitung subtotal setelah diskon
                $detail->sub_total = ($detail->harga_satuan * $detail->qty) - $diskonValue;
            }

            // Tampilkan PDF
            $pdf = PDF::loadView('pdf.pembelian', compact('pembelian', 'details'));
            return $pdf->stream('pembelian-'.$nomor_faktur.'.pdf');
        }

    // Pembelian end

    // Kasir

    public function kasir()
    {
        $title = "Kasir";

        $apotek = apotek::with('detail_obat','detail_tindakan')->where('status_kasir', 0)->get();

        $tanggal = Carbon::now()->format('Ymd');

        $tindakan = pelayanan_soap_dokter_tindakan::where('status_kasir', 0)->whereDoesntHave('cek_resep')->with('data_soap')->get();

        $latestFaktur = kasir::where('kode_faktur', 'LIKE', "TND-{$tanggal}-%")
                    ->orderBy('kode_faktur', 'desc')
                    ->first();

        $lastNumber = 0;
        if ($latestFaktur) {
            $lastNumber = (int) substr($latestFaktur->kode_faktur, -4);
        }

        $kodeFakturMap = [];

        foreach ($tindakan as $item) {
            if (isset($kodeFakturMap[$item->no_rawat])) {
                $item->kode_faktur = $kodeFakturMap[$item->no_rawat];
            } else {
                $existing = kasir::where('no_rawat', $item->no_rawat)->first();
                if ($existing) {
                    $kodeFakturMap[$item->no_rawat] = $existing->kode_faktur;
                    $item->kode_faktur = $existing->kode_faktur;
                } else {
                    $lastNumber++;
                    $newNumber = str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
                    $newKodeFaktur = "TND-{$tanggal}-{$newNumber}";
                    $kodeFakturMap[$item->no_rawat] = $newKodeFaktur;
                    $item->kode_faktur = $newKodeFaktur;
                }
            }
        }

        // Filter supaya unique berdasarkan no_rawat (hanya 1 data tiap no_rawat)
        $tindakan = $tindakan->unique('no_rawat')->values();

        return view('dashboard.kasir', compact('title', 'apotek', 'tindakan'));
    }

    public function kasirPembayaran(Request $request, $kode_faktur)
    {
        $title = "Detail Pembayaran Kasir";
        $no_rawat = $request->query('no_rawat');
        $apotek = apotek::with('data_soap','detail_obat')->where('kode_faktur', $kode_faktur)->first();
        $tindakan = pelayanan_soap_dokter_tindakan::with('data_soap')->where('no_rawat', $no_rawat)->first();

        $apotekTabel = apotek_prebayar::where('kode_faktur', $kode_faktur)->get();
        $tindakanTabel = pelayanan_soap_dokter_tindakan::with('data_soap')->where('no_rawat', $no_rawat)->get();

        $penjamin = penjamin::all();
        $tindakanTambahan = perawatan_kategori::with('perawatan_tindakan')->get();

        $bank = bank::all();

        // dd($tindakanTabel);
        return view('dashboard.kasir_pembayaran', compact('title','no_rawat','kode_faktur','apotek','apotekTabel','tindakan','tindakanTabel','penjamin','tindakanTambahan','bank'));
    }

    public function kasiradd(Request $request)
    {
        try {
            $validated = $request->validate([
                'data_hidden' => 'nullable|string',
                'kode_faktur_hidden' => 'required|string',
                'no_rawat_hidden' => 'nullable|string',
                'no_rm' => 'required|string',
                'nama' => 'required|string',
                'sex' => 'nullable|string',
                'usia' => 'nullable|string',
                'alamat' => 'nullable|string',
                'poli' => 'required|string',
                'dokter' => 'nullable|string',
                'jenis_perawatan' => 'required|string',
                'penjamin' => 'required|string',
                'sub_total' => 'required|string',
                'potongan_harga' => 'nullable|string',
                'administrasi' => 'nullable|string',
                'materai' => 'nullable|string',
                'total' => 'required|string',
                'tagihan' => 'required|string',
                'kurang_dibayar' => 'required|string',
                'payment_method_1' => 'required|string',
                'payment_nominal_1' => 'required|string',
                'payment_type_1' => 'nullable|string',
                'payment_ref_1' => 'nullable|string',
                'payment_method_2' => 'nullable|string',
                'payment_nominal_2' => 'nullable|string',
                'payment_type_2' => 'nullable|string',
                'payment_ref_2' => 'nullable|string',
                'payment_method_3' => 'nullable|string',
                'payment_nominal_3' => 'nullable|string',
                'payment_type_3' => 'nullable|string',
                'payment_ref_3' => 'nullable|string',
            ], [
                'kode_faktur_hidden'  => 'Kode Faktur',
                'no_faktur_hidden'    => 'No Rawat',
                'no_rm'               => 'No RM',
                'nama'                => 'Nama Pasien',
                'sex'                 => 'Jenis Kelamin',
                'usia'                => 'Usia',
                'alamat'              => 'Alamat',
                'poli'                => 'Poli',
                'dokter'              => 'Dokter',
                'jenis_perawatan'     => 'Jenis Perawatan',
                'penjamin'            => 'Penjamin',
                'sub_total'           => 'Subtotal',
                'potongan_harga'      => 'Potongan Harga',
                'administrasi'        => 'Administrasi',
                'materai'             => 'Materai',
                'total'               => 'Total',
                'tagihan'             => 'Tagihan',
                'kurang_dibayar'      => 'Kurang Dibayar',
                'payment_method_1'    => 'Metode Pembayaran 1',
                'payment_nominal_1'   => 'Nominal Pembayaran 1',
                'payment_type_1'      => 'Tipe Pembayaran 1',
                'payment_ref_1'       => 'Referensi Pembayaran 1',
                'payment_method_2'    => 'Metode Pembayaran 2',
                'payment_nominal_2'   => 'Nominal Pembayaran 2',
                'payment_type_2'      => 'Tipe Pembayaran 2',
                'payment_ref_2'       => 'Referensi Pembayaran 2',
                'payment_method_3'    => 'Metode Pembayaran 3',
                'payment_nominal_3'   => 'Nominal Pembayaran 3',
                'payment_type_3'      => 'Tipe Pembayaran 3',
                'payment_ref_3'       => 'Referensi Pembayaran 3',
            ]);

            $kasir = kasir::create([
                'kode_faktur'       => $validated['kode_faktur_hidden'],
                'no_rawat'          => $validated['no_rawat_hidden'] ?? null,
                'no_rm'             => $validated['no_rm'],
                'nama'              => $validated['nama'],
                'sex'               => $validated['sex'] ?? null,
                'usia'              => $validated['usia'] ?? null,
                'alamat'            => $validated['alamat'] ?? null,
                'poli'              => $validated['poli'],
                'dokter'            => $validated['dokter'] ?? null,
                'jenis_perawatan'   => $validated['jenis_perawatan'],
                'penjamin'          => $validated['penjamin'],
                'tanggal'           => now()->format('Y-m-d'),
                'sub_total'         => $validated['sub_total'],
                'potongan_harga'    => $validated['potongan_harga'] ?? '0',
                'administrasi'      => $validated['administrasi'] ?? '0',
                'materai'           => $validated['materai'] ?? '0',
                'total'             => $validated['total'],
                'tagihan'           => $validated['tagihan'],
                'kembalian'         => $validated['kurang_dibayar'], // atau hitung: bayar - tagihan?

                'payment_method_1'  => $validated['payment_method_1'],
                'payment_nominal_1' => $validated['payment_nominal_1'],
                'payment_type_1'    => $validated['payment_type_1'] ?? null,
                'payment_ref_1'     => $validated['payment_ref_1'] ?? null,

                'payment_method_2'  => $validated['payment_method_2'] ?? null,
                'payment_nominal_2' => $validated['payment_nominal_2'] ?? null,
                'payment_type_2'    => $validated['payment_type_2'] ?? null,
                'payment_ref_2'     => $validated['payment_ref_2'] ?? null,

                'payment_method_3'  => $validated['payment_method_3'] ?? null,
                'payment_nominal_3' => $validated['payment_nominal_3'] ?? null,
                'payment_type_3'    => $validated['payment_type_3'] ?? null,
                'payment_ref_3'     => $validated['payment_ref_3'] ?? null,

                'user_input_id'     => Auth::user()->id,
                'user_input_name'   => Auth::user()->name,
            ]);

            // Simpan detail pembelian
            $dataDetail = json_decode($request->data_hidden, true);

            if (!empty($dataDetail['tindakan'])) {
                foreach ($dataDetail['tindakan'] as $t) {
                    kasir_detail_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_obat_tindakan'   => $t['jenis_tindakan'],
                        'harga_obat_tindakan'  => $t['harga'],
                        'qty_pelaksana'        => $t['jenis_pelaksana'],
                        'total'                => $t['total'],
                        'tanggal'              => $t['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);

                    kasir_tindakan_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_tindakan'        => $t['jenis_tindakan'],
                        'harga_tindakan'       => $t['harga'],
                        'pelaksana'            => $t['jenis_pelaksana'],
                        'total'                => $t['total'],
                        'tanggal'              => $t['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);
                }
            }

            if (!empty($dataDetail['apotek'])) {
                foreach ($dataDetail['apotek'] as $a) {
                    kasir_detail_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_obat_tindakan'   => $a['nama_obat_alkes'],
                        'harga_obat_tindakan'  => $a['harga'],
                        'qty_pelaksana'        => $a['qty'],
                        'total'                => $a['total'],
                        'tanggal'              => $a['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);

                    kasir_apotek_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_obat_alkes'      => $a['nama_obat_alkes'],
                        'harga_obat_alkes'     => $a['harga'],
                        'qty'                  => $a['qty'],
                        'total'                => $a['total'],
                        'tanggal'              => $a['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);
                }
            }

            if (!empty($dataDetail['diskon'])) {
                foreach ($dataDetail['diskon'] as $d) {
                    kasir_detail_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_obat_tindakan'   => $d['nama'],
                        'harga_obat_tindakan'  => abs($d['harga']),
                        'qty_pelaksana'        => $d['jenis'],
                        'total'                => abs($d['nilai']),
                        'tanggal'              => $d['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);

                    kasir_diskon::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_diskon'          => $d['nama'],
                        'harga_diskon'         => abs($d['harga']),
                        'qty'                  => $d['jenis'],
                        'total'                => abs($d['nilai']),
                        'tanggal'              => $d['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);
                }
            }

            $updateApotek = apotek::where('kode_faktur', $request->kode_faktur_hidden)->first();

            if ($updateApotek) {
                $updateApotek->status_kasir = 1;
                $updateApotek->save();
            }

            $updateTindakan = pelayanan_soap_dokter_tindakan::where('no_rawat', $request->no_rawat_hidden)->get();

            if ($updateTindakan->isNotEmpty()) {
                foreach ($updateTindakan as $item) {
                    $item->status_kasir = 1;
                    $item->save();
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran kasir berhasil dilakukan.',
                'data' => $kasir,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function previewData(Request $request)
    {
        $noRawat = $request->input('no_rawat');

        $data = DB::table('pelayanan_soap_dokter_tindakans')
            ->where('no_rawat', $noRawat)
            ->get(['jenis_tindakan', 'jenis_pelaksana', 'harga']);

        return response()->json($data);
    }

    public function generatePdf($kode_faktur)
    {
        $kasir = kasir::with('detail_lunas')->where('kode_faktur', $kode_faktur)->firstOrFail();

        $pdf = Pdf::loadView('pdf.kasir_bil', compact('kasir'))->setPaper('a5', 'landscape');
        return $pdf->stream('kasir_' . $kode_faktur . '.pdf');
    }


    // End Kasir

    // Data Lunas Kasir

    public function datakasir_lunas()
    {
        $title = "Kasir Lunas";

        $header = kasir::all();

        return view('dashboard.datakasir_lunas', compact('title','header'));
    }

    // Contoh format rupiah tanpa desimal
    private function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    public function datakasir_lunas_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = count($data);

        $cash = 0;
        $debit = 0;
        $credit = 0;
        $transfer = 0;

        foreach ($data as $item) {
            for ($i = 1; $i <= 3; $i++) {
                $methodKey = "payment_method_$i";
                $nominalKey = "payment_nominal_$i";

                if (!empty($item[$methodKey]) && !empty($item[$nominalKey])) {
                    $method = strtolower($item[$methodKey]);
                    // Hilangkan 'Rp ', titik dan spasi dari nominal sebelum konversi
                    $nominalStr = str_replace(['Rp', '.', ' '], '', $item[$nominalKey]);

                    // Cek apakah setelah dibersihkan adalah angka
                    if ($nominalStr) {
                        $nominal = $nominalStr;

                        switch ($method) {
                            case 'cash':
                                $cash += $nominal;
                                break;
                            case 'debit':
                                $debit += $nominal;
                                break;
                            case 'credit':
                                $credit += $nominal;
                                break;
                            case 'transfer':
                                $transfer += $nominal;
                                break;
                        }
                    }
                }
            }
        }



        // Contoh penggunaan:
        $cashFormatted = $this->formatRupiah($cash);
        $debitFormatted = $this->formatRupiah($debit);
        $creditFormatted = $this->formatRupiah($credit);
        $transferFormatted = $this->formatRupiah($transfer);

        $pendapatan = $cash + $debit + $credit + $transfer;
        $pendapatanFormatted = $this->formatRupiah($pendapatan);

        $pdf = Pdf::loadView('pdf.data_lunas_kasir', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli','total_invoice', 'cashFormatted', 'debitFormatted', 'creditFormatted', 'transferFormatted', 'pendapatanFormatted'))
                ->setPaper('a4', 'landscape');

        $filename = 'kasir_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // End Data Lunas Kasir

    // Data Lunas Detail

    public function datakasir_detail()
    {
        $title = "Kasir Detail Lunas";

        $header = kasir::with('detail_lunas')->get();

        return view('dashboard.datakasir_detail_lunas', compact('title','header'));
    }

    public function datakasir_detail_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $cash = 0;
        $debit = 0;
        $credit = 0;
        $transfer = 0;

        foreach ($data as $item) {
            for ($i = 1; $i <= 3; $i++) {
                $methodKey = "payment_method_$i";
                $nominalKey = "payment_nominal_$i";

                if (!empty($item[$methodKey]) && !empty($item[$nominalKey])) {
                    $method = strtolower($item[$methodKey]);
                    // Hilangkan 'Rp ', titik dan spasi dari nominal sebelum konversi
                    $nominalStr = str_replace(['Rp', '.', ' '], '', $item[$nominalKey]);

                    // Cek apakah setelah dibersihkan adalah angka
                    if ($nominalStr) {
                        $nominal = $nominalStr;

                        switch ($method) {
                            case 'cash':
                                $cash += $nominal;
                                break;
                            case 'debit':
                                $debit += $nominal;
                                break;
                            case 'credit':
                                $credit += $nominal;
                                break;
                            case 'transfer':
                                $transfer += $nominal;
                                break;
                        }
                    }
                }
            }
        }

        // Contoh penggunaan:
        $cashFormatted = $this->formatRupiah($cash);
        $debitFormatted = $this->formatRupiah($debit);
        $creditFormatted = $this->formatRupiah($credit);
        $transferFormatted = $this->formatRupiah($transfer);

        $pendapatan = $cash + $debit + $credit + $transfer;
        $pendapatanFormatted = $this->formatRupiah($pendapatan);

        $pdf = Pdf::loadView('pdf.data_lunas_kasir_detail', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli','total_invoice', 'cashFormatted', 'debitFormatted', 'creditFormatted', 'transferFormatted', 'pendapatanFormatted'))
                ->setPaper('a4', 'landscape');

        $filename = 'kasir_detail_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // End Data Lunas Detail

    // Data Lunas Apotek

    public function datakasir_apotek()
    {
        $title = "Kasir Apotek Lunas";

        $header = kasir::has('apotek_lunas')->with('apotek_lunas')->get();

        $obatList = collect($header)->flatMap(function ($item) {
            return collect($item['apotek_lunas'])->pluck('nama_obat_alkes');
        })->unique()->sort()->values();

        return view('dashboard.datakasir_apotek_lunas', compact('title','header','obatList'));
    }

    public function datakasir_apotek_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $pendapatan = 0;

        foreach ($data as $item) {
            $pendapatan += $item['total_sementara'];
        }

        $pendapatanFormatted = $this->formatRupiah($pendapatan);

        $obatQtySummary = []; // array penampung

        foreach ($data as $item) {
            $nama_obat = $item['nama_obat_tindakan'] ?? '-';
            $qty = (int) $item['qty_pelaksana'] ?? 0;

            if (!isset($obatQtySummary[$nama_obat])) {
                $obatQtySummary[$nama_obat] = 0;
            }

            $obatQtySummary[$nama_obat] += $qty;
        }

        $pdf = Pdf::loadView('pdf.data_lunas_kasir_apotek', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli','total_invoice','pendapatanFormatted','obatQtySummary'))
                ->setPaper('a4', 'landscape');

        $filename = 'kasir_apotek_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // End Data Lunas Apotek

    // Data Lunas Tindakan

    public function datakasir_tindakan()
    {
        $title = "Kasir Tindakan Lunas";

        $header = kasir::has('tindakan_lunas')->with('tindakan_lunas')->get();

        $tindakanList = collect($header)->flatMap(function ($item) {
            return collect($item['tindakan_lunas'])->pluck('nama_tindakan');
        })->unique()->sort()->values();

        return view('dashboard.datakasir_tindakan_lunas', compact('title','header','tindakanList'));
    }

    public function datakasir_tindakan_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $pendapatan = 0;

        foreach ($data as $item) {
            $pendapatan += $item['total_sementara'];
        }

        $pendapatanFormatted = $this->formatRupiah($pendapatan);

        $tindakanQtySummary = []; // array penampung

        $tindakanQtySummary = [];

        foreach ($data as $item) {
            $namaTindakan = $item['nama_obat_tindakan'] ?? '-';

            if (!isset($tindakanQtySummary[$namaTindakan])) {
                $tindakanQtySummary[$namaTindakan] = 0;
            }

            $tindakanQtySummary[$namaTindakan] += 1; // Hitung jumlah kemunculan
        }


        $pdf = Pdf::loadView('pdf.data_lunas_kasir_tindakan', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli','total_invoice','pendapatanFormatted','tindakanQtySummary'))
                ->setPaper('a4', 'landscape');

        $filename = 'kasir_tindakan_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // End Data Lunas Tindakan

    // Data Diskon

    public function datakasir_diskon()
    {
        $title = "Kasir Diskon";

        $header = kasir::has('diskon')->with('diskon')->get();

        return view('dashboard.datakasir_diskon', compact('title','header'));
    }

    public function datakasir_diskon_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $pendapatan = 0;

        foreach ($data as $item) {
            $pendapatan += $item['total_sementara'];
        }

        // Contoh format rupiah tanpa desimal
        function formatRupiah($angka) {
            return 'Rp ' . number_format($angka, 0, ',', '.');
        }

        $pendapatanFormatted = formatRupiah($pendapatan);

        $pdf = Pdf::loadView('pdf.data_lunas_kasir_diskon', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli','total_invoice','pendapatanFormatted'))
                ->setPaper('a4', 'landscape');

        $filename = 'kasir_diskon_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // End Data Diskon

    // Apotek

    public function apotek()
    {
        $title = "Apotek";
        $data_soap = pelayanan_soap_dokter::with('resep', 'pendaftaran', 'pasien')
                ->where('status_apotek', '0')
                ->whereHas('resep', function($query) {
                    $query->whereNotNull('Resep_obat');
                })
                ->get();
        $dokter = dokter::with('namauser')->get();
        $poli = poli::all();
        $penjamin = penjamin::all();
        $embalase = gudang_setting_harga::value('embalase_poin');
        $stok_raw = gudang_barang_stok::selectRaw('MAX(id) as id')
                ->groupBy('kode_obat_alkes')
                ->pluck('id');

        $stok = gudang_barang_stok::whereIn('id', $stok_raw)->get();

        $obat = gudang_barang::all();
        $satuan = gudang_satuan::all();

        return view('dashboard.apotek', compact('title','data_soap','dokter','poli','penjamin','embalase','stok','obat','satuan'));
    }

    public function apotekadd(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_rawat' => 'nullable|string',
                'no_rm' => 'required|string',
                'nama' => 'required|string',
                'alamat' => 'nullable|string',
                'resep' => 'required|string',
                'faktur_apotek' => 'required|string',
                'dokter' => 'nullable|string',
                'poli' => 'nullable|string',
                'penjamin' => 'required|string',
                'nilai_embis_input' => 'nullable|string',
                'sub_total_hidden' => 'required|string',
                'embalase_total_hidden' => 'nullable|string',
                'total_hidden' => 'required|string',
                'note_apotek' => 'nullable|string',
                'tabel_apotek_harga_hidden' => 'required|string',
            ]);

            $apotek = apotek::create([
                'kode_faktur' => $validated['faktur_apotek'],
                'no_rm' => $validated['no_rm'],
                'no_rawat' => $validated['no_rawat'],
                'nama' => $validated['nama'],
                'alamat' => $validated['alamat'] ?? null,
                'tanggal' => now()->format('Y-m-d'),
                'jenis_resep' => $validated['resep'],
                'jenis_rawat' => 'RAWAT JALAN',
                'poli' => $validated['poli'],
                'dokter' => $validated['dokter'],
                'penjamin' => $validated['penjamin'],
                'embalase_poin' => $validated['nilai_embis_input'] ?? 0,
                'sub_total' => $validated['sub_total_hidden'] ?? 0,
                'embis_total' => $validated['embalase_total_hidden'] ?? 0,
                'total' => $validated['total_hidden'] ?? 0,
                'note_apotek' => $validated['note_apotek'] ?? null,
                'status_kasir' => 0,
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);

            // Decode JSON
            $tabel_apotek_harga = json_decode($validated['tabel_apotek_harga_hidden'], true);

            foreach ($tabel_apotek_harga as $detail) {
                apotek_prebayar::create([
                    'kode_faktur' => $validated['faktur_apotek'],
                    'no_rm' => $validated['no_rm'],
                    'nama' => $validated['nama'],
                    'tanggal' => now()->format('Y-m-d'),
                    'nama_obat_alkes' => $detail['nama'],
                    'kode_obat_alkes' => $detail['kode'],
                    'harga' => $detail['harga'],
                    'qty' => $detail['qty'],
                    'total' => $detail['total'],
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);

                $qtyToDeduct = $detail['qty']; // Jumlah yang akan dikurangi dari stok
                $kodeObat = $detail['kode'];
                $today = now()->startOfDay()->toDateString();

                $stokList = gudang_barang_stok::where('kode_obat_alkes', $kodeObat)
                    ->where('qty', '>', 0)
                    ->whereDate('expired', '>=', $today)
                    ->orderBy('expired', 'asc')
                    ->get();

                foreach ($stokList as $stok) {
                    if ($qtyToDeduct <= 0) break;

                    $availableQty = $stok->qty;
                    $deductQty = min($availableQty, $qtyToDeduct);

                    // Kurangi stok
                    $stok->qty -= $deductQty;
                    $stok->save();

                    $qtyToDeduct -= $deductQty;
                }
            }

            $updated = pelayanan_soap_dokter::where('no_rawat', $validated['no_rawat'])
                ->update(['status_apotek' => '1']);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $apotek,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getKodeObat(Request $request)
    {
        $nama = $request->input('nama');
        $penjamin = $request->input('penjamin');

        // Cari berdasarkan nama_obat_alkes
        $data = DB::table('gudang_barang_hargas')
            ->where('nama_obat_alkes', $nama)
            ->first();

        $query = DB::table('gudang_barang_hargas')
            ->where('nama_obat_alkes', $nama);

        if ($penjamin === 'BPJS') {
            // Ambil nilai max dari harga_jual_1
            $harga_jual = $query->max('harga_jual_1');
        } elseif ($penjamin === 'ASURANSI') {
            // Ambil nilai max dari harga_jual_2
            $harga_jual = $query->max('harga_jual_2');
        } elseif ($penjamin === 'UMUM') {
            // Ambil nilai max dari harga_jual_3
            $harga_jual = $query->max('harga_jual_3');
        } else {
            // Default ambil max harga_jual_3
            $harga_jual = $query->max('harga_jual_3');
        }

        // $harga_jual sekarang adalah nilai tertinggi sesuai penjamin

        return response()->json([
            'kode' => $data->kode_obat_alkes ?? null,
            'harga' => $harga_jual ?? null
        ]);
    }

    public function hargaBebas(Request $request)
    {
        $kode = $request->kode;
        $penjamin = strtoupper($request->penjamin); // pastikan huruf besar

        switch ($penjamin) {
            case 'BPJS':
                $harga = gudang_barang_harga::where('kode_obat_alkes', $kode)->max('harga_jual_1');
                break;
            case 'ASURANSI':
                $harga = gudang_barang_harga::where('kode_obat_alkes', $kode)->max('harga_jual_2');
                break;
            default: // UMUM atau lainnya
                $harga = gudang_barang_harga::where('kode_obat_alkes', $kode)->max('harga_jual_3');
                break;
        }

        return response()->json(['harga' => $harga]);
    }

    public function getKodeFaktur(Request $request)
    {

        try {
            // Ambil kode faktur terakhir
            $last = apotek::orderBy('id', 'desc')->first();

            $lastNumber = 1;

            if ($last && preg_match('/(\d+)$/', $last->kode_faktur, $matches)) {
                $lastNumber = (int)$matches[1] + 1;
            }

            // Buat kode faktur baru
            $datePart = date('Ymd');
            $numberPart = str_pad($lastNumber, 5, '0', STR_PAD_LEFT);
            $kodeFaktur = "RSP-$datePart-$numberPart";

            return response()->json([
                'kode' => $kodeFaktur
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal generate kode faktur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        //BELI BEBAS
    public function getBeliBebas()
    {
        $last = apotek::where('no_rm', 'like', 'BBS-%')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 1;

        if ($last && preg_match('/BBS-(\d+)/', $last->no_rm, $matches)) {
            $lastNumber = (int)$matches[1] + 1;
        }

        $noRm = 'BBS-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        return response()->json(['no_rm' => $noRm]);
    }

    public function getKodeFakturBeliBebas()
    {
        $datePart = date('Ymd');

        // Ambil angka terakhir dari semua kode_faktur yang punya format -nnnnn di akhir
        $last = apotek::where('kode_faktur', 'regexp', '-[0-9]{5}$')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 1;
        if ($last && preg_match('/-(\d{5})$/', $last->kode_faktur, $matches)) {
            $lastNumber = (int)$matches[1] + 1;
        }

        $numberPart = str_pad($lastNumber, 5, '0', STR_PAD_LEFT);

        $kodeFaktur = "BBS-$datePart-$numberPart";

        return response()->json(['kode_faktur' => $kodeFaktur]);
    }

        //Print PDF

    public function resep_dokter (Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $note = $request->input('note');

        $pdf = Pdf::loadView('pdf.resepApotek_dokter', compact('data','note'))
                ->setPaper('a6', 'potrait');

        $filename = 'kasir_detail_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function resep_revisi (Request $request)
    {
        $resepList = json_decode($request->input('resep_data'), true);
        $note = $request->input('note');

        $pdf = Pdf::loadView('pdf.resepApotek_revisi', [
                    'resepList' => $resepList,
                    'note' => $note
                ])->setPaper('a6', 'portrait');

        $filename = 'resep_obat_revisi_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // Apotek End

    public function staff()
    {
        $title = "Dokter";

        $user = User::all();
        $poli = poli::all();
        $posker = posker::all();
        $dokter = staff::with('namauser','namastatuspegawai')->get();
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
        $dokternoverif = staff::where('verifikasi', 1)->count();
        $dokterall = staff::count();

        return view('module.staff-manajemen.staff', compact('title','user','poli','posker','dokter','provinsi','kelamin','goldar','agama','pernikahan','suku','bangsa','bahasa','pendidikan','bank','dokternoverif','dokterall'));

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

    public function loketAntrian()
    {
        $title = "Loket Antrian";
        // You can fetch active queue data here
        // For example:
        // $activeQueues = Pendaftaran_rawat_jalan::whereDate('created_at', Carbon::today())
        //     ->whereNotNull('antrian')
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        return view('monitor.loket_antrian', compact('title'));
    }

    // PENDATAAN

    public function pendataan_antrian()
    {
        $title = "Data Antrian";

        $data = pasien_antrian::with('pasien')->get();

        return view('module.pendataan.antrian', compact('title','data'));
    }

    public function print_antrian(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_antrian', compact('data', 'tanggal_awal', 'tanggal_akhir', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_antrian_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function pendataan_pendaftaran()
    {
        $title = "Data Pendaftaran";

        $data = Pendaftaran_rawat_jalan::with('poli','dokter.namauser','pasien','penjamin')->get();

        return view('module.pendataan.pendaftaran', compact('title','data'));
    }

    public function print_pendaftaran(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');
        $dokter = $request->input('dokter');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_pendaftaran', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'dokter', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_pendaftaran_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function pendataan_dokter()
    {
        $title = "Pendataan Pemeriksaan Dokter";

        $data = Pendaftaran_rawat_jalan::with('poli', 'dokter.namauser', 'pasien', 'penjamin', 'soap_dokter')
                ->whereHas('soap_dokter')
                ->get();

        return view('module.pendataan.dokter', compact('title','data'));
    }

    public function print_dokter(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');
        $dokter = $request->input('dokter');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_pelayanan_dokter', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'dokter', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_pelayanan_dokter_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function pendataan_perawat()
    {
        $title = "Pendataan Pemeriksaan Perawat";

        $data = Pendaftaran_rawat_jalan::with('poli', 'dokter.namauser', 'pasien', 'penjamin', 'soap_perawat')
                ->whereHas('soap_perawat')
                ->get();

        return view('module.pendataan.perawat', compact('title','data'));
    }

    public function print_perawat(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');
        $dokter = $request->input('dokter');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_pelayanan_perawat', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'dokter', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_pelayanan_perawat_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // END PENDATAAN

    // INVENTARIS


    public function inventaris()
    {
        $title = "Data Inventaris";
        $inventaris = inventaris_data_barang::all();
        $satuan = inventaris_satuan::all();
        $kategori = inventaris_kategori::all();
        $singkron = external_database::all();
        return view('dashboard.data_inventaris', compact('title','inventaris','satuan','kategori','singkron'));
    }

    public function inventarisadd(Request $request)
    {
        try {
            $request->validate([
                'kode_barang'   => 'required|string',
                'nama_barang'   => 'required|string',
                'kategori_barang'   => 'required|string',
                'satuan_barang' => 'required|string',
                'jenis_barang' => 'required|string',
                'masa_pakai_barang' => 'required|string',
                'masa_pakai_waktu_barang'   => 'required|string',
                'deskripsi_barang'  => 'required|string',
            ], [
                // 👇 Custom attribute names
                'kode_barang'   => 'Kode Barang',
                'nama_barang'   => 'Nama Barang',
                'kategori_barang'   => 'Kategori Barang',
                'satuan_barang' => 'Satuan Barang',
                'jenis_barang' => 'Jenis Barang',
                'masa_pakai_barang' => 'Masa Pakai Barang',
                'masa_pakai_waktu_barang'   => 'Pilihan Masa Pakai Barang',
                'deskripsi_barang'  => 'Deskripsi Barang',
            ]);

            // Simpan data ke database
            $inventaris_data = inventaris_data_barang::create([
                'kode_barang' => $request->input('kode_barang'),
                'nama_barang' => $request->input('nama_barang'),
                'kategori_barang' => $request->input('kategori_barang'),
                'satuan_barang' => $request->input('satuan_barang'),
                'jenis_barang' => $request->input('jenis_barang'),
                'masa_pakai_barang' => $request->input('masa_pakai_barang'),
                'masa_pakai_waktu_barang' => $request->input('masa_pakai_waktu_barang'),
                'deskripsi_barang' => $request->input('deskripsi_barang'),
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data Barang berhasil ditambahkan!',
                'data' => $inventaris_data
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data Barang Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Data Barang!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function inventarisedit(Request $request)
    {
        $request->validate([
            'kode_barang_edit'   => 'required|string',
            'nama_barang_edit'   => 'required|string',
            'kategori_barang_edit'   => 'required|string',
            'satuan_barang_edit' => 'required|string',
            'masa_pakai_barang_edit' => 'required|string',
            'masa_pakai_waktu_barang_edit'   => 'required|string',
            'deskripsi_barang_edit'  => 'required|string',
        ], [
            // 👇 Custom attribute names
            'kode_barang_edit'   => 'Kode Barang',
            'nama_barang_edit'   => 'Nama Barang',
            'kategori_barang_edit'   => 'Kategori Barang',
            'satuan_barang_edit' => 'Satuan Barang',
            'masa_pakai_barang_edit' => 'Masa Pakai Barang',
            'masa_pakai_waktu_barang_edit'   => 'Pilihan Masa Pakai Barang',
            'deskripsi_barang_edit'  => 'Deskripsi Barang',
        ]);

        $inventaris = inventaris_data_barang::find($request->inventarisid_edit);

        if (!$inventaris) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan!'
            ], 404);
        }

        $inventaris->kode_barang = $request->kode_barang_edit;
        $inventaris->nama_barang = $request->nama_barang_edit;
        $inventaris->kategori_barang = $request->kategori_barang_edit;
        $inventaris->satuan_barang = $request->satuan_barang_edit;
        $inventaris->masa_pakai_barang = $request->masa_pakai_barang_edit;
        $inventaris->masa_pakai_waktu_barang = $request->masa_pakai_waktu_barang_edit;
        $inventaris->deskripsi_barang = $request->deskripsi_barang_edit;
        $inventaris->user_input_id = Auth::user()->id;
        $inventaris->user_input_name = Auth::user()->name;
        $inventaris->save();

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil diperbarui!'
        ]);
    }

    public function inventarisdelete(Request $request)
    {

        $request->validate([
            'inventarisid_delete' => 'required'
        ]);

        $inventaris = inventaris_data_barang::find($request->inventarisid_delete);

        if (!$inventaris) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan!'
            ], 404);
        }

        $inventaris->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil dihapus!'
        ]);
    }

    public function inventarisexport()
    {
        return Excel::download(new Inventaris_data_barangExport, 'Inventaris Data Barang.xlsx');
    }

    public function inventarisimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Inventaris_data_barangImport, $request->file('file'));


        return redirect()->route('inventaris.get')->with('success', 'Data berhasil di import!');
    }

    // // Koneksi antar database
    public function inventarissingkron($id)
    {
        $externalDb = external_database::findOrFail($id);

        $config = [
            'driver' => 'mysql',
            'host' => $externalDb->host,
            'database' => $externalDb->database,
            'username' => $externalDb->username,
            'password' => $externalDb->password,
            'port' => $externalDb->port ?? 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        $factory = app(ConnectionFactory::class);
        $connection = $factory->make($config, $externalDb->name);

        // Gunakan koneksi ini untuk query
        $data = $connection->table('inventaris_data_barangs')->get();

        $response = response()->json($data)->getData();

        try {
            // Simpan data ke database
            foreach ($response  as $item) {
                inventaris_data_barang::updateOrCreate(
                    [
                        'kode_barang' => $item->kode_barang,
                        'nama_barang' => $item->nama_barang,
                        'kategori_barang' => $item->kategori_barang,
                        'satuan_barang' => $item->satuan_barang,
                        'masa_pakai_barang' => $item->masa_pakai_barang,
                        'masa_pakai_waktu_barang' => $item->masa_pakai_waktu_barang,
                        'deskripsi_barang' => $item->deskripsi_barang,
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]
                );
            }

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Data barang!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        //Generate Kode Barang Otomatis
        public function generateKodeInventaris()
        {
            // Mengambil data barang terakhir dari tabel
            $last = inventaris_data_barang::orderBy('id', 'desc')->first();

            // Jika tidak ada data barang sebelumnya atau kode barang tidak sesuai format 'KBI-xxxx'
            if (!$last || !preg_match('/^KBI-(\d{4})$/', $last->kode_barang, $match)) {
                $nextNumber = 1;  // Mulai dengan nomor 1 jika tidak ada data atau format kode salah
            } else {
                // Jika ada data sebelumnya, ambil angka terakhir dan tambah 1
                $nextNumber = (int)$match[1] + 1;
            }

            // Membuat kode barang baru dengan format 'KBI-xxxx' (dengan padding 0 di depan)
            $kode = 'KBI-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Mengembalikan response dalam format JSON
            return response()->json([
                'success' => true,
                'kode_barang' => $kode
            ]);
        }

    // END INVENTARIS

    // PEMBELIAN INVENTARIS

    public function inventaris_pembelian()
    {
        $title = "Inventaris Pembelian";
        $inventaris = inventaris_data_barang::all();
        $user = User::all();

        return view('dashboard.inventaris_pembelian', compact('title','inventaris','user'));
    }

    public function inventaris_pembelianadd(Request $request)
    {
        try {
            $request->validate([
                'data_hidden' => 'required|string',
                'kode_pembelian_inventaris' => 'required|string',
                'total_keseluruhan_input' => 'nullable|string',
                'penerima_barang' => 'nullable|string',
            ]);

            // Simpan data ke database (1)
            $pembelian = inventaris_pembelian::create([
                'kode' => $request->input('kode_pembelian_inventaris'),
                'tanggal_pembelian' => now()->format('Y-m-d'),
                'total_harga' => $request->input('total_keseluruhan_input'),
                'petugas_penerima' => $request->input('penerima_barang'),
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);

            // Simpan detail pembelian
            $dataDetail = json_decode($request->data_hidden, true);

            foreach ($dataDetail as $detail) {
                inventaris_pembelian_detail::create([
                    'kode' => $request->input('kode_pembelian_inventaris'),
                    'kode_barang' => $detail['kode_barang'],
                    'nama_barang' => $detail['nama_barang'],
                    'kategori_barang' => $detail['kategori_barang'],
                    'jenis_barang' => $detail['jenis_barang'],
                    'qty_barang' => $detail['qty_pembelian'],
                    'harga_barang' => $detail['harga_satuan'],
                    'lokasi' => $detail['lokasi_barang'],
                    'kondisi' => $detail['kondisi_barang'],
                    'masa_akhir_penggunaan' => $detail['masa_akhir_penggunaan'],
                    'tanggal_pembelian' => $detail['tanggal_pembelian'],
                    'detail_barang' => $detail['detail_barang'],
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);

                 // === Simpan ke stok sesuai jenis barang ===
                if ($detail['jenis_barang'] === 'Inventaris') {
                    for ($i = 0; $i < intval($detail['qty_pembelian']); $i++) {
                        inventaris_stok::create([
                            'kode_pembelian' => $request->input('kode_pembelian_inventaris'),
                            'kode_barang' => $detail['kode_barang'],
                            'nama_barang' => $detail['nama_barang'],
                            'kategori_barang' => $detail['kategori_barang'],
                            'jenis_barang' => $detail['jenis_barang'],
                            'qty_barang' => '1',
                            'harga_barang' => $detail['harga_satuan'],
                            'masa_akhir_penggunaan' => $detail['masa_akhir_penggunaan'],
                            'tanggal_pembelian' => $detail['tanggal_pembelian'],
                            'detail_barang' => $detail['detail_barang'],
                            'lokasi' => $detail['lokasi_barang'],
                            'penanggung_jawab' => null,
                            'kondisi' => $detail['kondisi_barang'],
                            'no_seri' => null,
                            'user_input_id' => Auth::id(),
                            'user_input_name' => Auth::user()->name,
                        ]);
                    }
                } else {
                    inventaris_stok::create([
                        'kode_pembelian' => $request->input('kode_pembelian_inventaris'),
                        'kode_barang' => $detail['kode_barang'],
                        'nama_barang' => $detail['nama_barang'],
                        'kategori_barang' => $detail['kategori_barang'],
                        'jenis_barang' => $detail['jenis_barang'],
                        'qty_barang' => $detail['qty_pembelian'],
                        'harga_barang' => $detail['harga_satuan'],
                        'masa_akhir_penggunaan' => $detail['masa_akhir_penggunaan'],
                        'tanggal_pembelian' => $detail['tanggal_pembelian'],
                        'detail_barang' => $detail['detail_barang'],
                        'lokasi' => $detail['lokasi_barang'],
                        'penanggung_jawab' => null,
                        'kondisi' => $detail['kondisi_barang'],
                        'no_seri' => null,
                        'user_input_id' => Auth::id(),
                        'user_input_name' => Auth::user()->name,
                    ]);
                }
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data pembelian berhasil ditambahkan!',
                'data' => $pembelian
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembelian Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Data pembelian!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generatePembelianInventaris()
    {
        $today = date('Ymd'); // Tanggal hari ini dalam format YYYYMMDD

        // Ambil data terakhir yang dibuat hari ini berdasarkan kode
        $last = inventaris_pembelian::orderBy('id', 'desc')
            ->first();

        if (!$last) {
            $nextNumber = 1;
        } else {
            // Ambil nomor urut terakhir dari kode
            preg_match('/FIP-\d{8}-(\d{5})$/', $last->kode, $match);
            $nextNumber = isset($match[1]) ? ((int)$match[1] + 1) : 1;
        }

        $kode = 'FIP-' . $today . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'kode' => $kode
        ]);
    }
    // END PEMBELIAN INVENTARIS


    public function jadwal_dokter($id)
    {
        $dokter = Dokter::findOrFail($id);
        $kodepoli = $dokter->namapoli->kode;
        $tanggal = date('Y-m-d');


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
                        'title'=> "Jadwal Masuk",
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
