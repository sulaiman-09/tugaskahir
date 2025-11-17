<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingsContent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SettingsContentController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $showAll = strtolower($perPage) === 'all';

        $query = SettingsContent::orderBy('order');

        // 🔍 Search
        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
        }

        // 🧾 Pagination / All
        if ($showAll) {
            $contents = $query->get();
        } else {
            $contents = $query->paginate((int)$perPage)->withQueryString();
        }

        return view('settingscontent.index', compact('contents', 'perPage', 'search', 'showAll'));
    }

    // CREATE FORM
    public function create()
    {
        return view('settingscontent.create');
    }

    // STORE DATA
    public function store(Request $request)
    {
        $request->validate([
            'content_type_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['content_type_id', 'title', 'name', 'description', 'order']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('content/images', 'public');
        }

        if ($request->hasFile('icon')) {
            $data['icon_path'] = $request->file('icon')->store('content/icons', 'public');
        }

        SettingsContent::create($data);

        return redirect()->route('settings-content.index')
            ->with('success', 'Content created successfully!');
    }

    // EDIT FORM
    public function edit($id)
    {
        $content = SettingsContent::findOrFail($id);
        return view('settingscontent.edit', compact('content'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'content_type_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $content = SettingsContent::findOrFail($id);
        $data = $request->only(['content_type_id', 'title', 'name', 'description', 'order']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Update Image
        if ($request->hasFile('image')) {
            if ($content->image_path && Storage::disk('public')->exists($content->image_path)) {
                Storage::disk('public')->delete($content->image_path);
            }

            $data['image_path'] = $request->file('image')->store('content/images', 'public');
        }

        // Update Icon
        if ($request->hasFile('icon')) {
            if ($content->icon_path && Storage::disk('public')->exists($content->icon_path)) {
                Storage::disk('public')->delete($content->icon_path);
            }

            $data['icon_path'] = $request->file('icon')->store('content/icons', 'public');
        }

        $content->update($data);

        return redirect()->route('settings-content.index')
            ->with('success', 'Content updated successfully!');
    }

    // DELETE
    public function destroy($id)
    {
        $content = SettingsContent::findOrFail($id);

        if ($content->image && Storage::disk('public')->exists($content->image)) {
            Storage::disk('public')->delete($content->image);
        }
        if ($content->icon && Storage::disk('public')->exists($content->icon)) {
            Storage::disk('public')->delete($content->icon);
        }

        $content->delete();
        return redirect()->route('settings-content.index')->with('success', 'Content deleted successfully!');
    }

    // ✅ Export Excel
    public function exportExcel()
    {
        $contents = \App\Models\SettingsContent::orderBy('order')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray(['ID', 'Title', 'Name', 'Type ID', 'Order', 'Status'], null, 'A1');

        // Data
        $row = 2;
        foreach ($contents as $c) {
            $sheet->fromArray([
                $c->id,
                $c->title,
                $c->name,
                $c->content_type_id,
                $c->order,
                $c->is_active ? 'Active' : 'Inactive',
            ], null, "A{$row}");
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'settings_content_' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'settings_content_');
        (new Xlsx($spreadsheet))->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    // ✅ Export PDF
    public function exportPdf()
    {
        $contents = \App\Models\SettingsContent::orderBy('order')->get();

        $pdf = Pdf::loadView('settingscontent.export-pdf', compact('contents'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('settings_content_' . now()->format('Ymd_His') . '.pdf');
    }


    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) {
            return response()->json(['success' => false, 'message' => 'No selected contents.']);
        }

        SettingsContent::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' contents deleted successfully.'
        ]);
    }
}
