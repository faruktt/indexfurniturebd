@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_number)
@section('page_title', 'Order Details: ' . $order->order_number)

@section('content')
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-dark btn-sm">
        <i class="fas fa-print"></i> Generate / Print Invoice
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Left Column: Items & Summary -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Order Items ({{ $order->items->count() }})</h3>
                <div>{!! $order->status_badge !!}</div>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Code</th>
                            <th>Option</th>
                            <th style="text-align: right;">Unit Price</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td style="font-weight: 600;">
                                    {{ $item->product_name }}
                                </td>
                                <td><code>{{ $item->product_code ?? 'N/A' }}</code></td>
                                <td>{{ $item->color ?? 'Standard' }}</td>
                                <td style="text-align: right;">BDT {{ number_format($item->price, 0) }}</td>
                                <td style="text-align: center;">{{ $item->quantity }}</td>
                                <td style="text-align: right; font-weight: 700;">BDT {{ number_format($item->subtotal, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 20px; border-top: 1px solid var(--admin-card-border); display: flex; justify-content: flex-end;">
                <div style="width: 250px; font-size: 13px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span>Subtotal:</span>
                        <span>BDT {{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; color: #16a34a; font-weight: 600;">
                            <span>Discount {{ $order->coupon_code ? '(' . $order->coupon_code . ')' : '' }}:</span>
                            <span>- BDT {{ number_format($order->discount, 0) }}</span>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span>Delivery Charge:</span>
                        <span>BDT {{ number_format($order->delivery_charge, 0) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 700; color: #ea1d24; border-top: 1px solid #cbd5e1; padding-top: 6px; margin-top: 6px;">
                        <span>Grand Total:</span>
                        <span>BDT {{ number_format($order->grand_total, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Status Controls & Customer Info -->
    <div>
        <!-- Status Control Box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Update Status</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <label class="form-label" style="margin: 0;">Order Fulfillment Status</label>
                            <a href="{{ route('admin.order-statuses.index') }}" target="_blank" style="font-size: 11px; color: var(--admin-primary); text-decoration: underline;">
                                <i class="fas fa-cog"></i> Manage Statuses
                            </a>
                        </div>
                        <select name="order_status" class="form-control">
                            @if(isset($statuses) && $statuses->count() > 0)
                                @foreach($statuses as $st)
                                    <option value="{{ $st->slug }}" {{ $order->order_status == $st->slug ? 'selected' : '' }}>
                                        {{ $st->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-control">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-check"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Delivery &amp; Customer Info</h3>
            </div>
            <div class="card-body" style="font-size: 12.5px; line-height: 1.8;">
                <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $order->phone }}</p>
                <hr style="margin: 10px 0; border: none; border-top: 1px solid #f0f0f0;">
                <p><strong>Delivery Method:</strong> {{ $order->delivery_method }}</p>
                <p><strong>Address:</strong> {{ $order->address }}</p>
                <p><strong>Area:</strong> {{ $order->area }}</p>
                <p><strong>District:</strong> {{ $order->district }}</p>
                <p><strong>Postal Code:</strong> {{ $order->postal_code ?? 'N/A' }}</p>
                <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
