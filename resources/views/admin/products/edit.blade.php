@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page_title', 'Edit: ' . $product->title)

@section('content')
<div class="card" style="max-width: 950px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Edit Product Details</h3>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Row 1: Title & Code -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Product Title *</label>
                    <input type="text" name="title" value="{{ old('title', $product->title) }}" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Product Code / SKU</label>
                    <input type="text" name="product_code" value="{{ old('product_code', $product->product_code) }}" class="form-control">
                </div>
            </div>

            <!-- Row 2: Categories -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" id="prodCatSelect" required class="form-control" onchange="loadSubcategories(this.value)">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Subcategory</label>
                    <select name="sub_category_id" id="prodSubCatSelect" class="form-control" onchange="loadChildCategories(this.value)">
                        <option value="">None</option>
                        @foreach($subCategories as $sub)
                            <option value="{{ $sub->id }}" {{ $product->sub_category_id == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Child Category</label>
                    <select name="child_category_id" id="prodChildCatSelect" class="form-control">
                        <option value="">None</option>
                        @foreach($childCategories as $ch)
                            <option value="{{ $ch->id }}" {{ $product->child_category_id == $ch->id ? 'selected' : '' }}>
                                {{ $ch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Row 3: Prices & Stock -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Regular Price (BDT) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Discount Price (BDT)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Stock Quantity *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="form-control">
                </div>
            </div>

            <!-- Row 4: Dimensions & Warranty -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Dimensions (WxDxH)</label>
                    <input type="text" name="dimension" value="{{ old('dimension', $product->dimension) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Warranty Details</label>
                    <input type="text" name="warranty" value="{{ old('warranty', $product->warranty) }}" class="form-control">
                </div>
            </div>

            <!-- Row 5: Colors & Sizes -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label class="form-label">Available Colors</label>
                    <div style="max-height: 120px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px; background: #fff;">
                        @php $productColorIds = $product->colors->pluck('id')->toArray(); @endphp
                        @foreach($colors as $color)
                            <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 12px; cursor: pointer;">
                                <input type="checkbox" name="colors[]" value="{{ $color->id }}" {{ in_array($color->id, $productColorIds) ? 'checked' : '' }}>
                                <span style="display: inline-block; width: 14px; height: 14px; background: {{ $color->code ?? '#ccc' }}; border: 1px solid #aaa; border-radius: 2px;"></span>
                                <span>{{ $color->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="form-label">Available Sizes / Variations</label>
                    <div style="max-height: 120px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px; background: #fff;">
                        @php $productSizeIds = $product->sizes->pluck('id')->toArray(); @endphp
                        @foreach($sizes as $size)
                            <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 12px; cursor: pointer;">
                                <input type="checkbox" name="sizes[]" value="{{ $size->id }}" {{ in_array($size->id, $productSizeIds) ? 'checked' : '' }}>
                                <span>{{ $size->name }} ({{ $size->code ?? 'N/A' }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Row 6: Image Uploads -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Current Main Image</label>
                    <div style="margin-bottom: 8px;">
                        <img src="{{ $product->image_url }}" alt="Main" style="width: 70px; height: 70px; object-fit: contain; border: 1px solid #ccc; border-radius: 4px;">
                        <div style="font-size: 11px; color: #64748b;">Filename in DB: <code>{{ $product->image ?? 'none' }}</code></div>
                    </div>
                    <label class="form-label">Change Main Image (Uploads to public/uploads/)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label class="form-label">Add More Gallery Images</label>
                    <input type="file" name="gallery[]" multiple class="form-control" accept="image/*">
                    <small style="color: #64748b; font-size: 11px;">Uploads to <code>public/uploads/</code>.</small>

                    @if($product->images->count() > 0)
                        <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                            @foreach($product->images as $gImg)
                                <div style="position: relative; width: 50px; height: 50px; border: 1px solid #ccc; border-radius: 4px; overflow: hidden;" id="gImg-{{ $gImg->id }}">
                                    <img src="{{ $gImg->image_url }}" style="width: 100%; height: 100%; object-fit: contain;">
                                    <button type="button" onclick="deleteGalleryImage({{ $gImg->id }})" style="position: absolute; top: 0; right: 0; background: red; color: #fff; border: none; font-size: 9px; width: 16px; height: 16px; cursor: pointer;">&times;</button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Descriptions -->
            <div class="form-group">
                <label class="form-label">Short Tagline / Subtitle</label>
                <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Full Product Description &amp; Features</label>
                <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Checkboxes Row -->
            <div style="display: flex; gap: 25px; flex-wrap: wrap; margin: 20px 0; padding: 12px; background: #f8fafc; border-radius: 6px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                    <span>Featured Product</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $product->is_popular) ? 'checked' : '' }}>
                    <span>Popular Product</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="cash_on_delivery" value="1" {{ old('cash_on_delivery', $product->cash_on_delivery) ? 'checked' : '' }}>
                    <span>Cash on Delivery Available</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px;">
                    <input type="checkbox" name="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
                    <span>Active Status</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 28px;">
                <i class="fas fa-save"></i> Update Product
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
        childSelect.innerHTML = '<option value="">None</option>';

        fetch(`{{ url('admin/api/subcategories') }}/${categoryId}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">None</option>';
                data.forEach(sub => {
                    html += `<option value="${sub.id}">${sub.name}</option>`;
                });
                subSelect.innerHTML = html;
            });
    }

    function loadChildCategories(subCategoryId) {
        const childSelect = document.getElementById('prodChildCatSelect');
        childSelect.innerHTML = '<option value="">Loading...</option>';

        fetch(`{{ url('admin/api/childcategories') }}/${subCategoryId}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">None</option>';
                data.forEach(child => {
                    html += `<option value="${child.id}">${child.name}</option>`;
                });
                childSelect.innerHTML = html;
            });
    }

    function deleteGalleryImage(id) {
        if (!confirm('Remove this gallery image?')) return;

        fetch(`{{ url('admin/products/gallery-image') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`gImg-${id}`).remove();
            }
        });
    }
</script>
@endpush
