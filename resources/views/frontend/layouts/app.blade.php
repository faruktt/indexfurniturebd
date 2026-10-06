<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', \App\Models\SiteSetting::get('site_name', 'Indexfurniture') . ' - ' . \App\Models\SiteSetting::get('tagline', 'Living Reimagined | Official Online Store'))</title>

    @if($siteFavicon = \App\Models\SiteSetting::faviconUrl())
        <link rel="shortcut icon" href="{{ $siteFavicon }}" type="image/x-icon">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --otobi-red: #ea1d24;
            --otobi-red-hover: #c41218;
            --otobi-dark: #222222;
            --otobi-blue: #0077b6;
            --otobi-blue-header: #3b82f6;
            --otobi-header-bg: #4a90e2;
            --border-light: #e5e7eb;
            --text-main: #333333;
            --text-muted: #666666;
            --text-light: #888888;
            --bg-body: #ffffff;
            --bg-gray: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            font-size: 13px;
            line-height: 1.5;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        a:hover {
            color: var(--otobi-red);
        }

        .container {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Top Header */
        .top-bar {
            background-color: #ffffff;
            border-bottom: 1px solid #ededed;
            padding: 6px 0;
            font-size: 11.5px;
            color: #666666;
        }

        .top-bar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .top-bar-left a {
            color: #666666;
        }

        .top-bar-left a:hover {
            color: var(--otobi-red);
        }

        .top-social-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .top-social-links a {
            color: #555555;
            font-size: 12px;
        }

        .top-social-links a:hover {
            color: var(--otobi-red);
        }

        /* Main Header */
        .main-header {
            padding: 16px 0 12px;
            background-color: #ffffff;
        }

        .main-header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Search Bar */
        .search-box {
            position: relative;
            width: 250px;
        }

        .search-box input {
            width: 100%;
            padding: 7px 34px 7px 12px;
            background-color: #ededed;
            border: 1px solid #dfdfdf;
            border-radius: 20px;
            font-size: 12px;
            outline: none;
            color: #333333;
        }

        .search-box input::placeholder {
            color: #777777;
        }

        .search-box button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #777777;
            cursor: pointer;
            font-size: 13px;
        }

        .search-box button:hover {
            color: var(--otobi-red);
        }

        /* Otobi Logo */
        .brand-logo-wrap {
            text-align: center;
        }

        .otobi-logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 38px;
            font-weight: 700;
            letter-spacing: -0.05em;
            color: #000000;
            line-height: 1;
            position: relative;
            display: inline-block;
        }

        .otobi-logo-text sup {
            font-size: 11px;
            font-weight: 600;
            position: absolute;
            top: 2px;
            right: -14px;
        }

        /* Header Right: Login/Signup/Cart */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 12px;
            color: #555555;
        }

        .header-actions a {
            color: #555555;
        }

        .header-actions a:hover {
            color: var(--otobi-red);
        }

        .header-cart-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            color: #000000;
            font-size: 18px;
            margin-left: 5px;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background-color: var(--otobi-red);
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            width: 17px;
            height: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Navigation & Mega Menu */
        .nav-wrapper {
            background-color: #ffffff;
            border-top: 1px solid #ebebeb;
            border-bottom: 1px solid #e0e0e0;
            position: relative;
            z-index: 100;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: block;
            padding: 11px 4px;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #333333;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-item:hover > .nav-link,
        .nav-item.active > .nav-link {
            color: var(--otobi-red);
            border-bottom: 2px solid var(--otobi-red);
        }

        /* Mega Dropdown Menu (Matching Image 5) */
        .mega-dropdown {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            width: 820px;
            max-width: 95vw;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-bottom: 3px solid var(--otobi-red);
            padding: 24px 30px;
            display: none;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            z-index: 999;
            text-align: left;
        }

        .nav-item:hover .mega-dropdown {
            display: grid;
        }

        .mega-col-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #222222;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 1px solid #f0f0f0;
        }

        .mega-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mega-list li {
            margin-bottom: 7px;
            font-size: 11.5px;
            position: relative;
        }

        .mega-list li a {
            color: #666666;
            display: inline-block;
        }

        .mega-list li a:before {
            content: "•";
            color: #999999;
            margin-right: 6px;
        }

        .mega-list li a:hover {
            color: var(--otobi-red);
        }

        .mega-sublist {
            list-style: none;
            padding-left: 14px;
            margin-top: 4px;
        }

        .mega-sublist li a:before {
            content: "○";
            font-size: 9px;
            margin-right: 5px;
        }

        /* Simple Clean E-Catalog Dropdown (like category subcategories) */
        .ecatalog-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 250px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            border-top: 2px solid var(--otobi-red);
            border-bottom: 1px solid #e5e5e5;
            border-left: 1px solid #e5e5e5;
            border-right: 1px solid #e5e5e5;
            list-style: none;
            margin: 0;
            padding: 6px 0;
            display: none;
            z-index: 999;
            text-align: left;
        }

        .nav-item:hover .ecatalog-dropdown-menu {
            display: block;
        }

        .ecatalog-dropdown-menu li {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .ecatalog-dropdown-menu li a {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 18px;
            font-size: 12px;
            font-weight: 600;
            color: #444444;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .ecatalog-dropdown-menu li a:hover {
            color: var(--otobi-red);
            background-color: #f8fafc;
            padding-left: 22px;
        }

        /* Alerts */
        .alert-box {
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 13px;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Footer Layout (Matching Image 1, 2, 3) */
        .site-footer {
            background-color: #ffffff;
            border-top: 1px solid #e0e0e0;
            padding: 45px 0 25px;
            margin-top: 50px;
            color: #555555;
            font-size: 11.5px;
            line-height: 1.8;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2.2fr 1.3fr 1.3fr 2.2fr;
            gap: 30px;
            margin-bottom: 35px;
        }

        .footer-logo {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.05em;
            color: #000000;
            margin-bottom: 12px;
            display: inline-block;
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 6px;
            color: #666666;
        }

        .footer-contact-item i {
            margin-top: 3px;
            color: #777777;
            font-size: 12px;
        }

        .footer-col-title {
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #111111;
            margin-bottom: 15px;
            letter-spacing: 0.05em;
        }

        .footer-links-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links-list li {
            margin-bottom: 6px;
        }

        .footer-links-list a {
            color: #666666;
        }

        .footer-links-list a:hover {
            color: var(--otobi-red);
        }

        .footer-social-icons {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }

        .footer-social-icons a {
            color: #555555;
            font-size: 13px;
        }

        .footer-social-icons a:hover {
            color: var(--otobi-red);
        }

        /* Payment Icons Grid */
        .payment-logos-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            background: #ffffff;
            padding: 8px;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            margin-bottom: 10px;
            align-items: center;
        }

        .payment-logo-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 3px 6px;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 700;
            color: #333333;
            height: 24px;
        }

        .sslcommerz-badge {
            font-size: 11px;
            color: #444444;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        .sslcommerz-badge span {
            background-color: #0b2545;
            color: #ffffff;
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 10px;
            letter-spacing: 0.05em;
        }

        .footer-bottom-bar {
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #777777;
        }

        @media (max-width: 992px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
            .mega-dropdown {
                width: 95vw;
                grid-template-columns: 1fr 1fr;
            }
        }

        /* Mobile Dedicated Header (Matching Screenshot) */
        .mobile-header-bar {
            display: none;
        }

        .mobile-search-row {
            display: none;
        }

        /* Mobile Category Drawer & Overlay */
        .mobile-drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(2px);
            z-index: 9998;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .mobile-drawer-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .mobile-category-drawer {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 320px;
            max-width: 86vw;
            background: #ffffff;
            z-index: 9999;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.18);
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .mobile-category-drawer.open {
            transform: translateX(0);
        }

        .drawer-header {
            padding: 16px 20px;
            border-bottom: 1px solid #eef2f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }

        .drawer-close-btn {
            background: #f1f5f9;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .drawer-close-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .drawer-body {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 30px;
        }

        .drawer-section-heading {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            padding: 14px 20px 6px;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }

        .drawer-cat-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px 12px 20px;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }

        .drawer-cat-row:hover {
            background: #f8fafc;
        }

        .drawer-cat-link {
            font-size: 13.5px;
            font-weight: 600;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            flex: 1;
            padding-right: 8px;
            text-decoration: none;
        }

        .drawer-cat-link:hover {
            color: var(--otobi-red);
        }

        .drawer-expand-btn {
            width: 30px;
            height: 30px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .drawer-expand-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .drawer-sub-tree {
            background: #f8fafc;
            border-bottom: 1px solid #edf2f7;
        }

        .drawer-sub-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 18px 10px 32px;
            border-bottom: 1px solid #f1f5f9;
        }

        .drawer-sub-link {
            font-size: 12.5px;
            font-weight: 500;
            color: #334155;
            flex: 1;
            padding-right: 8px;
            text-decoration: none;
        }

        .drawer-sub-link:hover {
            color: var(--otobi-red);
        }

        .drawer-child-tree {
            background: #f1f5f9;
            padding: 4px 0 4px 44px;
            border-bottom: 1px solid #e2e8f0;
        }

        .drawer-child-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px 7px 0;
            font-size: 12px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.15s;
        }

        .drawer-child-link:hover {
            color: var(--otobi-red);
            padding-left: 4px;
        }

        .drawer-account-section {
            padding: 16px 20px;
            background: #ffffff;
        }

        .drawer-user-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 0;
            font-size: 13px;
            color: #334155;
            font-weight: 500;
            text-decoration: none;
        }

        .drawer-user-link:hover {
            color: var(--otobi-red);
        }

        .drawer-logout-btn {
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            border-radius: 4px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-drawer-auth {
            flex: 1;
            padding: 8px 12px;
            border-radius: 4px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .floating-whatsapp-btn {
            position: fixed;
            bottom: 24px;
            right: 20px;
            width: 52px;
            height: 52px;
            background: #25d366;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.45);
            z-index: 999;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .floating-whatsapp-btn:hover {
            transform: scale(1.08);
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(37, 211, 102, 0.6);
        }

        @media (max-width: 768px) {
            .top-bar {
                display: none;
            }
            .main-header {
                display: none;
            }
            .nav-wrapper {
                display: none;
            }
            .mobile-header-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 18px;
                background: #ffffff;
                border-bottom: 1px solid #ededed;
                position: sticky;
                top: 0;
                z-index: 100;
            }
            .mobile-logo img {
                max-height: 42px;
                max-width: 150px;
                object-fit: contain;
                display: block;
            }
            .mobile-header-right {
                display: flex;
                align-items: center;
                gap: 18px;
            }
            .mobile-cart-btn {
                position: relative;
                color: #000000;
                font-size: 21px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .mobile-cart-btn .cart-badge {
                position: absolute;
                top: -8px;
                right: -10px;
                background-color: var(--otobi-red);
                color: #ffffff;
                font-size: 10px;
                font-weight: 700;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .mobile-menu-toggle-btn {
                background: none;
                border: none;
                color: #111111;
                font-size: 24px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 4px 6px;
                line-height: 1;
            }
            .mobile-search-row {
                display: block;
                padding: 8px 18px 12px;
                background: #ffffff;
                border-bottom: 1px solid #eeeeee;
            }
            .mobile-search-form {
                position: relative;
                width: 100%;
            }
            .mobile-search-form input {
                width: 100%;
                padding: 9px 38px 9px 14px;
                background: #ededed;
                border: 1px solid #dfdfdf;
                border-radius: 4px;
                font-size: 13px;
                outline: none;
                color: #333333;
            }
            .mobile-search-form input::placeholder {
                color: #777777;
            }
            .mobile-search-form button {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #777777;
                font-size: 15px;
                cursor: pointer;
            }
            .footer-grid {
                grid-template-columns: 1fr;
            }
            .footer-bottom-bar {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @php
        $allNavCategories = \App\Models\Category::with(['subCategories.childCategories'])->where('status', true)->where('slug', '!=', 'e-catalog')->orderBy('order', 'asc')->get();
        $navCatalogs = \App\Models\Catalog::active()->get();
        $cartCount = count(session('cart', []));
    @endphp

    <!-- Mobile Header Bar (Matching Screenshot) -->
    <div class="mobile-header-bar">
        <a href="{{ route('home') }}" class="mobile-logo">
            @if($brandLogo = \App\Models\SiteSetting::logoUrl())
                <img src="{{ $brandLogo }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}">
            @else
                <span class="otobi-logo-text" style="font-size: 32px;">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}<sup>®</sup></span>
            @endif
        </a>

        <div class="mobile-header-right">
            <a href="{{ route('checkout') }}" class="mobile-cart-btn" title="View Cart / Checkout">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-badge">{{ $cartCount }}</span>
            </a>
            <button type="button" class="mobile-menu-toggle-btn" id="openMobileDrawerBtn" aria-label="Open Category Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Search Row (Matching Screenshot) -->
    <div class="mobile-search-row">
        <form action="{{ route('products.index') }}" method="GET" class="mobile-search-form">
            <input type="text" name="search" placeholder="Search your choice" value="{{ request('search') }}">
            <button type="submit" title="Search"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- 1. Top Header Bar (Desktop Only) -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <a href="{{ route('home') }}">Home</a>
                <span>|</span>
                <span>Customer Care: <strong>{{ \App\Models\SiteSetting::get('phone_primary', '+880 9613808080') }}</strong> ({{ \App\Models\SiteSetting::get('customer_care_hours', '9 AM – 6 PM') }}), <strong>{{ \App\Models\SiteSetting::get('phone_secondary', '+88 01847088444') }}</strong></span>
                <span>|</span>
                <a href="#store-location">Store Location</a>
            </div>
            <div class="top-social-links">
                <a href="{{ \App\Models\SiteSetting::get('facebook', 'https://facebook.com') }}" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ \App\Models\SiteSetting::get('twitter', 'https://twitter.com') }}" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="{{ \App\Models\SiteSetting::get('linkedin', 'https://linkedin.com') }}" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="{{ \App\Models\SiteSetting::get('instagram', 'https://instagram.com') }}" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="{{ \App\Models\SiteSetting::get('youtube', 'https://youtube.com') }}" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- 2. Main Brand Header (Desktop Only) -->
    <header class="main-header">
        <div class="container">
            <!-- Search Box -->
            <form action="{{ route('products.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Search your choice" value="{{ request('search') }}">
                <button type="submit" title="Search"><i class="fas fa-search"></i></button>
            </form>

            <!-- Brand Logo -->
            <div class="brand-logo-wrap">
                <a href="{{ route('home') }}" style="display: inline-block;">
                    @if($brandLogo = \App\Models\SiteSetting::logoUrl())
                        <img src="{{ $brandLogo }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}" style="max-height: 52px; max-width: 240px; object-fit: contain;">
                    @else
                        <span class="otobi-logo-text">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}<sup>®</sup></span>
                    @endif
                </a>
            </div>

            <!-- Header Actions -->
            <div class="header-actions">
                @if(Auth::guard('web')->check())
                    <a href="{{ route('customer.dashboard') }}" style="font-weight: 600;">
                        <i class="fas fa-user-circle"></i> {{ Auth::guard('web')->user()->name }}
                    </a>
                    <span>|</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; font-size: inherit; color: inherit; cursor: pointer; padding: 0;">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Sign Up</a>
                @endif

                <!-- Shopping Cart Icon -->
                <a href="{{ route('checkout') }}" class="header-cart-btn" title="View Cart / Checkout">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge" id="cartCountBadge">{{ $cartCount }}</span>
                </a>
            </div>
        </div>
    </header>

    <!-- 3. Navigation Bar & Mega Menu -->
    <nav class="nav-wrapper">
        <div class="container">
            <ul class="nav-menu">
                @php
                    $allNavCategories = \App\Models\Category::with(['subCategories.childCategories'])->where('status', true)->where('slug', '!=', 'e-catalog')->orderBy('order', 'asc')->get();
                @endphp

                @foreach($allNavCategories as $navCat)
                    @php
                        $isActive = request('category') === $navCat->slug || (isset($category) && $category->id === $navCat->id);
                    @endphp
                    <li class="nav-item {{ $isActive ? 'active' : '' }}">
                        <a href="{{ route('products.index', ['category' => $navCat->slug]) }}" class="nav-link">
                            {{ $navCat->name }}
                        </a>

                        @if($navCat->subCategories->count() > 0)
                            <div class="mega-dropdown">
                                @foreach($navCat->subCategories as $sub)
                                    <div class="mega-col">
                                        <div class="mega-col-title">
                                            <a href="{{ route('products.index', ['category' => $navCat->slug, 'subcategory' => $sub->slug]) }}">
                                                {{ $sub->name }}
                                            </a>
                                        </div>
                                        @if($sub->childCategories->count() > 0)
                                            <ul class="mega-list">
                                                @foreach($sub->childCategories as $child)
                                                    <li>
                                                        <a href="{{ route('products.index', ['category' => $navCat->slug, 'subcategory' => $sub->slug, 'childcategory' => $child->slug]) }}">
                                                            {{ $child->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach

                <!-- E-CATALOG (at the end of all categories, opens clean list like subcategories) -->
                @php
                    $navCatalogs = \App\Models\Catalog::active()->get();
                @endphp
                @if($navCatalogs->count() > 0)
                    <li class="nav-item">
                        <a href="{{ $navCatalogs->first()->pdf_url }}" target="_blank" class="nav-link" style="color: var(--otobi-red); font-weight: 700;">
                            E-CATALOG
                        </a>

                        <ul class="ecatalog-dropdown-menu">
                            @foreach($navCatalogs as $catItem)
                                <li>
                                    <a href="{{ $catItem->pdf_url }}" target="_blank">
                                        <i class="fas fa-file-pdf" style="color: var(--otobi-red); font-size: 11.5px;"></i>
                                        <span>{{ $catItem->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container" style="margin-top: 10px;">
        @if(session('success'))
            <div class="alert-box alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-box alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Main Content Yield -->
    <main>
        @yield('content')
    </main>

    <!-- 4. Site Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Col 1: Brand & Contact -->
                <div>
                    <div class="footer-logo">
                        @if($brandLogo = \App\Models\SiteSetting::logoUrl())
                            <img src="{{ $brandLogo }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'OTOBI') }}" style="max-height: 42px; max-width: 180px; object-fit: contain;">
                        @else
                            {{ \App\Models\SiteSetting::get('site_name', 'otobi') }}<sup>®</sup>
                        @endif
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ \App\Models\SiteSetting::get('address', 'OTOBI Center (3rd Floor), Plot 12, Block CWS (C), Gulshan South Avenue, Gulshan-1, Dhaka 1212, Bangladesh.') }}</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ \App\Models\SiteSetting::get('email', 'info@otobi.com') }}</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>{{ \App\Models\SiteSetting::get('phone_primary', '+880 9613808080') }} ({{ \App\Models\SiteSetting::get('customer_care_hours', '9am – 6pm') }})<br>{{ \App\Models\SiteSetting::get('phone_secondary', '+88 01847088444') }} ({{ \App\Models\SiteSetting::get('customer_care_hours', '9am – 6pm') }})</span>
                    </div>
                    <div class="footer-contact-item" style="margin-top: 5px;">
                        <span>Customer Care: {{ \App\Models\SiteSetting::get('phone_primary', '+880 9613808080') }} ({{ \App\Models\SiteSetting::get('customer_care_hours', '9am – 6pm') }})</span>
                    </div>
                </div>

                <!-- Col 2: The Company -->
                <div>
                    <div class="footer-col-title">THE COMPANY</div>
                    <ul class="footer-links-list">
                        @if(isset($footerCompanyPages) && $footerCompanyPages->count() > 0)
                            @foreach($footerCompanyPages as $p)
                                <li><a href="{{ route('pages.show', $p->slug) }}">{{ $p->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('pages.show', 'about-us') }}">About Us</a></li>
                            <li><a href="{{ route('pages.show', 'factory') }}">Otobi Factory</a></li>
                            <li><a href="{{ route('pages.show', 'awards') }}">Awards &amp; Milestones</a></li>
                            <li><a href="{{ route('pages.show', 'corporate') }}">Corporate Solutions</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Col 3: More Information -->
                <div>
                    <div class="footer-col-title">MORE INFORMATION</div>
                    <ul class="footer-links-list">
                        <li><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                        @if(isset($footerInfoPages) && $footerInfoPages->count() > 0)
                            @foreach($footerInfoPages as $p)
                                <li><a href="{{ route('pages.show', $p->slug) }}">{{ $p->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('pages.show', 'privacy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('pages.show', 'store') }}">Store Location</a></li>
                            <li><a href="{{ route('pages.show', 'coverage') }}">Delivery Coverage Area</a></li>
                            <li><a href="{{ route('pages.show', 'sitemap') }}">Sitemap</a></li>
                        @endif
                    </ul>
                    <div style="margin-top: 12px; font-weight: 600; color: #333333;">Follow Us On Social</div>
                    <div class="footer-social-icons">
                        <a href="{{ \App\Models\SiteSetting::get('facebook', 'https://facebook.com') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ \App\Models\SiteSetting::get('twitter', 'https://twitter.com') }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="{{ \App\Models\SiteSetting::get('linkedin', 'https://linkedin.com') }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="{{ \App\Models\SiteSetting::get('instagram', 'https://instagram.com') }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="{{ \App\Models\SiteSetting::get('youtube', 'https://youtube.com') }}" target="_blank"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Col 4: Online Payment -->
                <div>
                    <div class="footer-col-title">ONLINE PAYMENT</div>
                    @if(file_exists(public_path('uploads/otobi_payment_methods.png')))
                        <div style="background: #ffffff; padding: 10px; border-radius: 4px; border: 1px solid #e0e0e0; margin-bottom: 12px;">
                            <img src="{{ asset('uploads/otobi_payment_methods.png') }}" alt="Payment Channels" style="width: 100%; height: auto; display: block;">
                        </div>
                    @else
                        <div class="payment-logos-grid">
                            <span class="payment-logo-badge" style="color: #1a1f71;">VISA</span>
                            <span class="payment-logo-badge" style="color: #eb001b;">MasterCard</span>
                            <span class="payment-logo-badge" style="color: #006fcf;">AMEX</span>
                            <span class="payment-logo-badge" style="color: #e2136e;">bKash</span>
                            <span class="payment-logo-badge" style="color: #8c1d40;">Rocket</span>
                            <span class="payment-logo-badge" style="color: #f7941d;">Nagad</span>
                            <span class="payment-logo-badge" style="color: #0070ba;">DBBL Nexus</span>
                            <span class="payment-logo-badge" style="color: #2b7a78;">Upay</span>
                            <span class="payment-logo-badge" style="color: #ffb703;">Tap</span>
                            <span class="payment-logo-badge" style="color: #023047;">OK Wallet</span>
                        </div>
                    @endif
                    <div class="sslcommerz-badge">
                        Verified By <span>SSLCOMMERZ</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="footer-bottom-bar">
                <div>{{ \App\Models\SiteSetting::get('copyright', 'Copyright ' . date('Y') . ' © Indexfurniture. All Rights Reserved.') }}</div>
                <div>{{ \App\Models\SiteSetting::get('developed_by', 'Developed By Access InfoTech Ltd.') }}</div>
            </div>
        </div>
    </footer>

    <!-- Mobile Category Off-canvas Drawer -->
    <div id="mobileDrawerOverlay" class="mobile-drawer-overlay"></div>
    <div id="mobileCategoryDrawer" class="mobile-category-drawer">
        <div class="drawer-header">
            <div class="drawer-title">
                @if($brandLogo = \App\Models\SiteSetting::logoUrl())
                    <img src="{{ $brandLogo }}" alt="Indexfurniture" style="max-height: 36px; max-width: 140px; object-fit: contain;">
                @else
                    <span style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 700; color: #000;">Indexfurniture<sup>®</sup></span>
                @endif
            </div>
            <button type="button" class="drawer-close-btn" id="closeMobileDrawerBtn" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="drawer-body">
            <div class="drawer-section-heading">
                <i class="fas fa-th-large" style="color: var(--otobi-red); margin-right: 5px;"></i> Categories
            </div>

            <div class="drawer-categories-tree">
                @foreach($allNavCategories as $navCat)
                    <div class="drawer-cat-item">
                        <div class="drawer-cat-row">
                            <a href="{{ route('products.index', ['category' => $navCat->slug]) }}" class="drawer-cat-link">
                                {{ $navCat->name }}
                            </a>
                            @if($navCat->subCategories->count() > 0)
                                <button type="button" class="drawer-expand-btn" onclick="toggleDrawerAccordion(this, 'drawer-sub-{{ $navCat->id }}')" aria-label="Expand {{ $navCat->name }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            @endif
                        </div>

                        @if($navCat->subCategories->count() > 0)
                            <div class="drawer-sub-tree" id="drawer-sub-{{ $navCat->id }}" style="display: none;">
                                @foreach($navCat->subCategories as $sub)
                                    <div class="drawer-sub-item">
                                        <div class="drawer-sub-row">
                                            <a href="{{ route('products.index', ['category' => $navCat->slug, 'subcategory' => $sub->slug]) }}" class="drawer-sub-link">
                                                {{ $sub->name }}
                                            </a>
                                            @if($sub->childCategories->count() > 0)
                                                <button type="button" class="drawer-expand-btn drawer-child-expand-btn" onclick="toggleDrawerAccordion(this, 'drawer-child-{{ $sub->id }}')" aria-label="Expand {{ $sub->name }}">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            @endif
                                        </div>

                                        @if($sub->childCategories->count() > 0)
                                            <div class="drawer-child-tree" id="drawer-child-{{ $sub->id }}" style="display: none;">
                                                @foreach($sub->childCategories as $child)
                                                    <a href="{{ route('products.index', ['category' => $navCat->slug, 'subcategory' => $sub->slug, 'childcategory' => $child->slug]) }}" class="drawer-child-link">
                                                        <i class="fas fa-angle-right" style="font-size: 10px; color: #94a3b8;"></i>
                                                        <span>{{ $child->name }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- E-Catalog Link in Drawer -->
                @if($navCatalogs->count() > 0)
                    <div class="drawer-cat-item">
                        <div class="drawer-cat-row">
                            <a href="{{ $navCatalogs->first()->pdf_url }}" target="_blank" class="drawer-cat-link" style="color: var(--otobi-red); font-weight: 700;">
                                <i class="fas fa-book-open" style="margin-right: 6px;"></i> E-CATALOG
                            </a>
                            @if($navCatalogs->count() > 1)
                                <button type="button" class="drawer-expand-btn" onclick="toggleDrawerAccordion(this, 'drawer-catalogs-list')" aria-label="Expand Catalogs">
                                    <i class="fas fa-plus"></i>
                                </button>
                            @endif
                        </div>
                        @if($navCatalogs->count() > 1)
                            <div class="drawer-sub-tree" id="drawer-catalogs-list" style="display: none;">
                                @foreach($navCatalogs as $catItem)
                                    <a href="{{ $catItem->pdf_url }}" target="_blank" class="drawer-sub-link" style="color: #444; display: flex; align-items: center; gap: 8px; padding: 10px 24px;">
                                        <i class="fas fa-file-pdf" style="color: var(--otobi-red);"></i>
                                        <span>{{ $catItem->title }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Drawer User/Account Shortcuts -->
            <div class="drawer-account-section">
                <div class="drawer-section-heading" style="padding-left: 0; background: transparent; border: none;">Account &amp; Support</div>
                @if(Auth::guard('web')->check())
                    <a href="{{ route('customer.dashboard') }}" class="drawer-user-link">
                        <i class="fas fa-user-circle" style="color: var(--otobi-blue);"></i>
                        <span>{{ Auth::guard('web')->user()->name }} (Dashboard)</span>
                    </a>
                    <a href="{{ route('customer.orders') }}" class="drawer-user-link">
                        <i class="fas fa-box" style="color: #64748b;"></i>
                        <span>My Orders</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin-top: 8px;">
                        @csrf
                        <button type="submit" class="drawer-logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                @else
                    <div style="display: flex; gap: 10px; margin-top: 8px;">
                        <a href="{{ route('login') }}" class="btn-drawer-auth" style="background: #3e80d1; color: #fff;">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn-drawer-auth" style="background: #f1f5f9; color: #333;">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </div>
                @endif

                <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid #eef2f6; font-size: 11.5px; color: #64748b;">
                    <div><i class="fas fa-headset" style="color: var(--otobi-red); margin-right: 6px;"></i> Hotline: <strong>{{ \App\Models\SiteSetting::get('phone_primary', '+880 9613808080') }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating WhatsApp Button (Matching Screenshot) -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\SiteSetting::get('phone_secondary', '+8801847088444')) }}" 
       target="_blank" 
       class="floating-whatsapp-btn" 
       title="Chat with us on WhatsApp"
       aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script>
        function toggleDrawerAccordion(btn, targetId) {
            const target = document.getElementById(targetId);
            if (!target) return;
            const isExpanded = target.style.display === 'block';
            const icon = btn.querySelector('i');

            if (isExpanded) {
                target.style.display = 'none';
                if (icon) {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                }
            } else {
                target.style.display = 'block';
                if (icon) {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const openBtn = document.getElementById('openMobileDrawerBtn');
            const closeBtn = document.getElementById('closeMobileDrawerBtn');
            const overlay = document.getElementById('mobileDrawerOverlay');
            const drawer = document.getElementById('mobileCategoryDrawer');

            function openDrawer() {
                if (drawer) drawer.classList.add('open');
                if (overlay) overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeDrawer() {
                if (drawer) drawer.classList.remove('open');
                if (overlay) overlay.classList.remove('open');
                document.body.style.overflow = '';
            }

            if (openBtn) openBtn.addEventListener('click', openDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            if (overlay) overlay.addEventListener('click', closeDrawer);
        });
    </script>

    @stack('scripts')
</body>
</html>
