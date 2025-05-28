<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinsiSeeder::class,
            kabupatenSeeder::class,
            KecamatanSeeder::class,
            DesaSeeder::class,
            RolePermissionSeeder::class,
            MenuSeeder::class,
            UserSeeder::class,
            WebSettingSeeder::class,
            KelaminSeeder::class,
            GcsSeeder::class,
        ]);
    }
}
