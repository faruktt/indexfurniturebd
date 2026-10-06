@extends('frontend.layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' - ' . \App\Models\SiteSetting::get('site_name', 'Indexfurniture'))
@section('meta_description', $page->meta_description ?: Str::limit(strip_tags($page->content), 160))

@section('content')
<style>
    .page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 25px 20px 60px;
    }

    .page-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #777777;
        margin-bottom: 25px;
    }

    .page-breadcrumb a {
        color: #333333;
        text-decoration: none;
        transition: color 0.2s;
    }

    .page-breadcrumb a:hover {
        color: var(--otobi-red);
    }

    .page-breadcrumb span.separator {
        color: #bbbbbb;
    }

    .page-breadcrumb span.current {
        color: #888888;
        font-weight: 500;
    }

    .page-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 35px;
        align-items: start;
    }

    /* Sidebar Navigation */
    .page-sidebar {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 90px;
    }

    .page-sidebar-header {
        background: #f8fafc;
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #1e293b;
    }

    .page-sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .page-sidebar-item {
        border-bottom: 1px solid #f1f5f9;
    }

    .page-sidebar-item:last-child {
        border-bottom: none;
    }

    .page-sidebar-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        font-size: 13.5px;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-sidebar-link:hover {
        background: #f8fafc;
        color: var(--otobi-red);
        padding-left: 24px;
    }

    .page-sidebar-link.active {
        background: #fff1f2;
        color: var(--otobi-red);
        font-weight: 600;
        border-left: 3px solid var(--otobi-red);
        padding-left: 17px;
    }

    .page-help-card {
        margin-top: 20px;
        background: #fdf2f2;
        border: 1px solid #fecaca;
        border-radius: 6px;
        padding: 18px 20px;
    }

    .page-help-title {
        font-size: 14px;
        font-weight: 700;
        color: #991b1b;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-help-text {
        font-size: 12.5px;
        color: #4b5563;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .page-help-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--otobi-red);
        color: #ffffff;
        padding: 7px 14px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
    }

    .page-help-btn:hover {
        background: #b01017;
    }

    /* Main Page Content Card */
    .page-content-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 6px;
        padding: 35px 40px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        min-height: 480px;
    }

    .page-content-header {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .page-title {
        font-family: 'Outfit', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 10px;
        line-height: 1.25;
    }

    .page-section-tag {
        display: inline-block;
        background: #f1f5f9;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 3px 10px;
        border-radius: 12px;
    }

    /* Rich Content Styles */
    .page-body {
        font-size: 14.5px;
        line-height: 1.8;
        color: #374151;
    }

    .page-body h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin: 28px 0 12px;
        padding-bottom: 6px;
        border-bottom: 1px solid #e5e7eb;
    }

    .page-body h3 {
        font-size: 17px;
        font-weight: 600;
        color: #374151;
        margin: 22px 0 10px;
    }

    .page-body p {
        margin: 0 0 16px;
    }

    .page-body ul, .page-body ol {
        margin: 0 0 18px;
        padding-left: 24px;
    }

    .page-body li {
        margin-bottom: 8px;
    }

    .page-body strong {
        color: #111827;
    }

    .page-body a {
        color: var(--otobi-red);
        text-decoration: underline;
    }

    .page-body table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 13.5px;
    }

    .page-body table th,
    .page-body table td {
        border: 1px solid #e5e7eb;
        padding: 10px 14px;
        text-align: left;
    }

    .page-body table th {
        background: #f9fafb;
        font-weight: 600;
        color: #111827;
    }

    .page-body table tr:nth-child(even) td {
        background: #fbfcfe;
    }

    @media (max-width: 991px) {
        .page-layout {
            grid-template-columns: 1fr;
        }

        .page-sidebar {
            position: static;
        }

        .page-content-card {
            padding: 25px 20px;
        }
    }
</style>

<div class="page-container">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="separator">/</span>
        <span class="current">{{ $page->section_name }}</span>
        <span class="separator">/</span>
        <span class="current">{{ $page->title }}</span>
    </div>

    <div class="page-layout">
        <!-- Sidebar -->
        <aside class="page-sidebar-wrap">
            <div class="page-sidebar">
                <div class="page-sidebar-header">
                    {{ $page->section_name }}
                </div>
                <ul class="page-sidebar-menu">
                    @foreach($relatedPages as $rp)
                        <li class="page-sidebar-item">
                            <a href="{{ route('pages.show', $rp->slug) }}" class="page-sidebar-link {{ $rp->id === $page->id ? 'active' : '' }}">
                                <span>{{ $rp->title }}</span>
                                <i class="fas fa-chevron-right" style="font-size: 10px; opacity: 0.6;"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Customer Care Box -->
            <div class="page-help-card">
                <div class="page-help-title">
                    <i class="fas fa-headset"></i> Need Assistance?
                </div>
                <div class="page-help-text">
                    For inquiries, bulk corporate orders, or store locations, contact our support team.
                </div>
                <a href="tel:{{ \App\Models\SiteSetting::get('phone_primary', '+8809613808080') }}" class="page-help-btn">
                    <i class="fas fa-phone-alt"></i> {{ \App\Models\SiteSetting::get('phone_primary', '+880 9613808080') }}
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="page-content-card">
            <header class="page-content-header">
                <div class="page-section-tag">{{ $page->section_name }}</div>
                <h1 class="page-title">{{ $page->title }}</h1>
            </header>

            <article class="page-body">
                {!! $page->content !!}
            </article>
        </main>
    </div>
</div>
@endsection
