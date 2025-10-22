<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $query = Career::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        }

        $careers = $query->latest()->paginate(10);

        $careers->getCollection()->transform(function ($career) {
            $career->status = $career->is_active ? 'Active' : 'Inactive';
            return $career;
        });

        return view('career.index', compact('careers'));
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
            'overview' => 'nullable|string',
            'description' => 'required|string',
            'job_requirements' => 'sometimes|array',
            'job_requirements.*' => 'string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        // Pastikan checkbox is_active selalu ada
        $validated['is_active'] = $request->has('is_active');

        // Job requirements harus array, default kosong jika tidak ada
        $validated['job_requirements'] = $request->input('job_requirements', []);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('careers', 'public');
            $validated['image'] = 'storage/' . $path;
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
            'overview' => 'nullable|string',
            'description' => 'required|string',
            'job_requirements' => 'sometimes|array',
            'job_requirements.*' => 'string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['job_requirements'] = $request->input('job_requirements', []);

        // Handle image upload dan hapus file lama jika ada
        if ($request->hasFile('image')) {
            if ($career->image && file_exists(public_path($career->image))) {
                unlink(public_path($career->image));
            }
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
