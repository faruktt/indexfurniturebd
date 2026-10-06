@extends('admin.layouts.app')

@section('title', 'Add Category')
@section('page_title', 'Add New Category')

@section('content')
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Category Details</h3>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="e.g. LIVING, BEDROOM, DINING">
            </div>

            <div class="form-group">
                <label class="form-label">Display Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Category Image (Uploads to public/uploads/)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small style="color: #64748b; font-size: 11px;">Recommended size: 500x500. Stored in public/uploads with filename only in DB.</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_popular_home" value="1" {{ old('is_popular_home') ? 'checked' : '' }}>
                    <span>Show in Popular Home Categories</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_popular_office" value="1" {{ old('is_popular_office') ? 'checked' : '' }}>
                    <span>Show in Popular Office Categories</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <span>Mark as Featured Category</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="status" value="1" checked>
                    <span>Active Status</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Category
            </button>
        </form>
    </div>
</div>
@endsection
