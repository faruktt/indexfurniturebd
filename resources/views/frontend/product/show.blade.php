@extends('frontend.layouts.app')

@section('title', $product->title . ' - ' . \App\Models\SiteSetting::get('site_name', 'Indexfurniture') . ' Furniture')

@push('styles')
<style>
    /* Breadcrumbs */
    .breadcrumb-nav {
        padding: 14px 0;
        font-size: 11.5px;
        color: #777777;
        border-bottom: 1px solid #ededed;
        margin-bottom: 30px;
    }

    .breadcrumb-nav a {
        color: #555555;
    }

    .breadcrumb-nav a:hover {
        color: var(--otobi-red);
    }

    .breadcrumb-nav span {
        margin: 0 6px;
        color: #aaaaaa;
    }

    /* Product Details Grid (Matching Image 2) */
    .product-details-grid {
        display: grid;
        grid-template-columns: 460px 1fr;
        gap: 45px;
        margin-bottom: 45px;
    }

    /* Gallery Left */
    .product-main-image-wrap {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 440px;
        position: relative;
    }

    .product-main-image-wrap img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s;
    }

    .gallery-thumbs-row {
        display: flex;
        gap: 10px;
        margin-top: 12px;
    }

    .thumb-item {
        width: 70px;
        height: 70px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 4px;
        cursor: pointer;
        background: #ffffff;
    }

    .thumb-item.active {
        border-color: var(--otobi-red);
    }

    .thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Product Info Right */
    .product-category-subtitle {
        font-size: 13px;
        color: #888888;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .product-headline {
        font-family: 'Outfit', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #222222;
        line-height: 1.25;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: -0.01em;
    }

    .product-sub-headline {
        font-size: 16px;
        font-weight: 600;
        color: #555555;
        margin-bottom: 16px;
    }

    .product-price-large {
        font-family: 'Outfit', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: var(--otobi-red);
        margin-bottom: 18px;
    }

    .product-old-price-large {
        font-size: 16px;
        color: #888888;
        text-decoration: line-through;
        margin-left: 10px;
        font-weight: normal;
    }

    .product-meta-row {
        display: flex;
        margin-bottom: 12px;
        font-size: 12.5px;
    }

    .product-meta-label {
        width: 140px;
        color: #777777;
    }

    .product-meta-value {
        color: #222222;
        font-weight: 600;
    }

    .product-color-select {
        padding: 6px 12px;
        border: 1px solid #cccccc;
        border-radius: 3px;
        font-size: 12px;
        color: #333333;
        background-color: #ffffff;
        min-width: 180px;
        outline: none;
    }

    /* Add to Cart Button (Matching Image 2 black button) */
    .add-to-cart-box {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 25px 0 30px;
    }

    .qty-input-wrap {
        display: flex;
        align-items: center;
        border: 1px solid #cccccc;
        border-radius: 3px;
        overflow: hidden;
    }

    .qty-btn {
        width: 32px;
        height: 38px;
        background: #f8f9fa;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: bold;
        color: #444444;
    }

    .qty-btn:hover {
        background: #e9ecef;
    }

    .qty-number {
        width: 44px;
        height: 38px;
        border: none;
        border-left: 1px solid #cccccc;
        border-right: 1px solid #cccccc;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
        outline: none;
    }

    .btn-add-to-cart-black {
        background-color: #000000;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        border: none;
        padding: 0 32px;
        height: 40px;
        cursor: pointer;
        border-radius: 2px;
        transition: background-color 0.2s;
    }

    .btn-add-to-cart-black:hover {
        background-color: #333333;
    }

    /* Delivery & Warranty notes */
    .delivery-options-box {
        border-top: 1px solid #eeeeee;
        padding-top: 18px;
        font-size: 12px;
        color: #666666;
    }

    .delivery-option-item {
        margin-bottom: 8px;
    }

    .delivery-option-title {
        font-weight: 600;
        color: #333333;
        margin-bottom: 3px;
    }

    /* Product Description Tab (Matching Image 2) */
    .description-section {
        margin: 40px 0 50px;
    }

    .tab-header-strip {
        border-bottom: 1.5px solid #2b9348;
        margin-bottom: 20px;
    }

    .tab-header-btn {
        display: inline-block;
        font-size: 15px;
        font-weight: 700;
        color: #2b9348;
        padding-bottom: 8px;
        border-bottom: 3px solid #2b9348;
        margin-bottom: -1.5px;
        text-transform: capitalize;
    }

    .desc-product-code {
        color: var(--otobi-red);
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 12px;
    }

    .description-body {
        font-size: 12.5px;
        line-height: 1.8;
        color: #555555;
    }

    .description-body ul {
        padding-left: 20px;
        margin-bottom: 15px;
    }

    .description-body li {
        margin-bottom: 4px;
    }

    .warranty-note {
        font-size: 12px;
        font-weight: 600;
        color: #333333;
        margin-top: 15px;
    }

    /* Suggest Furniture Section (Matching Image 2) */
    .suggest-furniture-wrap {
        margin-top: 40px;
    }

    .suggest-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1.5px solid #2b9348;
        padding-bottom: 6px;
        margin-bottom: 20px;
    }

    .suggest-title {
        font-size: 15px;
        font-weight: 700;
        color: #2b9348;
    }

    .suggest-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 15px;
    }

    .suggest-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 10px;
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .suggest-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .suggest-img {
        width: 100%;
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }

    .suggest-img img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .suggest-name {
        font-size: 11px;
        font-weight: 600;
        color: #0077b6;
        line-height: 1.3;
        height: 28px;
        overflow: hidden;
    }

    .suggest-item:hover .suggest-name {
        color: var(--otobi-red);
    }

    @media (max-width: 992px) {
        .product-details-grid {
            grid-template-columns: 1fr;
        }
        .suggest-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 600px) {
        .suggest-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <div class="breadcrumb-nav">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        @if($product->category)
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
            <span>/</span>
        @endif
        @if($product->subCategory)
            <a href="{{ route('products.index', ['category' => $product->category->slug, 'subcategory' => $product->subCategory->slug]) }}">{{ $product->subCategory->name }}</a>
            <span>/</span>
        @endif
        <strong>{{ $product->title }}</strong>
    </div>

    <!-- Product Details Main Grid (Matching Image 2) -->
    <div class="product-details-grid">
        <!-- Left: Image Box -->
        <div>
            <div class="product-main-image-wrap">
                <img id="mainProductImg" src="{{ $product->image_url }}" alt="{{ $product->title }}">
            </div>

            @if($product->images->count() > 0)
                <div class="gallery-thumbs-row">
                    <div class="thumb-item active" onclick="changeImage('{{ $product->image_url }}', this)">
                        <img src="{{ $product->image_url }}" alt="Main">
                    </div>
                    @foreach($product->images as $gImg)
                        <div class="thumb-item" onclick="changeImage('{{ $gImg->image_url }}', this)">
                            <img src="{{ $gImg->image_url }}" alt="Gallery Thumb">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Product Info -->
        <div>
            <div class="product-category-subtitle">
                {{ $product->childCategory ? $product->childCategory->name : ($product->subCategory ? $product->subCategory->name : 'Laminate Furniture') }}
            </div>

            <h1 class="product-headline">{{ $product->title }}</h1>
            <div class="product-sub-headline">{{ $product->short_description ?? $product->title }}</div>

            <div class="product-price-large">
                {{ $product->formatted_price }}
                @if($product->discount_price > 0)
                    <span class="product-old-price-large">{{ $product->formatted_regular_price }}</span>
                @endif
            </div>

            <!-- Product Code -->
            @if($product->product_code)
                <div class="product-meta-row">
                    <div class="product-meta-label">Product Code:</div>
                    <div class="product-meta-value">{{ $product->product_code }}</div>
                </div>
            @endif

            <!-- Dimensions -->
            @if($product->dimension)
                <div class="product-meta-row">
                    <div class="product-meta-label">Dimension (WxDxH):</div>
                    <div class="product-meta-value">{{ $product->dimension }}</div>
                </div>
            @endif

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Colors -->
                @if($product->colors->count() > 0)
                    <div class="product-meta-row" style="align-items: center;">
                        <label for="productColor" class="product-meta-label">* Product Color</label>
                        <select name="color" id="productColor" class="product-color-select">
                            @foreach($product->colors as $col)
                                <option value="{{ $col->name }}">{{ $col->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Sizes -->
                @if($product->sizes->count() > 0)
                    <div class="product-meta-row" style="align-items: center;">
                        <label for="productSize" class="product-meta-label">Size / Variant</label>
                        <select name="size" id="productSize" class="product-color-select">
                            @foreach($product->sizes as $sz)
                                <option value="{{ $sz->name }}">{{ $sz->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Quantity & Add to Cart -->
                <div class="add-to-cart-box">
                    <div class="qty-input-wrap">
                        <button type="button" class="qty-btn" onclick="decrementQty()">-</button>
                        <input type="number" name="quantity" id="productQty" value="1" min="1" max="100" class="qty-number">
                        <button type="button" class="qty-btn" onclick="incrementQty()">+</button>
                    </div>

                    <button type="submit" class="btn-add-to-cart-black">
                        ADD TO CART
                    </button>
                </div>
            </form>

            <!-- Delivery & Warranty Info (Matching Image 2) -->
            <div class="delivery-options-box">
                <div class="delivery-option-item">
                    <div class="delivery-option-title">Delivery Options</div>
                    <div>{{ $product->cash_on_delivery ? 'Cash on Delivery Available' : 'Cash on Delivery Not Available' }}</div>
                </div>

                <div class="delivery-option-item">
                    <div class="delivery-option-title">Return &amp; Warranty</div>
                    <div>{{ $product->warranty ? 'Warranty Available' : 'Standard Indexfurniture Service Warranty' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description (Matching Image 2) -->
    <div class="description-section">
        <div class="tab-header-strip">
            <span class="tab-header-btn">Product Description</span>
        </div>

        <div class="description-body">
            @if($product->product_code)
                <div class="desc-product-code">Product Code: {{ $product->product_code }}</div>
            @endif

            @if($product->description)
                {!! nl2br(e($product->description)) !!}
            @else
                <ul>
                    <li>Made of high quality scratch free melamine faced laminated board</li>
                    <li>PVC edge banded with hot melt glue to protect moisture gaining</li>
                    <li>Anti-insects chemicals added to make the board termite-proof</li>
                    <li>High-quality fasteners used for assembly</li>
                    <li>Knock down product – assembly required</li>
                    <li>Indoor use only.</li>
                </ul>
            @endif

            @if($product->warranty)
                <div class="warranty-note">
                    <strong>Warranty:</strong> {{ $product->warranty }}
                </div>
            @endif
        </div>
    </div>

    <!-- Suggest Furniture Section (Matching Image 2) -->
    @if($suggestedProducts->count() > 0)
        <div class="suggest-furniture-wrap">
            <div class="suggest-header">
                <span class="suggest-title">Suggest Furniture</span>
                <div style="font-size: 13px; color: #2b9348;">
                    <i class="fas fa-chevron-left" style="cursor: pointer; margin-right: 8px;"></i>
                    <i class="fas fa-chevron-right" style="cursor: pointer;"></i>
                </div>
            </div>

            <div class="suggest-grid">
                @foreach($suggestedProducts as $sp)
                    <a href="{{ route('products.show', $sp->slug) }}" class="suggest-item">
                        <div class="suggest-img">
                            <img src="{{ $sp->image_url }}" alt="{{ $sp->title }}">
                        </div>
                        <div class="suggest-name">{{ $sp->title }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function changeImage(src, element) {
        document.getElementById('mainProductImg').src = src;
        document.querySelectorAll('.thumb-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    function incrementQty() {
        const input = document.getElementById('productQty');
        input.value = parseInt(input.value || 1) + 1;
    }

    function decrementQty() {
        const input = document.getElementById('productQty');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endpush
