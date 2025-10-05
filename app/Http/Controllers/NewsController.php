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

    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'news_title' => 'required|string|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('news_image')) {
            $data['news_image'] = $request->file('news_image')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('news.index')->with('success', 'News updated successfully!');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('news.index')->with('success', 'News deleted successfully!');
    }
}
