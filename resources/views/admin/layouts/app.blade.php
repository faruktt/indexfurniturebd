<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') | {{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }} Admin Panel</title>

    @if($adminFavicon = \App\Models\SiteSetting::faviconUrl())
        <link rel="shortcut icon" href="{{ $adminFavicon }}" type="image/x-icon">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --admin-primary: #ea1d24;
            --admin-dark: #0f172a;
            --admin-dark-hover: #1e293b;
            --admin-sidebar-w: 260px;
            --admin-bg: #f8fafc;
            --admin-card-border: #e2e8f0;
            --admin-text-main: #1e293b;
            --admin-text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--admin-bg);
            color: var(--admin-text-main);
            font-size: 13px;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--admin-sidebar-w);
            background-color: var(--admin-dark);
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand-box {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-logo {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.04em;
        }

        .sidebar-logo span {
            color: var(--admin-primary);
        }

        .sidebar-menu-wrap {
            padding: 16px 12px;
            flex: 1;
        }

        .menu-heading {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #64748b;
            font-weight: 700;
            padding: 12px 12px 6px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 2px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 6px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-link i {
            font-size: 15px;
            width: 20px;
            text-align: center;
        }

        .sidebar-link:hover,
        .sidebar-item.active .sidebar-link {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .sidebar-item.active .sidebar-link {
            background-color: var(--admin-primary);
            color: #ffffff;
        }

        /* Main Content Container */
        .admin-main {
            margin-left: var(--admin-sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header */
        .admin-topbar {
            height: 64px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--admin-card-border);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-user-dropdown-wrap {
            position: relative;
        }

        .admin-user-profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            border: 1px solid transparent;
            padding: 4px 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .admin-user-profile-btn:hover,
        .admin-user-profile-btn.active {
            background: #f1f5f9;
            border-color: #e2e8f0;
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        .admin-user-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 240px;
            background: #ffffff;
            border: 1px solid var(--admin-card-border);
            border-radius: 10px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
            z-index: 1100;
            overflow: hidden;
            animation: dropdownFadeIn 0.15s ease-out;
        }

        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .admin-user-dropdown-menu.show {
            display: block;
        }

        .admin-user-dropdown-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
        }

        .admin-user-dropdown-body {
            padding: 6px 0;
        }

        .dropdown-item-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            text-decoration: none;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .dropdown-item-link:hover {
            background: #f1f5f9;
            color: var(--admin-primary);
        }

        .admin-user-dropdown-footer {
            padding: 6px;
            border-top: 1px solid #f1f5f9;
            background: #fafbfc;
        }

        .dropdown-logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
            color: #dc2626;
            background: transparent;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.15s ease;
            text-align: left;
        }

        .dropdown-logout-btn:hover {
            background: #fee2e2;
        }

        /* Body Container */
        .admin-body {
            padding: 28px 30px;
            flex: 1;
        }

        /* Cards & Tables */
        .card {
            background-color: #ffffff;
            border: 1px solid var(--admin-card-border);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--admin-card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        .card-body {
            padding: 20px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        .admin-table th {
            background-color: #f8fafc;
            border-bottom: 1px solid var(--admin-card-border);
            padding: 11px 16px;
            text-align: left;
            color: #475569;
            font-weight: 600;
            white-space: nowrap;
        }

        .admin-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .admin-table tr:hover td {
            background-color: #fbfcfe;
        }

        /* Badges & Buttons */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        .badge-secondary { background: #f1f5f9; color: #475569; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--admin-primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #c41218;
            color: #ffffff;
        }

        .btn-dark {
            background-color: #0f172a;
            color: #ffffff;
        }

        .btn-dark:hover {
            background-color: #1e293b;
            color: #ffffff;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #cbd5e1;
            color: #334155;
        }

        .btn-outline:hover {
            background: #f1f5f9;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 11.5px;
        }

        .btn-danger-sm {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 11.5px;
            border: none;
            cursor: pointer;
        }

        .btn-danger-sm:hover {
            background-color: #fecaca;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
            color: #0f172a;
            outline: none;
            background: #ffffff;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 1px var(--admin-primary);
        }

        /* Alerts */
        .admin-alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .admin-alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* Admin Pagination Styling */
        .admin-pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: #ffffff;
            border-top: 1px solid var(--admin-card-border);
            flex-wrap: wrap;
            gap: 12px;
        }
        .pagination-info {
            font-size: 12.5px;
            color: #64748b;
        }
        .pagination-info .font-semibold {
            font-weight: 600;
            color: #1e293b;
        }
        .admin-pagination-list {
            display: inline-flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            border-radius: 6px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        .admin-pagination-list .page-item {
            margin: 0;
        }
        .admin-pagination-list .page-item:not(:last-child) {
            border-right: 1px solid #e2e8f0;
        }
        .admin-pagination-list .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            font-size: 12.5px;
            font-weight: 500;
            color: #334155;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            background: #ffffff;
        }
        .admin-pagination-list .page-item a.page-link:hover {
            background: #f8fafc;
            color: var(--admin-primary);
        }
        .admin-pagination-list .page-item.active .page-link {
            background: var(--admin-primary);
            color: #ffffff;
            font-weight: 700;
        }
        .admin-pagination-list .page-item.disabled .page-link {
            color: #cbd5e1;
            cursor: not-allowed;
            background: #fafafa;
        }

        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand-box" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); background: #0b1120;">
            <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; text-decoration: none;">
                @if($adminLogo = \App\Models\SiteSetting::logoUrl())
                    <img src="{{ $adminLogo }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}" style="max-height: 38px; max-width: 140px; object-fit: contain; background: #ffffff; padding: 4px 8px; border-radius: 4px;">
                @else
                    <span class="sidebar-logo" style="font-size: 18px; font-weight: 700; color: #ffffff;">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}<span style="color: var(--admin-primary);">.ADMIN</span></span>
                @endif
            </a>
            <a href="{{ route('home') }}" target="_blank" title="View Storefront" style="color: #94a3b8; font-size: 14px; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#94a3b8'">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>

        <div class="sidebar-menu-wrap">
            <div class="menu-heading">Main</div>
            <ul class="sidebar-menu">
                <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>

            <!-- Orders & Sales (First) -->
            <div class="menu-heading">Orders &amp; Sales</div>
            <ul class="sidebar-menu">
                <li class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}" class="sidebar-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders Management</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.order-statuses.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.order-statuses.index') }}" class="sidebar-link">
                        <i class="fas fa-tags"></i>
                        <span>Order Statuses</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.shipping.index') }}" class="sidebar-link">
                        <i class="fas fa-truck"></i>
                        <span>Shipping Charges</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.coupons.index') }}" class="sidebar-link">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Coupons &amp; Discounts</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.customers.index') }}" class="sidebar-link">
                        <i class="fas fa-users"></i>
                        <span>Customers</span>
                    </a>
                </li>
            </ul>

            <!-- Catalog & Products (Second) -->
            <div class="menu-heading">Catalog &amp; Products</div>
            <ul class="sidebar-menu">
                <li class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}" class="sidebar-link">
                        <i class="fas fa-couch"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}" class="sidebar-link">
                        <i class="fas fa-th-large"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subcategories.index') }}" class="sidebar-link">
                        <i class="fas fa-stream"></i>
                        <span>Sub Categories</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.childcategories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.childcategories.index') }}" class="sidebar-link">
                        <i class="fas fa-sitemap"></i>
                        <span>Child Categories</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.colors.*') || request()->routeIs('admin.sizes.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.colors.index') }}" class="sidebar-link">
                        <i class="fas fa-palette"></i>
                        <span>Colors &amp; Sizes</span>
                    </a>
                </li>
            </ul>

            <!-- Frontend & Homepage -->
            <div class="menu-heading">Frontend &amp; Homepage</div>
            <ul class="sidebar-menu">
                <li class="sidebar-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.sliders.index') }}" class="sidebar-link">
                        <i class="fas fa-images"></i>
                        <span>Hero Sliders</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.solutions.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.solutions.index') }}" class="sidebar-link">
                        <i class="fas fa-th-large"></i>
                        <span>Homepage Solutions</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.brands.index') }}" class="sidebar-link">
                        <i class="fas fa-trademark"></i>
                        <span>Brand Portfolio</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}" class="sidebar-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Pages Management</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.catalogs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.catalogs.index') }}" class="sidebar-link">
                        <i class="fas fa-book-open"></i>
                        <span>E-Catalogs</span>
                    </a>
                </li>
            </ul>

            <!-- Administration -->
            <div class="menu-heading">Administration</div>
            <ul class="sidebar-menu">
                <li class="sidebar-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.roles.index') }}" class="sidebar-link">
                        <i class="fas fa-user-shield"></i>
                        <span>Roles &amp; Permissions</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}" class="sidebar-link">
                        <i class="fas fa-sliders-h"></i>
                        <span>Site Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a;">@yield('page_title', 'Dashboard')</h2>
            </div>

            <div class="topbar-right">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline btn-sm">
                    <i class="fas fa-store"></i> Live Storefront
                </a>

                <!-- Admin User Profile Dropdown -->
                <div class="admin-user-dropdown-wrap" id="adminUserDropdownWrap">
                    <button type="button" class="admin-user-profile-btn" id="adminProfileDropdownBtn" onclick="toggleAdminUserDropdown(event)" aria-expanded="false" title="Account settings">
                        <img src="{{ Auth::guard('admin')->user()?->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin' }}" alt="Avatar" class="admin-avatar">
                        <div style="text-align: left;">
                            <div style="font-weight: 700; font-size: 12.5px; color: #0f172a; line-height: 1.2;">
                                {{ Auth::guard('admin')->user()?->name ?? 'Admin User' }}
                            </div>
                            <div style="font-size: 10.5px; color: #ea1d24; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                                <span>{{ Auth::guard('admin')->user()?->roles?->first()?->name ?? 'Super Admin' }}</span>
                                <i class="fas fa-chevron-down" style="font-size: 9px; color: #64748b; margin-left: 2px;"></i>
                            </div>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="admin-user-dropdown-menu" id="adminProfileDropdownMenu">
                        <div class="admin-user-dropdown-header">
                            <div style="font-weight: 700; color: #0f172a; font-size: 13px;">{{ Auth::guard('admin')->user()?->name }}</div>
                            <div style="font-size: 11px; color: #64748b; margin-bottom: 6px;">{{ Auth::guard('admin')->user()?->email }}</div>
                            <span class="badge badge-info" style="font-size: 10px;">{{ Auth::guard('admin')->user()?->roles?->first()?->name ?? 'Super Admin' }}</span>
                        </div>
                        <div class="admin-user-dropdown-body">
                            <a href="{{ route('admin.profile') }}" class="dropdown-item-link">
                                <i class="fas fa-user-edit" style="color: #0284c7; width: 16px;"></i> Edit Profile & Password
                            </a>
                            <a href="{{ route('admin.roles.index') }}" class="dropdown-item-link">
                                <i class="fas fa-user-shield" style="color: #9333ea; width: 16px;"></i> Roles & Permissions
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="dropdown-item-link">
                                <i class="fas fa-sliders-h" style="color: #d97706; width: 16px;"></i> Site Settings
                            </a>
                        </div>
                        <div class="admin-user-dropdown-footer">
                            <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-logout-btn">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Body Area -->
        <main class="admin-body">
            <!-- Flash alerts -->
            @if(session('success'))
                <div class="admin-alert admin-alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="admin-alert admin-alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="admin-alert admin-alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleAdminUserDropdown(e) {
            e.stopPropagation();
            const menu = document.getElementById('adminProfileDropdownMenu');
            const btn = document.getElementById('adminProfileDropdownBtn');
            if (menu) {
                menu.classList.toggle('show');
                btn.classList.toggle('active');
            }
        }

        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('adminUserDropdownWrap');
            const menu = document.getElementById('adminProfileDropdownMenu');
            const btn = document.getElementById('adminProfileDropdownBtn');
            if (wrap && !wrap.contains(e.target) && menu && menu.classList.contains('show')) {
                menu.classList.remove('show');
                if (btn) btn.classList.remove('active');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const menu = document.getElementById('adminProfileDropdownMenu');
                const btn = document.getElementById('adminProfileDropdownBtn');
                if (menu && menu.classList.contains('show')) {
                    menu.classList.remove('show');
                    if (btn) btn.classList.remove('active');
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
