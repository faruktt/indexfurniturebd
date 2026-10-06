@extends('admin.layouts.app')

@section('title', 'Add New Brand Logo')
@section('page_title', 'Add Brand Portfolio Logo')

@section('content')
<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">New Brand Logo Information</h3>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Brands
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Name / Title -->
            <div class="form-group">
                <label class="form-label">Brand Name <span style="color: red;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="e.g. Index Furniture, Index Interior, Apollo">
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label class="form-label">Brand Logo Image <span style="color: red;">*</span> (Uploads to public/uploads/)</label>
                <input type="file" name="image" id="brandImageInput" required accept="image/*" class="form-control" onchange="previewBrandImage(this)">
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    Recommended: PNG with transparent background or high-res JPG (Max: 4MB).
                </div>
                <div id="imagePreviewBox" style="margin-top: 10px; display: none;">
                    <div style="padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center;">
                        <img id="previewImg" src="#" alt="Preview" style="max-height: 55px; max-width: 160px; object-fit: contain;">
                    </div>
                </div>
            </div>

            <!-- Redirect URL / Link -->
            <div class="form-group">
                <label class="form-label">Redirect Link / URL (Optional)</label>
                <input type="text" name="url" value="{{ old('url') }}" class="form-control" placeholder="e.g. https://www.indexfurniture.com or /products?brand=apollo">
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    When clicked on the homepage, the visitor will be directed to this link.
                </div>
            </div>

            <!-- Display Order -->
            <div class="form-group">
                <label class="form-label">Display Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control" placeholder="0">
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    Lower number shows first (e.g. 1, 2, 3...).
                </div>
            </div>

            <!-- Status -->
            <div class="form-group" style="margin-top: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="status" value="1" {{ old('status', '1') ? 'checked' : '' }} style="width: 16px; height: 16px;">
                    <span style="font-size: 13px; font-weight: 600; color: #334155;">Active (Display on Homepage)</span>
                </label>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Brand Logo
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewBrandImage(input) {
    const previewBox = document.getElementById('imagePreviewBox');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewBox.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewBox.style.display = 'none';
    }
}
</script>
@endsection
