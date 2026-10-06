@extends('admin.layouts.app')

@section('title', 'Add New Slider')
@section('page_title', 'Create Slider / Bulk Upload Slides')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Upload Slider Images (Single or Multiple)</h3>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Sliders
        </a>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="admin-alert admin-alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Upload Multiple Images -->
            <div class="form-group" style="background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 8px; padding: 25px; text-align: center; margin-bottom: 24px;">
                <i class="fas fa-cloud-upload-alt" style="font-size: 40px; color: var(--admin-primary); margin-bottom: 12px; display: block;"></i>
                <label class="form-label" style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                    Select Slide Image(s) (You can choose multiple at once!)
                </label>
                <p style="font-size: 12px; color: #64748b; margin-bottom: 15px;">
                    Images will be uploaded directly to <code>public/uploads/</code> and only the filename will be saved in the database.
                </p>
                <input type="file" name="images[]" multiple class="form-control" accept="image/*" style="max-width: 400px; margin: 0 auto;" id="sliderImagesInput">
                <div style="font-size: 11px; color: #94a3b8; margin-top: 8px;">Recommended resolution: 1920x600px or similar widescreen banner</div>
            </div>

            <div style="font-weight: 700; font-size: 13px; color: #0f172a; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                Optional Overlay Content &amp; Call-to-Action (Applies to first slide if multiple selected)
            </div>

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Subtitle / Badge</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="form-control" placeholder="e.g. FOR or SUPERIOR LIVING">
                </div>
                <div class="form-group">
                    <label class="form-label">Main Heading Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="e.g. YOUR COMFORT">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description / Tagline</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Brief description to display on slide...">{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="btn_text" value="{{ old('btn_text') }}" class="form-control" placeholder="e.g. Explore Collection >">
                </div>
                <div class="form-group">
                    <label class="form-label">Button Link URL</label>
                    <input type="text" name="btn_link" value="{{ old('btn_link') }}" class="form-control" placeholder="e.g. /products?category=bedroom">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control">
                </div>
                <div class="form-group" style="display: flex; align-items: center; margin-top: 25px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 600; color: #334155;">
                        <input type="checkbox" name="status" value="1" checked style="width: 18px; height: 18px; accent-color: var(--admin-primary);">
                        Active (Visible in Homepage Hero Slider)
                    </label>
                </div>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <i class="fas fa-upload"></i> Upload &amp; Save Slide(s)
                </button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline" style="padding: 10px 20px;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
