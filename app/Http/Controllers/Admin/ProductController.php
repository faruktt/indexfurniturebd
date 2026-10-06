<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subCategory']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('product_code', 'like', "%{$request->search}%");
            });
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::where('status', true)->orderBy('name', 'asc')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->orderBy('name', 'asc')->get();
        $colors = Color::all();
        $sizes = Size::all();

        return view('admin.products.create', compact('categories', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'child_category_id' => 'nullable|exists:child_categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'dimension' => 'nullable|string|max:255',
            'warranty' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        $data = $request->only([
            'title', 'product_code', 'category_id', 'sub_category_id', 'child_category_id',
            'price', 'discount_price', 'stock', 'dimension', 'warranty', 'short_description', 'description'
        ]);

        $slug = Str::slug($request->title);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        $data['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;

        $data['is_featured'] = $request->has('is_featured');
        $data['is_popular'] = $request->has('is_popular');
        $data['cash_on_delivery'] = $request->has('cash_on_delivery');
        $data['status'] = $request->has('status');

        // Main Image upload: direct to public/uploads, store filename only
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $product = Product::create($data);

        // Colors & Sizes
        if ($request->filled('colors')) {
            $product->colors()->sync($request->colors);
        }

        if ($request->filled('sizes')) {
            $product->sizes()->sync($request->sizes);
        }

        // Gallery Images upload: direct to public/uploads, store filename only
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $gFile) {
                $gFilename = time() . '_' . uniqid() . '.' . $gFile->getClientOriginalExtension();
                $gFile->move(public_path('uploads'), $gFilename);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $gFilename,
                    'order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->orderBy('name', 'asc')->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $childCategories = ChildCategory::where('sub_category_id', $product->sub_category_id)->get();
        $colors = Color::all();
        $sizes = Size::all();

        return view('admin.products.edit', compact(
            'product', 'categories', 'subCategories', 'childCategories', 'colors', 'sizes'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'child_category_id' => 'nullable|exists:child_categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'dimension' => 'nullable|string|max:255',
            'warranty' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        $data = $request->only([
            'title', 'product_code', 'category_id', 'sub_category_id', 'child_category_id',
            'price', 'discount_price', 'stock', 'dimension', 'warranty', 'short_description', 'description'
        ]);

        $data['is_featured'] = $request->has('is_featured');
        $data['is_popular'] = $request->has('is_popular');
        $data['cash_on_delivery'] = $request->has('cash_on_delivery');
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            // Delete old file
            if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
                @unlink(public_path('uploads/' . $product->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $product->update($data);

        $product->colors()->sync($request->colors ?? []);
        $product->sizes()->sync($request->sizes ?? []);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $gFile) {
                $gFilename = time() . '_' . uniqid() . '.' . $gFile->getClientOriginalExtension();
                $gFile->move(public_path('uploads'), $gFilename);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $gFilename,
                    'order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
            @unlink(public_path('uploads/' . $product->image));
        }

        foreach ($product->images as $pImg) {
            if ($pImg->image && file_exists(public_path('uploads/' . $pImg->image))) {
                @unlink(public_path('uploads/' . $pImg->image));
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function deleteGalleryImage($id)
    {
        $image = ProductImage::findOrFail($id);
        if ($image->image && file_exists(public_path('uploads/' . $image->image))) {
            @unlink(public_path('uploads/' . $image->image));
        }
        $image->delete();

        return response()->json(['success' => true]);
    }
}
