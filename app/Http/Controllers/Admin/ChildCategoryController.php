<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChildCategoryController extends Controller
{
    public function index()
    {
        $childCategories = ChildCategory::with(['category', 'subCategory'])->orderBy('order', 'asc')->paginate(10);
        return view('admin.childcategories.index', compact('childCategories'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->orderBy('name', 'asc')->get();
        return view('admin.childcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        ChildCategory::create($data);

        return redirect()->route('admin.childcategories.index')->with('success', 'Child category created successfully.');
    }

    public function edit(ChildCategory $childcategory)
    {
        $categories = Category::where('status', true)->orderBy('name', 'asc')->get();
        $subCategories = SubCategory::where('category_id', $childcategory->category_id)->get();
        return view('admin.childcategories.edit', compact('childcategory', 'categories', 'subCategories'));
    }

    public function update(Request $request, ChildCategory $childcategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
        ];

        if ($request->hasFile('image')) {
            if ($childcategory->image && file_exists(public_path('uploads/' . $childcategory->image))) {
                @unlink(public_path('uploads/' . $childcategory->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $childcategory->update($data);

        return redirect()->route('admin.childcategories.index')->with('success', 'Child category updated successfully.');
    }

    public function destroy(ChildCategory $childcategory)
    {
        if ($childcategory->image && file_exists(public_path('uploads/' . $childcategory->image))) {
            @unlink(public_path('uploads/' . $childcategory->image));
        }

        $childcategory->delete();
        return redirect()->route('admin.childcategories.index')->with('success', 'Child category deleted successfully.');
    }

    public function getBySubCategory($subCategoryId)
    {
        $childCategories = ChildCategory::where('sub_category_id', $subCategoryId)->where('status', true)->orderBy('name', 'asc')->get();
        return response()->json($childCategories);
    }
}
