<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat User Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'), // Password di-hash
            'role' => 'admin', // Pastikan kolom role ada di tabel users
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