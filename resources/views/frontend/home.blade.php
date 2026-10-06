@extends('frontend.layouts.app')

@section('title', 'Indexfurniture - Living Reimagined | Official Online Store')

@push('styles')
<style>
    /* Hero Carousel Slider - Fully Dynamic */
    .hero-slider-wrap {
        position: relative;
        overflow: hidden;
        background-color: #2b1f1f;
        color: #ffffff;
        min-height: 580px;
        height: 580px;
    }

    .slide {
        display: none;
        position: relative;
        min-height: 580px;
        height: 100%;
        background-size: cover;
        background-position: center;
        align-items: center;
        transition: opacity 0.5s ease-in-out;
    }

    .slide.active {
        display: flex;
    }

    .hero-content {
        padding: 80px 40px;
        max-width: 620px;
        z-index: 2;
    }

    .hero-tagline-sub {
        font-family: 'Outfit', sans-serif;
        font-size: 24px;
        font-weight: 300;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin-bottom: -5px;
        color: #f7d6bf;
    }

    .hero-tagline-main {
        font-family: 'Outfit', sans-serif;
        font-size: 52px;
        font-weight: 700;
        line-height: 1.02;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 20px;
        color: #ffffff;
        text-shadow: 0 3px 12px rgba(0,0,0,0.5);
    }

    .hero-slider-arrows {
        position: absolute;
        width: 100%;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
        padding: 0 20px;
        pointer-events: none;
        z-index: 5;
    }

    .slider-arrow-btn {
        pointer-events: auto;
        background: rgba(0, 0, 0, 0.4);
        color: #ffffff;
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
    }

    .slider-arrow-btn:hover {
        background: var(--otobi-red);
    }

    .slider-dots {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 5;
    }

    .dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.2s;
    }

    .dot.active {
        background: #ffffff;
        width: 24px;
        border-radius: 6px;
    }

    /* Section Headings */
    .section-title-wrap {
        text-align: center;
        margin: 45px 0 25px;
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 19px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #222222;
        display: inline-block;
        position: relative;
    }

    /* 1. OTOBI SOLUTIONS */
    .solutions-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .solution-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        overflow: hidden;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        text-decoration: none;
    }

    .solution-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .solution-img-box {
        width: 100%;
        height: 190px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .solution-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .solution-card:hover .solution-img-box img {
        transform: scale(1.05);
    }

    .solution-label {
        padding: 12px 10px;
        background: #ffffff;
        font-size: 13px;
        font-weight: 700;
        color: #333333;
        border-top: 1px solid #eeeeee;
    }

    /* 2 & 3. Popular Categories Strip (Home & Office) */
    .popular-cats-strip {
        display: flex;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
        margin: 20px 0 35px;
    }

    .popular-cat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 110px;
        text-decoration: none;
    }

    .popular-cat-icon {
        width: 120px;
        height: 95px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        transition: all 0.25s ease;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .popular-cat-icon img {
        width: 90%;
        height: 80%;
        object-fit: contain;
        transition: transform 0.25s ease;
    }

    .popular-cat-item:hover .popular-cat-icon {
        border-color: var(--otobi-red);
        transform: scale(1.06);
        box-shadow: 0 4px 14px rgba(234, 29, 36, 0.2);
    }

    .popular-cat-item:hover .popular-cat-icon img {
        transform: scale(1.08);
    }

    .popular-cat-title {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #444444;
        line-height: 1.25;
    }

    .popular-cat-item:hover .popular-cat-title {
        color: var(--otobi-red);
    }

    /* 4. FEATURED FURNITURE CATEGORIES (Grid of 6) */
    .featured-cats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }

    .featured-cat-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .featured-cat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 22px rgba(0,0,0,0.1);
    }

    .featured-cat-img {
        width: 100%;
        height: 220px;
        background: #f8f9fa;
        overflow: hidden;
    }

    .featured-cat-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .featured-cat-card:hover .featured-cat-img img {
        transform: scale(1.05);
    }

    .featured-cat-body {
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
        border-top: 1px solid #f0f0f0;
    }

    .featured-cat-name {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #222222;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .featured-cat-link {
        font-size: 11px;
        font-weight: 700;
        color: var(--otobi-red);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        transition: color 0.2s;
    }

    .featured-cat-link:hover {
        color: #b01017;
        text-decoration: underline;
    }

    /* Featured Products Catalog Grid */
    .home-products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 45px;
    }

    .home-product-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 14px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
        position: relative;
    }

    .home-product-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-3px);
    }

    .home-prod-thumb {
        width: 100%;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ffffff;
        margin-bottom: 12px;
        overflow: hidden;
    }

    .home-prod-thumb img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        transition: transform 0.3s;
    }

    .home-product-card:hover .home-prod-thumb img {
        transform: scale(1.05);
    }

    .home-prod-code {
        font-size: 10px;
        font-weight: 600;
        color: #888888;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .home-prod-title {
        font-size: 12.5px;
        font-weight: 600;
        color: #222222;
        line-height: 1.4;
        margin-bottom: 8px;
        height: 36px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .home-prod-title a {
        color: #222222;
        text-decoration: none;
    }

    .home-prod-title a:hover {
        color: var(--otobi-red);
    }

    .home-prod-price {
        font-size: 14px;
        font-weight: 700;
        color: var(--otobi-red);
        margin-bottom: 12px;
    }

    .home-prod-old-price {
        font-size: 11px;
        color: #999999;
        text-decoration: line-through;
        margin-left: 6px;
    }

    .home-prod-actions {
        display: flex;
        gap: 8px;
    }

    .btn-view-prod {
        flex: 1;
        padding: 7px 10px;
        background: #f8f9fa;
        color: #333333;
        border: 1px solid #d1d5db;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }

    .btn-view-prod:hover {
        background: #e5e7eb;
        color: #111111;
    }

    .btn-cart-prod {
        padding: 7px 12px;
        background: var(--otobi-red);
        color: #ffffff;
        border: none;
        border-radius: 3px;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-cart-prod:hover {
        background: #b01017;
    }

    /* 5. OTOBI BRAND PORTFOLIO */
    .brand-portfolio-strip {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
        margin: 25px 0 45px;
        padding: 24px;
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }

    .brand-logo-item {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 55px;
        padding: 0 15px;
        transition: transform 0.2s, opacity 0.2s;
        text-decoration: none;
        cursor: pointer;
    }

    .brand-logo-item:hover {
        transform: scale(1.08);
        opacity: 0.95;
    }

    .brand-logo-item img {
        max-height: 48px;
        max-width: 140px;
        object-fit: contain;
    }

    /* 6. WHY OTOBI */
    .why-otobi-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 16px;
        margin-bottom: 55px;
    }

    .why-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        padding: 24px 16px;
        text-align: center;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .why-card:hover {
        transform: translateY(-3px);
        border-color: #cbd5e1;
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }

    .why-icon-box {
        width: 52px;
        height: 52px;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .why-icon-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .why-title {
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        color: #222222;
        margin-bottom: 6px;
        letter-spacing: 0.03em;
    }

    .why-desc {
        font-size: 11px;
        color: #666666;
        line-height: 1.45;
    }

    @media (max-width: 992px) {
        .hero-slider-wrap,
        .slide {
            min-height: 400px;
            height: 400px;
        }
        .solutions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .featured-cats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .home-products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .why-otobi-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        /* Hero Slider on Mobile (Matching Uploaded Screenshot) */
        .hero-slider-wrap,
        .slide {
            min-height: auto !important;
            height: 220px !important;
            background-size: cover !important;
            background-position: center !important;
        }
        .hero-slider-arrows {
            padding: 0 10px;
        }
        .slider-arrow-btn {
            width: 32px;
            height: 32px;
            font-size: 13px;
            background: rgba(0, 0, 0, 0.28);
        }
        .slider-dots {
            bottom: 8px;
            gap: 5px;
        }
        .dot {
            width: 6px;
            height: 6px;
        }
        .dot.active {
            width: 16px;
            border-radius: 4px;
        }
        .hero-content {
            padding: 20px 15px;
            max-width: 100%;
        }
        .hero-tagline-sub {
            font-size: 12px;
        }
        .hero-tagline-main {
            font-size: 20px;
            margin-bottom: 6px;
        }
        .hero-content p {
            display: none;
        }
        .hero-content a {
            padding: 6px 14px;
            font-size: 10px;
        }

        /* Products in Mobile View: 1 per line, full width */
        .home-products-grid {
            grid-template-columns: 1fr !important;
            gap: 18px;
        }
        .home-product-card {
            width: 100%;
            padding: 16px;
        }
        .home-prod-thumb {
            height: 220px;
        }
        .home-prod-thumb img {
            max-height: 95%;
        }

        /* Solutions & Why Otobi Grid on Mobile */
        .solutions-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .solution-img-box {
            height: 200px;
        }
        .featured-cats-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .why-otobi-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .hero-slider-wrap,
        .slide {
            height: 195px !important;
        }
    }
</style>
@endpush

@section('content')

    <!-- HERO SLIDER CAROUSEL (100% Dynamic from Admin Panel) -->
    <div class="hero-slider-wrap">
        @forelse($sliders as $index => $slide)
            @php
                $hasText = !empty($slide->title) || !empty($slide->subtitle) || !empty($slide->description) || !empty($slide->btn_text);
                $bgStyle = $hasText 
                    ? "background-image: linear-gradient(90deg, rgba(25, 15, 15, 0.82) 0%, rgba(35, 20, 20, 0.55) 45%, rgba(0, 0, 0, 0.15) 100%), url('{$slide->image_url}');"
                    : "background-image: url('{$slide->image_url}');";
            @endphp
            <div class="slide {{ $index === 0 ? 'active' : '' }}" 
                 style="{{ $bgStyle }} {{ !$hasText && $slide->btn_link ? 'cursor: pointer;' : '' }}"
                 @if(!$hasText && $slide->btn_link) onclick="window.location.href='{{ $slide->btn_link }}'" @endif>
                @if($hasText)
                    <div class="container">
                        <div class="hero-content">
                            @if($slide->subtitle)
                                <div class="hero-tagline-sub">{{ $slide->subtitle }}</div>
                            @endif
                            @if($slide->title)
                                <div class="hero-tagline-main">{!! nl2br(e($slide->title)) !!}</div>
                            @endif
                            @if($slide->description)
                                <p style="font-size: 13.5px; color: #fce7f3; margin-bottom: 22px; line-height: 1.6;">
                                    {{ $slide->description }}
                                </p>
                            @endif
                            @if($slide->btn_text)
                                <a href="{{ $slide->btn_link ?? route('products.index') }}" style="display: inline-block; padding: 11px 26px; background: var(--otobi-red); color: #ffffff; font-weight: 700; text-transform: uppercase; font-size: 12px; border-radius: 3px; letter-spacing: 0.05em; text-decoration: none; box-shadow: 0 4px 12px rgba(234, 29, 36, 0.4);">
                                    {{ $slide->btn_text }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <!-- Fallback slide if no sliders in database -->
            <div class="slide active" style="background-image: linear-gradient(90deg, rgba(30, 20, 20, 0.85) 0%, rgba(30, 20, 20, 0.4) 100%), url('{{ asset("uploads/otobi_banner_slide_1.jpg") }}');">
                <div class="container">
                    <div class="hero-content">
                        <div class="hero-tagline-sub">FOR</div>
                        <div class="hero-tagline-main">YOUR<br>COMFORT</div>
                        <p style="font-size: 13.5px; color: #fce7f3; margin-bottom: 22px; line-height: 1.6;">
                            Experience authentic master bedroom suites handcrafted with termite-proof laminated board and solid timber.
                        </p>
                        <a href="{{ route('products.index', ['category' => 'bedroom']) }}" style="display: inline-block; padding: 11px 26px; background: var(--otobi-red); color: #ffffff; font-weight: 700; text-transform: uppercase; font-size: 12px; border-radius: 3px; letter-spacing: 0.05em; text-decoration: none;">
                            Explore Bedroom Collection &gt;
                        </a>
                    </div>
                </div>
            </div>
        @endforelse

        @if($sliders->count() > 1)
            <!-- Arrows -->
            <div class="hero-slider-arrows">
                <button class="slider-arrow-btn" onclick="prevSlide()" title="Previous"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-arrow-btn" onclick="nextSlide()" title="Next"><i class="fas fa-chevron-right"></i></button>
            </div>

            <!-- Dots -->
            <div class="slider-dots">
                @foreach($sliders as $index => $slide)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="setSlide({{ $index }})"></span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="container">
        <!-- 1. SOLUTIONS (100% Dynamic from Admin Panel) -->
        <div class="section-title-wrap">
            <h2 class="section-title">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }} SOLUTIONS</h2>
        </div>

        <div class="solutions-grid">
            @forelse($solutions as $solution)
                <a href="{{ $solution->target_url }}" class="solution-card">
                    <div class="solution-img-box">
                        <img src="{{ $solution->image_url }}" alt="{{ $solution->name }}">
                    </div>
                    <div class="solution-label">{{ $solution->name }}</div>
                </a>
            @empty
                <!-- Fallback solutions if none in database -->
                <a href="{{ route('products.index', ['category' => 'living']) }}" class="solution-card">
                    <div class="solution-img-box">
                        <img src="{{ asset('uploads/otobi_solution_home.jpg') }}" alt="Home Solutions">
                    </div>
                    <div class="solution-label">Home Solutions</div>
                </a>

                <a href="{{ route('products.index', ['category' => 'office-table']) }}" class="solution-card">
                    <div class="solution-img-box">
                        <img src="{{ asset('uploads/otobi_solution_office.jpg') }}" alt="Office Solutions">
                    </div>
                    <div class="solution-label">Office Solutions</div>
                </a>

                <a href="{{ route('products.index', ['category' => 'hospital']) }}" class="solution-card">
                    <div class="solution-img-box">
                        <img src="{{ asset('uploads/otobi_solution_hospital.jpg') }}" alt="Hospital Solutions">
                    </div>
                    <div class="solution-label">Hospital Solutions</div>
                </a>

                <a href="{{ route('products.index') }}" class="solution-card">
                    <div class="solution-img-box">
                        <img src="{{ asset('uploads/otobi_solution_interior.jpg') }}" alt="Interior Solutions">
                    </div>
                    <div class="solution-label">Interior Solutions</div>
                </a>
            @endforelse
        </div>

        <!-- 2. EXPLORE OUR POPULAR HOME FURNITURE CATEGORIES (Dynamic from Database: strictly when is_popular_home = true) -->
        @php
            $hasHomePopular = (isset($popularHomeSubcategories) && $popularHomeSubcategories->count() > 0) 
                || (isset($popularHomeCategories) && $popularHomeCategories->count() > 0);
        @endphp

        @if($hasHomePopular)
            <div class="section-title-wrap">
                <h2 class="section-title">EXPLORE OUR POPULAR HOME FURNITURE CATEGORIES</h2>
            </div>

            <div class="popular-cats-strip">
                @if(isset($popularHomeSubcategories) && $popularHomeSubcategories->count() > 0)
                    @foreach($popularHomeSubcategories as $sub)
                        <a href="{{ route('products.index', ['category' => $sub->category?->slug ?? 'living', 'subcategory' => $sub->slug]) }}" class="popular-cat-item">
                            <div class="popular-cat-icon">
                                <img src="{{ $sub->image_url }}" alt="{{ $sub->name }}">
                            </div>
                            <span class="popular-cat-title">{{ $sub->name }}</span>
                        </a>
                    @endforeach
                @endif

                @if(isset($popularHomeCategories) && $popularHomeCategories->count() > 0)
                    @foreach($popularHomeCategories as $cat)
                        @if(!isset($popularHomeSubcategories) || $popularHomeSubcategories->where('category_id', $cat->id)->count() === 0)
                            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="popular-cat-item">
                                <div class="popular-cat-icon">
                                    <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}">
                                </div>
                                <span class="popular-cat-title">{{ $cat->name }}</span>
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        @endif

        <!-- 3. EXPLORE OUR POPULAR OFFICE FURNITURE CATEGORIES (Dynamic from Database: strictly when is_popular_office = true) -->
        @php
            $hasOfficePopular = (isset($popularOfficeChildcategories) && $popularOfficeChildcategories->count() > 0) 
                || (isset($popularOfficeSubcategories) && $popularOfficeSubcategories->count() > 0) 
                || (isset($popularOfficeCategories) && $popularOfficeCategories->count() > 0);
        @endphp

        @if($hasOfficePopular)
            <div class="section-title-wrap">
                <h2 class="section-title">EXPLORE OUR POPULAR OFFICE FURNITURE CATEGORIES</h2>
            </div>

            <div class="popular-cats-strip">
                @if(isset($popularOfficeChildcategories) && $popularOfficeChildcategories->count() > 0)
                    @foreach($popularOfficeChildcategories as $child)
                        <a href="{{ route('products.index', ['category' => $child->category?->slug ?? 'office-table', 'childcategory' => $child->slug]) }}" class="popular-cat-item">
                            <div class="popular-cat-icon">
                                <img src="{{ $child->image_url }}" alt="{{ $child->name }}">
                            </div>
                            <span class="popular-cat-title">{{ $child->name }}</span>
                        </a>
                    @endforeach
                @endif

                @if(isset($popularOfficeSubcategories) && $popularOfficeSubcategories->count() > 0)
                    @foreach($popularOfficeSubcategories as $sub)
                        <a href="{{ route('products.index', ['category' => $sub->category?->slug ?? 'office-table', 'subcategory' => $sub->slug]) }}" class="popular-cat-item">
                            <div class="popular-cat-icon">
                                <img src="{{ $sub->image_url }}" alt="{{ $sub->name }}">
                            </div>
                            <span class="popular-cat-title">{{ $sub->name }}</span>
                        </a>
                    @endforeach
                @endif

                @if((!isset($popularOfficeChildcategories) || $popularOfficeChildcategories->count() === 0) && (!isset($popularOfficeSubcategories) || $popularOfficeSubcategories->count() === 0))
                    @foreach($popularOfficeCategories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="popular-cat-item">
                            <div class="popular-cat-icon">
                                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}">
                            </div>
                            <span class="popular-cat-title">{{ $cat->name }}</span>
                        </a>
                    @endforeach
                @endif
            </div>
        @endif

        <!-- 4. FEATURED FURNITURE CATEGORIES (Dynamic from Database Categories where is_featured = true) -->
        <div class="section-title-wrap">
            <h2 class="section-title">FEATURED FURNITURE CATEGORIES</h2>
        </div>

        <div class="featured-cats-grid">
            @forelse($featuredCategories as $cat)
                <div class="featured-cat-card">
                    <div class="featured-cat-img">
                        <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}">
                    </div>
                    <div class="featured-cat-body">
                        <div class="featured-cat-name">{{ $cat->name }}</div>
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="featured-cat-link">EXPLORE NOW &gt;&gt;</a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; color: #94a3b8; padding: 20px;">
                    No featured categories set. Select featured categories from the Admin Panel.
                </div>
            @endforelse
        </div>

        <!-- 5. FEATURED PRODUCTS (Dynamic from Database) -->
        @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            <div class="section-title-wrap">
                <h2 class="section-title">FEATURED PRODUCTS</h2>
            </div>

            <div class="home-products-grid">
                @foreach($featuredProducts as $prod)
                    <div class="home-product-card">
                        <div>
                            <a href="{{ route('products.show', $prod->slug) }}" class="home-prod-thumb">
                                <img src="{{ $prod->image_url }}" alt="{{ $prod->title }}">
                            </a>
                            @if($prod->product_code)
                                <div class="home-prod-code">{{ $prod->product_code }}</div>
                            @endif
                            <h3 class="home-prod-title">
                                <a href="{{ route('products.show', $prod->slug) }}">{{ $prod->title }}</a>
                            </h3>
                        </div>

                        <div>
                            <div class="home-prod-price">
                                <span>{{ $prod->formatted_price }}</span>
                                @if($prod->discount_price > 0)
                                    <span class="home-prod-old-price">{{ $prod->formatted_regular_price }}</span>
                                @endif
                            </div>

                            <div class="home-prod-actions">
                                <a href="{{ route('products.show', $prod->slug) }}" class="btn-view-prod">View Details</a>
                                <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn-cart-prod" title="Add to Cart">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- 6. BRAND PORTFOLIO (Dynamic from Admin Panel) -->
        @if(isset($brands) && $brands->count() > 0)
            <div class="section-title-wrap">
                <h2 class="section-title">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }} BRAND PORTFOLIO</h2>
            </div>

            <div class="brand-portfolio-strip">
                @foreach($brands as $brand)
                    @if($brand->target_url)
                        <a href="{{ $brand->target_url }}" target="_blank" rel="noopener noreferrer" class="brand-logo-item" title="{{ $brand->name }}">
                            <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}">
                        </a>
                    @else
                        <div class="brand-logo-item" title="{{ $brand->name }}">
                            <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}">
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <!-- 7. WHY INDEXFURNITURE -->
        <div class="section-title-wrap">
            <h2 class="section-title">WHY {{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}</h2>
        </div>

        <div class="why-otobi-grid">
            <div class="why-card">
                <div class="why-icon-box">
                    <img src="{{ asset('uploads/otobi_why_customer_care.png') }}" alt="Customer Care">
                </div>
                <div class="why-title">CUSTOMER CARE</div>
                <div class="why-desc">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }} has dedicated customer care center.</div>
            </div>

            <div class="why-card">
                <div class="why-icon-box">
                    <img src="{{ asset('uploads/otobi_why_delivery.png') }}" alt="Home Delivery">
                </div>
                <div class="why-title">HOME DELIVERY</div>
                <div class="why-desc">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }} provide fastest home delivery all over Bangladesh.</div>
            </div>

            <div class="why-card">
                <div class="why-icon-box">
                    <img src="{{ asset('uploads/otobi_why_easy_payment.png') }}" alt="Easy Payment">
                </div>
                <div class="why-title">EASY PAYMENT</div>
                <div class="why-desc">Different types of payment are available for convenience.</div>
            </div>

            <div class="why-card">
                <div class="why-icon-box">
                    <img src="{{ asset('uploads/otobi_why_warranty.png') }}" alt="One Year Warranty">
                </div>
                <div class="why-title">ONE YEAR WARRANTY</div>
                <div class="why-desc">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }} provide one year service warranty for all Products. T&amp;C Apply.</div>
            </div>

            <div class="why-card">
                <div class="why-icon-box">
                    <img src="{{ asset('uploads/otobi_why_after_sales.png') }}" alt="After Sales Service">
                </div>
                <div class="why-title">AFTER SALES SERVICE</div>
                <div class="why-desc">Our dedicated team available for your service 24/7.</div>
            </div>

            <div class="why-card">
                <div class="why-icon-box">
                    <img src="{{ asset('uploads/otobi_why_assembling.png') }}" alt="Assembling &amp; Shifting">
                </div>
                <div class="why-title">ASSEMBLING &amp; SHIFTING</div>
                <div class="why-desc">Expert team ready for assembling &amp; shifting service.</div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slider-wrap .slide');
    const dots = document.querySelectorAll('.hero-slider-wrap .dot');

    function showSlide(index) {
        if (!slides.length) return;
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        if (index >= slides.length) currentSlide = 0;
        else if (index < 0) currentSlide = slides.length - 1;
        else currentSlide = index;

        slides[currentSlide].classList.add('active');
        if (dots[currentSlide]) dots[currentSlide].classList.add('active');
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    function setSlide(index) {
        showSlide(index);
    }

    if (slides.length > 1) {
        setInterval(nextSlide, 5000);
    }
</script>
@endpush
