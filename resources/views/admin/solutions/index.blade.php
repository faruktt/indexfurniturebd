@extends('admin.layouts.app')

@section('title', 'Homepage Solutions')
@section('page_title', 'Homepage Solutions Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Homepage Solutions Cards ({{ $solutions->count() }})</h3>
        <a href="{{ route('admin.solutions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Solution Card
        </a>
    </div>

    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Order</th>
                        <th style="width: 140px;">Image Preview</th>
                        <th>Solution Name / Title</th>
                        <th>Target Category / URL</th>
                        <th>File Name (public/uploads/)</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 140px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solutions as $solution)
                        <tr>
                            <td>
                                <span style="font-weight: 700; color: #475569;">#{{ $solution->order }}</span>
                            </td>
                            <td>
                                <div style="width: 120px; height: 75px; border-radius: 4px; overflow: hidden; background: #f8fafc; border: 1px solid #cbd5e1;">
                                    <img src="{{ $solution->image_url }}" alt="{{ $solution->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; font-size: 14px; color: #0f172a;">
                                    {{ $solution->name }}
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    Target: <a href="{{ $solution->target_url }}" target="_blank" style="color: var(--admin-primary); text-decoration: underline;">{{ $solution->target_url }}</a>
                                </div>
                            </td>
                            <td>
                                @if($solution->category)
                                    <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 12px; font-size: 11.5px; font-weight: 600;">
                                        <i class="fas fa-tag"></i> {{ $solution->category->name }}
                                    </span>
                                @elseif($solution->custom_url)
                                    <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 12px; font-size: 11.5px;">
                                        <i class="fas fa-link"></i> Custom URL
                                    </span>
                                @else
                                    <span style="color: #94a3b8; font-size: 11.5px;">All Products</span>
                                @endif
                            </td>
                            <td>
                                <code style="font-size: 11.5px; background: #f1f5f9; padding: 2px 6px; border-radius: 3px;">
                                    {{ $solution->image }}
                                </code>
                            </td>
                            <td>
                                <form action="{{ route('admin.solutions.toggle', $solution->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        @if($solution->status)
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
                                    <a href="{{ route('admin.solutions.edit', $solution->id) }}" class="btn btn-outline btn-sm" title="Edit Solution">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.solutions.destroy', $solution->id) }}" method="POST" onsubmit="return confirm('Delete this solution card?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm" title="Delete Solution">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fas fa-th-large" style="font-size: 36px; margin-bottom: 10px; display: block;"></i>
                                No solutions cards found. Click "Add New Solution Card" above to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
