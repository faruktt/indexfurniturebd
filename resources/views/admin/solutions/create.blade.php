@extends('admin.layouts.app')

@section('title', 'Add New Solution Card')
@section('page_title', 'Create Homepage Solution Card')

@section('content')
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">New Solution Card Information</h3>
        <a href="{{ route('admin.solutions.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Solutions
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.solutions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Name / Title -->
            <div class="form-group">
                <label class="form-label">Solution Title / Name <span style="color: red;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="e.g. Home Solutions, Office Solutions, Hospital Solutions">
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label class="form-label">Card Image <span style="color: red;">*</span> (Uploads to public/uploads/)</label>
                <input type="file" name="image" id="solutionImageInput" required accept="image/*" class="form-control" onchange="previewSolutionImage(this)">
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    Recommended size: 400x260px or similar horizontal ratio (Max: 4MB, JPG/PNG/WebP).
                </div>
                <div id="imagePreviewBox" style="margin-top: 10px; display: none;">
                    <img id="previewImg" src="#" alt="Preview" style="max-width: 200px; max-height: 120px; border-radius: 4px; border: 1px solid #cbd5e1; object-fit: cover;">
                </div>
            </div>

            <!-- Category Dropdown -->
            <div class="form-group">
                <label class="form-label">Select Target Category (When clicked on Homepage)</label>
                <select name="category_id" class="form-control">
                    <option value="">-- No Category (Use Custom URL or All Products) --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} (slug: {{ $category->slug }})
                        </option>
                    @endforeach
                </select>
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    Selecting a category will automatically link the card to <code>/products?category={slug}</code>.
                </div>
            </div>

            <!-- Custom URL Optional -->
            <div class="form-group">
                <label class="form-label">Or Custom Direct URL (Optional)</label>
                <input type="text" name="custom_url" value="{{ old('custom_url') }}" class="form-control" placeholder="e.g. /products, /interior-service, or full URL">
            </div>

            <!-- Display Order -->
            <div class="form-group">
                <label class="form-label">Display Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control" style="max-width: 150px;">
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Lower numbers appear first (e.g. 1, 2, 3, 4).</div>
            </div>

            <!-- Status -->
            <div class="form-group" style="margin-top: 15px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 600;">
                    <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                    Publish &amp; Show on Homepage Immediately
                </label>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <i class="fas fa-save"></i> Save Solution Card
                </button>
                <a href="{{ route('admin.solutions.index') }}" class="btn btn-outline" style="padding: 10px 20px;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewSolutionImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreviewBox').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
