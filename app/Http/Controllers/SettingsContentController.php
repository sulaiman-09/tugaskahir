<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingsContent;

class SettingsContentController extends Controller
{
    /**
     * Tampilkan daftar konten.
     */
    public function index()
    {
        $contents = SettingsContent::orderBy('order')->paginate(10);
        return view('settings.content.index', compact('contents'));
    }

    /**
     * Tampilkan form edit.
     */
    public function edit($id)
    {
        $content = SettingsContent::findOrFail($id);
        return view('settings.content.edit', compact('content'));
    }

    /**
     * Proses update data.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        $content = SettingsContent::findOrFail($id);
        $content->update($request->only(['title', 'name', 'type', 'order']));

        return redirect()->route('settings-content.index')
            ->with('success', 'Content updated successfully!');
    }

    /**
     * Toggle status aktif/nonaktif.
     */
    public function toggleStatus($id)
    {
        $content = SettingsContent::findOrFail($id);
        $content->status = !$content->status;
        $content->save();

        return response()->json(['success' => true]);
    }
}
