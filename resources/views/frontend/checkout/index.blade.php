@extends('frontend.layouts.app')

@section('title', 'Checkout | ' . \App\Models\SiteSetting::get('site_name', 'Indexfurniture') . ' Furniture')

@push('styles')
<style>
    /* Checkout Page Header (Matching Image 1) */
    .checkout-page-title-wrap {
        display: flex;
        align-items: baseline;
        gap: 12px;
        margin: 20px 0 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid #dcdcdc;
    }

    .checkout-main-heading {
        font-family: 'Outfit', sans-serif;
        font-size: 26px;
        font-weight: 600;
        color: #333333;
    }

    .checkout-breadcrumbs {
        font-size: 11.5px;
        color: #777777;
    }

    .checkout-breadcrumbs a {
        color: #0077b6;
    }

    /* Already registered box */
    .already-registered-bar {
        background-color: #f7f9fa;
        border: 1px solid #e2e8f0;
        border-radius: 3px;
        padding: 10px 16px;
        font-size: 12px;
        color: #444444;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
    }

    .btn-login-pill {
        display: inline-block;
        background: linear-gradient(180deg, #6ba4e8 0%, #3e80d1 100%);
        color: #ffffff;
        padding: 3px 14px;
        border-radius: 12px;
        font-size: 11.5px;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }

    .btn-login-pill:hover {
        background: #2b6cb0;
        color: #ffffff;
    }

    /* 2 Columns Layout */
    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 1.35fr;
        gap: 25px;
        margin-bottom: 50px;
    }

    /* Blue Section Headers (Matching Image 1) */
    .checkout-card {
        background: #ffffff;
        border: 1px solid #c9d8ea;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .card-header-blue {
        background: linear-gradient(180deg, #6ea8ee 0%, #4686d4 100%);
        color: #ffffff;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .header-step-num {
        background: #ffffff;
        color: #3e80d1;
        width: 17px;
        height: 17px;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
    }

    /* Ribbon for Order Review */
    .rainbow-ribbon {
        width: 20px;
        height: 14px;
        background: linear-gradient(135deg, #ef4444, #f59e0b, #10b981, #3b82f6);
        border-radius: 2px;
        display: inline-block;
        margin-right: 2px;
    }

    .card-body-padded {
        padding: 18px 20px;
    }

    /* Form Fields */
    .form-group-row {
        margin-bottom: 12px;
    }

    .form-group-row label {
        display: block;
        font-size: 11.5px;
        color: #333333;
        margin-bottom: 4px;
    }

    .form-group-row label .req {
        color: #ea1d24;
    }

    .form-control-input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #d5d9df;
        border-radius: 2px;
        font-size: 12px;
        color: #333333;
        outline: none;
        background: #ffffff;
    }

    .form-control-input:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 1px #4a90e2;
    }

    .form-control-textarea {
        width: 100%;
        height: 70px;
        padding: 6px 10px;
        border: 1px solid #d5d9df;
        border-radius: 2px;
        font-size: 12px;
        color: #333333;
        outline: none;
        resize: vertical;
    }

    .form-hint-text {
        font-size: 11px;
        color: #666666;
        margin: 14px 0 8px;
    }

    /* Delivery Method Radio Options (Matching Image 1) */
    .delivery-radio-item {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-bottom: 1px solid #eeeeee;
        font-size: 12px;
        cursor: pointer;
    }

    .delivery-radio-item:last-child {
        border-bottom: none;
    }

    .delivery-radio-item input[type="radio"] {
        margin-right: 10px;
        cursor: pointer;
    }

    /* Payment Methods Box */
    .payment-box-inner {
        padding: 12px 16px;
    }

    .payment-gateways-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
        margin-bottom: 12px;
    }

    .pay-badge {
        padding: 3px 6px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        font-size: 9.5px;
        font-weight: 700;
        border-radius: 3px;
        color: #334155;
    }

    /* Order Review Table (Matching Image 1) */
    .order-review-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11.5px;
    }

    .order-review-table th {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 8px 10px;
        text-align: left;
        color: #475569;
        font-weight: 600;
    }

    .order-review-table td {
        padding: 10px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .btn-trash-red {
        background-color: #ef4444;
        color: #ffffff;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    .btn-trash-red:hover {
        background-color: #dc2626;
    }

    .qty-cell-wrap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .qty-edit-input {
        width: 32px;
        padding: 2px 4px;
        border: 1px solid #cbd5e1;
        border-radius: 2px;
        font-size: 11px;
        text-align: center;
    }

    .btn-sync-green {
        background-color: #16a34a;
        color: #ffffff;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    .btn-sync-green:hover {
        background-color: #15803d;
    }

    /* Order Totals Summary */
    .order-totals-wrap {
        padding: 15px 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        border-top: 1px solid #e2e8f0;
        font-size: 12px;
    }

    .totals-row {
        display: flex;
        width: 220px;
        justify-content: space-between;
    }

    .grand-total-row {
        display: flex;
        width: 220px;
        justify-content: space-between;
        margin-top: 4px;
        font-size: 13.5px;
        font-weight: 700;
    }

    .grand-total-val {
        color: var(--otobi-red);
        font-size: 15px;
        font-weight: 800;
    }

    /* Big Blue Place Order Button (Matching Image 1) */
    .place-order-btn-box {
        padding: 10px 20px 18px;
        text-align: right;
    }

    .btn-place-order-blue {
        background: linear-gradient(180deg, #6ba4e8 0%, #3e80d1 100%);
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        padding: 10px 32px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: filter 0.2s;
    }

    .btn-place-order-blue:hover {
        filter: brightness(1.08);
    }

    .terms-disclaimer {
        font-size: 11px;
        color: #666666;
        text-align: right;
        margin-top: 8px;
    }

    @media (max-width: 900px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Title & Breadcrumbs -->
    <div class="checkout-page-title-wrap">
        <h1 class="checkout-main-heading">Checkout</h1>
        <div class="checkout-breadcrumbs">
            <a href="{{ route('home') }}">Home</a> / <a href="{{ route('customer.orders') }}">My Orders</a> / Checkout
        </div>
    </div>

    <!-- Already registered? Please Login -->
    @if(!Auth::guard('web')->check())
        <div class="already-registered-bar">
            <span>Already registered? Please</span>
            <a href="{{ route('login') }}" class="btn-login-pill">Login</a>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="checkout-grid">
            <!-- LEFT COLUMN: 1. DELIVERY ADDRESS -->
            <div>
                <div class="checkout-card">
                    <div class="card-header-blue">
                        <span class="header-step-num">1</span>
                        <span>DELIVERY ADDRESS</span>
                    </div>

                    <div class="card-body-padded">
                        <div class="form-group-row">
                            <label for="fullName"><span class="req">*</span> Full Name</label>
                            <input type="text" name="full_name" id="fullName" class="form-control-input" 
                                   value="{{ old('full_name', $user->name ?? '') }}" required>
                        </div>

                        <div class="form-group-row">
                            <label for="email">E-Mail</label>
                            <input type="email" name="email" id="email" class="form-control-input" 
                                   value="{{ old('email', $user->email ?? '') }}">
                        </div>

                        <div class="form-group-row">
                            <label for="contactNo"><span class="req">*</span> Contact No</label>
                            <input type="text" name="contact_no" id="contactNo" class="form-control-input" 
                                   value="{{ old('contact_no', $user->phone ?? '') }}" required>
                        </div>

                        <div class="form-group-row">
                            <label for="country"><span class="req">*</span> Country</label>
                            <select name="country" id="country" class="form-control-input" required>
                                <option value="Bangladesh" selected>Bangladesh</option>
                            </select>
                        </div>

                        <div class="form-group-row">
                            <label for="district"><span class="req">*</span> District</label>
                            <input type="text" name="district" id="district" class="form-control-input" 
                                   placeholder="e.g. Dhaka, Chittagong, Sylhet"
                                   value="{{ old('district', $user->district ?? 'Dhaka') }}" required>
                        </div>

                        <div class="form-group-row">
                            <label for="area"><span class="req">*</span> Area</label>
                            <input type="text" name="area" id="area" class="form-control-input" 
                                   placeholder="e.g. Gulshan, Dhanmondi, Mirpur, Uttara"
                                   value="{{ old('area') }}" required>
                        </div>

                        <div class="form-group-row">
                            <label for="postalCode">Postal Code</label>
                            <input type="text" name="postal_code" id="postalCode" class="form-control-input" 
                                   value="{{ old('postal_code') }}">
                        </div>

                        <div class="form-group-row">
                            <label for="streetAddress"><span class="req">*</span> Street Address</label>
                            <textarea name="street_address" id="streetAddress" class="form-control-textarea" 
                                      placeholder="House / Flat / Road Details" required>{{ old('street_address', $user->address ?? '') }}</textarea>
                        </div>

                        <!-- Optional Password Registration if guest -->
                        @if(!Auth::guard('web')->check())
                            <div class="form-hint-text">
                                If you want to register your account, please provide your preferred password:
                            </div>

                            <div class="form-group-row">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control-input">
                            </div>

                            <div class="form-group-row">
                                <label for="password_confirmation">Re-type Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control-input">
                            </div>
                        @endif

                        <!-- Promo Code section -->
                        <div class="form-hint-text" style="margin-top: 18px;">
                            If you have promo/coupon code, please provide below:
                        </div>
                        
                        <div id="couponInputContainer" style="{{ !empty($appliedCoupon) ? 'display: none;' : 'display: flex;' }} gap: 8px;">
                            <input type="text" id="couponCodeInput" placeholder="Enter Coupon Code (e.g. SUMMER20)" class="form-control-input" style="flex: 1; text-transform: uppercase; font-family: monospace; font-weight: 600;">
                            <button type="button" id="btnApplyCoupon" onclick="applyCouponCode()" style="padding: 6px 16px; background: #3e80d1; color: #ffffff; border: 1px solid #2b6cb0; border-radius: 3px; font-size: 11.5px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: background 0.2s ease;">
                                <i class="fas fa-tag"></i> Apply Code
                            </button>
                        </div>

                        <!-- Applied Coupon Box -->
                        <div id="appliedCouponBox" style="{{ !empty($appliedCoupon) ? 'display: flex;' : 'display: none;' }} margin-top: 8px; padding: 10px 14px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 4px; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #166534; font-weight: 600;">
                                <i class="fas fa-check-circle" style="color: #22c55e; font-size: 14px;"></i>
                                <span>Coupon applied: <strong id="appliedCodeText" style="font-family: monospace; background: #dcfce7; padding: 2px 6px; border-radius: 3px;">{{ $appliedCoupon['code'] ?? '' }}</strong></span>
                                <span id="appliedDiscountBadge" style="font-size: 11px; font-weight: normal; color: #15803d;">(-BDT {{ number_format($discount ?? 0, 0) }})</span>
                            </div>
                            <button type="button" onclick="removeCouponCode()" style="background: none; border: none; color: #ef4444; font-size: 11.5px; cursor: pointer; display: flex; align-items: center; gap: 4px; font-weight: 600;" title="Remove this coupon">
                                <i class="fas fa-trash-alt"></i> Remove
                            </button>
                        </div>

                        <div id="couponMessageAlert" style="display: none; margin-top: 8px; font-size: 11.5px; padding: 6px 10px; border-radius: 4px;"></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: 2. DELIVERY, 3. PAYMENT, 4. ORDER REVIEW -->
            <div>
                <!-- 2. DELIVERY METHOD -->
                <div class="checkout-card">
                    <div class="card-header-blue">
                        <span class="header-step-num">2</span>
                        <span>DELIVERY METHOD</span>
                    </div>
                    <div>
                        @foreach($shippingMethods as $index => $sm)
                            <label class="delivery-radio-item">
                                <input type="radio" name="delivery_method" value="{{ $sm->title }}" 
                                       data-charge="{{ $sm->charge }}" 
                                       {{ ($sm->key === 'inside_dhaka' || $index === 0) ? 'checked' : '' }}
                                       onchange="updateDeliveryCharge(this)">
                                <span>{{ $sm->title }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 3. PAYMENT METHOD -->
                <div class="checkout-card">
                    <div class="card-header-blue">
                        <span class="header-step-num">3</span>
                        <span>PAYMENT METHOD</span>
                    </div>
                    <div class="payment-box-inner">
                        <div class="payment-gateways-preview">
                            <span class="pay-badge" style="color: #1a1f71;">VISA</span>
                            <span class="pay-badge" style="color: #eb001b;">MasterCard</span>
                            <span class="pay-badge" style="color: #006fcf;">AMEX</span>
                            <span class="pay-badge" style="color: #e2136e;">bKash</span>
                            <span class="pay-badge" style="color: #8c1d40;">Rocket</span>
                            <span class="pay-badge" style="color: #f7941d;">Nagad</span>
                            <span class="pay-badge" style="color: #0070ba;">DBBL Nexus</span>
                            <span class="pay-badge" style="color: #2b7a78;">Upay</span>
                            <span class="pay-badge" style="color: #ffb703;">Tap</span>
                        </div>

                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-size: 12px; cursor: pointer;">
                            <input type="radio" name="payment_method" value="sslcommerz" checked>
                            <strong>Online Payment (SSLCommerz Gateway)</strong>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; cursor: pointer;">
                            <input type="radio" name="payment_method" value="cod">
                            <span>Cash on Delivery (Available in selected zones)</span>
                        </label>
                    </div>
                </div>

                <!-- 4. ORDER REVIEW (Matching Image 1) -->
                <div class="checkout-card">
                    <div class="card-header-blue">
                        <span class="rainbow-ribbon"></span>
                        <span>ORDER REVIEW</span>
                    </div>

                    <table class="order-review-table">
                        <thead>
                            <tr>
                                <th style="width: 25px;"></th>
                                <th>Product Name</th>
                                <th style="width: 75px; text-align: center;">Quantity</th>
                                <th style="width: 85px; text-align: right;">Price</th>
                                <th style="width: 95px; text-align: right;">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $key => $item)
                                <tr id="row-{{ md5($key) }}">
                                    <td>
                                        <button type="button" class="btn-trash-red" title="Remove Item" onclick="removeCartItem('{{ $key }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <span style="color: #333333; font-weight: 500;">{{ $item['title'] }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="qty-cell-wrap">
                                            <input type="number" id="qty-{{ md5($key) }}" value="{{ $item['quantity'] }}" min="1" class="qty-edit-input">
                                            <button type="button" class="btn-sync-green" title="Update Quantity" onclick="updateCartQty('{{ $key }}')">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td style="text-align: right; color: #555555;">
                                        BDT {{ number_format($item['price'], 0) }}
                                    </td>
                                    <td style="text-align: right; font-weight: 600;" id="subtotal-{{ md5($key) }}">
                                        BDT {{ number_format($item['subtotal'], 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Totals Summary (Matching Image 1) -->
                    <div class="order-totals-wrap">
                        <div class="totals-row">
                            <span style="color: #666666;">Sub Total:</span>
                            <span id="summarySubtotal" style="font-weight: 600;">BDT {{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="totals-row" id="couponDiscountRow" style="{{ ($discount ?? 0) > 0 ? 'display: flex;' : 'display: none;' }} color: #16a34a; font-weight: 600;">
                            <span style="display: flex; align-items: center; gap: 4px;">
                                <i class="fas fa-ticket-alt" style="font-size: 11px;"></i> Coupon Discount (<span id="summaryCouponCode">{{ $appliedCoupon['code'] ?? '' }}</span>):
                            </span>
                            <span id="summaryDiscount" style="color: #16a34a;">- BDT {{ number_format($discount ?? 0, 0) }}</span>
                        </div>
                        <div class="totals-row">
                            <span style="color: #666666;">Delivery Charge:</span>
                            <span id="summaryDelivery">BDT {{ number_format($deliveryCharge, 0) }}</span>
                        </div>
                        <div class="grand-total-row">
                            <span>Grand Total:</span>
                            <span class="grand-total-val" id="summaryGrandTotal">BDT {{ number_format($grandTotal, 0) }}</span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <div class="place-order-btn-box">
                        <button type="submit" class="btn-place-order-blue">
                            <span>Place Order</span>
                            <i class="fas fa-chevron-right" style="font-size: 13px;"></i>
                        </button>
                        <div class="terms-disclaimer">
                            By clicking/tapping Place Order, I agree the <strong>Terms and Conditions</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    let currentSubtotal = {{ (float) $subtotal }};
    let currentDelivery = {{ (float) $deliveryCharge }};
    let currentDiscount = {{ (float) ($discount ?? 0) }};

    function updateDeliveryCharge(radio) {
        currentDelivery = parseFloat(radio.dataset.charge || 0);
        document.getElementById('summaryDelivery').textContent = 'BDT ' + Math.round(currentDelivery).toLocaleString();
        recalcGrandTotal();
    }

    function recalcGrandTotal() {
        const grandTotal = Math.max(0, currentSubtotal - currentDiscount + currentDelivery);
        document.getElementById('summaryGrandTotal').textContent = 'BDT ' + Math.round(grandTotal).toLocaleString();
    }

    function applyCouponCode() {
        const codeInput = document.getElementById('couponCodeInput');
        const code = codeInput.value.trim();
        const alertBox = document.getElementById('couponMessageAlert');
        const btn = document.getElementById('btnApplyCoupon');

        if (!code) {
            showCouponAlert('Please enter a coupon code.', 'error');
            return;
        }

        const originalBtnText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
        btn.disabled = true;

        const email = document.getElementById('email') ? document.getElementById('email').value : '';
        const phone = document.getElementById('contactNo') ? document.getElementById('contactNo').value : '';

        fetch("{{ route('checkout.apply-coupon') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                code: code,
                email: email,
                phone: phone,
                delivery_charge: currentDelivery
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalBtnText;
            btn.disabled = false;

            if (data.success) {
                currentDiscount = parseFloat(data.discount || 0);
                
                // Show applied coupon box
                document.getElementById('appliedCodeText').textContent = data.coupon_code;
                document.getElementById('appliedDiscountBadge').textContent = '(-' + data.formatted_discount + ')';
                document.getElementById('appliedCouponBox').style.display = 'flex';
                document.getElementById('couponInputContainer').style.display = 'none';

                // Update Order Summary
                document.getElementById('summaryCouponCode').textContent = data.coupon_code;
                document.getElementById('summaryDiscount').textContent = '- ' + data.formatted_discount;
                document.getElementById('couponDiscountRow').style.display = 'flex';
                
                recalcGrandTotal();
                showCouponAlert(data.message, 'success');
            } else {
                showCouponAlert(data.message || 'Failed to apply coupon.', 'error');
            }
        })
        .catch(err => {
            btn.innerHTML = originalBtnText;
            btn.disabled = false;
            showCouponAlert('An error occurred while verifying the coupon.', 'error');
        });
    }

    function removeCouponCode() {
        fetch("{{ route('checkout.remove-coupon') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                delivery_charge: currentDelivery
            })
        })
        .then(res => res.json())
        .then(data => {
            currentDiscount = 0;

            // Reset UI
            document.getElementById('appliedCouponBox').style.display = 'none';
            document.getElementById('couponInputContainer').style.display = 'flex';
            document.getElementById('couponCodeInput').value = '';
            document.getElementById('couponDiscountRow').style.display = 'none';

            recalcGrandTotal();
            showCouponAlert(data.message || 'Coupon removed.', 'info');
        })
        .catch(err => {
            showCouponAlert('Failed to remove coupon.', 'error');
        });
    }

    function showCouponAlert(msg, type) {
        const alertBox = document.getElementById('couponMessageAlert');
        alertBox.textContent = msg;
        alertBox.style.display = 'block';

        if (type === 'success') {
            alertBox.style.background = '#dcfce7';
            alertBox.style.color = '#15803d';
            alertBox.style.border = '1px solid #86efac';
        } else if (type === 'error') {
            alertBox.style.background = '#fee2e2';
            alertBox.style.color = '#b91c1c';
            alertBox.style.border = '1px solid #fca5a5';
        } else {
            alertBox.style.background = '#f1f5f9';
            alertBox.style.color = '#334155';
            alertBox.style.border = '1px solid #cbd5e1';
        }

        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 6000);
    }

    function updateCartQty(key) {
        const md5Key = hex_md5 ? hex_md5(key) : '';
        // Find input by key in rows
        const inputs = document.querySelectorAll('.qty-edit-input');
        // Let's use form submit via fetch
        const row = event.target.closest('tr');
        const qty = row.querySelector('.qty-edit-input').value;

        fetch("{{ route('cart.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ key: key, quantity: qty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function removeCartItem(key) {
        if (!confirm('Are you sure you want to remove this item from your order?')) return;

        fetch("{{ route('cart.remove') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ key: key })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endpush
