<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        // Data dummy sementara
        $permissions = [
            ['id' => 1, 'name' => 'view_users', 'description' => 'Dapat melihat daftar pengguna'],
            ['id' => 2, 'name' => 'edit_users', 'description' => 'Dapat mengedit data pengguna'],
            ['id' => 3, 'name' => 'delete_users', 'description' => 'Dapat menghapus pengguna'],
            ['id' => 4, 'name' => 'manage_roles', 'description' => 'Dapat mengelola peran pengguna'],
        ];

        return view('permissions.index', compact('permissions'));
    }
}
