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
        $query = Role::withCount('permissions');

        if ($search = $request->query('search')) {
            // Ambil daftar kolom nyata dari tabel roles
            $table = (new Role)->getTable();
            $columns = Schema::getColumnListing($table);

            // Hilangkan kolom yang tidak layak dicari jika perlu
            $ignore = ['password', 'remember_token'];
            $columns = array_filter($columns, fn($c) => !in_array($c, $ignore));

            // Buat where dinamis: jika pencarian numerik dan kolom adalah id, gunakan equality
            $query->where(function($qb) use ($columns, $search) {
                foreach ($columns as $col) {
                    if ($col === 'id' && is_numeric($search)) {
                        $qb->orWhere($col, $search);
                    } else {
                        $qb->orWhere($col, 'like', "%{$search}%");
                    }
                }
            });

            // Juga cari berdasarkan nama permission terkait
            $query->orWhereHas('permissions', function($p) use ($search) {
                $p->where('name', 'like', "%{$search}%");
            });
        }

        // Kembalikan hasil dengan pagination agar UI konsisten
        $roles = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
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
