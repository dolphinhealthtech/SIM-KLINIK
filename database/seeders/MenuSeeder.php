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
        // untuk mencegah duplikasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('menu_roles')->truncate();
        DB::table('menus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Ambil semua role (existing + new)
        $superAdminRole = Role::where('name', 'Super-admin')->first();
        $dokterRole = Role::where('name', 'Dokter')->first();
        $resepsionisRole = Role::where('name', 'Resepsionis')->first();
        $perawatRole = Role::where('name', 'Perawat')->first();
        $kasirRole = Role::where('name', 'Kasir')->first();
        $sdmRole = Role::where('name', 'SDM')->first();
        $apotekerRole = Role::where('name', 'Apoteker')->first();
        $registrasiRole = Role::where('name', 'Registrasi')->first();
        $personaliaRole = Role::where('name', 'Personalia')->first();
        $gudangRole = Role::where('name', 'Gudang')->first();
        $managementRole = Role::where('name', 'Manajemen')->first();

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

        $obatdanallkes = menu::create([
            'name' => 'Obat dan Alkes',
            'url' => '#',
            'icon' => 'fas fa-boxes-stacked',
            'parent_id' => null,
            'order' => 10,
        ]);

        $inventaris = menu::create([
            'name' => 'Inventaris',
            'url' => '#',
            'icon' => 'fas fa-boxes-stacked',
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

        // ===== ROLE ASSIGNMENTS FOR MAIN MENUS =====

        // Super-admin gets access to everything
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
            $obatdanallkes->roles()->attach($superAdminRole->id);
            $inventaris->roles()->attach($superAdminRole->id);
            $dataMaster->roles()->attach($superAdminRole->id);
            $pengaturan->roles()->attach($superAdminRole->id);
        }

        // User Registrasi: Dashboard Antrian, Pasien, Pendaftaran, Laporan (Antrian + Pendaftaran)
        if ($registrasiRole) {
            $dashboard->roles()->attach($registrasiRole->id);
            $antrian->roles()->attach($registrasiRole->id);
            $pasien->roles()->attach($registrasiRole->id);
            $pendaftaran->roles()->attach($registrasiRole->id);
            $pendataan->roles()->attach($registrasiRole->id); // For Laporan
        }

        // User Perawat: Perawat, Laporan Perawat
        if ($perawatRole) {
            $dashboard->roles()->attach($perawatRole->id);
            $pemeriksaan->roles()->attach($perawatRole->id);
            $pendataan->roles()->attach($perawatRole->id); // For Laporan
        }

        // User Dokter: Dokter, Laporan Dokter
        if ($dokterRole) {
            $dashboard->roles()->attach($dokterRole->id);
            $pemeriksaan->roles()->attach($dokterRole->id);
            $pendataan->roles()->attach($dokterRole->id); // For Laporan
        }

        // User Apoteker: Apotek, Laporan Apotek
        if ($apotekerRole) {
            $dashboard->roles()->attach($apotekerRole->id);
            $apotek->roles()->attach($apotekerRole->id);
            $pendataan->roles()->attach($apotekerRole->id); // For Laporan
        }

        // User Kasir: Kasir, Keuangan
        if ($kasirRole) {
            $dashboard->roles()->attach($kasirRole->id);
            $kasir->roles()->attach($kasirRole->id);
            $keuangan->roles()->attach($kasirRole->id);
        }

        // User Personalia: SDM
        if ($personaliaRole) {
            $dashboard->roles()->attach($personaliaRole->id);
            $sdm->roles()->attach($personaliaRole->id);
        }

        // User Gudang: Pembelian, Data Barang, Gudang Utama, Request Obat, Inventaris
        if ($gudangRole) {
            $dashboard->roles()->attach($gudangRole->id);
            $obatdanallkes->roles()->attach($gudangRole->id);
            $inventaris->roles()->attach($gudangRole->id);
        }

        // User Management: Data Master, Setting
        if ($managementRole) {
            $dashboard->roles()->attach($managementRole->id);
            $dataMaster->roles()->attach($managementRole->id);
            $pengaturan->roles()->attach($managementRole->id);
        }

        // Legacy role assignments (keeping existing functionality)
        if ($resepsionisRole) {
            $dashboard->roles()->attach($resepsionisRole->id);
            $antrian->roles()->attach($resepsionisRole->id);
            $pasien->roles()->attach($resepsionisRole->id);
            $pendaftaran->roles()->attach($resepsionisRole->id);
        }

        if ($sdmRole) {
            $dashboard->roles()->attach($sdmRole->id);
            $sdm->roles()->attach($sdmRole->id);
            $dataMaster->roles()->attach($sdmRole->id);
        }

        // ===== SUBMENUS =====

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

            // Assign submenu Dokter to Dokter role
            if ($subMenu['name'] === 'Dokter' && $dokterRole) {
                $menu->roles()->attach($dokterRole->id);
            }

            // Assign submenu Perawat to Perawat role
            if ($subMenu['name'] === 'Perawat' && $perawatRole) {
                $menu->roles()->attach($perawatRole->id);
            }
        }

        //submenu dashboard antrian
        $subMenusAntrian = [
            ['name' => 'Antrian', 'url' => '/monitor', 'icon' => 'users', 'order' => 1],
            ['name' => 'Loket Antrian', 'url' => '/monitor/loket-antrian', 'icon' => 'desktop', 'order' => 2],
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

            // Assign to Registrasi role
            if ($registrasiRole) {
                $menu->roles()->attach($registrasiRole->id);
            }

            // Legacy assignment for Resepsionis
            if ($resepsionisRole) {
                $menu->roles()->attach($resepsionisRole->id);
            }
        }


        // Submenu Obat dan Allkes
$subMenuObatAllkes = [
    [
        'name' => 'Daftar Barang',
        'url' => '/data-barang',
        'icon' => 'pills',
        'order' => 1,
    ],
    [
        'name' => 'Daftar Barang Utama',
        'url' => '/data-barang-utama',
        'icon' => 'fas fa-list',
        'order' => 2,
    ],

    ['name' => 'Stok Obat', 'url' => '/data-master-gudang/stok-obat-alkes', 'icon' => 'boxes', 'order' => 3],
    ['name' => 'Stok Obat Utama', 'url' => '/data-master-gudang/stok-obat-alkes-utama', 'icon' => 'boxes', 'order' => 4],
    [
        'name' => 'Gudang utama (obat)',
        'url' => '/data-master-gudang/gudang-utama',
        'icon' => 'fas fa-boxes',
        'order' => 5,
    ],

    [
        'name' => 'Permintaan Obat',
        'url' => '/data-master-gudang/gudang-request',
        'icon' => 'fas fa-prescription-bottle',
        'order' => 6,
    ],
    [
        'name' => 'Pembelian',
        'url' => '/pembelian',
        'icon' => 'shopping-cart',
        'order' => 7,
    ],

];

foreach ($subMenuObatAllkes as $subMenu) {
    $menu = menu::create([
        'name' => $subMenu['name'],
        'url' => $subMenu['url'],
        'icon' => $subMenu['icon'],
        'parent_id' => $obatdanallkes->id,
        'order' => $subMenu['order'],
    ]);

    if ($superAdminRole) {
        $menu->roles()->attach($superAdminRole->id);
    }

    // Assign to Kasir role, atau role lain sesuai kebutuhan
    if ($gudangRole) {
        $menu->roles()->attach($gudangRole->id);
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

            // Assign to Kasir role
            if ($kasirRole) {
                $menu->roles()->attach($kasirRole->id);
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

            // Assign to Personalia role
            if ($personaliaRole) {
                $menu->roles()->attach($personaliaRole->id);
            }

            // Legacy assignment for SDM role
            if ($sdmRole) {
                $menu->roles()->attach($sdmRole->id);
            }
        }

        // Submenu inventaris
        $subMenusinventaris = [
            ['name' => 'Daftar Inventaris', 'url' => '/data-inventaris', 'icon' => 'fas fa-database', 'order' => 1],
            ['name' => 'Daftar Inventaris Utama','url' => '/data-inventaris-utama','icon' => 'fas fa-clipboard-list','order' => 2],
            ['name' => 'Pembelian Inventaris', 'url' => '/inventaris-pembelian', 'icon' => 'fas fa-cart-plus', 'order' => 3],
            ['name' => 'Pembelian Inventaris Utama','url' => '/inventaris-pembelian-utama','icon' => 'fas fa-cart-plus','order' => 4],
            ['name' => 'Permintaan Inventaris', 'url' => '/data-master-gudang/inventaris-request', 'icon' => 'fas fa-paper-plane', 'order' => 5],
            ['name' => 'Gudang Utama (Inventaris)', 'url' => '/data-master-gudang/inventaris-utama', 'icon' => 'fas fa-box', 'order' => 6],
            ['name' => 'Stok Inventaris', 'url' => '/data-master-gudang/stok-inventaris', 'icon' => 'fas fa-clipboard-list', 'order' => 7],
            ['name' => 'Stok Inventaris Utama','url' => '/data-master-gudang/stok-inventaris-utama','icon' => 'fas fa-warehouse','order' => 8],
        ];

        foreach ($subMenusinventaris as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $inventaris->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

            // Assign to Gudang role
            if ($gudangRole) {
                $menu->roles()->attach($gudangRole->id);
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
                'parent_id' => $pendataan->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

            // Role-specific laporan assignments
            switch ($subMenu['name']) {
                case 'Antrian':
                case 'Pendaftaran':
                    if ($registrasiRole) {
                        $menu->roles()->attach($registrasiRole->id);
                    }
                    break;
                case 'Dokter':
                    if ($dokterRole) {
                        $menu->roles()->attach($dokterRole->id);
                    }
                    break;
                case 'Perawat':
                    if ($perawatRole) {
                        $menu->roles()->attach($perawatRole->id);
                    }
                    break;
                case 'Apotek':
                    if ($apotekerRole) {
                        $menu->roles()->attach($apotekerRole->id);
                    }
                    break;
                    case 'Tindakan':
                // Tindakan boleh diakses oleh dokter, perawat, dan manajemen
                if ($dokterRole) {
                    $menu->roles()->attach($dokterRole->id);
                }
                if ($perawatRole) {
                    $menu->roles()->attach($perawatRole->id);
                }
                if ($managementRole) {
                    $menu->roles()->attach($managementRole->id);
                }
                break;
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

        if ($managementRole) {
            $loket->roles()->attach($managementRole->id);
        }

        // Legacy assignment
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

        if ($managementRole) {
            $dataMasterUmum->roles()->attach($managementRole->id);
        }

        // Legacy assignment
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

            if ($managementRole) {
                $menu->roles()->attach($managementRole->id);
            }

            // Legacy assignment
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

        if ($managementRole) {
            $dataMasterMedis->roles()->attach($managementRole->id);
        }

        // Legacy assignment
        if ($sdmRole) {
            $dataMasterMedis->roles()->attach($sdmRole->id);
        }

        // Submenu untuk Data Master Medis
        $subMenusDataMasterMedis = [
            ['name' => 'Poli', 'url' => '/data-master-medis/poli', 'icon' => 'hospital', 'order' => 1],
            ['name' => 'Sarana', 'url' => '/data-master-medis/sarana', 'icon' => 'building', 'order' => 2],
            ['name' => 'Spesialis', 'url' => '/data-master-medis/spesialis', 'icon' => 'user-md', 'order' => 3],
            ['name' => 'Kategori Pemeriksaan & Tindakan', 'url' => '/data-master-medis/katper', 'icon' => 'list', 'order' => 4],
            ['name' => 'Pemeriksaan & Tindakan', 'url' => '/data-master-medis/perawatan-tindakan', 'icon' => 'procedures', 'order' => 5],
            ['name' => 'HTT Pemeriksaan', 'url' => '/data-master-medis/htt-pemeriksaan', 'icon' => 'stethoscope', 'order' => 6],
            ['name' => 'Alergi', 'url' => '/data-master-medis/alergi', 'icon' => 'allergies', 'order' => 7],
            ['name' => 'Jenis Diet', 'url' => '/data-master-medis/jenis-diet', 'icon' => 'utensils', 'order' => 8],
            ['name' => 'Makanan', 'url' => '/data-master-medis/nama-makanan', 'icon' => 'hamburger', 'order' => 9],
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

            if ($managementRole) {
                $menu->roles()->attach($managementRole->id);
            }

            // Legacy assignment
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

        if ($managementRole) {
            $dataMasterGudang->roles()->attach($managementRole->id);
        }

        // Legacy assignment
        if ($sdmRole) {
            $dataMasterGudang->roles()->attach($sdmRole->id);
        }

        // Submenu untuk Data Master Gudang
        $subMenusDataMasterGudang = [
            ['name' => 'Satuan Obat', 'url' => '/data-master-gudang/satuan', 'icon' => 'ruler', 'order' => 1],
            ['name' => 'Kategori Obat', 'url' => '/data-master-gudang/kategori', 'icon' => 'tags', 'order' => 2],
            ['name' => 'Supplier', 'url' => '/data-master-gudang/supplier-industri', 'icon' => 'industry', 'order' => 3],
            ['name' => 'Setting Harga Jual', 'url' => '/data-master-gudang/setting-harga-jual', 'icon' => 'money-bill-wave', 'order' => 4],
            ['name' => 'Setting Harga Jual Utama','url' => '/data-master-gudang/setting-harga-jual-utama','icon' => 'fas fa-cogs','order' => 5],
            ['name' => 'Daftar Harga Jual', 'url' => '/data-master-gudang/harga-barang-jual', 'icon' => 'tag', 'order' => 6],
            ['name' => 'Daftar Harga Jual Utama','url' => '/data-master-gudang/harga-barang-jual-utama','icon' => 'fas fa-tags','order' => 7,],
            ['name' => 'Satuan Inventaris', 'url' => '/data-master-gudang/satuan-inventaris', 'icon' => 'fas fa-ruler-combined', 'order' => 8],
            ['name' => 'Kategori Inventaris', 'url' => '/data-master-gudang/kategori-inventaris', 'icon' => 'fas fa-layer-group', 'order' =>  9],
            ['name' => 'Mutasi Penyesuaian', 'url' => '/data-master-gudang/stok-penyesuaian-opname', 'icon' => 'fas fa-exchange-alt', 'order' => 10],
            ['name' => 'Mutasi Penyesuaian Utama','url' => '/data-master-gudang/stok-penyesuaian-opname-utama','icon' => 'fas fa-exchange-alt','order' => 11],
            ['name' => 'Kartu Stok', 'url' => '/data-master-gudang/kartu-stok', 'icon' => 'fas fa-clipboard-list', 'order' => 12],
            ['name' => 'Kartu Stok Utama','url' => '/data-master-gudang/kartu-stok-utama','icon' => 'fas fa-id-card','order' => 13],
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

            if ($managementRole) {
                $menu->roles()->attach($managementRole->id);
            }

            // Legacy assignment
            if ($sdmRole) {
                $menu->roles()->attach($sdmRole->id);
            }
        }

        // Data Master Manajemen
        $dataMasterManajemen = menu::create([
            'name' => 'Data Master Manajemen',
            'url' => '#',
            'icon' => 'briefcase',
            'parent_id' => $dataMaster->id,
            'order' => 5,
        ]);

        if ($superAdminRole) {
            $dataMasterManajemen->roles()->attach($superAdminRole->id);
        }

        if ($managementRole) {
            $dataMasterManajemen->roles()->attach($managementRole->id);
        }

        // Legacy assignment
        if ($sdmRole) {
            $dataMasterManajemen->roles()->attach($sdmRole->id);
        }

        // Submenu untuk Data Master Manajemen
        $subMenusDataMasterManajemen = [
            ['name' => 'Unit/Departemen', 'url' => '/data-master-manajemen/posker', 'icon' => 'chart-line', 'order' => 1],
        ];

        foreach ($subMenusDataMasterManajemen as $subMenu) {
            $menu = menu::create([
                'name' => $subMenu['name'],
                'url' => $subMenu['url'],
                'icon' => $subMenu['icon'],
                'parent_id' => $dataMasterManajemen->id,
                'order' => $subMenu['order'],
            ]);

            if ($superAdminRole) {
                $menu->roles()->attach($superAdminRole->id);
            }

            if ($managementRole) {
                $menu->roles()->attach($managementRole->id);
            }

            // Legacy assignment
            if ($sdmRole) {
                $menu->roles()->attach($sdmRole->id);
            }
        }

        // Submenu Setting - Only for Management and Super Admin
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

            if ($managementRole) {
                $menu->roles()->attach($managementRole->id);
            }
        }


    }
}






