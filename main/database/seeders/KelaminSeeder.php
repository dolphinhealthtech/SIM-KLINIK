<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelaminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data jenis kelamin
        $kelamins = [
            [
                'nama' => 'Laki-laki',
                'kode' => 'L',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Perempuan',
                'kode' => 'P',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tidak Diketahui',
                'kode' => 'U',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Masukkan data ke dalam tabel kelamins
        DB::table('kelamins')->insert($kelamins);
    }
}