<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@lifemedia.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Sales user
        User::create([
            'name' => 'Sales',
            'email' => 'sales@lifemedia.com',
            'password' => Hash::make('sales123'),
            'role' => 'sales',
        ]);

        // Sudirman Park user
        User::create([
            'name' => 'Sudirman Park',
            'email' => 'sudirmanpark@lifemedia.com',
            'password' => Hash::make('sudirman123'),
            'role' => 'sudirman park',
        ]);

        // Report user
        User::create([
            'name' => 'Report',
            'email' => 'report@lifemedia.com',
            'password' => Hash::make('report123'),
            'role' => 'report',
        ]);

        // Test user
        // Removed duplicate test user to avoid unique constraint violation
        /*
         User::create([
            'name' => 'Test',
            'email' => 'test@lifemedia.com',
            'password' => Hash::make('qwerty'),
            'role' => 'admin',
        ]);
        */
    }
}