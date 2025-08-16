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
       // Matikan foreign key sementara untuk mencegah error truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('menu_roles')->truncate();
        DB::table('menus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Ambil semua role
        $roleSuperAdmin  = Role::where('name', 'Super Admin')->first(); // akses penuh semua modul & setting
        $roleAdministrasi = Role::where('name', 'Administrasi')->first(); // Data Master
        $roleRegistrasi   = Role::where('name', 'Registrasi')->first(); // pendaftaran pasien & input data awal
        $rolePerawat      = Role::where('name', 'Perawat')->first(); // pelayanan keperawatan & input SOAP
        $roleDokter       = Role::where('name', 'Dokter')->first(); // pemeriksaan & diagnosis pasien
        $roleApoteker     = Role::where('name', 'Apoteker')->first(); // pengelolaan obat & resep
        $roleKasir        = Role::where('name', 'Kasir')->first(); // pembayaran & tagihan pasien
        $roleGudang       = Role::where('name', 'Gudang')->first(); // stok & logistik alat kesehatan
        $roleGudangUtama  = Role::where('name', 'Gudang utama')->first(); // stok & logistik alat kesehatan Utama
        $roleManajemen    = Role::where('name', 'Manajemen')->first(); // laporan, analisis, pengambilan keputusan
        $rolePersonalia   = Role::where('name', 'Personalia')->first(); // SDM, jadwal pegawai
        $rolePasien       = Role::where('name', 'Pasien')->first(); // akses pasien / login umum

        // Buat menu utama
        $menuDashboard = Menu::create([
            'name' => 'Dashboard',
            'url' => '/dashboard',
            'icon' => 'tachometer-alt',
            'parent_id' => null,
            'order' => 1,
        ]);

        $menuPasien = Menu::create([
            'name' => 'Pasien',
            'url' => '/data-pasien',
            'icon' => 'hospital-user',
            'parent_id' => null,
            'order' => 3,
        ]);

        $menuPendaftaran = Menu::create([
            'name' => 'Pendaftaran',
            'url' => '/pendaftaran-offline',
            'icon' => 'clipboard-list',
            'parent_id' => null,
            'order' => 4,
        ]);

        $menuPemeriksaan = Menu::create([
            'name' => 'Pemeriksaan',
            'url' => '#',
            'icon' => 'stethoscope',
            'parent_id' => null,
            'order' => 5,
        ]);

        $menuApotek = Menu::create([
            'name' => 'Apotek',
            'url' => '/apotek',
            'icon' => 'clinic-medical',
            'parent_id' => null,
            'order' => 6,
        ]);

        $menuKasir = Menu::create([
            'name' => 'Kasir',
            'url' => '/kasir',
            'icon' => 'cash-register',
            'parent_id' => null,
            'order' => 7,
        ]);

        $menuKeuangan = Menu::create([
            'name' => 'Keuangan',
            'url' => '#',
            'icon' => 'hand-holding-usd',
            'parent_id' => null,
            'order' => 8,
        ]);

        $menuSDM = Menu::create([
            'name' => 'SDM',
            'url' => '#',
            'icon' => 'users',
            'parent_id' => null,
            'order' => 9,
        ]);

        $menuObatAlkes = Menu::create([
            'name' => 'Obat dan Alkes',
            'url' => '#',
            'icon' => 'fas fa-boxes-stacked',
            'parent_id' => null,
            'order' => 10,
        ]);

        $menuInventaris = Menu::create([
            'name' => 'Inventaris',
            'url' => '#',
            'icon' => 'fas fa-boxes-stacked',
            'parent_id' => null,
            'order' => 11,
        ]);

        $menuLaporan = Menu::create([
            'name' => 'Laporan',
            'url' => '#',
            'icon' => 'fa-pen', // gunakan salah satu icon yang valid
            'parent_id' => null,
            'order' => 12,
        ]);

        $menuDataMaster = Menu::create([
            'name' => 'Data Master',
            'url' => '#',
            'icon' => 'database',
            'parent_id' => null,
            'order' => 13,
        ]);

        $menuPengaturan = Menu::create([
            'name' => 'Setting',
            'url' => '#', // dropdown
            'icon' => 'cog',
            'parent_id' => null,
            'order' => 14,
        ]);


        // ===== ROLE ASSIGNMENTS FOR MAIN MENUS =====

        // Super Admin: akses semua menu
        if ($roleSuperAdmin) {
            foreach ([
                $menuDashboard,
                $menuPasien,
                $menuPendaftaran,
                $menuPemeriksaan,
                $menuApotek,
                $menuKasir,
                $menuKeuangan,
                $menuSDM,
                $menuLaporan,
                $menuObatAlkes,
                $menuInventaris,
                $menuDataMaster,
                $menuPengaturan
            ] as $menu) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }
        }

        // Registrasi: Dashboard, Pasien, Pendaftaran, Laporan
        if ($roleRegistrasi) {
            foreach ([
                $menuDashboard,
                $menuPasien,
                $menuPendaftaran,
                $menuLaporan
            ] as $menu) {
                $menu->roles()->attach($roleRegistrasi->id);
            }
        }

        // Perawat: Dashboard, Pemeriksaan, Laporan
        if ($rolePerawat) {
            foreach ([
                $menuDashboard,
                $menuPemeriksaan,
                $menuLaporan
            ] as $menu) {
                $menu->roles()->attach($rolePerawat->id);
            }
        }

        // Dokter: Dashboard, Pemeriksaan, Laporan
        if ($roleDokter) {
            foreach ([
                $menuDashboard,
                $menuPemeriksaan,
                $menuLaporan
            ] as $menu) {
                $menu->roles()->attach($roleDokter->id);
            }
        }

        // Apoteker: Dashboard, Apotek, Laporan
        if ($roleApoteker) {
            foreach ([
                $menuDashboard,
                $menuApotek,
                $menuLaporan
            ] as $menu) {
                $menu->roles()->attach($roleApoteker->id);
            }
        }

        // Kasir: Dashboard, Kasir, Keuangan
        if ($roleKasir) {
            foreach ([
                $menuDashboard,
                $menuKasir,
                $menuKeuangan
            ] as $menu) {
                $menu->roles()->attach($roleKasir->id);
            }
        }

        // Personalia: Dashboard, SDM
        if ($rolePersonalia) {
            foreach ([
                $menuDashboard,
                $menuSDM
            ] as $menu) {
                $menu->roles()->attach($rolePersonalia->id);
            }
        }

        // Gudang: Dashboard, Obat & Alkes, Inventaris
        if ($roleGudang) {
            foreach ([
                $menuDashboard,
                $menuObatAlkes,
                $menuInventaris
            ] as $menu) {
                $menu->roles()->attach($roleGudang->id);
            }
        }

        // Gudang Utama: Dashboard, Obat & Alkes, Inventaris
        if ($roleGudangUtama) {
            foreach ([
                $menuDashboard,
                $menuObatAlkes,
                $menuInventaris
            ] as $menu) {
                $menu->roles()->attach($roleGudangUtama->id);
            }
        }

        // Administrasi: Dashboard, Data Master
        if ($roleAdministrasi) {
            foreach ([
                $menuDashboard,
                $menuDataMaster,
            ] as $menu) {
                $menu->roles()->attach($roleAdministrasi->id);
            }
        }

        // Manajemen: Dashboard, Data Master, Setting
        if ($roleManajemen) {
            foreach ([
                $menuDashboard,
                $menuPengaturan
            ] as $menu) {
                $menu->roles()->attach($roleManajemen->id);
            }
        }


        // ===== SUBMENUS =====

        // Submenu Pemeriksaan
        $subMenusPemeriksaan = [
            ['name' => 'Dokter', 'url' => '/pasien-pelayanan/dokter', 'icon' => 'user-md', 'order' => 1],
            ['name' => 'Perawat', 'url' => '/pasien-pelayanan/perawat', 'icon' => 'user-nurse', 'order' => 2],
        ];

        foreach ($subMenusPemeriksaan as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $menuPemeriksaan->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            // Assign submenu Dokter to Dokter role
            if ($subMenu['name'] === 'Dokter' && $roleDokter) {
                $menu->roles()->attach($roleDokter->id);
            }

            // Assign submenu Perawat to Perawat role
            if ($subMenu['name'] === 'Perawat' && $rolePerawat) {
                $menu->roles()->attach($rolePerawat->id);
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
                'parent_id' => $menuKeuangan->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            // Assign to Kasir role
            if ($roleKasir) {
                $menu->roles()->attach($roleKasir->id);
            }
        }

        // Submenu SDM
        $subMenusSDM = [
            ['name' => 'Dokter', 'url' => '/sdm/dokter', 'icon' => 'user-md', 'order' => 1],
            ['name' => 'Staff', 'url' => '/sdm/staff', 'icon' => 'user-tie', 'order' => 2],
        ];

        foreach ($subMenusSDM as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $menuSDM->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            // Assign to Personalia role
            if ($rolePersonalia) {
                $menu->roles()->attach($rolePersonalia->id);
            }
        }

        $subMenuObatAllkes = menu::create([
            'name' => 'Gudang',
            'url' => '#',
            'icon' => 'folder',
            'parent_id' => $menuObatAlkes->id,
            'order' => 1,
        ]);

        if ($roleSuperAdmin) {
            $subMenuObatAllkes->roles()->attach($roleSuperAdmin->id);
        }

        if ($roleGudang) {
            $subMenuObatAllkes->roles()->attach($roleGudang->id);
        }


        $subsubMenuObatAllkes = [
            ['name' => 'Daftar Barang','url' => '/data-barang','icon' => 'pills','order' => 1,],
            ['name' => 'Stok Obat', 'url' => '/data-master/gudang/stok-obat-alkes', 'icon' => 'boxes', 'order' => 2],
            ['name' => 'Permintaan Obat','url' => '/data-master/gudang/gudang-request','icon' => 'fas fa-prescription-bottle','order' => 3,],
            ['name' => 'Mutasi Penyesuaian', 'url' => '/data-master/gudang/stok-penyesuaian-opname', 'icon' => 'fas fa-exchange-alt', 'order' => 4],
            ['name' => 'Kartu Stok', 'url' => '/data-master/gudang/kartu-stok', 'icon' => 'fas fa-clipboard-list', 'order' => 5],
        ];

        foreach ($subsubMenuObatAllkes as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $subMenuObatAllkes->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
            $menu->roles()->attach($roleSuperAdmin->id);
            }

            if ($roleGudang) {
                $menu->roles()->attach($roleGudang->id);
            }
        }

        $subMenuObatAllkesutama = menu::create([
            'name' => 'Gudang Utama',
            'url' => '#',
            'icon' => 'folder',
            'parent_id' => $menuObatAlkes->id,
            'order' => 2,
        ]);

        if ($roleSuperAdmin) {
            $subMenuObatAllkesutama->roles()->attach($roleSuperAdmin->id);
        }

        if ($roleGudangUtama) {
            $subMenuObatAllkesutama->roles()->attach($roleGudangUtama->id);
        }

        $subsubMenuObatAllkesUtama = [

            ['name' => 'Daftar Barang Utama','url' => '/data-barang-utama','icon' => 'fas fa-list','order' => 1,],
            ['name' => 'Stok Obat Utama', 'url' => '/data-master/gudang/stok-obat-alkes-utama', 'icon' => 'boxes', 'order' => 2],
            ['name' => 'Gudang utama (obat)','url' => '/data-master/gudang/gudang-utama','icon' => 'fas fa-boxes','order' => 3,],
            ['name' => 'Pembelian','url' => '/pembelian','icon' => 'shopping-cart','order' => 4,],
            ['name' => 'Mutasi Penyesuaian Utama','url' => '/data-master/gudang/stok-penyesuaian-opname-utama','icon' => 'fas fa-exchange-alt','order' => 5],
            ['name' => 'Kartu Stok Utama','url' => '/data-master/gudang/kartu-stok-utama','icon' => 'fas fa-id-card','order' => 6],

        ];

        foreach ($subsubMenuObatAllkesUtama as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $subMenuObatAllkesutama->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            if ($roleGudangUtama) {
                $menu->roles()->attach($roleGudangUtama->id);
            }
        }


        $subMenusinventaris = menu::create([
            'name' => 'Inventaris',
            'url' => '#',
            'icon' => 'folder',
            'parent_id' => $menuInventaris->id,
            'order' => 1,
        ]);

        if ($roleSuperAdmin) {
            $subMenusinventaris->roles()->attach($roleSuperAdmin->id);
        }

        if ($roleGudang) {
            $subMenusinventaris->roles()->attach($roleGudang->id);
        }


         // Submenu inventaris
        $subsubMenusinventaris = [
            ['name' => 'Daftar Inventaris', 'url' => '/data-inventaris', 'icon' => 'fas fa-database', 'order' => 1],
            ['name' => 'Pembelian Inventaris', 'url' => '/inventaris-pembelian', 'icon' => 'fas fa-cart-plus', 'order' => 2],
            ['name' => 'Permintaan Inventaris', 'url' => '/data-master/gudang/inventaris-request', 'icon' => 'fas fa-paper-plane', 'order' => 3],
            ['name' => 'Stok Inventaris', 'url' => '/data-master/gudang/stok-inventaris', 'icon' => 'fas fa-clipboard-list', 'order' => 4],
        ];

        foreach ($subsubMenusinventaris as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $subMenusinventaris->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            // Assign to Gudang role
            if ($roleGudang) {
                $menu->roles()->attach($roleGudang->id);
            }
        }


        $subMenusinventarisutama = menu::create([
            'name' => 'Inventaris Utama',
            'url' => '#',
            'icon' => 'folder',
            'parent_id' => $menuInventaris->id,
            'order' => 2,
        ]);

        if ($roleSuperAdmin) {
            $subMenusinventarisutama->roles()->attach($roleSuperAdmin->id);
        }

        if ($roleGudangUtama) {
            $subMenusinventarisutama->roles()->attach($roleGudangUtama->id);
        }


         // Submenu inventaris
        $subsubMenusinventarisutama = [
            ['name' => 'Daftar Inventaris Utama','url' => '/data-inventaris-utama','icon' => 'fas fa-clipboard-list','order' => 2],
            ['name' => 'Pembelian Inventaris Utama','url' => '/inventaris-pembelian-utama','icon' => 'fas fa-cart-plus','order' => 4],
            ['name' => 'Gudang Utama (Inventaris)', 'url' => '/data-master/gudang/inventaris-utama', 'icon' => 'fas fa-box', 'order' => 6],
            ['name' => 'Stok Inventaris Utama','url' => '/data-master/gudang/stok-inventaris-utama','icon' => 'fas fa-warehouse','order' => 8],
        ];

        foreach ($subsubMenusinventarisutama as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $subMenusinventarisutama->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            // Assign to Gudang role
            if ($roleGudangUtama) {
                $menu->roles()->attach($roleGudangUtama->id);
            }
        }

        //sub menu Laporan
        $subMenuPendataan = [
            ['name' => 'Antrian', 'url' => '/pendataan/antrian', 'icon' => 'fas fa-people-arrows', 'order' => 1],
            ['name' => 'Pendaftaran', 'url' => '/pendataan/pendaftaran', 'icon' => 'fas fa-user-plus', 'order' => 2],
            ['name' => 'Dokter', 'url' => '/pendataan/soap-dokter', 'icon' => 'fas fa-user-md', 'order' => 3],
            ['name' => 'Perawat', 'url' => '/pendataan/so-perawat', 'icon' => 'fas fa-user-nurse', 'order' => 4],
            ['name' => 'Apotek', 'url' => '/datakasir/apotek', 'icon' => 'capsules', 'order' => 5],
            ['name' => 'Tindakan', 'url' => '/datakasir/tindakan', 'icon' => 'briefcase-medical', 'order' => 6],
            ['name' => 'Mutasi Penyesuaian', 'url' => '/pendataan/stok-penyesuaian', 'icon' => 'exchange-alt', 'order' => 7],
            ['name' => 'Stok Opname', 'url' => '/pendataan/stok-opname', 'icon' => 'clipboard-check', 'order' => 8],
            ['name' => 'Gudang Utama','url' => '/pendataan/gudang-utama','icon' => 'fas fa-file-alt','order' => 9],
        ];

        foreach ($subMenuPendataan as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $menuLaporan->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }
            if ($roleManajemen) {
                $menu->roles()->attach($roleManajemen->id);
            }
        }


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
                'parent_id' => $menuPengaturan->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            if ($roleManajemen) {
                $menu->roles()->attach($roleManajemen->id);
            }
        }



        // 1. Buat submenu Data Master Umum di bawah Data Master
        $dataMasterUmum = menu::create([
            'name' => 'Data Master Umum',
            'url' => '#',
            'icon' => 'folder',
            'parent_id' => $menuDataMaster->id,
            'order' => 2,
        ]);

        if ($roleSuperAdmin) {
            $dataMasterUmum->roles()->attach($roleSuperAdmin->id);
        }

        if ($roleAdministrasi) {
            $dataMasterUmum->roles()->attach($roleAdministrasi->id);
        }

        // Submenu Data Master Umum
        $subMenusDataMasterUmum = [
            ['name' => 'Golongan Darah', 'url' => '/data-master/umum/goldar', 'icon' => 'tint', 'order' => 1],
            ['name' => 'Suku', 'url' => '/data-master/umum/suku', 'icon' => 'users', 'order' => 2],
            ['name' => 'Bangsa', 'url' => '/data-master/umum/bangsa', 'icon' => 'globe', 'order' => 3],
            ['name' => 'Bahasa', 'url' => '/data-master/umum/bahasa', 'icon' => 'language', 'order' => 4],
            ['name' => 'Agama', 'url' => '/data-master/umum/agama', 'icon' => 'pray', 'order' => 5],
            ['name' => 'Pendidikan', 'url' => '/data-master/umum/pendidikan', 'icon' => 'book', 'order' => 6],
            ['name' => 'Jenis Kelamin', 'url' => '/data-master/umum/kelamin', 'icon' => 'venus-mars', 'order' => 7],
            ['name' => 'Pernikahan', 'url' => '/data-master/umum/pernikahan', 'icon' => 'ring', 'order' => 8],
            ['name' => 'Pekerjaan', 'url' => '/data-master/umum/pekerjaan', 'icon' => 'briefcase', 'order' => 9],
            ['name' => 'Penjamin', 'url' => '/data-master/umum/penjamin', 'icon' => 'money-bill-alt', 'order' => 10],
            ['name' => 'Bank', 'url' => '/data-master/umum/bank', 'icon' => 'university', 'order' => 11],
            ['name' => 'Asuransi','url' => '/data-master/umum/asuransi','icon' => 'file-medical','order' => 12,],
            ['name' => 'Loket','url' => '/data-master/umum/loket','icon' => 'door-open','order' => 13,],
        ];

        foreach ($subMenusDataMasterUmum as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterUmum->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            if ($roleAdministrasi) {
                $menu->roles()->attach($roleAdministrasi->id);
            }
        }

        // 2. Buat submenu Data Master Medis di bawah Data Master
        $dataMasterMedis = menu::create([
            'name' => 'Data Master Medis',
            'url' => '#',
            'icon' => 'medkit',
            'parent_id' => $menuDataMaster->id,
            'order' => 3,
        ]);

        if ($roleSuperAdmin) {
            $dataMasterMedis->roles()->attach($roleSuperAdmin->id);
        }

        if ($roleAdministrasi) {
            $dataMasterMedis->roles()->attach($roleAdministrasi->id);
        }

        // Submenu untuk Data Master Medis
        $subMenusDataMasterMedis = [
            ['name' => 'Poli', 'url' => '/data-master/medis/poli', 'icon' => 'hospital', 'order' => 1],
            ['name' => 'Sarana', 'url' => '/data-master/medis/sarana', 'icon' => 'building', 'order' => 2],
            ['name' => 'Spesialis', 'url' => '/data-master/medis/spesialis', 'icon' => 'user-md', 'order' => 3],
            ['name' => 'Kategori Pemeriksaan & Tindakan', 'url' => '/data-master/medis/katper', 'icon' => 'list', 'order' => 4],
            ['name' => 'Pemeriksaan & Tindakan', 'url' => '/data-master/medis/perawatan-tindakan', 'icon' => 'procedures', 'order' => 5],
            ['name' => 'HTT Pemeriksaan', 'url' => '/data-master/medis/htt-pemeriksaan', 'icon' => 'stethoscope', 'order' => 6],
            ['name' => 'Alergi', 'url' => '/data-master/medis/alergi', 'icon' => 'allergies', 'order' => 7],
            ['name' => 'Jenis Diet', 'url' => '/data-master/medis/jenis-diet', 'icon' => 'utensils', 'order' => 8],
            ['name' => 'Makanan', 'url' => '/data-master/medis/nama-makanan', 'icon' => 'hamburger', 'order' => 9],
            ['name' => 'ICD 10', 'url' => '/data-master/medis/icd10', 'icon' => 'file', 'order' => 10],
            ['name' => 'ICD 9', 'url' => '/data-master/medis/icd9', 'icon' => 'file', 'order' => 11],
            ['name' => 'Radiologi', 'url' => '/data-master/medis/radiologi_jenis', 'icon' => 'x-ray', 'order' => 12],
            ['name' => 'Radiologi Pemeriksaan', 'url' => '/data-master/medis/radiologi_pemeriksaan', 'icon' => 'microscope', 'order' => 13],
            ['name' => 'Laboratorium', 'url' => '/data-master/medis/bidang-lab', 'icon' => 'vials', 'order' => 14],
        ];

        foreach ($subMenusDataMasterMedis as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterMedis->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            if ($roleAdministrasi) {
                $menu->roles()->attach($roleAdministrasi->id);
            }
        }

        // 3. Buat submenu Data Master Gudang di bawah Data Master
        $dataMasterGudang = menu::create([
            'name' => 'Data Master Gudang',
            'url' => '#',
            'icon' => 'warehouse',
            'parent_id' => $menuDataMaster->id,
            'order' => 4,
        ]);

        if ($roleSuperAdmin) {
            $dataMasterGudang->roles()->attach($roleSuperAdmin->id);
        }

        if ($roleAdministrasi) {
            $dataMasterGudang->roles()->attach($roleAdministrasi->id);
        }

        // Submenu untuk Data Master Gudang
        $subMenusDataMasterGudang = [
            ['name' => 'Satuan Obat', 'url' => '/data-master/gudang/satuan', 'icon' => 'ruler', 'order' => 1],
            ['name' => 'Kategori Obat', 'url' => '/data-master/gudang/kategori', 'icon' => 'tags', 'order' => 2],
            ['name' => 'Supplier', 'url' => '/data-master/gudang/supplier-industri', 'icon' => 'industry', 'order' => 3],
            ['name' => 'Setting Harga Jual', 'url' => '/data-master/gudang/setting-harga-jual', 'icon' => 'money-bill-wave', 'order' => 4],
            ['name' => 'Setting Harga Jual Utama','url' => '/data-master/gudang/setting-harga-jual-utama','icon' => 'fas fa-cogs','order' => 5],
            ['name' => 'Daftar Harga Jual', 'url' => '/data-master/gudang/harga-barang-jual', 'icon' => 'tag', 'order' => 6],
            ['name' => 'Daftar Harga Jual Utama','url' => '/data-master/gudang/harga-barang-jual-utama','icon' => 'fas fa-tags','order' => 7,],
            ['name' => 'Satuan Inventaris', 'url' => '/data-master/gudang/satuan-inventaris', 'icon' => 'fas fa-ruler-combined', 'order' => 8],
            ['name' => 'Kategori Inventaris', 'url' => '/data-master/gudang/kategori-inventaris', 'icon' => 'fas fa-layer-group', 'order' =>  9],

        ];

        foreach ($subMenusDataMasterGudang as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterGudang->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            if ($roleAdministrasi) {
                $menu->roles()->attach($roleAdministrasi->id);
            }
        }

        // Data Master Manajemen
        $dataMasterManajemen = menu::create([
            'name' => 'Data Master Manajemen',
            'url' => '#',
            'icon' => 'briefcase',
            'parent_id' => $menuDataMaster->id,
            'order' => 5,
        ]);

        if ($roleSuperAdmin) {
            $dataMasterManajemen->roles()->attach($roleSuperAdmin->id);
        }

        if ($roleAdministrasi) {
            $dataMasterManajemen->roles()->attach($roleAdministrasi->id);
        }

        // Submenu untuk Data Master Manajemen
        $subMenusDataMasterManajemen = [
            ['name' => 'Unit/Departemen', 'url' => '/data-master/manajemen/posker', 'icon' => 'chart-line', 'order' => 1],
        ];

        foreach ($subMenusDataMasterManajemen as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterManajemen->id,
                'order' => $subMenu['order'],
            ]);

            if ($roleSuperAdmin) {
                $menu->roles()->attach($roleSuperAdmin->id);
            }

            if ($roleAdministrasi) {
                $menu->roles()->attach($roleAdministrasi->id);
            }
        }
    }
}
