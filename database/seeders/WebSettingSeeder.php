<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('set__bpjs')->insert([
            'CONSID'            => '10791',
            'USERNAME'          => 'ecodumy',
            'PASSWORD'          => 'Asdf123#',
            'SCREET_KEY'        => '4iJAEE30E7',
            'USER_KEY'          => 'cf03a8d46531a8ee3d1575196d31a443',
            'APP_CODE'          => '095',
            'BASE_URL'          => 'https://apijkn-dev.bpjs-kesehatan.go.id',
            'SERVICE'           => 'pcare-rest-dev',
            'SERVICE_ANTREAN'   => 'antreanfktp_dev',
            'KPFK'              => '0221B252',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Seed kedua: Table kedua
        DB::table('set__sehats')->insert([
            'org_id'             => '100021907',
            'client_id'          => 'f9P1MFTYAF453MLbBx5y5sqQPM1xU3zLGrKiGptYCEhWgtvk',
            'client_secret'      => 'd4yvu7PgVlZe2pZhAvpeFDMKhnTfVAkkwLP4cqSbZgdNi2rqeJPVYoLDDnWpOXbS',
            'SATUSEHAT_BASE_URL' => 'https://api-satusehat.kemkes.go.id',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // Seed ketiga: Table ketiga
        DB::table('web_settings')->insert([
            'nama'          => 'Tekno App',
            'alamat'        => 'Jl. Merdeka No. 123, Jakarta',
            'profile_image' => 'default.png',
            'profile_image' => 'default.png',
            'kode_klinik'   => '0000',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
