@extends('frontend.layouts.app')

@section('title', ($currentCategory ? $currentCategory->name . ' - ' : '') . \App\Models\SiteSetting::get('site_name', 'Indexfurniture') . ' Furniture Online')

@push('styles')
<style>
    /* Breadcrumbs */
    .breadcrumb-nav {
        padding: 14px 0;
        font-size: 11.5px;
        color: #777777;
        border-bottom: 1px solid #ededed;
        margin-bottom: 25px;
    }

    .breadcrumb-nav a {
        color: #555555;
    }

    .breadcrumb-nav a:hover {
        color: var(--otobi-red);
    }

    .breadcrumb-nav span {
        margin: 0 6px;
        color: #aaaaaa;
    }

    /* Page Layout */
    .catalog-layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 25px;
        margin-bottom: 50px;
    }

    /* Sidebar Filters */
    .catalog-sidebar {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        padding: 16px;
    }

    .sidebar-widget-title {
        font-size: 12.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: #111111;
        margin-bottom: 12px;
        padding-bottom: 6px;
        border-bottom: 1px solid #eeeeee;
    }

    .sidebar-cat-list {
        list-style: none;
        padding: 0;
        margin: 0 0 20px;
    }

    .sidebar-cat-list li {
        margin-bottom: 7px;
    }

    .sidebar-cat-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #555555;
        padding: 3px 0;
    }

    .sidebar-cat-link:hover,
    .sidebar-cat-link.active {
        color: var(--otobi-red);
        font-weight: 600;
    }

    /* Catalog Header / Toolbar */
    .catalog-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fdfdfd;
        border: 1px solid #e8e8e8;
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        font-size: 12px;
    }

    .toolbar-title {
        font-size: 15px;
        font-weight: 700;
        text-transform: uppercase;
        color: #222222;
    }

    .sort-select {
        padding: 5px 10px;
        border: 1px solid #dcdcdc;
        border-radius: 3px;
        font-size: 12px;
        color: #444444;
        outline: none;
        background: #ffffff;
    }

    /* Product Grid (Matching Image 4) */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .product-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 12px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
        position: relative;
    }

    .product-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .product-thumb {
        width: 100%;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ffffff;
        margin-bottom: 10px;
        overflow: hidden;
    }

    .product-thumb img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        transition: transform 0.3s;
    }

    .product-card:hover .product-thumb img {
        transform: scale(1.04);
    }

    .product-title {
        font-size: 12px;
        font-weight: 600;
        color: #2b3a4a;
        line-height: 1.35;
        margin-bottom: 8px;
        height: 32px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-title a:hover {
        color: var(--otobi-red);
    }

    .product-code-tag {
        font-size: 10px;
        color: #888888;
        margin-bottom: 4px;
    }

    .product-price-box {
        margin: 6px 0 10px;
    }

    .product-price {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--otobi-red);
    }

    .product-old-price {
        font-size: 11.5px;
        color: #999999;
        text-decoration: line-through;
        margin-left: 6px;
    }

    .product-btn-wrap {
        display: flex;
        gap: 6px;
    }

    .btn-view-details {
        flex: 1;
        padding: 6px 0;
        background-color: #ffffff;
        border: 1px solid #111111;
        color: #111111;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        border-radius: 2px;
        transition: all 0.2s;
    }

    .btn-view-details:hover {
        background-color: #111111;
        color: #ffffff;
    }

    .btn-quick-cart {
        padding: 6px 12px;
        background-color: var(--otobi-red);
        border: 1px solid var(--otobi-red);
        color: #ffffff;
        font-size: 11px;
        border-radius: 2px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-quick-cart:hover {
        background-color: var(--otobi-red-hover);
    }

    /* Pagination */
    .pagination-wrap {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 992px) {
        .catalog-layout {
            grid-template-columns: 1fr;
        }
        .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: 1fr !important;
            gap: 18px;
        }
        .product-card {
            width: 100%;
            padding: 16px;
        }
        .product-thumb {
            height: 220px;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <div class="breadcrumb-nav">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        @if($currentCategory)
            <a href="{{ route('products.index', ['category' => $currentCategory->slug]) }}">{{ $currentCategory->name }}</a>
            @if($currentSubCategory)
                <span>/</span>
                <a href="{{ route('products.index', ['category' => $currentCategory->slug, 'subcategory' => $currentSubCategory->slug]) }}">{{ $currentSubCategory->name }}</a>
                @if($currentChildCategory)
                    <span>/</span>
                    <strong>{{ $currentChildCategory->name }}</strong>
                @endif
            @endif
        @else
            <strong>All Products</strong>
        @endif
    </div>

    <div class="catalog-layout">
        <!-- Sidebar Filter -->
        <aside class="catalog-sidebar">
            <div class="sidebar-widget-title">Categories</div>
            <ul class="sidebar-cat-list">
                @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                           class="sidebar-cat-link {{ ($currentCategory && $currentCategory->id === $cat->id) ? 'active' : '' }}">
                            <span>{{ $cat->name }}</span>
                            <i class="fas fa-chevron-right" style="font-size: 9px;"></i>
                        </a>
                    </li>
                @endforeach
            </ul>

            @if($currentCategory && $currentCategory->subCategories->count() > 0)
                <div class="sidebar-widget-title">{{ $currentCategory->name }} Subcategories</div>
                <ul class="sidebar-cat-list">
                    @foreach($currentCategory->subCategories as $sub)
                        <li>
                            <a href="{{ route('products.index', ['category' => $currentCategory->slug, 'subcategory' => $sub->slug]) }}"
                               class="sidebar-cat-link {{ ($currentSubCategory && $currentSubCategory->id === $sub->id) ? 'active' : '' }}">
                                <span>{{ $sub->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </aside>

        <!-- Main Products Content -->
        <section>
            <!-- Toolbar -->
            <div class="catalog-toolbar">
                <div class="toolbar-title">
                    {{ $currentCategory ? $currentCategory->name : 'Furniture Collection' }} 
                    <span style="font-size: 12px; font-weight: normal; color: #777777;">({{ $products->total() }} items found)</span>
                </div>
                <form action="{{ url()->current() }}" method="GET" style="display: flex; align-items: center; gap: 8px;">
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('subcategory')) <input type="hidden" name="subcategory" value="{{ request('subcategory') }}"> @endif
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    
                    <label for="sortSelect" style="color: #666666;">Sort by:</label>
                    <select name="sort" id="sortSelect" class="sort-select" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>New Arrivals</option>
                        <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            <!-- Products Grid (Matching Image 4) -->
            @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                        <div class="product-card">
                            <div>
                                <a href="{{ route('products.show', $product->slug) }}" class="product-thumb">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->title }}">
                                </a>

                                @if($product->product_code)
                                    <div class="product-code-tag">{{ $product->product_code }}</div>
                                @endif

                                <h3 class="product-title">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ $product->title }}
                                    </a>
                                </h3>
                            </div>

                            <div>
                                <div class="product-price-box">
                                    <span class="product-price">{{ $product->formatted_price }}</span>
                                    @if($product->discount_price > 0)
                                        <span class="product-old-price">{{ $product->formatted_regular_price }}</span>
                                    @endif
                                </div>

                                <div class="product-btn-wrap">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn-view-details">
                                        View Details
                                    </a>
                                    <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-quick-cart" title="Add to Cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrap">
                    {{ $products->links() }}
                </div>
            @else
                <div style="background: #ffffff; border: 1px solid #e5e5e5; padding: 40px; text-align: center; border-radius: 4px;">
                    <i class="fas fa-couch" style="font-size: 36px; color: #cccccc; margin-bottom: 12px;"></i>
                    <h3 style="font-size: 16px; color: #444444; margin-bottom: 8px;">No products found in this category</h3>
                    <p style="color: #777777; margin-bottom: 20px;">Try browsing our popular furniture collections.</p>
                    <a href="{{ route('products.index') }}" style="display: inline-block; padding: 8px 20px; background: var(--otobi-red); color: #ffffff; border-radius: 3px; font-weight: 600;">Browse All Furniture</a>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
