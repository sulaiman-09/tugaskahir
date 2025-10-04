<?php

namespace App\Http\Controllers;

use App\Models\SudirmanPark;
use Illuminate\Http\Request;

class SudirmanParkController extends Controller
{
    public function index()
    {
        $customers = SudirmanPark::latest()->get();
        return view('sudirmanpark.index', compact('customers'));
    }

    public function create()
    {
        return view('sudirmanpark.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'tower' => 'required|string|max:255',
            'package' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'note' => 'nullable|string',
        ]);

        if ($request->hasFile('ktp')) {
            $fileName = time() . '.' . $request->ktp->extension();
            $request->ktp->move(public_path('uploads/ktp'), $fileName);
            $validated['ktp'] = $fileName;
        }

        SudirmanPark::create($validated);

        return redirect()->route('sudirmanpark.index')
                         ->with('success', 'Customer baru berhasil ditambahkan.');
    }
}
