@extends('admin.layouts.app')

@section('title', 'Child Categories')
@section('page_title', 'Child Category Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Child Categories ({{ $childCategories->total() }})</h3>
        <a href="{{ route('admin.childcategories.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Child Category
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Child Category Name</th>
                    <th>Slug</th>
                    <th>Subcategory</th>
                    <th>Parent Category</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($childCategories as $child)
                    <tr>
                        <td style="font-weight: 700; color: #0f172a;">{{ $child->name }}</td>
                        <td style="color: #64748b;"><code>{{ $child->slug }}</code></td>
                        <td>
                            <span class="badge badge-info">{{ $child->subCategory->name ?? 'None' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $child->category->name ?? 'None' }}</span>
                        </td>
                        <td>{{ $child->order }}</td>
                        <td>
                            @if($child->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Disabled</span>
                            @endif
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ route('admin.childcategories.edit', $child->id) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.childcategories.destroy', $child->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this child category?')">
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
                            No child categories created yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $childCategories->links('vendor.pagination.admin') }}
</div>
@endsection
