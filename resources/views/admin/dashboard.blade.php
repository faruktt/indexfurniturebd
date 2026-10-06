@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Store Analytics & Overview')

@push('styles')
<style>
    /* Shopify-Style Compact High-Density Dashboard */
    :root {
        --sp-border: #e2e8f0;
        --sp-bg-card: #ffffff;
        --sp-text: #0f172a;
        --sp-muted: #64748b;
    }

    /* Top Compact Header Bar */
    .sp-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        border: 1px solid var(--sp-border);
        border-radius: 8px;
        padding: 10px 16px;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .sp-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sp-page-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sp-date-badge {
        font-size: 11px;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 500;
    }

    .sp-action-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sp-btn-sm {
        font-size: 11.5px;
        font-weight: 600;
        padding: 5px 11px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    /* 4-Column Grids */
    .sp-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 10px;
    }

    /* Compact Cards */
    .sp-card {
        background: #ffffff;
        border: 1px solid var(--sp-border);
        border-radius: 8px;
        padding: 10px 14px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-decoration: none;
        color: inherit;
        transition: all 0.18s ease;
    }

    .sp-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 3px 8px rgba(15, 23, 42, 0.05);
        transform: translateY(-1px);
    }

    .sp-card-interactive:hover {
        cursor: pointer;
    }

    .sp-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .sp-label {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.04em;
    }

    .sp-icon-box-sm {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .sp-val {
        font-size: 19px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        letter-spacing: -0.01em;
    }

    .sp-sub {
        font-size: 11px;
        color: #64748b;
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .sp-pill {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        text-transform: uppercase;
    }

    /* Colors */
    .theme-red { background: #fee2e2; color: #ea1d24; }
    .theme-blue { background: #e0f2fe; color: #0284c7; }
    .theme-amber { background: #fef3c7; color: #d97706; }
    .theme-green { background: #dcfce7; color: #16a34a; }
    .theme-purple { background: #f3e8ff; color: #9333ea; }
    .theme-indigo { background: #ede9fe; color: #4f46e5; }
    .theme-pink { background: #ffe4e6; color: #e11d48; }
    .theme-teal { background: #ccfbf1; color: #0d9488; }

    /* Compact Chart Row */
    .sp-chart-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 10px;
        margin-bottom: 12px;
    }

    .sp-chart-card {
        background: #ffffff;
        border: 1px solid var(--sp-border);
        border-radius: 8px;
        padding: 12px 14px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
        display: flex;
        flex-direction: column;
    }

    .sp-chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        padding-bottom: 6px;
        border-bottom: 1px solid #f1f5f9;
    }

    .sp-chart-title {
        font-size: 12.5px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sp-chart-legend-compact {
        display: flex;
        gap: 8px;
        font-size: 10.5px;
        font-weight: 600;
    }

    /* Status Grid inside chart card */
    .sp-status-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 6px;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #f1f5f9;
    }

    .sp-status-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 11px;
        color: #334155;
        background: #f8fafc;
        padding: 4px 8px;
        border-radius: 5px;
        border: 1px solid #f1f5f9;
    }

    .sp-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }

    /* Bottom Section */
    .sp-bottom-row {
        display: grid;
        grid-template-columns: 2.3fr 1fr;
        gap: 10px;
    }

    /* Compact Table */
    .sp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11.5px;
    }

    .sp-table th {
        background: #f8fafc;
        padding: 6px 10px;
        font-size: 10.5px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        border-bottom: 1px solid var(--sp-border);
        text-align: left;
    }

    .sp-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        vertical-align: middle;
    }

    .sp-table tr:hover td {
        background: #f8fafc;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .sp-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }
        .sp-chart-row {
            grid-template-columns: 1fr;
        }
        .sp-bottom-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .sp-grid-4 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

    <!-- 1. SLEEK SHOPIFY-STYLE TOP BAR -->
    <div class="sp-top-bar">
        <div class="sp-title-wrap">
            <h2 class="sp-page-title">
                <i class="fas fa-tachometer-alt" style="color: var(--admin-primary);"></i>
                Store Analytics & Overview
            </h2>
            <span class="sp-date-badge">
                <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::now()->format('D, d M Y') }}
            </span>
            <span class="sp-pill" style="background: #dcfce7; color: #16a34a;">
                <i class="fas fa-circle" style="font-size: 7px; margin-right: 2px;"></i> Live
            </span>
        </div>

        <div class="sp-action-buttons">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary sp-btn-sm">
                <i class="fas fa-plus"></i> New Product
            </a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-dark sp-btn-sm">
                <i class="fas fa-folder-plus"></i> Category
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline sp-btn-sm">
                <i class="fas fa-receipt"></i> Orders ({{ $totalOrders }})
            </a>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline sp-btn-sm" title="Open storefront">
                <i class="fas fa-external-link-alt"></i> Store
            </a>
        </div>
    </div>

    <!-- 2. PRIMARY 4 STORE METRICS (COMPACT & DENSE) -->
    <div class="sp-grid-4">
        <!-- Revenue -->
        <div class="sp-card">
            <div class="sp-card-header">
                <span class="sp-label">Total Revenue</span>
                <div class="sp-icon-box-sm theme-red"><i class="fas fa-coins"></i></div>
            </div>
            <div class="sp-val" style="color: #ea1d24;">BDT {{ number_format($totalSales, 0) }}</div>
            <div class="sp-sub"><i class="fas fa-check-circle" style="color: #16a34a; font-size: 10px;"></i> Net completed sales</div>
        </div>

        <!-- Total Orders -->
        <a href="{{ route('admin.orders.index') }}" class="sp-card sp-card-interactive">
            <div class="sp-card-header">
                <span class="sp-label">Total Orders</span>
                <div class="sp-icon-box-sm theme-blue"><i class="fas fa-shopping-bag"></i></div>
            </div>
            <div class="sp-val">{{ number_format($totalOrders) }}</div>
            <div class="sp-sub" style="color: #0284c7;"><i class="fas fa-arrow-right" style="font-size: 10px;"></i> View all orders</div>
        </a>

        <!-- Pending Orders -->
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="sp-card sp-card-interactive" style="border-left: 3px solid #d97706;">
            <div class="sp-card-header">
                <span class="sp-label">Pending Orders</span>
                <div class="sp-icon-box-sm theme-amber"><i class="fas fa-clock"></i></div>
            </div>
            <div class="sp-val" style="color: #d97706;">{{ number_format($pendingOrders) }}</div>
            <div class="sp-sub"><i class="fas fa-exclamation-circle" style="color: #d97706; font-size: 10px;"></i> Action required</div>
        </a>

        <!-- Active Products -->
        <a href="{{ route('admin.products.index') }}" class="sp-card sp-card-interactive">
            <div class="sp-card-header">
                <span class="sp-label">Active Products</span>
                <div class="sp-icon-box-sm theme-green"><i class="fas fa-couch"></i></div>
            </div>
            <div class="sp-val">{{ number_format($totalProducts) }}</div>
            <div class="sp-sub"><i class="fas fa-boxes" style="color: #16a34a; font-size: 10px;"></i> Live inventory items</div>
        </a>
    </div>

    <!-- 3. ORDER TIMELINE / VELOCITY CARDS (TODAY, YESTERDAY, THIS MONTH, THIS YEAR) -->
    <div class="sp-grid-4">
        <!-- Today's Orders -->
        <div class="sp-card" style="border-left: 3px solid #0284c7; background: #fdfdfe;">
            <div class="sp-card-header">
                <span class="sp-label">Today's Orders</span>
                <span class="sp-pill" style="background: #e0f2fe; color: #0284c7;">Today</span>
            </div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <div class="sp-val">{{ number_format($todayOrdersCount) }} <span style="font-size: 11px; font-weight: 500; color: #64748b;">orders</span></div>
                <div style="font-size: 12px; font-weight: 700; color: #0284c7;">৳ {{ number_format($todaySales, 0) }}</div>
            </div>
        </div>

        <!-- Yesterday's Orders -->
        <div class="sp-card" style="border-left: 3px solid #6366f1; background: #fdfdfe;">
            <div class="sp-card-header">
                <span class="sp-label">Yesterday's Orders</span>
                <span class="sp-pill" style="background: #ede9fe; color: #5b21b6;">Yesterday</span>
            </div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <div class="sp-val">{{ number_format($yesterdayOrdersCount) }} <span style="font-size: 11px; font-weight: 500; color: #64748b;">orders</span></div>
                <div style="font-size: 12px; font-weight: 700; color: #4f46e5;">৳ {{ number_format($yesterdaySales, 0) }}</div>
            </div>
        </div>

        <!-- This Month's Orders -->
        <div class="sp-card" style="border-left: 3px solid #16a34a; background: #fdfdfe;">
            <div class="sp-card-header">
                <span class="sp-label">This Month</span>
                <span class="sp-pill" style="background: #dcfce7; color: #15803d;">{{ now()->format('M Y') }}</span>
            </div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <div class="sp-val">{{ number_format($thisMonthOrdersCount) }} <span style="font-size: 11px; font-weight: 500; color: #64748b;">orders</span></div>
                <div style="font-size: 12px; font-weight: 700; color: #16a34a;">৳ {{ number_format($thisMonthSales, 0) }}</div>
            </div>
        </div>

        <!-- This Year's Orders -->
        <div class="sp-card" style="border-left: 3px solid #9333ea; background: #fdfdfe;">
            <div class="sp-card-header">
                <span class="sp-label">This Year</span>
                <span class="sp-pill" style="background: #f3e8ff; color: #7e22ce;">{{ now()->format('Y') }}</span>
            </div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <div class="sp-val">{{ number_format($thisYearOrdersCount) }} <span style="font-size: 11px; font-weight: 500; color: #64748b;">orders</span></div>
                <div style="font-size: 12px; font-weight: 700; color: #9333ea;">৳ {{ number_format($thisYearSales, 0) }}</div>
            </div>
        </div>
    </div>

    <!-- 4. CATALOG HIERARCHY CARDS (CATEGORY, SUBCATEGORY, CHILD CATEGORY, BRANDS) -->
    <div class="sp-grid-4">
        <!-- Categories -->
        <a href="{{ route('admin.categories.index') }}" class="sp-card sp-card-interactive">
            <div class="sp-card-header">
                <span class="sp-label">Categories</span>
                <div class="sp-icon-box-sm theme-purple"><i class="fas fa-folder-tree"></i></div>
            </div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <div class="sp-val">{{ number_format($totalCategories) }}</div>
                <span style="font-size: 10.5px; color: #9333ea; font-weight: 600;">Main Tier &rarr;</span>
            </div>
        </a>

        <!-- Subcategories -->
        <a href="{{ route('admin.subcategories.index') }}" class="sp-card sp-card-interactive">
            <div class="sp-card-header">
                <span class="sp-label">Subcategories</span>
                <div class="sp-icon-box-sm theme-indigo"><i class="fas fa-network-wired"></i></div>
            </div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <div class="sp-val">{{ number_format($totalSubCategories) }}</div>
                <span style="font-size: 10.5px; color: #4f46e5; font-weight: 600;">Sub Tier &rarr;</span>
            </div>
        </a>

        <!-- Child Categories -->
        <a href="{{ route('admin.childcategories.index') }}" class="sp-card sp-card-interactive">
            <div class="sp-card-header">
                <span class="sp-label">Child Categories</span>
                <div class="sp-icon-box-sm theme-pink"><i class="fas fa-sitemap"></i></div>
            </div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <div class="sp-val">{{ number_format($totalChildCategories) }}</div>
                <span style="font-size: 10.5px; color: #e11d48; font-weight: 600;">Child Tier &rarr;</span>
            </div>
        </a>

        <!-- Brands & Portfolio -->
        <a href="{{ route('admin.brands.index') }}" class="sp-card sp-card-interactive">
            <div class="sp-card-header">
                <span class="sp-label">Brands & Portfolio</span>
                <div class="sp-icon-box-sm theme-teal"><i class="fas fa-award"></i></div>
            </div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <div class="sp-val">{{ number_format($totalBrands) }}</div>
                <span style="font-size: 10.5px; color: #0d9488; font-weight: 600;">Brands &rarr;</span>
            </div>
        </a>
    </div>

    <!-- 5. COMPACT CHARTS & VISUAL TRENDS (SHOPIFY DENSITY) -->
    <div class="sp-chart-row">
        <!-- Chart 1: Monthly Sales & Orders Trend (Height: ~180px) -->
        <div class="sp-chart-card">
            <div class="sp-chart-header">
                <div class="sp-chart-title">
                    <i class="fas fa-chart-area" style="color: var(--admin-primary);"></i>
                    Sales & Order Trends (Last 6 Months)
                </div>
                <div class="sp-chart-legend-compact">
                    <span style="color: #ea1d24;">● Revenue (BDT)</span>
                    <span style="color: #0284c7;">● Orders</span>
                </div>
            </div>
            <div style="position: relative; height: 180px; width: 100%;">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Order Status Doughnut (Height: ~110px canvas + status pills) -->
        <div class="sp-chart-card">
            <div class="sp-chart-header">
                <div class="sp-chart-title">
                    <i class="fas fa-circle-notch" style="color: #0284c7;"></i>
                    Fulfillment Status
                </div>
                <span class="sp-pill" style="background: #f1f5f9; color: #475569;">{{ $totalOrders }} Total</span>
            </div>
            <div style="position: relative; height: 110px; width: 100%;">
                <canvas id="orderStatusChart"></canvas>
            </div>
            <div class="sp-status-grid">
                @foreach($chartStatusLabels as $index => $label)
                    <div class="sp-status-item">
                        <div style="display: flex; align-items: center; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <span class="sp-status-dot" style="background-color: {{ $chartStatusColors[$index] }};"></span>
                            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $label }}</span>
                        </div>
                        <strong style="color: #0f172a; margin-left: 4px;">{{ $chartStatusCounts[$index] }}</strong>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 6. HIGH-DENSITY BOTTOM ROW: RECENT ORDERS & OPERATIONAL WIDGET -->
    <div class="sp-bottom-row">
        <!-- Recent Orders Table -->
        <div class="sp-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 10px 14px; border-bottom: 1px solid var(--sp-border); display: flex; align-items: center; justify-content: space-between; background: #fdfdfe;">
                <div style="font-size: 12.5px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-history" style="color: #64748b;"></i> Recent Orders
                </div>
                <a href="{{ route('admin.orders.index') }}" style="font-size: 11.5px; font-weight: 600; color: #ea1d24; text-decoration: none;">
                    View All &rarr;
                </a>
            </div>

            <div class="table-responsive" style="margin: 0;">
                <table class="sp-table">
                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>District</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td style="font-weight: 700; color: #ea1d24;">
                                    {{ $order->order_number }}
                                </td>
                                <td style="font-weight: 600; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $order->customer_name }}
                                </td>
                                <td style="color: #475569;">{{ $order->phone }}</td>
                                <td>{{ $order->district }}</td>
                                <td style="font-weight: 700; color: #0f172a;">৳ {{ number_format($order->grand_total, 0) }}</td>
                                <td>
                                    {!! $order->status_badge !!}
                                </td>
                                <td style="color: #64748b; font-size: 11px;">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline btn-sm" style="padding: 2px 7px; font-size: 11px;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 20px; color: #94a3b8;">
                                    No orders received yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Operational Summary & Quick Links -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <!-- Inventory & Store Pulse -->
            <div class="sp-card" style="padding: 10px 12px;">
                <div style="font-size: 11.5px; font-weight: 700; color: #0f172a; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fas fa-heartbeat" style="color: #ea1d24; margin-right: 4px;"></i> Store Pulse</span>
                    <span class="sp-pill" style="background: #f1f5f9; color: #64748b;">Snapshot</span>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #f1f5f9; font-size: 11.5px;">
                    <span style="color: #475569;"><i class="fas fa-exclamation-triangle" style="color: #dc2626; width: 16px;"></i> Low Stock Items</span>
                    <strong style="color: {{ $lowStockCount > 0 ? '#dc2626' : '#16a34a' }}; background: {{ $lowStockCount > 0 ? '#fee2e2' : '#dcfce7' }}; padding: 1px 6px; border-radius: 4px; font-size: 11px;">
                        {{ $lowStockCount }} items
                    </strong>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #f1f5f9; font-size: 11.5px;">
                    <span style="color: #475569;"><i class="fas fa-users" style="color: #0284c7; width: 16px;"></i> Customers</span>
                    <strong style="color: #0284c7; background: #e0f2fe; padding: 1px 6px; border-radius: 4px; font-size: 11px;">
                        {{ $totalCustomers }}
                    </strong>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 5px 0; font-size: 11.5px;">
                    <span style="color: #475569;"><i class="fas fa-check-double" style="color: #16a34a; width: 16px;"></i> Completed</span>
                    <strong style="color: #16a34a; background: #dcfce7; padding: 1px 6px; border-radius: 4px; font-size: 11px;">
                        {{ $deliveredOrders }} orders
                    </strong>
                </div>
            </div>

            <!-- Quick Action Links Widget -->
            <div class="sp-card" style="padding: 10px 12px; background: #fdfdfe;">
                <div style="font-size: 11.5px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                    <i class="fas fa-bolt" style="color: #eab308; margin-right: 4px;"></i> Quick Setup Links
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                    <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-outline" style="font-size: 11px; padding: 5px 8px; justify-content: center; text-align: center;">
                        <i class="fas fa-tags" style="margin-right: 3px;"></i> Statuses
                    </a>
                    <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline" style="font-size: 11px; padding: 5px 8px; justify-content: center; text-align: center;">
                        <i class="fas fa-truck" style="margin-right: 3px;"></i> Shipping
                    </a>
                    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline" style="font-size: 11px; padding: 5px 8px; justify-content: center; text-align: center;">
                        <i class="fas fa-images" style="margin-right: 3px;"></i> Sliders
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-outline" style="font-size: 11px; padding: 5px 8px; justify-content: center; text-align: center;">
                        <i class="fas fa-cog" style="margin-right: 3px;"></i> Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthlyLabels = {!! json_encode($chartMonthlyLabels) !!};
        const monthlySales = {!! json_encode($chartMonthlySales) !!};
        const monthlyOrders = {!! json_encode($chartMonthlyOrders) !!};

        const statusLabels = {!! json_encode($chartStatusLabels) !!};
        const statusCounts = {!! json_encode($chartStatusCounts) !!};
        const statusColors = {!! json_encode($chartStatusColors) !!};

        // 1. Monthly Revenue & Order Volume Area Chart (Compact)
        const salesCtx = document.getElementById('monthlySalesChart');
        if (salesCtx) {
            const ctx = salesCtx.getContext('2d');
            const revenueGradient = ctx.createLinearGradient(0, 0, 0, 180);
            revenueGradient.addColorStop(0, 'rgba(234, 29, 36, 0.22)');
            revenueGradient.addColorStop(1, 'rgba(234, 29, 36, 0.00)');

            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [
                        {
                            label: 'Revenue (BDT)',
                            data: monthlySales,
                            borderColor: '#ea1d24',
                            backgroundColor: revenueGradient,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#ea1d24',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointRadius: 3.5,
                            pointHoverRadius: 5.5,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Order Count',
                            data: monthlyOrders,
                            borderColor: '#0284c7',
                            backgroundColor: 'transparent',
                            borderWidth: 1.5,
                            borderDash: [4, 4],
                            fill: false,
                            tension: 0.3,
                            pointBackgroundColor: '#0284c7',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#ffffff',
                            bodyColor: '#e2e8f0',
                            padding: 8,
                            cornerRadius: 6,
                            titleFont: { size: 11 },
                            bodyFont: { size: 10.5 },
                            callbacks: {
                                label: function(context) {
                                    if (context.dataset.yAxisID === 'y') {
                                        return ' Revenue: BDT ' + Number(context.raw).toLocaleString();
                                    } else {
                                        return ' Orders: ' + context.raw;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: '#f8fafc'
                            },
                            ticks: {
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif",
                                    size: 10
                                },
                                color: '#64748b'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif",
                                    size: 9.5
                                },
                                color: '#64748b',
                                callback: function(value) {
                                    if (value >= 1000) {
                                        return '৳' + (value / 1000).toFixed(0) + 'k';
                                    }
                                    return '৳' + value;
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif",
                                    size: 9.5
                                },
                                color: '#0284c7',
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // 2. Order Fulfillment Status Doughnut Chart (Compact)
        const statusCtx = document.getElementById('orderStatusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusCounts,
                        backgroundColor: statusColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '74%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#ffffff',
                            bodyColor: '#e2e8f0',
                            padding: 8,
                            cornerRadius: 6,
                            titleFont: { size: 11 },
                            bodyFont: { size: 10.5 },
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.raw;
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return ` ${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
