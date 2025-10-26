<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $query = Career::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Records per page
        $perPage = $request->query('per_page', 10); // default 10
        if ($perPage === 'all') {
            $careers = $query->latest()->get();
        } else {
            $perPage = (int)$perPage;
            $careers = $query->latest()->paginate($perPage)->withQueryString();
        }

        // Tambahkan status string
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
            'job_requirements' => 'sometimes|array',
            'job_requirements.*' => 'string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            // ubah rule ini ↓
            'is_active' => 'nullable',
        ]);

        // Pastikan checkbox jadi boolean
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $validated['job_requirements'] = $request->input('job_requirements', []);
        $validated['slug'] = Str::slug($validated['title']);
        $validated['job_description'] = $validated['description'];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('careers', 'public');
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'education_level' => 'required|string|in:SMA/SMK,Diploma,S1,S2,S3',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'job_requirements' => 'sometimes|array',
            'job_requirements.*' => 'string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_active' => 'nullable',
        ]);

        $career = Career::findOrFail($id);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['job_requirements'] = $request->input('job_requirements', []);
        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('careers', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $career->update($validated);

        return redirect()->route('career.index')->with('success', 'Career updated successfully');
    }

    public function destroy($id)
    {
        $career = Career::findOrFail($id);

        if ($career->image && file_exists(public_path($career->image))) {
            unlink(public_path($career->image));
        }

        $career->delete();

        return redirect()->route('career.index')->with('success', 'Career deleted successfully.');
    }
}
