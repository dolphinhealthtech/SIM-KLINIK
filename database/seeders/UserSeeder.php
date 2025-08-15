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
            'name' => 'Admin',
            'email' => 'Administrator@dhrigos.my.id',
            'username' => 'Administrator',
            'password' => bcrypt('Myapp2025'),
            'profile' => 'default.png',
            'email_verified_at' => now(),
        ]);

        $user->assignRole('Super Admin');
    }
}
