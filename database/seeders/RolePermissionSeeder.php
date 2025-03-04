<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ['add', 'edit', 'delete'];
        $roles = ['Super-admin', 'Administrasi', 'Apoteker', 'Perawat', 'Dokter', 'Manajemen', 'User'];

        // Membuat Permission
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Membuat Role
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
