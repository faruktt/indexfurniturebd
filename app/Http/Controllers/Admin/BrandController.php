<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'url' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
        ]);

        $data = [
            'name' => $request->name,
            'url' => $request->url,
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand portfolio logo created successfully.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'url' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
        ]);

        $data = [
            'name' => $request->name,
            'url' => $request->url,
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
        ];

        if ($request->hasFile('image')) {
            if ($brand->image && !str_starts_with($brand->image, 'otobi_brand_') && file_exists(public_path('uploads/' . $brand->image))) {
                @unlink(public_path('uploads/' . $brand->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand portfolio logo updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->image && !str_starts_with($brand->image, 'otobi_brand_') && file_exists(public_path('uploads/' . $brand->image))) {
            @unlink(public_path('uploads/' . $brand->image));
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand portfolio logo deleted successfully.');
    }

    public function toggle(Brand $brand)
    {
        $brand->status = !$brand->status;
        $brand->save();

        return back()->with('success', 'Brand status updated.');
    }
}
