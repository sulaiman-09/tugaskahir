<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingsContent;

class SettingsContentController extends Controller
{
    // Tampilkan index
    public function index()
    {
        $contents = SettingsContent::orderBy('order')->get();
        return view('settingscontent.index', compact('contents'));
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $content = SettingsContent::findOrFail($id);
        return view('settingscontent.edit', compact('content'));
    }

    // Proses update
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'content_type_id' => 'nullable|integer',
            'order' => 'nullable|integer',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $content = SettingsContent::findOrFail($id);
        $content->update([
            'title' => $request->title,
            'name' => $request->name,
            'content_type_id' => $request->content_type_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        if ($request->hasFile('image')) {
        $path = $request->file('image')->store('content_sections', 'public');
        $section->image_path = $path;
    }
    $content->save();

        return redirect()->route('settings-content.index')->with('success', 'Content updated successfully!');
    }
}
