@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page_title', 'Category Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Categories ({{ $categories->total() }})</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Image</th>
                    <th>Category Name</th>
                    <th>Slug</th>
                    <th>Subcategories</th>
                    <th>Products</th>
                    <th>Popular Home</th>
                    <th>Popular Office</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr>
                        <td>
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0;">
                        </td>
                        <td style="font-weight: 700; color: #0f172a;">{{ $cat->name }}</td>
                        <td style="color: #64748b;"><code>{{ $cat->slug }}</code></td>
                        <td>{{ $cat->sub_categories_count }}</td>
                        <td>{{ $cat->products_count }}</td>
                        <td>
                            @if($cat->is_popular_home)
                                <span class="badge badge-success">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($cat->is_popular_office)
                                <span class="badge badge-info">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($cat->is_featured)
                                <span class="badge badge-warning">Featured</span>
                            @else
                                <span class="badge badge-secondary">Standard</span>
                            @endif
                        </td>
                        <td>
                            @if($cat->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Disabled</span>
                            @endif
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this category and its subcategories?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 30px; color: #94a3b8;">
                            No categories created yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $categories->links('vendor.pagination.admin') }}
</div>
@endsection
