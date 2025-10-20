<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan semua user (search + paginate)
    public function index(Request $request)
    {
        $query = User::query();
        if ($q = $request->query('search')) {
            $query->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('user.index', compact('users'));
    }

    public function export(Request $request)
    {
        $q = $request->query('search');
        $query = User::query();
        if ($q) $query->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
        $items = $query->orderBy('created_at', 'desc')->get();

        $filename = 'users_export_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID','Name','Email','Role','Created At']);
            foreach ($items as $i) {
                fputcsv($out, [
                    $i->id,
                    $i->name,
                    $i->email,
                    $i->role,
                    $i->created_at,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Form tambah user
    public function create()
    {
        return view('user.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3|confirmed',
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // otomatis di-hash di model
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // Form edit user
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:3|confirmed',
            'role' => 'required',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = $request->password; // otomatis di-hash di model
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
