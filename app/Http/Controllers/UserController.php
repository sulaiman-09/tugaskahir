<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan semua user (search + paginate)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);
        $showAll = strtolower($perPage) === 'all';

        $query = \App\Models\User::orderBy('created_at', 'desc');

        // 🔍 Search
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        // 🧾 Pagination / All
        if ($showAll) {
            $users = $query->get();
        } else {
            $users = $query->paginate((int)$perPage)->withQueryString();
        }

        return view('user.index', compact('users', 'perPage', 'search', 'showAll'));
    }

    // export removed per request

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

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) {
            return response()->json(['success' => false, 'message' => 'No users selected.']);
        }

        User::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' user(s) deleted successfully.'
        ]);
    }
}
