@extends('admin.layouts.app')

@section('title', 'Edit Child Category')
@section('page_title', 'Edit Child Category: ' . $childcategory->name)

@section('content')
<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Edit Child Category</h3>
        <a href="{{ route('admin.childcategories.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.childcategories.update', $childcategory->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Parent Category *</label>
                <select name="category_id" id="categorySelect" required class="form-control" onchange="loadSubcategories(this.value)">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $childcategory->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Subcategory *</label>
                <select name="sub_category_id" id="subCategorySelect" required class="form-control">
                    @foreach($subCategories as $sub)
                        <option value="{{ $sub->id }}" {{ $childcategory->sub_category_id == $sub->id ? 'selected' : '' }}>
                            {{ $sub->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Child Category Name *</label>
                <input type="text" name="name" value="{{ old('name', $childcategory->name) }}" required class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Child Category Image (Uploads to public/uploads/)</label>
                @if($childcategory->image)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ $childcategory->image_url }}" alt="Preview" style="height: 60px; border-radius: 4px; border: 1px solid #ccc; padding: 2px;">
                        <span style="font-size: 11px; color: #64748b; margin-left: 8px;"><code>{{ $childcategory->image }}</code></span>
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Leave blank to keep existing image</div>
            </div>

            <div class="form-group">
                <label class="form-label">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $childcategory->order) }}" class="form-control">
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="status" value="1" {{ $childcategory->status ? 'checked' : '' }}>
                    <span>Active Status</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Child Category
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadSubcategories(categoryId) {
        const select = document.getElementById('subCategorySelect');
        select.innerHTML = '<option value="">Loading...</option>';

        fetch(`{{ url('admin/api/subcategories') }}/${categoryId}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">Select Subcategory</option>';
                data.forEach(sub => {
                    html += `<option value="${sub.id}">${sub.name}</option>`;
                });
                select.innerHTML = html;
            });
    }
</script>
@endpush
