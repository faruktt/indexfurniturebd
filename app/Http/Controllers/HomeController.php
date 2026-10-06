<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Solution;
use App\Models\Brand;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Dynamic Sliders
        $sliders = Slider::where('status', true)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        // 1.1 Dynamic Homepage Solutions
        $solutions = Solution::with('category')
            ->where('status', true)
            ->orderBy('order', 'asc')
            ->get();

        // 2. All Categories for Mega-Menu
        $categories = Category::with(['subCategories.childCategories'])
            ->where('status', true)
            ->orderBy('order', 'asc')
            ->get();

        // 3. Popular Home Categories & Subcategories (STRICTLY based on is_popular_home = true)
        $popularHomeCategories = Category::where('status', true)
            ->where('is_popular_home', true)
            ->orderBy('order', 'asc')
            ->get();

        $popularHomeCategoryIds = $popularHomeCategories->pluck('id')->toArray();

        $popularHomeSubcategories = empty($popularHomeCategoryIds)
            ? collect([])
            : SubCategory::with('category')
                ->where('status', true)
                ->whereIn('category_id', $popularHomeCategoryIds)
                ->orderBy('order', 'asc')
                ->get();

        // 4. Popular Office Categories, Subcategories & Childcategories (STRICTLY based on is_popular_office = true)
        $popularOfficeCategories = Category::where('status', true)
            ->where('is_popular_office', true)
            ->orderBy('order', 'asc')
            ->get();

        $popularOfficeCategoryIds = $popularOfficeCategories->pluck('id')->toArray();

        $popularOfficeSubcategories = empty($popularOfficeCategoryIds)
            ? collect([])
            : SubCategory::with('category')
                ->where('status', true)
                ->whereIn('category_id', $popularOfficeCategoryIds)
                ->orderBy('order', 'asc')
                ->get();

        $popularOfficeChildcategories = empty($popularOfficeCategoryIds)
            ? collect([])
            : ChildCategory::with(['category', 'subCategory'])
                ->where('status', true)
                ->whereIn('category_id', $popularOfficeCategoryIds)
                ->orderBy('order', 'asc')
                ->get();

        // 5. Featured Room Categories (Grid of 6)
        $featuredCategories = Category::where('status', true)
            ->where('is_featured', true)
            ->orderBy('order', 'asc')
            ->take(6)
            ->get();

        // 6. Featured Products
        $featuredProducts = Product::with(['category', 'subCategory', 'childCategory', 'colors', 'sizes'])
            ->where('status', true)
            ->where('is_featured', true)
            ->take(8)
            ->get();

        // 7. Popular Products
        $popularProducts = Product::with(['category', 'subCategory', 'childCategory', 'colors', 'sizes'])
            ->where('status', true)
            ->where('is_popular', true)
            ->take(8)
            ->get();

        // 7.1 Brand Portfolio
        $brands = Brand::where('status', true)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // 8. Site Settings & Social Media
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();

        return view('frontend.home', compact(
            'sliders',
            'solutions',
            'brands',
            'categories',
            'popularHomeCategories',
            'popularHomeSubcategories',
            'popularOfficeCategories',
            'popularOfficeSubcategories',
            'popularOfficeChildcategories',
            'featuredCategories',
            'featuredProducts',
            'popularProducts',
            'settings'
        ));
    }
}
