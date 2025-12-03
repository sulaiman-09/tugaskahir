<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed demo users such as admin and sales.
     */
    public function run(): void
    {
        // Membuat User Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'), // Password di-hash
            'role' => 'admin',
        ]);

        // Membuat User Sales (Contoh tambahan)
        User::create([
            'name' => 'Sales Staff',
            'email' => 'sales@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'sales',
        ]);
    }
}
