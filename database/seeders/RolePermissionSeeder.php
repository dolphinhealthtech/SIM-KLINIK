<?php

namespace Database\Seeders;

use App\Models\role_redirect;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['tambah', 'rubah', 'hapus', 'lihat'];
        $roles = [
            'Super Admin',         // akses penuh semua modul & setting
            'Administrasi',        // Data Master
            'Registrasi',          // pendaftaran pasien & input data awal
            'Perawat',             // pelayanan keperawatan & input SOAP
            'Dokter',              // pemeriksaan & diagnosis pasien
            'Apoteker',            // pengelolaan obat & resep
            'Kasir',               // pembayaran & tagihan pasien
            'Gudang',         // stok & logistik alat kesehatan
            'Gudang utama',        // stok & logistik alat kesehatan
            'Manajemen',           // laporan, analisis, pengambilan keputusan
            'Personalia',          // SDM, jadwal pegawai
            'Pasien'               // akses pasien / login umum
        ];

        // Membuat Permissions (hindari duplikat)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Membuat Roles + mapping ke role_redirect
        foreach ($roles as $role) {
            $newRole = Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web'
            ]);

            role_redirect::firstOrCreate([
                'role_id' => $newRole->id
            ], [
                'redirect_route' => 'dashboard'
            ]);
        }
    }
}
