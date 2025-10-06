<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = [
            ['id' => 1, 'name' => 'Admin', 'email' => 'admin@example.com', 'role' => 'Admin'],
            ['id' => 2, 'name' => 'Marketing', 'email' => 'marketing@example.com', 'role' => 'Marketing'],
            ['id' => 3, 'name' => 'Sales', 'email' => 'sales@example.com', 'role' => 'Sales'],
        ];

        return view('user.index', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('user.create');
    }

    // ✅ Menyimpan user baru (sementara hanya simulasi)
    public function store(Request $request)
    {
        // Validasi input sederhana
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:3|confirmed',
            'role' => 'required',
        ]);

        // Sementara hanya redirect kembali ke daftar user
        // (nanti bisa ditambah logika simpan ke database)
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }
}
