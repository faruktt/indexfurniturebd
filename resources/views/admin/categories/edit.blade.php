@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page_title', 'Edit Category: ' . $category->name)

@section('content')
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Edit Category</h3>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $category->order) }}" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Current Image</label>
                <div style="margin-bottom: 8px;">
                    <img src="{{ $category->image_url }}" alt="Category" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Filename in DB: <code>{{ $category->image ?? 'None' }}</code></div>
                </div>
                <label class="form-label">Change Image (Uploads to public/uploads/)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_popular_home" value="1" {{ old('is_popular_home', $category->is_popular_home) ? 'checked' : '' }}>
                    <span>Show in Popular Home Categories</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_popular_office" value="1" {{ old('is_popular_office', $category->is_popular_office) ? 'checked' : '' }}>
                    <span>Show in Popular Office Categories</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                    <span>Mark as Featured Category</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="status" value="1" {{ old('status', $category->status) ? 'checked' : '' }}>
                    <span>Active Status</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Category
            </button>
        </form>
    </div>
</div>
@endsection
