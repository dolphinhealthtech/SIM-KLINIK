<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RollbackMenuSeeder extends Seeder
{
    /**
     * Rollback the menu seeder.
     */
    public function run(): void
    {
        // Nonaktifkan pemeriksaan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus semua relasi menu-role terlebih dahulu
        DB::table('menu_roles')->truncate();
        
        // Hapus semua menu
        DB::table('menus')->truncate();
        
        // Aktifkan kembali pemeriksaan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Semua menu telah dihapus.');
    }
}