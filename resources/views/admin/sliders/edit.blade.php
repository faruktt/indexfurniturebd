@extends('admin.layouts.app')

@section('title', 'Edit Slider')
@section('page_title', 'Edit Slide #' . $slider->id)

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Edit Slide Details</h3>
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

        <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Current Slide Image Preview -->
            <div class="form-group">
                <label class="form-label">Current Slide Image</label>
                <div style="width: 100%; height: 200px; border-radius: 6px; overflow: hidden; background: #0f172a; margin-bottom: 8px; border: 1px solid #cbd5e1;">
                    <img src="{{ $slider->image_url }}" alt="Slide" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="font-size: 11px; color: #64748b;">
                    Filename in public/uploads/: <code>{{ $slider->image }}</code>
                </div>
            </div>

            <!-- Replace Image -->
            <div class="form-group">
                <label class="form-label">Replace Slide Image (Uploads to public/uploads/)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Leave blank if you don't want to replace current image</div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Subtitle / Badge</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}" class="form-control" placeholder="e.g. FOR">
                </div>
                <div class="form-group">
                    <label class="form-label">Main Heading Title</label>
                    <input type="text" name="title" value="{{ old('title', $slider->title) }}" class="form-control" placeholder="e.g. YOUR COMFORT">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description / Tagline</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $slider->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="btn_text" value="{{ old('btn_text', $slider->btn_text) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Button Link URL</label>
                    <input type="text" name="btn_link" value="{{ old('btn_link', $slider->btn_link) }}" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="order" value="{{ old('order', $slider->order) }}" class="form-control">
                </div>
                <div class="form-group" style="display: flex; align-items: center; margin-top: 25px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 600; color: #334155;">
                        <input type="checkbox" name="status" value="1" {{ $slider->status ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--admin-primary);">
                        Active (Visible in Homepage Hero Slider)
                    </label>
                </div>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline" style="padding: 10px 20px;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
