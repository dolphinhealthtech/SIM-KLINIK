<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua menu yang ada terlebih dahulu
        // untuk mencegah duplikasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('menu_roles')->truncate();
        DB::table('menus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Buat menu utama terlebih dahulu
        $dataMaster = Menu::create([
            'name' => 'Data Master',
            'url' => '#',
            'icon' => 'database',
            'parent_id' => null,
            'order' => 1,
        ]);
        
        $pengaturan = Menu::create([
            'name' => 'Pengaturan',
            'url' => '#',
            'icon' => 'cogs',
            'parent_id' => null,
            'order' => 2,
        ]);
        
        // Tambahkan role ke menu utama
        $role = Role::where('name', 'Super-admin')->first();
        $dataMaster->roles()->attach($role->id);
        $pengaturan->roles()->attach($role->id);
        
        // Submenu Data Master
        $subMenusDataMaster = [
            ['name' => 'Golongan Darah', 'url' => '/data-master/goldar', 'icon' => 'tint', 'order' => 1],
            ['name' => 'Suku', 'url' => '/data-master/suku', 'icon' => 'users', 'order' => 2],
            ['name' => 'Bangsa', 'url' => '/data-master/bangsa', 'icon' => 'globe', 'order' => 3],
            ['name' => 'Bahasa', 'url' => '/data-master/bahasa', 'icon' => 'language', 'order' => 4],
            ['name' => 'Agama', 'url' => '/data-master/agama', 'icon' => 'pray', 'order' => 5],
            ['name' => 'Pendidikan', 'url' => '/data-master/pendidikan', 'icon' => 'book', 'order' => 6],
            ['name' => 'Jenis Kelamin', 'url' => '/data-master/kelamin', 'icon' => 'venus-mars', 'order' => 7],
            ['name' => 'Pernikahan', 'url' => '/data-master/pernikahan', 'icon' => 'ring', 'order' => 8],
            ['name' => 'Pekerjaan', 'url' => '/data-master/pekerjaan', 'icon' => 'briefcase', 'order' => 9],  
            ['name' => 'Penjamin', 'url' => '/data-master/penjamin', 'icon' => 'money-bill-alt', 'order' => 10],
            ['name' => 'Loket', 'url' => '/data-master/loket', 'icon' => 'door-open', 'order' => 11],
        ];
        
        foreach ($subMenusDataMaster as $subMenu) {
            $menu = Menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMaster->id,
                'order' => $subMenu['order'],
            ]);
            $menu->roles()->attach($role->id);
        }
        
        // 1. Buat submenu Data Master Medis di bawah Data Master
        $dataMasterMedis = Menu::create([
            'name' => 'Data Master Medis',
            'url' => '#', // Penting: URL '#' menandakan ini adalah dropdown
            'icon' => 'medkit',
            'parent_id' => $dataMaster->id,
            'order' => 12,
        ]);
        $dataMasterMedis->roles()->attach($role->id);
        
        // Submenu untuk Data Master Medis
        $subMenusDataMasterMedis = [
            ['name' => 'Poli', 'url' => '/data-master-medis/poli', 'icon' => 'hospital', 'order' => 1],
            ['name' => 'Spesialis', 'url' => '/data-master-medis/spesialis', 'icon' => 'user-md', 'order' => 2],
            ['name' => 'Kategori Perawatan', 'url' => '/data-master-medis/katper', 'icon' => 'list', 'order' => 3],
            ['name' => 'Perawatan & Tindakan', 'url' => '/data-master-medis/perawatan-tindakan', 'icon' => 'procedures', 'order' => 4],
            ['name' => 'HTT Pemeriksaan', 'url' => '/data-master-medis/htt-pemeriksaan', 'icon' => 'stethoscope', 'order' => 5],
            ['name' => 'Alergi', 'url' => '/data-master-medis/alergi', 'icon' => 'allergies', 'order' => 6],
        ];
        
        foreach ($subMenusDataMasterMedis as $subMenu) {
            $menu = Menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterMedis->id, // Penting: parent_id adalah ID dari Data Master Medis
                'order' => $subMenu['order'],
            ]);
            $menu->roles()->attach($role->id);
        }
        
        // 2. Buat submenu Data Master Gudang di bawah Data Master
        $dataMasterGudang = Menu::create([
            'name' => 'Data Master Gudang',
            'url' => '#',
            'icon' => 'warehouse',
            'parent_id' => $dataMaster->id,
            'order' => 13,
        ]);
        $dataMasterGudang->roles()->attach($role->id);
        
        // Submenu untuk Data Master Gudang
        $subMenusDataMasterGudang = [
            ['name' => 'Satuan', 'url' => '/data-master-gudang/satuan', 'icon' => 'ruler', 'order' => 1],
            ['name' => 'Kategori', 'url' => '/data-master-gudang/kategori', 'icon' => 'tags', 'order' => 2],
            ['name' => 'Supplier Industri', 'url' => '/data-master-gudang/supplier-industri', 'icon' => 'industry', 'order' => 3],
        ];
        
        foreach ($subMenusDataMasterGudang as $subMenu) {
            $menu = Menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterGudang->id,
                'order' => $subMenu['order'],
            ]);
            $menu->roles()->attach($role->id);
        }
        
        // 3. Buat submenu Data Master Manajemen di bawah Data Master
        $dataMasterManajemen = Menu::create([
            'name' => 'Data Master Manajemen',
            'url' => '#',
            'icon' => 'sitemap',
            'parent_id' => $dataMaster->id,
            'order' => 14,
        ]);
        $dataMasterManajemen->roles()->attach($role->id);
        
        // Submenu untuk Data Master Manajemen
        $subMenusDataMasterManajemen = [
            ['name' => 'Posisi Kerja', 'url' => '/data-master-manajemen/posker', 'icon' => 'user-tie', 'order' => 1],
        ];
        
        foreach ($subMenusDataMasterManajemen as $subMenu) {
            $menu = Menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterManajemen->id,
                'order' => $subMenu['order'],
            ]);
            $menu->roles()->attach($role->id);
        }
        
        // Submenu Pengaturan
        $subMenusPengaturan = [
            ['name' => 'Role', 'url' => '/setting/role', 'icon' => 'user-shield', 'order' => 1],
            ['name' => 'Permission', 'url' => '/setting/permission', 'icon' => 'key', 'order' => 2],
            ['name' => 'Users', 'url' => '/setting/user', 'icon' => 'users-cog', 'order' => 3],
            ['name' => 'Web Setting', 'url' => '/setting/web', 'icon' => 'tools', 'order' => 4],
        ];
        
        foreach ($subMenusPengaturan as $subMenu) {
            $menu = Menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $pengaturan->id,
                'order' => $subMenu['order'],
            ]);
            $menu->roles()->attach($role->id);
        }
    }
}



