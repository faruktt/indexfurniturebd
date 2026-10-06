@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page_title', 'Order Management')

@section('content')
<!-- Status Tabs -->
<div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
        <a href="{{ route('admin.orders.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline' }} btn-sm">
            All Orders ({{ $counts['all'] ?? 0 }})
        </a>

        @if(isset($statuses))
            @foreach($statuses as $st)
                <a href="{{ route('admin.orders.index', ['status' => $st->slug]) }}" class="btn {{ request('status') == $st->slug ? 'btn-primary' : 'btn-outline' }} btn-sm">
                    {{ $st->name }} ({{ $counts[$st->slug] ?? 0 }})
                </a>
            @endforeach
        @endif
    </div>

    <div>
        <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-tags"></i> Manage Order Statuses
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header" style="justify-content: space-between;">
        <h3 class="card-title">Order Records ({{ $orders->total() }})</h3>
        <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; gap: 8px;">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order #, customer, phone..." class="form-control" style="width: 260px; padding: 6px 12px; font-size: 12px;">
            <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Location</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Delivery Method</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="font-weight: 700; color: #ea1d24;">
                            <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_number }}</a>
                        </td>
                        <td style="font-weight: 600;">{{ $order->customer_name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ $order->district }}</td>
                        <td>{{ $order->items->count() }} items</td>
                        <td style="font-weight: 700;">BDT {{ number_format($order->grand_total, 0) }}</td>
                        <td style="font-size: 11.5px; color: #475569;">{{ $order->delivery_method }}</td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge badge-success">PAID</span>
                            @else
                                <span class="badge badge-warning">PENDING</span>
                            @endif
                        </td>
                        <td>{!! $order->status_badge !!}</td>
                        <td style="color: #64748b; font-size: 11px;">{{ $order->created_at->format('d M, Y') }}</td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline btn-sm" title="View Order">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline btn-sm" title="Print Invoice">
                                <i class="fas fa-print"></i>
                            </a>
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this order?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger-sm" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center; padding: 30px; color: #94a3b8;">
                            No orders found matching the criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 15px 20px;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
