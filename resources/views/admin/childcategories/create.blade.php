@extends('admin.layouts.app')

@section('title', 'Add Child Category')
@section('page_title', 'Add New Child Category')

@section('content')
<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Child Category Details</h3>
        <a href="{{ route('admin.childcategories.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.childcategories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Parent Category *</label>
                <select name="category_id" id="categorySelect" required class="form-control" onchange="loadSubcategories(this.value)">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Subcategory *</label>
                <select name="sub_category_id" id="subCategorySelect" required class="form-control">
                    <option value="">Select Category First</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Child Category Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="e.g. Table with side return, Work station">
            </div>

            <div class="form-group">
                <label class="form-label">Child Category Image (Uploads to public/uploads/)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Optional: Custom image for homepage strip &amp; catalog filters</div>
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
                <i class="fas fa-save"></i> Save Child Category
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

        if (!categoryId) {
            select.innerHTML = '<option value="">Select Category First</option>';
            return;
        }

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
