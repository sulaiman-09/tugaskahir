<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Menampilkan daftar role
    public function index()
    {
        // Data dummy sementara (bisa diganti dari database nanti)
        $roles = session('roles', [
            ['id' => 1, 'name' => 'Admin', 'permissions_count' => 8, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 2, 'name' => 'Sales', 'permissions_count' => 5, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 3, 'name' => 'Report', 'permissions_count' => 3, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 4, 'name' => 'Sudirman Park', 'permissions_count' => 1, 'created_at' => '28/08/2025 17:30:12'],
        ]);

        return view('roles.index', compact('roles'));
    }

    // Menampilkan form tambah role
    public function create()
    {
        // Daftar permission dummy
        $permissions = [
            'create_sales',
            'edit_sales',
            'generate_reports',
            'manage_users',
            'sudirman_manage',
            'view_dashboard',
            'view_reports',
            'view_sales'
        ];

        return view('roles.create', compact('permissions'));
    }

    // Menyimpan data role baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array|min:1',
        ]);

        // Ambil data role yang sudah ada
        $roles = session('roles', []);

        // Buat ID baru berdasarkan jumlah role sebelumnya
        $newId = count($roles) + 1;

        // Data baru
        $newRole = [
            'id' => $newId,
            'name' => $request->name,
            'permissions_count' => count($request->permissions),
            'created_at' => now()->format('d/m/Y H:i:s'),
        ];

        // Simpan ke session (sementara)
        $roles[] = $newRole;
        session(['roles' => $roles]);

        // Redirect ke halaman index
        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan!');
    }
}
