<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('subCategories')->where('status', true)->orderBy('order', 'asc')->get();

        $query = Product::with(['category', 'colors'])->where('status', true);

        $currentCategory = null;
        $currentSubCategory = null;
        $currentChildCategory = null;

        if ($request->filled('category')) {
            $currentCategory = Category::where('slug', $request->category)->first();
            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
            }
        }

        if ($request->filled('subcategory')) {
            $currentSubCategory = SubCategory::where('slug', $request->subcategory)->first();
            if ($currentSubCategory) {
                $query->where('sub_category_id', $currentSubCategory->id);
            }
        }

        if ($request->filled('childcategory')) {
            $currentChildCategory = ChildCategory::where('slug', $request->childcategory)->first();
            if ($currentChildCategory) {
                $query->where('child_category_id', $currentChildCategory->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low_high':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high_low':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(16)->withQueryString();

        return view('frontend.product.index', compact(
            'products',
            'categories',
            'currentCategory',
            'currentSubCategory',
            'currentChildCategory'
        ));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'subCategory', 'childCategory', 'colors', 'sizes', 'images'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        // Suggested furniture from same category or subcategory
        $suggestedProducts = Product::where('status', true)
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($product) {
                $q->where('category_id', $product->category_id)
                  ->orWhere('sub_category_id', $product->sub_category_id);
            })
            ->take(6)
            ->get();

        if ($suggestedProducts->count() < 4) {
            $additional = Product::where('status', true)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $suggestedProducts->pluck('id'))
                ->take(6 - $suggestedProducts->count())
                ->get();
            $suggestedProducts = $suggestedProducts->merge($additional);
        }

        return view('frontend.product.show', compact('product', 'suggestedProducts'));
    }
}
