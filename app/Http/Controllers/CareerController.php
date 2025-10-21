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
            'title' => 'required',
            'type' => 'required',
            'education_level' => 'required',
            'location' => 'required',
            'description' => 'required',
            'job_requirements' => 'required',
            'is_active' => 'boolean',
        ]);

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
            'title' => 'required',
            'type' => 'required',
            'education_level' => 'required',
            'location' => 'required',
            'description' => 'required',
            'job_requirements' => 'required',
            'is_active' => 'boolean',
        ]);

        $career = Career::findOrFail($id);
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
