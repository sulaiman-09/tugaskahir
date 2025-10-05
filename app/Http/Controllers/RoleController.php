<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        // Data dummy sementara
        $roles = [
            ['id' => 1, 'name' => 'Admin', 'description' => 'Memiliki semua hak akses.'],
            ['id' => 2, 'name' => 'Marketing', 'description' => 'Mengelola konten promosi dan banner.'],
            ['id' => 3, 'name' => 'Sales', 'description' => 'Mengelola data pelanggan dan transaksi.'],
        ];

        return view('roles.index', compact('roles'));
    }
}
