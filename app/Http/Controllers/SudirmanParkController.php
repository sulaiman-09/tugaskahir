<?php

namespace App\Http\Controllers;

use App\Models\SudirmanPark;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SudirmanParkController extends Controller
{
    public function index()
    {
        $q = request('q');
        $showAll = request('show_all') == '1';

        $query = SudirmanPark::query();
        if (!$showAll) {
            // default only visible
            $query->where('visible', true);
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('ktp', 'like', "%{$q}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('sudirmanpark.index', compact('customers', 'q', 'showAll'));
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
            'ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'note' => 'nullable|string',
        ]);

        if ($request->hasFile('ktp')) {
            $file = $request->file('ktp');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/ktp', $filename);
            // store only filename (or use Storage::url later in views)
            $validated['ktp'] = $filename;
        }

        $validated['visible'] = $request->has('visible') ? true : true; // default visible

        SudirmanPark::create($validated);

        return redirect()->route('sudirmanpark.index')
            ->with('success', 'Customer baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $customer = SudirmanPark::findOrFail($id);
        return view('sudirmanpark.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = SudirmanPark::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'tower' => 'required|string|max:255',
            'package' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'note' => 'nullable|string',
        ]);

        if ($request->hasFile('ktp')) {
            // delete old file if exists
            if ($customer->ktp) {
                Storage::delete('public/ktp/' . $customer->ktp);
            }
            $file = $request->file('ktp');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/ktp', $filename);
            $validated['ktp'] = $filename;
        }

        $validated['visible'] = $request->has('visible') ? true : false;

        $customer->update($validated);

        return redirect()->route('sudirmanpark.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $customer = SudirmanPark::findOrFail($id);
        if ($customer->ktp) {
            Storage::delete('public/ktp/' . $customer->ktp);
        }
        $customer->delete();
        return back()->with('success', 'Customer dihapus.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $customer = SudirmanPark::findOrFail($id);
        $customer->status = $request->input('status', $customer->status == 'approved' ? 'registration' : 'approved');
        $customer->save();
        return response()->json(['status' => $customer->status]);
    }

    public function export(Request $request): StreamedResponse
    {
        $q = $request->query('q');
        $showAll = $request->query('show_all') == '1';

        $query = SudirmanPark::query();
        if (!$showAll) {
            $query->where('visible', true);
        }
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $items = $query->latest()->get();

        $filename = 'sudirmanpark_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Phone', 'Email', 'Tower', 'Package', 'KTP', 'Status', 'Visible', 'Created At']);
            foreach ($items as $i) {
                fputcsv($out, [
                    $i->id,
                    $i->name,
                    $i->phone,
                    $i->email,
                    $i->tower,
                    $i->package,
                    $i->ktp ? asset('storage/ktp/' . $i->ktp) : '',
                    $i->status,
                    $i->visible ? '1' : '0',
                    $i->created_at,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function alamat()
    {
        return view('sudirmanpark.alamat');
    }

    public function updateStatus(Request $request, $id)
    {
        $customer = SudirmanPark::findOrFail($id);
        $customer->status = $request->status;
        $customer->save();

        return response()->json(['success' => true]);
    }
}
