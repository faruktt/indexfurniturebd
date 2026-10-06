<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Overall Key Metrics
        $totalSales = Order::where('order_status', '!=', 'cancelled')->sum('grand_total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $processingOrders = Order::where('order_status', 'processing')->count();
        $deliveredOrders = Order::where('order_status', 'delivered')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::count();
        $lowStockCount = Product::where('stock', '<=', 5)->count();

        // 2. Order Time-Based Metrics (Today, Yesterday, This Month, This Year)
        $todayOrdersCount = Order::whereDate('created_at', Carbon::today())->count();
        $todaySales = Order::whereDate('created_at', Carbon::today())->where('order_status', '!=', 'cancelled')->sum('grand_total');

        $yesterdayOrdersCount = Order::whereDate('created_at', Carbon::yesterday())->count();
        $yesterdaySales = Order::whereDate('created_at', Carbon::yesterday())->where('order_status', '!=', 'cancelled')->sum('grand_total');

        $thisMonthOrdersCount = Order::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->count();
        $thisMonthSales = Order::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->where('order_status', '!=', 'cancelled')->sum('grand_total');

        $thisYearOrdersCount = Order::whereYear('created_at', Carbon::now()->year)->count();
        $thisYearSales = Order::whereYear('created_at', Carbon::now()->year)->where('order_status', '!=', 'cancelled')->sum('grand_total');

        // 3. Catalog Hierarchy Metrics
        $totalCategories = Category::count();
        $totalSubCategories = SubCategory::count();
        $totalChildCategories = ChildCategory::count();
        $totalBrands = Brand::count();

        // 4. Chart 1: Monthly Sales & Order Trends (Last 6 Months)
        $chartMonthlyLabels = [];
        $chartMonthlySales = [];
        $chartMonthlyOrders = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $chartMonthlyLabels[] = $monthDate->format('M Y');
            $sales = Order::where('order_status', '!=', 'cancelled')
                ->whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->sum('grand_total');
            $count = Order::whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->count();

            $chartMonthlySales[] = (float) $sales;
            $chartMonthlyOrders[] = $count;
        }

        // 5. Chart 2: Order Fulfillment Status Distribution
        $configuredStatuses = OrderStatus::where('status', true)->orderBy('order', 'asc')->get();
        if ($configuredStatuses->isEmpty()) {
            $configuredStatuses = collect([
                (object)['name' => 'Pending', 'slug' => 'pending', 'text_color' => '#d97706'],
                (object)['name' => 'Processing', 'slug' => 'processing', 'text_color' => '#0284c7'],
                (object)['name' => 'Shipped', 'slug' => 'shipped', 'text_color' => '#7e22ce'],
                (object)['name' => 'Delivered', 'slug' => 'delivered', 'text_color' => '#16a34a'],
                (object)['name' => 'Cancelled', 'slug' => 'cancelled', 'text_color' => '#dc2626'],
            ]);
        }

        $chartStatusLabels = [];
        $chartStatusCounts = [];
        $chartStatusColors = [];
        $defaultPalette = ['#d97706', '#0284c7', '#7e22ce', '#16a34a', '#dc2626', '#ea580c', '#0d9488'];
        $colorIdx = 0;

        foreach ($configuredStatuses as $st) {
            $chartStatusLabels[] = $st->name;
            $chartStatusCounts[] = Order::where('order_status', $st->slug)->count();
            $chartStatusColors[] = !empty($st->text_color) && $st->text_color !== '#334155' 
                ? $st->text_color 
                : ($defaultPalette[$colorIdx % count($defaultPalette)]);
            $colorIdx++;
        }

        // 6. Recent Orders & Featured Products
        $recentOrders = Order::with('items')->latest()->take(6)->get();
        $recentProducts = Product::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'deliveredOrders',
            'totalProducts',
            'totalCustomers',
            'lowStockCount',
            'todayOrdersCount',
            'todaySales',
            'yesterdayOrdersCount',
            'yesterdaySales',
            'thisMonthOrdersCount',
            'thisMonthSales',
            'thisYearOrdersCount',
            'thisYearSales',
            'totalCategories',
            'totalSubCategories',
            'totalChildCategories',
            'totalBrands',
            'chartMonthlyLabels',
            'chartMonthlySales',
            'chartMonthlyOrders',
            'chartStatusLabels',
            'chartStatusCounts',
            'chartStatusColors',
            'recentOrders',
            'recentProducts'
        ));
    }
}
