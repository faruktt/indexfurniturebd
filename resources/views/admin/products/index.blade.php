@extends('admin.layouts.app')

@section('title', 'Products')
@section('page_title', 'Product Catalog')

@section('content')
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <h3 class="card-title">All Products ({{ $products->total() }})</h3>
            <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or code..." class="form-control" style="width: 200px; padding: 5px 10px; font-size: 12px;">
                <select name="category_id" class="form-control" style="width: 160px; padding: 5px 10px; font-size: 12px;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Image</th>
                    <th>Product Title</th>
                    <th>Code</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image_url }}" alt="{{ $product->title }}" style="width: 44px; height: 44px; object-fit: contain; background: #fff; border: 1px solid #e2e8f0; border-radius: 4px;">
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a;">{{ $product->title }}</div>
                            <div style="font-size: 11px; color: #64748b;">DB Image: <code>{{ $product->image ?? 'none' }}</code></div>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $product->product_code ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $product->category->name ?? 'None' }}</td>
                        <td>{{ $product->subCategory->name ?? '-' }}</td>
                        <td>
                            <strong style="color: var(--admin-primary);">{{ $product->formatted_price }}</strong>
                            @if($product->discount_price > 0)
                                <div style="font-size: 11px; color: #94a3b8; text-decoration: line-through;">{{ $product->formatted_regular_price }}</div>
                            @endif
                        </td>
                        <td>
                            @if($product->stock > 5)
                                <span class="badge badge-success">{{ $product->stock }} in stock</span>
                            @elseif($product->stock > 0)
                                <span class="badge badge-warning">Low: {{ $product->stock }}</span>
                            @else
                                <span class="badge badge-danger">Out of stock</span>
                            @endif
                        </td>
                        <td>
                            @if($product->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Disabled</span>
                            @endif
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-outline btn-sm" title="View in Store">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this product permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger-sm" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px; color: #94a3b8;">
                            No products found matching your search.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links('vendor.pagination.admin') }}
</div>
@endsection
