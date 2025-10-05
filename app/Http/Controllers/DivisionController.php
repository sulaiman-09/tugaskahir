<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::latest()->paginate(10);
        return view('division.index', compact('divisions'));
    }

    public function create()
    {
        return view('division.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'customer_leads' => 'nullable|integer',
        ]);

        Division::create($request->all());
        return redirect()->route('division.index')->with('success', 'Division added successfully.');
    }

    public function edit(Division $division)
    {
        return view('division.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'customer_leads' => 'nullable|integer',
        ]);

        $division->update($request->all());
        return redirect()->route('division.index')->with('success', 'Division updated successfully.');
    }

    public function destroy(Division $division)
    {
        $division->delete();
        return redirect()->route('division.index')->with('success', 'Division deleted successfully.');
    }
}
