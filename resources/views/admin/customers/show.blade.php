@extends('admin.layouts.app')

@section('title', 'Customer ' . $customer->name)
@section('page_title', 'Customer Profile: ' . $customer->name)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Customers
    </a>
</div>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 25px;">
    <!-- Profile Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Customer Info</h3>
        </div>
        <div class="card-body" style="font-size: 13px; line-height: 1.8;">
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
            <p><strong>District:</strong> {{ $customer->district ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
            <p><strong>Joined:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
            <p><strong>Status:</strong> 
                @if($customer->status)
                    <span class="badge badge-success">Active</span>
                @else
                    <span class="badge badge-danger">Suspended</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Orders Placed by this Customer -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Order History ({{ $customer->orders->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->orders as $ord)
                        <tr>
                            <td style="font-weight: 700; color: #ea1d24;">{{ $ord->order_number }}</td>
                            <td>{{ $ord->created_at->format('d M, Y') }}</td>
                            <td>{{ $ord->items->count() }} items</td>
                            <td style="font-weight: 700;">BDT {{ number_format($ord->grand_total, 0) }}</td>
                            <td><span class="badge badge-secondary">{{ strtoupper($ord->payment_method) }}</span></td>
                            <td>{!! $ord->status_badge !!}</td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i> Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #94a3b8;">No orders placed yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
