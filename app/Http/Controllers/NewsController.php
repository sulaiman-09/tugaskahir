<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('news.index', compact('news'));
    }

    public function create()
    {
        return view('news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'news_title' => 'required|string|max:255',
            'news_content' => 'nullable|string',
            'news_image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $data = $request->all();

        if ($request->hasFile('news_image')) {
            $data['news_image'] = $request->file('news_image')->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('news.index')->with('success', 'News added successfully!');
    }

    public function edit($id)
    {
        $news = News::where('news_id', $id)->firstOrFail();
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::where('news_id', $id)->firstOrFail();

        $validated = $request->validate([
            'news_title' => 'required|string|max:255',
            'news_content' => 'required|string',
            'news_image' => 'nullable|image',
            'news_image_app' => 'nullable|image',
            'news_image_caption' => 'nullable|string|max:255',
            'admin' => 'nullable|string|max:255',
        ]);

        // update gambar jika ada upload baru
        if ($request->hasFile('news_image')) {
            $validated['news_image'] = $request->file('news_image')->store('uploads/news', 'public');
        }

        if ($request->hasFile('news_image_app')) {
            $validated['news_image_app'] = $request->file('news_image_app')->store('uploads/news', 'public');
        }

        $news->update($validated);

        return redirect()->route('news.index')->with('success', 'News updated successfully!');
    }

    public function destroy($id)
    {
        $news = News::where('news_id', $id)->firstOrFail();
        $news->delete();

        return redirect()->route('news.index')->with('success', 'News deleted successfully!');
    }
}
