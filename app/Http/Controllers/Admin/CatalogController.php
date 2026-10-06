<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    public function index()
    {
        $catalogs = Catalog::orderBy('order', 'asc')->latest()->paginate(15);
        return view('admin.catalogs.index', compact('catalogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'pdf_file' => 'required|file|mimes:pdf|max:30720',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
        ]);

        $filename = null;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'catalog_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
        }

        Catalog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'pdf_file' => $filename,
            'description' => $request->description,
            'status' => $request->has('status'),
            'order' => $request->order ?? 0,
        ]);

        return back()->with('success', 'E-Catalog uploaded successfully.');
    }

    public function update(Request $request, Catalog $catalog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'pdf_file' => 'nullable|file|mimes:pdf|max:30720',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'status' => $request->has('status'),
            'order' => $request->order ?? 0,
        ];

        if ($request->hasFile('pdf_file')) {
            if ($catalog->pdf_file && file_exists(public_path('uploads/' . $catalog->pdf_file))) {
                @unlink(public_path('uploads/' . $catalog->pdf_file));
            }

            $file = $request->file('pdf_file');
            $filename = 'catalog_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['pdf_file'] = $filename;
        }

        $catalog->update($data);

        return back()->with('success', 'E-Catalog updated successfully.');
    }

    public function destroy(Catalog $catalog)
    {
        if ($catalog->pdf_file && file_exists(public_path('uploads/' . $catalog->pdf_file))) {
            @unlink(public_path('uploads/' . $catalog->pdf_file));
        }

        $catalog->delete();

        return back()->with('success', 'E-Catalog deleted.');
    }

    public function toggle(Catalog $catalog)
    {
        $catalog->status = !$catalog->status;
        $catalog->save();

        return back()->with('success', 'E-Catalog status updated.');
    }
}
