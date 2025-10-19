<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    // Menampilkan semua division
    public function index()
    {
        $divisions = Division::orderBy('created_at', 'desc')->paginate(10);
        return view('division.index', compact('divisions'));
    }

    // Menampilkan form tambah division
    public function create()
    {
        return view('division.create');
    }

    // Menyimpan data baru ke tabel divisions
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'customer_leads' => 'nullable|integer',
        ]);

        Division::create($validated);

        return redirect()->route('division.index')
            ->with('success', 'Division berhasil ditambahkan ke database.');
    }

    // Menampilkan form edit
    public function edit(Division $division)
    {
        return view('division.edit', compact('division'));
    }

    // Update data division
    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'customer_leads' => 'nullable|integer',
        ]);

        $division->update($validated);

        return redirect()->route('division.index')
            ->with('success', 'Division berhasil diperbarui.');
    }

    // Hapus division
    public function destroy(Division $division)
    {
        $division->delete();

        return redirect()->route('division.index')
            ->with('success', 'Division berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        $division->status = $request->status; // true/false
        $division->save();

        return response()->json(['success' => true, 'status' => $division->status]);
    }
}
