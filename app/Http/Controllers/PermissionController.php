<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermissionMenu;
use Illuminate\Support\Facades\Schema;

class PermissionController extends Controller
{
    // Menampilkan semua permission
    public function index(Request $request)
    {
        $query = PermissionMenu::withCount('roles');

        if ($search = $request->query('search')) {
            $table = (new PermissionMenu)->getTable();
            $columns = Schema::getColumnListing($table);
            $ignore = ['guard_name'];
            $columns = array_filter($columns, fn($c) => !in_array($c, $ignore));

            $query->where(function ($qb) use ($columns, $search) {
                foreach ($columns as $col) {
                    if ($col === 'id' && is_numeric($search)) {
                        $qb->orWhere($col, $search);
                    } else {
                        $qb->orWhere($col, 'like', "%{$search}%");
                    }
                }
            });

            $query->orWhereHas('roles', function ($r) use ($search) {
                $r->where('name', 'like', "%{$search}%");
            });
        }

        // Ambil nilai per_page dari request, default 15
        $perPage = $request->query('per_page', 15);

        // Jika pilih All, ambil semua data
        if ($perPage === 'All') {
            $permissions = $query->orderBy('created_at', 'desc')->get();
            // wrap Collection agar bisa dipakai sama Blade dengan $permissions->links() dikondisikan
            $permissions = new \Illuminate\Pagination\LengthAwarePaginator(
                $permissions,
                $permissions->count(),
                $permissions->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $permissions = $query->orderBy('created_at', 'desc')
                ->paginate(intval($perPage))
                ->withQueryString();
        }

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
