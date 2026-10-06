<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['subCategories', 'products'])->orderBy('order', 'asc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'order' => $request->order ?? 0,
            'is_popular_home' => $request->has('is_popular_home'),
            'is_popular_office' => $request->has('is_popular_office'),
            'is_featured' => $request->has('is_featured'),
            'status' => $request->has('status'),
        ];

        // Upload directly to public/uploads and store filename only
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'order' => $request->order ?? 0,
            'is_popular_home' => $request->has('is_popular_home'),
            'is_popular_office' => $request->has('is_popular_office'),
            'is_featured' => $request->has('is_featured'),
            'status' => $request->has('status'),
        ];

        if ($request->hasFile('image')) {
            // Remove old image if exists
            if ($category->image && file_exists(public_path('uploads/' . $category->image))) {
                @unlink(public_path('uploads/' . $category->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->image && file_exists(public_path('uploads/' . $category->image))) {
            @unlink(public_path('uploads/' . $category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
