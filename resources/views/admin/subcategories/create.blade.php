@extends('admin.layouts.app')

@section('title', 'Add Subcategory')
@section('page_title', 'Add New Subcategory')

@section('content')
<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Subcategory Details</h3>
        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Parent Category *</label>
                <select name="category_id" required class="form-control">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Subcategory Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="e.g. SOFA, Bed Side Cabinet, Wardrobe">
            </div>

            <div class="form-group">
                <label class="form-label">Subcategory Image (Uploads to public/uploads/)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Optional: Custom image for homepage strip &amp; category filters</div>
            </div>

            <div class="form-group">
                <label class="form-label">Display Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control">
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="status" value="1" checked>
                    <span>Active Status</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Subcategory
            </button>
        </form>
    </div>
</div>
@endsection
