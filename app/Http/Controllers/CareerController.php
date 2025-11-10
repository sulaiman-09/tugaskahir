<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $query = Career::query();

        // 🔍 Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // 🔢 Pagination
        $perPage = $request->query('per_page', 10);
        $careers = $perPage === 'all'
            ? $query->latest()->get()
            : $query->latest()->paginate((int) $perPage)->withQueryString();

        // Tambah status aktif/tidak
        $careers->getCollection()->transform(function ($career) {
            $career->status = $career->is_active ? 'Active' : 'Inactive';
            return $career;
        });

        return view('career.index', compact('careers', 'perPage'));
    }

    public function create()
    {
        return view('career.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'education_level' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'job_description' => 'nullable|string',
            'job_requirements' => 'sometimes|array',
            'job_requirements.*' => 'string',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['job_description'] = $request->input('job_description', '');
        $validated['job_requirements'] = json_encode($request->input('job_requirements', []));

        // 📁 Upload Gambar
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('careers', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        Career::create($validated);

        return redirect()->route('career.index')->with('success', 'Career added successfully');
    }

    public function edit($id)
    {
        $career = Career::findOrFail($id);
        $career->status = $career->is_active ? 'Active' : 'Inactive';
        return view('career.edit', compact('career'));
    }

    public function update(Request $request, $id)
    {
        $career = Career::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'education_level' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'job_description' => 'nullable|string',
            'job_requirements' => 'sometimes|array',
            'job_requirements.*' => 'string',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['job_description'] = $request->input('job_description', '');
        $validated['job_requirements'] = json_encode($request->input('job_requirements', []));

        // 🖼️ Jika upload gambar baru
        if ($request->hasFile('image_path')) {
            if ($career->image_path && file_exists(public_path($career->image_path))) {
                unlink(public_path($career->image_path));
            }
            $path = $request->file('image_path')->store('careers', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        $career->update($validated);

        return redirect()->route('career.index')->with('success', 'Career updated successfully');
    }

    public function destroy($id)
    {
        $career = Career::findOrFail($id);

        if ($career->image_path && file_exists(public_path($career->image_path))) {
            unlink(public_path($career->image_path));
        }

        $career->delete();

        return redirect()->route('career.index')->with('success', 'Career deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        Career::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function exportExcel(Request $request)
    {
        $careers = Career::orderBy('created_at', 'desc')->get();

        $data = $careers->map(function ($career) {
            return [
                'ID' => $career->id,
                'Title' => $career->title,
                'Type' => $career->type,
                'Education Level' => $career->education_level,
                'Location' => $career->location,
                'Status' => $career->is_active ? 'Active' : 'Inactive',
                'Created At' => $career->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return Excel::download(new \App\Exports\ArrayExport($data), 'careers_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $careers = Career::orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('career.export-pdf', ['careers' => $careers]);
        return $pdf->download('careers_' . now()->format('Ymd_His') . '.pdf');
    }
}
