@extends('admin.layouts.app')

@section('title', 'Brand Portfolio')
@section('page_title', 'Brand Portfolio Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Brand Portfolio Logos ({{ $brands->count() }})</h3>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Brand Logo
        </a>
    </div>

    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Order</th>
                        <th style="width: 150px;">Logo Preview</th>
                        <th>Brand Name</th>
                        <th>Redirect URL / Link</th>
                        <th>File Name (public/uploads/)</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 140px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                        <tr>
                            <td>
                                <span style="font-weight: 700; color: #475569;">#{{ $brand->order }}</span>
                            </td>
                            <td>
                                <div style="width: 130px; height: 55px; border-radius: 4px; overflow: hidden; background: #ffffff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; padding: 4px;">
                                    <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" style="max-height: 45px; max-width: 100%; object-fit: contain;">
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; font-size: 14px; color: #0f172a;">
                                    {{ $brand->name }}
                                </div>
                            </td>
                            <td>
                                @if($brand->url)
                                    <a href="{{ $brand->target_url }}" target="_blank" rel="noopener noreferrer" style="color: var(--admin-primary); font-size: 12.5px; text-decoration: underline; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-external-link-alt" style="font-size: 11px;"></i>
                                        {{ Str::limit($brand->url, 45) }}
                                    </a>
                                @else
                                    <span style="color: #94a3b8; font-size: 12px; font-style: italic;">No Link Set</span>
                                @endif
                            </td>
                            <td>
                                <code style="font-size: 11.5px; background: #f1f5f9; padding: 2px 6px; border-radius: 3px;">
                                    {{ $brand->image }}
                                </code>
                            </td>
                            <td>
                                <form action="{{ route('admin.brands.toggle', $brand->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        @if($brand->status)
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
                                    <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-outline btn-sm" title="Edit Brand">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this brand logo?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm" title="Delete Brand">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">
                                No brand portfolio logos found. Click "Add New Brand Logo" above to add one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
