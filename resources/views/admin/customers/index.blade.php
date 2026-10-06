@extends('admin.layouts.app')

@section('title', 'Customers')
@section('page_title', 'Customer Management')

@section('content')
<div class="card">
    <div class="card-header" style="justify-content: space-between;">
        <h3 class="card-title">Registered Customers ({{ $customers->total() }})</h3>
        <form action="{{ route('admin.customers.index') }}" method="GET" style="display: flex; gap: 8px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer name, email, phone..." class="form-control" style="width: 280px; padding: 6px 12px; font-size: 12px;">
            <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>District</th>
                    <th>Orders Placed</th>
                    <th>Account Status</th>
                    <th>Joined Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $cust)
                    <tr>
                        <td style="font-weight: 700; color: #0f172a;">{{ $cust->name }}</td>
                        <td>{{ $cust->email }}</td>
                        <td>{{ $cust->phone ?? '-' }}</td>
                        <td>{{ $cust->district ?? '-' }}</td>
                        <td>
                            <span class="badge badge-secondary">{{ $cust->orders_count }} orders</span>
                        </td>
                        <td>
                            @if($cust->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Disabled</span>
                            @endif
                        </td>
                        <td style="color: #64748b; font-size: 11px;">{{ $cust->created_at->format('d M, Y') }}</td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ route('admin.customers.show', $cust->id) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-eye"></i> Details
                            </a>
                            <form action="{{ route('admin.customers.toggle', $cust->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-sm" title="Toggle Active / Suspended">
                                    <i class="fas fa-toggle-on"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px; color: #94a3b8;">
                            No customers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 15px 20px;">
        {{ $customers->links() }}
    </div>
</div>
@endsection
