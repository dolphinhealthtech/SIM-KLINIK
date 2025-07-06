<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Administartor',
            'email' => 'Administartor@tekno.co',
            'username' => 'Administartor',
            'password' => bcrypt('Myapp2025'),
            'profile' => 'default.png',
            'email_verified_at' => now(),
        ]);

        $user->assignRole('Super-admin');
    }
}
