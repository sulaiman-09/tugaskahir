<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    // Menampilkan semua division
    public function index(Request $request)
    {
        $q = $request->query('search');
        $perPage = $request->query('per_page', 15);
        $showAll = strtolower($perPage) === 'all';

        $query = Division::query();

        // 🔍 Search
        if ($q) {
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->orWhere('status', 'like', "%{$q}%");
        }

        // 🧾 Pagination / All
        if ($showAll) {
            $divisions = $query->orderBy('created_at', 'desc')->get();
        } else {
            $divisions = $query->orderBy('created_at', 'desc')
                ->paginate((int)$perPage)
                ->withQueryString();
        }

        return view('division.index', compact('divisions', 'perPage', 'q', 'showAll'));
    }

    public function export(Request $request)
    {
        $q = $request->query('search');
        $query = \App\Models\Division::query();

        // Filter by search
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            });
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $filename = 'divisions_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Description', 'Status', 'Customer Leads', 'Created At']);

            foreach ($items as $i) {
                fputcsv($out, [
                    $i->id,
                    $i->name,
                    $i->description,
                    $i->status ? 'Active' : 'Inactive',
                    $i->customer_leads,
                    $i->created_at,
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
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
