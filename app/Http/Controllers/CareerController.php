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

        // Tambahkan properti virtual 'status'
        $careers->getCollection()->transform(function ($career) {
            // Misal kolom di DB = is_active (1=Active,0=Inactive)
            $career->status = $career->is_active ? 'Active' : 'Inactive';
            return $career;
        });

        return view('career.index', compact('careers'));
    }

    public function edit($id)
    {
        $career = Career::findOrFail($id);

        // Tambahkan properti virtual 'status' juga di edit form
        $career->status = $career->is_active ? 'Active' : 'Inactive';

        return view('career.edit', compact('career'));
    }

    public function update(Request $request, $id)
    {
        $career = Career::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'education_level' => 'nullable|string|max:255', // sesuaikan nama kolom DB
            'location' => 'nullable|string|max:255',
            'status' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Konversi status menjadi kolom DB
        $validated['is_active'] = $validated['status'] === 'Active' ? 1 : 0;
        unset($validated['status']); // hapus key status supaya tidak error

        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/career'), $fileName);
            $validated['image'] = 'uploads/career/' . $fileName;
        }

        $career->update($validated);

        return redirect()->route('career.index')->with('success', 'Career updated successfully.');
    }

    public function destroy($id)
    {
        $career = Career::findOrFail($id);

        // Hapus file image jika ada
        if ($career->image && file_exists(public_path($career->image))) {
            unlink(public_path($career->image));
        }

        // Hapus data career
        $career->delete();

        return redirect()->route('career.index')->with('success', 'Career deleted successfully.');
    }
}
