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
        // Panggil seeder khusus pengguna agar akun demo (admin, sales, dll.) dibuat
        // dan juga user test yang baru ditambahkan
        $this->call([
            UserSeeder::class,
            TestUserSeeder::class,
        ]);
    }
}
