<?php

namespace Database\Seeders;

use App\Models\menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            // Data Master
            ['name' => 'Data Master', 'url' => '#', 'icon' => 'database', 'roles' => ['Super-admin'], 'parent_id' => null, 'order' => 1],
            ['name' => 'Golongan Darah', 'url' => '/data-master/goldar', 'icon' => 'tint', 'roles' => ['Super-admin'], 'parent_id' => 1, 'order' => 1],
            ['name' => 'Suku', 'url' => '/data-master/suku', 'icon' => 'users', 'roles' => ['Super-admin'], 'parent_id' => 1, 'order' => 2],
            ['name' => 'Bangsa', 'url' => '/data-master/bangsa', 'icon' => 'globe', 'roles' => ['Super-admin'], 'parent_id' => 1, 'order' => 3],
            ['name' => 'Bahasa', 'url' => '/data-master/bahasa', 'icon' => 'language', 'roles' => ['Super-admin'], 'parent_id' => 1, 'order' => 4],
            ['name' => 'Agama', 'url' => '/data-master/agama', 'icon' => 'pray', 'roles' => ['Super-admin'], 'parent_id' => 1, 'order' => 5],
            ['name' => 'Pendidikan', 'url' => '/data-master/pendidikan', 'icon' => 'book', 'roles' => ['Super-admin'], 'parent_id' => 1, 'order' => 6],
            ['name' => 'Jenis Kelamin', 'url' => '/data-master/kelamin', 'icon' => 'venus-mars', 'roles' => ['Super-admin'], 'parent_id' => 1, 'order' => 7],

            // Setting
            ['name' => 'Pengaturan', 'url' => '#', 'icon' => 'cogs', 'roles' => ['Super-admin'], 'parent_id' => null, 'order' => 2],
            ['name' => 'Role', 'url' => '/setting/role', 'icon' => 'user-shield', 'roles' => ['Super-admin'], 'parent_id' => 9, 'order' => 1],
            ['name' => 'Permission', 'url' => '/setting/permission', 'icon' => 'key', 'roles' => ['Super-admin'], 'parent_id' => 9, 'order' => 2],
            ['name' => 'Users', 'url' => '/setting/user', 'icon' => 'users-cog', 'roles' => ['Super-admin'], 'parent_id' => 9, 'order' => 3],
            ['name' => 'Web Setting', 'url' => '/setting/web', 'icon' => 'tools', 'roles' => ['Super-admin'], 'parent_id' => 9, 'order' => 4],
            // Data Master Medis
            ['name' => 'Data Master Medis', 'url' => '#', 'icon' => 'database', 'roles' => ['Super-admin'], 'parent_id' => null, 'order' => 3],
            ['name' => 'Poli', 'url' => '/data-master-medis/poli', 'icon' => 'tint', 'roles' => ['Super-admin'], 'parent_id' => 15, 'order' => 1],
        ];

        // Masukkan data ke dalam database
        foreach ($menus as $menuData) {
            $menu = Menu::create([
                'name' => $menuData['name'],
                'url' => $menuData['url'],
                'icon' => $menuData['icon'],
                'parent_id' => $menuData['parent_id'],
                'order' => $menuData['order'],
            ]);

            // Hubungkan menu dengan role yang sesuai
            foreach ($menuData['roles'] as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $menu->roles()->attach($role->id);
                }
            }
        }
    }
}
