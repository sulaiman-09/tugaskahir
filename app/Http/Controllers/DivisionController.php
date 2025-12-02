<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ArrayExport;

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
    $categories = Category::all();
    $products   = Product::all();
    $divisions  = Division::all();

    return view('customer.create', compact('categories', 'products', 'divisions'));
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
        if (request()->ajax() || request()->query('modal')) {
            return view('division.partials.form', [
                'division' => $division,
                'hideCancel' => true,
            ]);
        }

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

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

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

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids; // array of IDs

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            Division::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Divisions deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete divisions.']);
        }
    }

    public function exportExcel(Request $request)
    {
        $q = $request->query('search');
        $query = Division::query();

        // 🔍 Filter berdasarkan pencarian
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            });
        }

        $divisions = $query->orderBy('created_at', 'desc')->get();

        $data = $divisions->map(function ($d) {
            return [
                'ID' => $d->id,
                'Name' => $d->name,
                'Description' => $d->description,
                'Status' => $d->status ? 'Active' : 'Inactive',
                'Customer Leads' => $d->customer_leads,
                'Created At' => $d->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return Excel::download(new ArrayExport($data), 'divisions_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $q = $request->query('search');
        $query = Division::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            });
        }

        $divisions = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('division.export-pdf', compact('divisions'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('divisions_' . now()->format('Ymd_His') . '.pdf');
    }
}
