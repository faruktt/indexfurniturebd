@extends('frontend.layouts.app')

@section('title', 'Order Confirmation - ' . $order->order_number . ' | ' . \App\Models\SiteSetting::get('site_name', 'Indexfurniture'))

@push('styles')
<style>
    .order-success-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 40px;
        max-width: 800px;
        margin: 40px auto;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .success-header-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: #d1fae5;
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        margin: 0 auto 18px;
    }

    .order-invoice-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 20px;
        margin: 25px 0;
        font-size: 12.5px;
    }

    .receipt-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 12px;
    }

    .receipt-table th {
        background: #f1f5f9;
        padding: 10px;
        text-align: left;
        border-bottom: 2px solid #cbd5e1;
    }

    .receipt-table td {
        padding: 10px;
        border-bottom: 1px solid #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="order-success-card">
        <div class="success-header-icon">
            <i class="fas fa-check"></i>
        </div>

        <h1 style="font-family: 'Outfit', sans-serif; font-size: 26px; text-align: center; color: #111111; margin-bottom: 8px;">
            Thank You for Your Order!
        </h1>
        <p style="text-align: center; color: #666666; font-size: 13px;">
            Your order has been placed successfully. An SMS/Email confirmation will be sent shortly.
        </p>

        <div class="order-invoice-meta">
            <div>
                <p><strong>Order Number:</strong> <span style="color: var(--otobi-red);">{{ $order->order_number }}</span></p>
                <p><strong>Order Date:</strong> {{ optional($order->created_at)->format('M d, Y h:i A') ?? date('M d, Y') }}</p>
                <p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
                <p><strong>Contact Phone:</strong> {{ $order->phone }}</p>
            </div>
            <div>
                <p><strong>Delivery Method:</strong> {{ $order->delivery_method }}</p>
                <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                <p><strong>Delivery Address:</strong> {{ $order->address }}, {{ $order->area }}, {{ $order->district }}</p>
                <p><strong>Order Status:</strong> {!! $order->status_badge !!}</p>
            </div>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Color / Option</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->product_code)
                                <div style="font-size: 11px; color: #888888;">Code: {{ $item->product_code }}</div>
                            @endif
                        </td>
                        <td>{{ $item->color ?? 'Standard' }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">BDT {{ number_format($item->price, 0) }}</td>
                        <td style="text-align: right; font-weight: 600;">BDT {{ number_format($item->subtotal, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: 600;">Subtotal:</td>
                    <td style="text-align: right;">BDT {{ number_format($order->subtotal, 0) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: 600;">Delivery Charge:</td>
                    <td style="text-align: right;">BDT {{ number_format($order->delivery_charge, 0) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right; font-size: 15px; font-weight: 700; color: #111111;">Grand Total:</td>
                    <td style="text-align: right; font-size: 16px; font-weight: 800; color: var(--otobi-red);">BDT {{ number_format($order->grand_total, 0) }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="text-align: center; margin-top: 30px; display: flex; justify-content: center; gap: 15px;">
            <a href="{{ route('home') }}" style="display: inline-block; padding: 10px 24px; background: var(--otobi-red); color: #ffffff; border-radius: 3px; font-weight: 600; text-transform: uppercase; font-size: 12px;">
                Continue Shopping
            </a>
            <button onclick="window.print()" style="padding: 10px 20px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 3px; font-weight: 600; cursor: pointer; font-size: 12px;">
                <i class="fas fa-print"></i> Print Receipt
            </button>
        </div>
    </div>
</div>
@endsection
