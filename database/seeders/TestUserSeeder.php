<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Test',
            'email' => 'test@lifemedia.com',
            'password' => Hash::make('qwerty'),
            'role' => 'admin',
        ]);
    }
}