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
        $permissions = ['add', 'edit', 'delete'];
        $roles = ['Super-admin', 'Administrasi', 'Apoteker', 'Perawat', 'Dokter', 'Manajemen', 'User', 'Gudang', 'Personalia', 'Registrasi'];

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
