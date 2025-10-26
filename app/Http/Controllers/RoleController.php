<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Schema;

class RoleController extends Controller
{
    // Menampilkan daftar role
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15); // default 15

        if ($search = $request->query('search')) {
            $table = (new Role)->getTable();
            $columns = Schema::getColumnListing($table);

            $ignore = ['password', 'remember_token'];
            $columns = array_filter($columns, fn($c) => !in_array($c, $ignore));

            $colQuery = Role::query();
            $colQuery->where(function ($qb) use ($columns, $search) {
                foreach ($columns as $col) {
                    if ($col === 'id' && is_numeric($search)) {
                        $qb->orWhere($col, $search);
                    } else {
                        $qb->orWhere($col, 'like', "%{$search}%");
                    }
                }
            });

            if ($colQuery->count() > 0) {
                $roles = $colQuery->withCount('permissions')->orderBy('created_at', 'desc')
                    ->paginate($perPage)->withQueryString();
                return view('roles.index', compact('roles'));
            }

            $query = Role::withCount('permissions')->whereHas('permissions', function ($p) use ($search) {
                $p->where('name', 'like', "%{$search}%");
            });
        } else {
            $query = Role::withCount('permissions');
        }

        $roles = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        return view('roles.index', compact('roles'));
    }

    // Form tambah role
    public function create()
    {
        $permissions = Permission::pluck('name', 'id');
        return view('roles.create', compact('permissions'));
    }

    // Simpan role baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array|min:1',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->permissions()->attach($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan!');
    }

    // Form edit role
    public function edit(Role $role)
    {
        $permissions = Permission::pluck('name', 'id');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // Update role
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array|min:1',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        $role->permissions()->sync($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate!');
    }

    // Hapus role
    public function destroy(Role $role)
    {
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
    }
}
