<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NewsController extends Controller
{
    /**
     * Tampilkan daftar berita.
     */
    public function index(Request $request)
    {
        $q = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $showAll = strtolower($perPage) === 'all';

        $query = News::orderBy('news_created_date', 'desc');

        // 🔍 Search
        if ($q) {
            $query->where('news_title', 'like', "%{$q}%")
                ->orWhere('news_content', 'like', "%{$q}%");
        }

        // 🧾 Pagination / All
        if ($showAll) {
            $news = $query->get();
        } else {
            $news = $query->paginate((int)$perPage)->withQueryString();
        }

        return view('news.index', compact('news', 'perPage', 'q', 'showAll'));
    }

    /**
     * Tampilkan form tambah berita.
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Simpan berita baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'news_title' => 'required|string|max:255',
            'news_content' => 'nullable|string',
            'news_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'news_image_app' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'news_image_caption' => 'nullable|string|max:255',
            'admin' => 'nullable|string|max:255',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('news_image')) {
            $validated['news_image'] = $request->file('news_image')->store('uploads/news', 'public');
        }

        if ($request->hasFile('news_image_app')) {
            $validated['news_image_app'] = $request->file('news_image_app')->store('uploads/news', 'public');
        }

        // Tambahkan kolom wajib agar tidak error
        $validated['news_user_id'] = Auth::id();           // user yang membuat berita
        $validated['admin'] = Auth::user()->name;          // nama admin
        $validated['news_created_date'] = now();           // tanggal dibuat

        News::create($validated);

        return redirect()->route('news.index')->with('success', 'News added successfully!');
    }

    /**
     * Tampilkan form edit berita.
     */
    public function edit($id)
    {
        $news = News::where('news_id', $id)->firstOrFail();
        return view('news.edit', compact('news'));
    }

    /**
     * Update data berita.
     */
    public function update(Request $request, $id)
    {
        $news = News::where('news_id', $id)->firstOrFail();

        $validated = $request->validate([
            'news_title' => 'required|string|max:255',
            'news_content' => 'nullable|string',
            'news_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'news_image_app' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'news_image_caption' => 'nullable|string|max:255',
            'admin' => 'nullable|string|max:255',
        ]);

        // Hapus dan ganti gambar utama jika upload baru
        if ($request->hasFile('news_image')) {
            if ($news->news_image && Storage::disk('public')->exists($news->news_image)) {
                Storage::disk('public')->delete($news->news_image);
            }
            $validated['news_image'] = $request->file('news_image')->store('uploads/news', 'public');
        }

        // Hapus dan ganti gambar app jika upload baru
        if ($request->hasFile('news_image_app')) {
            if ($news->news_image_app && Storage::disk('public')->exists($news->news_image_app)) {
                Storage::disk('public')->delete($news->news_image_app);
            }
            $validated['news_image_app'] = $request->file('news_image_app')->store('uploads/news', 'public');
        }

        // Pastikan kolom admin dan user tetap tersimpan
        $validated['news_user_id'] = $news->news_user_id;   // jangan diubah
        $validated['admin'] = $news->admin;                 // jangan diubah
        $validated['news_created_date'] = $news->news_created_date; // jangan diubah

        $news->update($validated);

        return redirect()->route('news.index')->with('success', 'News updated successfully!');
    }

    /**
     * Hapus berita beserta gambar dari storage.
     */
    public function destroy($id)
    {
        $news = News::where('news_id', $id)->firstOrFail();

        // Hapus file gambar jika ada
        if ($news->news_image && Storage::disk('public')->exists($news->news_image)) {
            Storage::disk('public')->delete($news->news_image);
        }

        if ($news->news_image_app && Storage::disk('public')->exists($news->news_image_app)) {
            Storage::disk('public')->delete($news->news_image_app);
        }

        $news->delete();

        return redirect()->route('news.index')->with('success', 'News deleted successfully!');
    }

    public function exportCsv()
    {
        $fileName = 'news_' . date('Y-m-d_H-i-s') . '.csv';
        $newsList = News::select('news_title', 'news_image_caption', 'news_user_id', 'news_created_date')->get();

        $response = new StreamedResponse(function () use ($newsList) {
            $handle = fopen('php://output', 'w');
            // Header CSV
            fputcsv($handle, ['Title', 'Image Caption', 'Created By (User ID)', 'Created Date']);

            foreach ($newsList as $news) {
                fputcsv($handle, [
                    $news->news_title,
                    $news->news_image_caption,
                    $news->news_user_id,
                    $news->news_created_date,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }


    public function exportXlsx()
    {
        $fileName = 'news_' . date('Y-m-d_H-i-s') . '.xlsx';
        $newsList = News::select('news_title', 'news_image_caption', 'news_user_id', 'news_created_date')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray(['Title', 'Image Caption', 'Created By (User ID)', 'Created Date'], null, 'A1');

        // Data
        $row = 2;
        foreach ($newsList as $news) {
            $sheet->setCellValue('A' . $row, $news->news_title);
            $sheet->setCellValue('B' . $row, $news->news_image_caption);
            $sheet->setCellValue('C' . $row, $news->news_user_id);
            $sheet->setCellValue('D' . $row, $news->news_created_date);
            $row++;
        }

        // Auto size
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'news_export_');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
