<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        // Data dummy sementara
        $permissions = [
            ['id' => 1, 'name' => 'view_dashboard', 'roles' => 3, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 2, 'name' => 'manage_users', 'roles' => 1, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 3, 'name' => 'create_sales', 'roles' => 2, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 4, 'name' => 'edit_sales', 'roles' => 2, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 5, 'name' => 'view_sales', 'roles' => 2, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 6, 'name' => 'generate_reports', 'roles' => 2, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 7, 'name' => 'view_reports', 'roles' => 3, 'created_at' => '29/11/2024 04:14:42'],
            ['id' => 8, 'name' => 'sudirman_manage', 'roles' => 2, 'created_at' => '28/08/2025 11:17:09'],
        ];

        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Simulasi penyimpanan data
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }
}
