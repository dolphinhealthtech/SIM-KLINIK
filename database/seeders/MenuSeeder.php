<?php

namespace Database\Seeders;

use App\Models\menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            // Data Master
            ['name' => 'Data Master', 'url' => '#', 'icon' => 'database', 'role' => 'Super-admin', 'parent_id' => null, 'order' => 1],
            ['name' => 'Golongan Darah', 'url' => '/data-master/goldar', 'icon' => 'tint', 'role' => 'Super-admin ', 'parent_id' => 1, 'order' => 1],
            ['name' => 'Suku', 'url' => '/data-master/suku', 'icon' => 'users', 'role' => 'Super-admin ', 'parent_id' => 1, 'order' => 2],
            ['name' => 'Bangsa', 'url' => '/data-master/bangsa', 'icon' => 'globe', 'role' => 'Super-admin ', 'parent_id' => 1, 'order' => 3],
            ['name' => 'Bahasa', 'url' => '/data-master/bahasa', 'icon' => 'language', 'role' => 'Super-admin ', 'parent_id' => 1, 'order' => 4],
            ['name' => 'Agama', 'url' => '/data-master/agama', 'icon' => 'pray', 'role' => 'Super-admin ', 'parent_id' => 1, 'order' => 5],
            ['name' => 'Pendidikan', 'url' => '/data-master/pendidikan', 'icon' => 'book', 'role' => 'Super-admin ', 'parent_id' => 1, 'order' => 6],
            ['name' => 'Jenis Kelamin', 'url' => '/data-master/kelamin', 'icon' => 'venus-mars', 'role' => 'Super-admin ', 'parent_id' => 1, 'order' => 7],

            // Setting
            ['name' => 'Pengaturan', 'url' => '#', 'icon' => 'cogs', 'role' => 'Super-admin ', 'parent_id' => null, 'order' => 2],
            ['name' => 'Role', 'url' => '/setting/role', 'icon' => 'user-shield', 'role' => 'Super-admin ', 'parent_id' => 9, 'order' => 1],
            ['name' => 'Permission', 'url' => '/setting/permission', 'icon' => 'key', 'role' => 'Super-admin ', 'parent_id' => 9, 'order' => 2],
            ['name' => 'Users', 'url' => '/setting/user', 'icon' => 'users-cog', 'role' => 'Super-admin ', 'parent_id' => 9, 'order' => 3],
            ['name' => 'Web Setting', 'url' => '/setting/web', 'icon' => 'tools', 'role' => 'Super-admin ', 'parent_id' => 9, 'order' => 4],
        ];

        // Masukkan data ke dalam database
        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
