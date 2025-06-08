<?php

namespace Database\Seeders;

use App\Models\menu;
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

        // Ambil semua role
        $superAdminRole = Role::where('name', 'Super-admin')->first();
        $dokterRole = Role::where('name', 'Dokter')->first();
        $resepsionisRole = Role::where('name', 'Resepsionis')->first();
        $perawatRole = Role::where('name', 'Perawat')->first();
        $kasirRole = Role::where('name', 'Kasir')->first();
        $sdmRole = Role::where('name', 'SDM')->first();
        $apotekerRole = Role::where('name', 'Apoteker')->first();

        // Buat menu utama terlebih dahulu
        $dashboard = Menu::create([
            'name' => 'Dashboard',
            'url' => '/dashboard',
            'icon' => 'tachometer-alt',
            'parent_id' => null,
            'order' => 1,
        ]);

        $antrian = menu::create([
            'name' => 'Dashboard Antrian',
            'url' => '#',
            'icon' => 'users-cog',
            'parent_id' => null,
            'order' => 2,
        ]);

        $pasien = menu::create([
            'name' => 'Pasien',
            'url' => '/pasien',
            'icon' => 'hospital-user',
            'parent_id' => null,
            'order' => 3,
        ]);

        $pendaftaran = menu::create([
            'name' => 'Pendaftaran',
            'url' => '/pendaftaran',
            'icon' => 'clipboard-list',
            'parent_id' => null,
            'order' => 4,
        ]);

        $pemeriksaan = menu::create([
            'name' => 'Pemeriksaan',
            'url' => '#',
            'icon' => 'stethoscope',
            'parent_id' => null,
            'order' => 5,
        ]);

        $apotek = menu::create([
            'name' => 'Apotek',
            'url' => '/apotek',
            'icon' => 'clinic-medical',
            'parent_id' => null,
            'order' => 6,
        ]);

        $kasir = menu::create([
            'name' => 'Kasir',
            'url' => '/kasir',
            'icon' => 'cash-register',
            'parent_id' => null,
            'order' => 7,
        ]);

        $keuangan = menu::create([
            'name' => 'Keuangan',
            'url' => '#',
            'icon' => 'hand-holding-usd',
            'parent_id' => null,
            'order' => 8,
        ]);

        $sdm = menu::create([
            'name' => 'SDM',
            'url' => '#',
            'icon' => 'users',
            'parent_id' => null,
            'order' => 9,
        ]);

        $pembelian = menu::create([
            'name' => 'Pembelian',
            'url' => '/pembelian',
            'icon' => 'shopping-cart',
            'parent_id' => null,
            'order' => 10,
        ]);

        $dataBarang = menu::create([
            'name' => 'Data Barang',
            'url' => '/data-barang',
            'icon' => 'pills',
            'parent_id' => null,
            'order' => 11,
        ]);

        //laporan
        $pendataan = menu::create([
            'name' => 'Laporan',
            'url' => '#',
            'icon' => 'fa-pen atau fa-edit',
            'parent_id' => null,
            'order' => 12,
        ]);


        $dataMaster = menu::create([
            'name' => 'Data Master',
            'url' => '#',
            'icon' => 'database',
            'parent_id' => null,
            'order' => 13,
        ]);

        $pengaturan = menu::create([
            'name' => 'Setting',
            'url' => '#', // Use # for dropdown menu
            'icon' => 'cog',
            'parent_id' => null,
            'order' => 14,
        ]);

        // Tambahkan role ke menu utama untuk Super-admin
        if ($superAdminRole) {
            $dashboard->roles()->attach($superAdminRole->id);
            $antrian->roles()->attach($superAdminRole->id);
            $pasien->roles()->attach($superAdminRole->id);
            $pendaftaran->roles()->attach($superAdminRole->id);
            $pemeriksaan->roles()->attach($superAdminRole->id);
            $apotek->roles()->attach($superAdminRole->id);
            $kasir->roles()->attach($superAdminRole->id);
            $keuangan->roles()->attach($superAdminRole->id);
            $sdm->roles()->attach($superAdminRole->id);
            $pendataan->roles()->attach($superAdminRole->id);
            $pembelian->roles()->attach($superAdminRole->id);
            $dataBarang->roles()->attach($superAdminRole->id);
            $dataMaster->roles()->attach($superAdminRole->id);
            $pengaturan->roles()->attach($superAdminRole->id);
        }

        // Tambahkan role ke menu untuk Dokter
        if ($dokterRole) {
            $dashboard->roles()->attach($dokterRole->id);
            $pemeriksaan->roles()->attach($dokterRole->id);
        }

        // Tambahkan role ke menu untuk Resepsionis
        if ($resepsionisRole) {
            $dashboard->roles()->attach($resepsionisRole->id);
            $antrian->roles()->attach($resepsionisRole->id);
            $pasien->roles()->attach($resepsionisRole->id);
            $pendaftaran->roles()->attach($resepsionisRole->id);
        }

        // Tambahkan role ke menu untuk Perawat
        if ($perawatRole) {
            $dashboard->roles()->attach($perawatRole->id);
            $pemeriksaan->roles()->attach($perawatRole->id);
        }

        // Tambahkan role ke menu untuk Kasir
        if ($kasirRole) {
            $dashboard->roles()->attach($kasirRole->id);
            $kasir->roles()->attach($kasirRole->id);
        }

        // Tambahkan role ke menu untuk SDM
        if ($sdmRole) {
            $dashboard->roles()->attach($sdmRole->id);
            $sdm->roles()->attach($sdmRole->id);
            $dataMaster->roles()->attach($sdmRole->id);
        }

        // Tambahkan role ke menu untuk Apoteker
        if ($apotekerRole) {
            $dashboard->roles()->attach($apotekerRole->id);
            $apotek->roles()->attach($apotekerRole->id);
        }

        // Submenu Pemeriksaan
        $subMenusPemeriksaan = [
            ['name' => 'Dokter', 'url' => '/pemeriksaan/dokter', 'icon' => 'user-md', 'order' => 1],
            ['name' => 'Perawat', 'url' => '/pemeriksaan/perawat', 'icon' => 'user-nurse', 'order' => 2],
        ];

        foreach ($subMenusPemeriksaan as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $pemeriksaan->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

            // Tambahkan submenu Dokter untuk role Dokter
            if ($subMenu['name'] === 'Dokter' && $dokterRole) {
                $menu->roles()->attach($dokterRole->id);
            }

            // Tambahkan submenu Perawat untuk role Perawat
            if ($subMenu['name'] === 'Perawat' && $perawatRole) {
                $menu->roles()->attach($perawatRole->id);
            }
        }

        //submenu dashboard antrian
        $subMenusAntrian = [
            ['name' => 'Antrian', 'url' => '/monitor', 'icon' => 'users', 'order' => 1], // 👥 Representasi orang dalam antrian
            ['name' => 'Loket Antrian', 'url' => '/monitor/loket-antrian', 'icon' => 'desktop', 'order' => 2], // 🖥️ Representasi loket/operator
        ];


        foreach ($subMenusAntrian as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $antrian->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

        }

        // Submenu Keuangan
        $subMenuKeuangan = [
            ['name' => 'Data Kasir', 'url' => '/datakasir', 'icon' => 'dollar-sign', 'order' => 1],
            ['name' => 'Data Kasir Detail', 'url' => '/datakasir/detail', 'icon' => 'file-text', 'order' => 2],
            ['name' => 'Data Kasir Diskon', 'url' => '/datakasir/diskon', 'icon' => 'percent', 'order' => 3],
        ];

        foreach ($subMenuKeuangan as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $keuangan->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

        }

        // Submenu SDM
        $subMenusSDM = [
            ['name' => 'Dokter', 'url' => '/dokter', 'icon' => 'user-md', 'order' => 1],
            ['name' => 'Staff', 'url' => '/staff', 'icon' => 'user-tie', 'order' => 2],
        ];

        foreach ($subMenusSDM as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $sdm->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

            if ($sdmRole) {
                $menu->roles()->attach($sdmRole->id);
            }
        }

        //sub menu Laporan
        $subMenuPendataan = [
            ['name' => 'Antrian', 'url' => '/pendataan/antrian', 'icon' => 'fas fa-people-arrows', 'order' => 1],
            ['name' => 'Pendaftaran', 'url' => '/pendataan/pendaftaran', 'icon' => 'fas fa-user-plus', 'order' => 2],
            ['name' => 'Dokter', 'url' => '/pendataan/soap-dokter', 'icon' => 'fas fa-user-md', 'order' => 3],
            ['name' => 'Perawat', 'url' => '/pendataan/so-perawat', 'icon' => 'fas fa-user-nurse', 'order' => 4], 
            ['name' => 'Apotek', 'url' => '/datakasir/apotek', 'icon' => 'capsules', 'order' => 5],
            ['name' => 'Tindakan', 'url' => '/datakasir/tindakan', 'icon' => 'briefcase-medical', 'order' => 6], // jika menggunakan Font Awesome
        ];


        foreach ($subMenuPendataan as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $pendataan->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

        }

        // Submenu Data Master - Loket
        $loket = menu::create([
            'name' => 'Loket',
            'url' => '/data-master/loket',
            'icon' => 'door-open',
            'parent_id' => $dataMaster->id,
            'order' => 1,
        ]);

        if ($superAdminRole) {
            $loket->roles()->attach($superAdminRole->id);
        }

        if ($sdmRole) {
            $loket->roles()->attach($sdmRole->id);
        }

        // 1. Buat submenu Data Master Umum di bawah Data Master
        $dataMasterUmum = menu::create([
            'name' => 'Data Master Umum',
            'url' => '#',
            'icon' => 'folder',
            'parent_id' => $dataMaster->id,
            'order' => 2,
        ]);

        if ($superAdminRole) {
            $dataMasterUmum->roles()->attach($superAdminRole->id);
        }

        if ($sdmRole) {
            $dataMasterUmum->roles()->attach($sdmRole->id);
        }

        // Submenu Data Master Umum
        $subMenusDataMasterUmum = [
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
            ['name' => 'Bank', 'url' => '/data-master/bank', 'icon' => 'university', 'order' => 11],
        ];

        foreach ($subMenusDataMasterUmum as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterUmum->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

            if ($sdmRole) {
                $menu->roles()->attach($sdmRole->id);
            }
        }

        // 2. Buat submenu Data Master Medis di bawah Data Master
        $dataMasterMedis = menu::create([
            'name' => 'Data Master Medis',
            'url' => '#',
            'icon' => 'medkit',
            'parent_id' => $dataMaster->id,
            'order' => 3,
        ]);

        if ($superAdminRole) {
            $dataMasterMedis->roles()->attach($superAdminRole->id);
        }

        if ($sdmRole) {
            $dataMasterMedis->roles()->attach($sdmRole->id);
        }

        // Submenu untuk Data Master Medis
        $subMenusDataMasterMedis = [
            ['name' => 'Poli', 'url' => '/data-master-medis/poli', 'icon' => 'hospital', 'order' => 1],
            ['name' => 'Sarana', 'url' => '/data-master-medis/sarana', 'icon' => 'building', 'order' => 2],
            ['name' => 'Spesialis', 'url' => '/data-master-medis/spesialis', 'icon' => 'user-md', 'order' => 3],
            ['name' => 'Kategori Perawatan', 'url' => '/data-master-medis/katper', 'icon' => 'list', 'order' => 4],
            ['name' => 'Perawatan & Tindakan', 'url' => '/data-master-medis/perawatan-tindakan', 'icon' => 'procedures', 'order' => 5],
            ['name' => 'HTT Pemeriksaan', 'url' => '/data-master-medis/htt-pemeriksaan', 'icon' => 'stethoscope', 'order' => 6],
            ['name' => 'Alergi', 'url' => '/data-master-medis/alergi', 'icon' => 'allergies', 'order' => 7],
            ['name' => 'Jenis Diet', 'url' => '/data-master-medis/jenis-diet', 'icon' => 'utensils', 'order' => 8],
            ['name' => 'Nama Makanan', 'url' => '/data-master-medis/nama-makanan', 'icon' => 'hamburger', 'order' => 9],
            ['name' => 'ICD 10', 'url' => '/data-master-medis/icd10', 'icon' => 'file', 'order' => 10],
            ['name' => 'ICD 9', 'url' => '/data-master-medis/icd9', 'icon' => 'file', 'order' => 11],
            ['name' => 'Radiologi', 'url' => '/data-master-medis/radiologi_jenis', 'icon' => 'x-ray', 'order' => 12], 
            ['name' => 'Radiologi Pemeriksaan', 'url' => '/data-master-medis/radiologi_pemeriksaan', 'icon' => 'microscope', 'order' => 13],
            ['name' => 'Laboratorium', 'url' => '/data-master-medis/bidang-lab', 'icon' => 'vials', 'order' => 14],
            

        ];

        foreach ($subMenusDataMasterMedis as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterMedis->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

            if ($sdmRole) {
                $menu->roles()->attach($sdmRole->id);
            }
        }

        // 3. Buat submenu Data Master Gudang di bawah Data Master
        $dataMasterGudang = menu::create([
            'name' => 'Data Master Gudang',
            'url' => '#',
            'icon' => 'warehouse',
            'parent_id' => $dataMaster->id,
            'order' => 4,
        ]);

        if ($superAdminRole) {
            $dataMasterGudang->roles()->attach($superAdminRole->id);
        }

        if ($sdmRole) {
            $dataMasterGudang->roles()->attach($sdmRole->id);
        }

        // Submenu untuk Data Master Gudang
        $subMenusDataMasterGudang = [
            ['name' => 'Satuan', 'url' => '/data-master-gudang/satuan', 'icon' => 'ruler', 'order' => 1],
            ['name' => 'Kategori', 'url' => '/data-master-gudang/kategori', 'icon' => 'tags', 'order' => 2],
            ['name' => 'Supplier Industri', 'url' => '/data-master-gudang/supplier-industri', 'icon' => 'industry', 'order' => 3],
            ['name' => 'Setting Harga Jual', 'url' => '/data-master-gudang/setting-harga-jual', 'icon' => 'money-bill-wave', 'order' => 4],
            ['name' => 'Harga Jual', 'url' => '/data-master-gudang/harga-barang-jual', 'icon' => 'tag', 'order' => 5],
            ['name' => 'Stok', 'url' => '/data-master-gudang/stok-obat-alkes', 'icon' => 'boxes', 'order' => 6],
        ];

        foreach ($subMenusDataMasterGudang as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterGudang->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

            if ($sdmRole) {
                $menu->roles()->attach($sdmRole->id);
            }
        }

        //baru
        $dataMasterGudang = menu::create([
            'name' => 'Data Master Manajemen',
            'url' => '#',
            'icon' => 'briefcase',
            'parent_id' => $dataMaster->id,
            'order' => 5,
        ]);

        if ($superAdminRole) {
            $dataMasterGudang->roles()->attach($superAdminRole->id);
        }

        if ($sdmRole) {
            $dataMasterGudang->roles()->attach($sdmRole->id);
        }

        // Submenu untuk Data Master Manajemen
        $subMenusDataMasterGudang = [
            ['name' => 'Posker', 'url' => '/data-master-manajemen/posker', 'icon' => 'chart-line', 'order' => 1],
        ];

        foreach ($subMenusDataMasterGudang as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterGudang->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

            if ($sdmRole) {
                $menu->roles()->attach($sdmRole->id);
            }
        }

        // Submenu Setting
        $subMenusSetting = [
            ['name' => 'Role', 'url' => '/setting/role', 'icon' => 'user-tag', 'order' => 1],
            ['name' => 'Permission', 'url' => '/setting/permission', 'icon' => 'key', 'order' => 2],
            ['name' => 'User', 'url' => '/setting/user', 'icon' => 'users', 'order' => 3],
            ['name' => 'Web Setting', 'url' => '/setting/web', 'icon' => 'globe', 'order' => 4],
        ];

        foreach ($subMenusSetting as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $pengaturan->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }
        }
    }
}






