<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = [
            ['id' => 1, 'name' => 'Admin', 'email' => 'admin@example.com', 'role' => 'Admin'],
            ['id' => 2, 'name' => 'Marketing', 'email' => 'marketing@example.com', 'role' => 'Marketing'],
            ['id' => 3, 'name' => 'Sales', 'email' => 'sales@example.com', 'role' => 'Sales'],
        ];

        return view('user.index', compact('users'));
    }
}
