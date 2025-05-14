<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data poli
        $polis = [
            [
                'kode' => 'UMU',
                'nama' => 'POLI UMUM',
                'jenis' => 'Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'GIG',
                'nama' => 'POLI GIGI',
                'jenis' => 'Gigi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'KIA',
                'nama' => 'POLI KIA',
                'jenis' => 'KIA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'INT',
                'nama' => 'POLI PENYAKIT DALAM',
                'jenis' => 'Spesialis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'OBG',
                'nama' => 'POLI KEBIDANAN DAN KANDUNGAN',
                'jenis' => 'Spesialis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Masukkan data ke dalam tabel polis
        DB::table('polis')->insert($polis);
    }
}