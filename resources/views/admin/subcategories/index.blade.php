@extends('admin.layouts.app')

@section('title', 'Subcategories')
@section('page_title', 'Subcategory Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Subcategories ({{ $subCategories->total() }})</h3>
        <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Subcategory
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Subcategory Name</th>
                    <th>Slug</th>
                    <th>Parent Category</th>
                    <th>Child Categories</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subCategories as $sub)
                    <tr>
                        <td style="font-weight: 700; color: #0f172a;">{{ $sub->name }}</td>
                        <td style="color: #64748b;"><code>{{ $sub->slug }}</code></td>
                        <td>
                            <span class="badge badge-info">{{ $sub->category->name ?? 'None' }}</span>
                        </td>
                        <td>{{ $sub->child_categories_count }}</td>
                        <td>{{ $sub->order }}</td>
                        <td>
                            @if($sub->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Disabled</span>
                            @endif
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ route('admin.subcategories.edit', $sub->id) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.subcategories.destroy', $sub->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this subcategory?')">
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
                        <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">
                            No subcategories created yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $subCategories->links('vendor.pagination.admin') }}
</div>
@endsection
