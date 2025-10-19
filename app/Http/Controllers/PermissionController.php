<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermissionMenu;

class PermissionController extends Controller
{
    // Menampilkan semua permission
    public function index()
    {
        // Menghitung jumlah role yang memiliki permission ini
        $permissions = PermissionMenu::withCount('roles')->get();
        return view('permissions.index', compact('permissions'));
    }

    // Menampilkan form tambah permission
    public function create()
    {
        return view('permissions.create');
    }

    // Menyimpan permission baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        PermissionMenu::create([
            'name' => $request->name,
            'guard_name' => 'web', // wajib diisi agar tidak error
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    // Menampilkan form edit permission
    public function edit(PermissionMenu $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    // Update permission
    public function update(Request $request, PermissionMenu $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
            'guard_name' => 'web', // wajib diisi juga
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    // Delete permission
    public function destroy(PermissionMenu $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
    
}
