@extends('admin.layouts.app')

@section('title', 'Edit Subcategory')
@section('page_title', 'Edit Subcategory: ' . $subcategory->name)

@section('content')
<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Edit Subcategory</h3>
        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.subcategories.update', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Parent Category *</label>
                <select name="category_id" required class="form-control">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $subcategory->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Subcategory Name *</label>
                <input type="text" name="name" value="{{ old('name', $subcategory->name) }}" required class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Subcategory Image (Uploads to public/uploads/)</label>
                @if($subcategory->image)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ $subcategory->image_url }}" alt="Preview" style="height: 60px; border-radius: 4px; border: 1px solid #ccc; padding: 2px;">
                        <span style="font-size: 11px; color: #64748b; margin-left: 8px;"><code>{{ $subcategory->image }}</code></span>
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Leave blank to keep existing image</div>
            </div>

            <div class="form-group">
                <label class="form-label">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $subcategory->order) }}" class="form-control">
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="status" value="1" {{ $subcategory->status ? 'checked' : '' }}>
                    <span>Active Status</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Subcategory
            </button>
        </form>
    </div>
</div>
@endsection
