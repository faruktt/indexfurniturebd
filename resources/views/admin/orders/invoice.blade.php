<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        body { padding: 40px; color: #333; font-size: 13px; line-height: 1.6; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; box-shadow: 0 0 10px rgba(0, 0, 0, .05); }
        .header-table { width: 100%; margin-bottom: 30px; }
        .header-logo { font-size: 32px; font-weight: bold; color: #ea1d24; letter-spacing: -1px; }
        .company-info { text-align: right; font-size: 11px; color: #777; }
        .meta-grid { display: flex; justify-content: space-between; margin-bottom: 30px; padding: 15px; background: #f9f9f9; border-radius: 4px; }
        .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .invoice-table th { background: #ea1d24; color: #fff; padding: 10px; text-align: left; font-size: 12px; }
        .invoice-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .totals-table { width: 280px; margin-left: auto; margin-bottom: 30px; }
        .totals-table td { padding: 6px 10px; }
        .grand-total { font-size: 16px; font-weight: bold; color: #ea1d24; border-top: 2px solid #ea1d24; }
        .print-btn-bar { text-align: center; margin-bottom: 20px; }
        .btn-print { padding: 8px 18px; background: #ea1d24; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        @media print {
            .print-btn-bar { display: none; }
            .invoice-box { border: none; box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="print-btn-bar">
        <button class="btn-print" onclick="window.print()">Print Invoice</button>
    </div>

    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td>
                    @if($invoiceLogo = \App\Models\SiteSetting::logoUrl())
                        <img src="{{ $invoiceLogo }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}" style="max-height: 40px; margin-bottom: 5px; object-fit: contain;">
                    @else
                        <div class="header-logo">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}<sup>®</sup></div>
                    @endif
                    <div style="font-size: 11px; color: #555;">Official Sales Invoice</div>
                </td>
                <td class="company-info">
                    <strong>{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }} Limited</strong><br>
                    {{ \App\Models\SiteSetting::get('address', 'Plot 12, Block CWS (C), Gulshan South Ave, Dhaka 1212, Bangladesh') }}<br>
                    Support: {{ \App\Models\SiteSetting::get('phone_primary', '+880 9613808080') }}<br>
                    {{ \App\Models\SiteSetting::get('email', 'info@indexfurniture.com') }}
                </td>
            </tr>
        </table>

        <div class="meta-grid">
            <div>
                <strong>Billed &amp; Shipped To:</strong><br>
                {{ $order->customer_name }}<br>
                {{ $order->address }}<br>
                {{ $order->area }}, {{ $order->district }}<br>
                Phone: {{ $order->phone }}<br>
                Email: {{ $order->email ?? 'N/A' }}
            </div>
            <div style="text-align: right;">
                <strong>Invoice Reference:</strong><br>
                Order #: <strong>{{ $order->order_number }}</strong><br>
                Date: {{ optional($order->created_at)->format('d M, Y') ?? date('d M, Y') }}<br>
                Payment: {{ strtoupper($order->payment_method) }} ({{ strtoupper($order->payment_status) }})<br>
                Delivery: {{ $order->delivery_method }}
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Color / Option</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->product_code)
                                <div style="font-size: 10px; color: #777;">Code: {{ $item->product_code }}</div>
                            @endif
                        </td>
                        <td>{{ $item->color ?? 'Standard' }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">BDT {{ number_format($item->price, 0) }}</td>
                        <td style="text-align: right; font-weight: bold;">BDT {{ number_format($item->subtotal, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">BDT {{ number_format($order->subtotal, 0) }}</td>
            </tr>
            @if($order->discount > 0)
                <tr style="color: #16a34a; font-weight: bold;">
                    <td>Coupon Discount {{ $order->coupon_code ? '(' . $order->coupon_code . ')' : '' }}:</td>
                    <td style="text-align: right;">- BDT {{ number_format($order->discount, 0) }}</td>
                </tr>
            @endif
            <tr>
                <td>Delivery Fee:</td>
                <td style="text-align: right;">BDT {{ number_format($order->delivery_charge, 0) }}</td>
            </tr>
            <tr class="grand-total">
                <td>Grand Total:</td>
                <td style="text-align: right;">BDT {{ number_format($order->grand_total, 0) }}</td>
            </tr>
        </table>

        <div style="text-align: center; font-size: 11px; color: #888; border-top: 1px solid #eee; padding-top: 15px;">
            Thank you for choosing {{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}. For warranty service or queries, please call our customer care hotline: {{ \App\Models\SiteSetting::get('phone_primary', '+880 9613808080') }}.
        </div>
    </div>
</body>
</html>
