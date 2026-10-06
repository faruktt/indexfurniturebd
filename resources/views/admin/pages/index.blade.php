@extends('admin.layouts.app')

@section('title', 'Pages Management')
@section('page_title', 'Pages & Footer Links Management')

@section('content')
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 15px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <h3 class="card-title">Pages ({{ $pages->count() }})</h3>
            
            <!-- Section Filter Badges -->
            <div style="display: inline-flex; gap: 6px;">
                <a href="{{ route('admin.pages.index') }}" class="btn {{ !request('section') ? 'btn-dark' : 'btn-outline' }} btn-sm">
                    All
                </a>
                <a href="{{ route('admin.pages.index', ['section' => 'company']) }}" class="btn {{ request('section') == 'company' ? 'btn-dark' : 'btn-outline' }} btn-sm">
                    The Company
                </a>
                <a href="{{ route('admin.pages.index', ['section' => 'information']) }}" class="btn {{ request('section') == 'information' ? 'btn-dark' : 'btn-outline' }} btn-sm">
                    More Information
                </a>
            </div>
        </div>

        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Page
        </a>
    </div>

    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Order</th>
                        <th>Page Title</th>
                        <th>Footer Section</th>
                        <th>URL Slug / View Link</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 140px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>
                                <span style="font-weight: 700; color: #475569;">#{{ $page->order }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 700; font-size: 14px; color: #0f172a;">
                                    {{ $page->title }}
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                                    {{ Str::limit(strip_tags($page->content), 65) }}
                                </div>
                            </td>
                            <td>
                                @if($page->section === 'company')
                                    <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 12px; font-size: 11.5px; font-weight: 600;">
                                        <i class="fas fa-building"></i> The Company
                                    </span>
                                @else
                                    <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 12px; font-size: 11.5px; font-weight: 600;">
                                        <i class="fas fa-info-circle"></i> More Information
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pages.show', $page->slug) }}" target="_blank" style="color: var(--admin-primary); font-size: 12.5px; text-decoration: underline; display: inline-flex; align-items: center; gap: 4px;">
                                    <code>/page/{{ $page->slug }}</code>
                                    <i class="fas fa-external-link-alt" style="font-size: 11px;"></i>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('admin.pages.toggle', $page->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        @if($page->status)
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
                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-outline btn-sm" title="Edit Page">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm" title="Delete Page">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 35px; color: #64748b;">
                                No pages found. Click "Add New Page" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
