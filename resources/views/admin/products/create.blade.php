@extends('admin.layouts.app')

@section('title', 'Add Product')
@section('page_title', 'Create New Product')

@section('content')
<div class="card" style="max-width: 950px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Product Information</h3>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Row 1: Title & Code -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Product Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="form-control" placeholder="e.g. CASTLE - INDEX BED SIDE CABINET A120LB">
                </div>
                <div class="form-group">
                    <label class="form-label">Product Code / SKU</label>
                    <input type="text" name="product_code" value="{{ old('product_code') }}" class="form-control" placeholder="e.g. CASTLE BCDA120LB">
                </div>
            </div>

            <!-- Row 2: Categories -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" id="prodCatSelect" required class="form-control" onchange="loadSubcategories(this.value)">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Subcategory</label>
                    <select name="sub_category_id" id="prodSubCatSelect" class="form-control" onchange="loadChildCategories(this.value)">
                        <option value="">Select Category First</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Child Category</label>
                    <select name="child_category_id" id="prodChildCatSelect" class="form-control">
                        <option value="">Select Subcategory First</option>
                    </select>
                </div>
            </div>

            <!-- Row 3: Prices & Stock -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Regular Price (BDT) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="form-control" placeholder="3800.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Discount Price (BDT)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}" class="form-control" placeholder="Leave empty if no discount">
                </div>
                <div class="form-group">
                    <label class="form-label">Stock Quantity *</label>
                    <input type="number" name="stock" value="{{ old('stock', 10) }}" required class="form-control">
                </div>
            </div>

            <!-- Row 4: Dimensions & Warranty -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Dimensions (WxDxH)</label>
                    <input type="text" name="dimension" value="{{ old('dimension') }}" class="form-control" placeholder="e.g. 404mmX380mmX388mm">
                </div>
                <div class="form-group">
                    <label class="form-label">Warranty Details</label>
                    <input type="text" name="warranty" value="{{ old('warranty', 'One Year for any manufacturing defects') }}" class="form-control">
                </div>
            </div>

            <!-- Row 5: Colors & Sizes -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label class="form-label">Available Colors</label>
                    <div style="max-height: 120px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px; background: #fff;">
                        @foreach($colors as $color)
                            <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 12px; cursor: pointer;">
                                <input type="checkbox" name="colors[]" value="{{ $color->id }}">
                                <span style="display: inline-block; width: 14px; height: 14px; background: {{ $color->code ?? '#ccc' }}; border: 1px solid #aaa; border-radius: 2px;"></span>
                                <span>{{ $color->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="form-label">Available Sizes / Variations</label>
                    <div style="max-height: 120px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px; background: #fff;">
                        @foreach($sizes as $size)
                            <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 12px; cursor: pointer;">
                                <input type="checkbox" name="sizes[]" value="{{ $size->id }}">
                                <span>{{ $size->name }} ({{ $size->code ?? 'N/A' }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Row 6: Image Uploads (public/uploads/) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Primary Featured Image *</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                    <small style="color: #64748b; font-size: 11px;">Uploads directly to <code>public/uploads/</code> (only filename stored in DB).</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Gallery Images (Multiple)</label>
                    <input type="file" name="gallery[]" multiple class="form-control" accept="image/*">
                    <small style="color: #64748b; font-size: 11px;">Uploads to <code>public/uploads/</code>.</small>
                </div>
            </div>

            <!-- Descriptions -->
            <div class="form-group">
                <label class="form-label">Short Tagline / Subtitle</label>
                <input type="text" name="short_description" value="{{ old('short_description') }}" class="form-control" placeholder="e.g. Index Bed side Cabinet A120LB">
            </div>

            <div class="form-group">
                <label class="form-label">Full Product Description &amp; Features</label>
                <textarea name="description" rows="5" class="form-control" placeholder="• Made of high quality scratch free melamine faced laminated board&#10;• Anti-insects chemicals added to make the board termite-proof">{{ old('description') }}</textarea>
            </div>

            <!-- Checkboxes Row -->
            <div style="display: flex; gap: 25px; flex-wrap: wrap; margin: 20px 0; padding: 12px; background: #f8fafc; border-radius: 6px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <span>Featured Product</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', true) ? 'checked' : '' }}>
                    <span>Popular Product</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="cash_on_delivery" value="1">
                    <span>Cash on Delivery Available</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="status" value="1" checked>
                    <span>Active Status</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 28px;">
                <i class="fas fa-save"></i> Publish Product
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadSubcategories(categoryId) {
        const subSelect = document.getElementById('prodSubCatSelect');
        const childSelect = document.getElementById('prodChildCatSelect');
        subSelect.innerHTML = '<option value="">Loading...</option>';
        childSelect.innerHTML = '<option value="">Select Subcategory First</option>';

        if (!categoryId) {
            subSelect.innerHTML = '<option value="">Select Category First</option>';
            return;
        }

        fetch(`{{ url('admin/api/subcategories') }}/${categoryId}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">Select Subcategory</option>';
                data.forEach(sub => {
                    html += `<option value="${sub.id}">${sub.name}</option>`;
                });
                subSelect.innerHTML = html;
            });
    }

    function loadChildCategories(subCategoryId) {
        const childSelect = document.getElementById('prodChildCatSelect');
        childSelect.innerHTML = '<option value="">Loading...</option>';

        if (!subCategoryId) {
            childSelect.innerHTML = '<option value="">Select Subcategory First</option>';
            return;
        }

        fetch(`{{ url('admin/api/childcategories') }}/${subCategoryId}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">Select Child Category</option>';
                data.forEach(child => {
                    html += `<option value="${child.id}">${child.name}</option>`;
                });
                childSelect.innerHTML = html;
            });
    }
</script>
@endpush
