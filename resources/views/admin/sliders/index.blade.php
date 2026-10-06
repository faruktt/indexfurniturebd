@extends('admin.layouts.app')

@section('title', 'Sliders & Banners')
@section('page_title', 'Hero Sliders & Banners Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Homepage Sliders ({{ $sliders->count() }})</h3>
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Slide / Upload Multiple
        </a>
    </div>

    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Order</th>
                        <th style="width: 160px;">Banner Preview</th>
                        <th>Title / Subtitle</th>
                        <th>Button &amp; Link</th>
                        <th>File Name (public/uploads/)</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 140px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $slider)
                        <tr>
                            <td>
                                <span style="font-weight: 700; color: #475569;">#{{ $slider->order }}</span>
                            </td>
                            <td>
                                <div style="width: 150px; height: 65px; border-radius: 4px; overflow: hidden; background: #1e293b; border: 1px solid #cbd5e1;">
                                    <img src="{{ $slider->image_url }}" alt="Slide" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 11px; text-transform: uppercase; color: var(--admin-primary); font-weight: 700;">
                                    {{ $slider->subtitle ?? 'No Subtitle' }}
                                </div>
                                <div style="font-weight: 700; font-size: 14px; color: #0f172a;">
                                    {{ $slider->title ?? 'Untitled Slide' }}
                                </div>
                                @if($slider->description)
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $slider->description }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($slider->btn_text)
                                    <div style="font-size: 11px; font-weight: 600; color: #1e293b;">
                                        {{ $slider->btn_text }}
                                    </div>
                                    <a href="{{ $slider->btn_link ?? '#' }}" target="_blank" style="font-size: 11px; color: #64748b; text-decoration: underline;">
                                        {{ $slider->btn_link ?? 'No Link' }}
                                    </a>
                                @else
                                    <span style="color: #94a3b8; font-size: 11px;">Image Only</span>
                                @endif
                            </td>
                            <td>
                                <code style="font-size: 11.5px; background: #f1f5f9; padding: 2px 6px; border-radius: 3px;">
                                    {{ $slider->image }}
                                </code>
                            </td>
                            <td>
                                <form action="{{ route('admin.sliders.toggle', $slider->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        @if($slider->status)
                                            <span style="background: #dcfce7; color: #166534; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                                <i class="fas fa-times-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-outline btn-sm" title="Edit Slide">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('Delete this slide banner?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm" title="Delete Slide">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fas fa-images" style="font-size: 36px; margin-bottom: 10px; display: block;"></i>
                                No slides found. Click "Add New Slide" above to upload banner images.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
