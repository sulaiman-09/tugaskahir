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
            'content_type_id' => 'required|integer',
            'order' => 'required|integer',
        ]);

        $content = SettingsContent::findOrFail($id);
        $content->update([
            'title' => $request->title,
            'name' => $request->name,
            'content_type_id' => $request->content_type_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('settings-content.index')->with('success', 'Content updated successfully!');
    }
}
