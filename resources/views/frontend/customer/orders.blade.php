@extends('frontend.layouts.app')

@section('title', 'My Orders | ' . \App\Models\SiteSetting::get('site_name', 'Indexfurniture') . ' Furniture')

@section('content')
<div class="container" style="margin: 30px auto 60px;">
    <div style="display: grid; grid-template-columns: 260px 1fr; gap: 30px;">
        <!-- Sidebar Navigation -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 20px;">
            <div style="text-align: center; padding-bottom: 20px; border-bottom: 1px solid #f0f0f0; margin-bottom: 15px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: #0077b6; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; margin: 0 auto 10px;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h3 style="font-size: 14px; font-weight: 700; color: #111;">{{ $user->name }}</h3>
                <div style="font-size: 11.5px; color: #777;">{{ $user->email }}</div>
            </div>

            <ul style="list-style: none; padding: 0; margin: 0; font-size: 12.5px;">
                <li style="margin-bottom: 8px;">
                    <a href="{{ route('customer.dashboard') }}" style="display: block; padding: 8px 12px; color: #555555; border-radius: 3px;">
                        <i class="fas fa-tachometer-alt" style="margin-right: 8px;"></i> Dashboard
                    </a>
                </li>
                <li style="margin-bottom: 8px;">
                    <a href="{{ route('customer.orders') }}" style="display: block; padding: 8px 12px; background: #f8fafc; color: var(--otobi-red); font-weight: 600; border-radius: 3px;">
                        <i class="fas fa-shopping-bag" style="margin-right: 8px;"></i> My Orders
                    </a>
                </li>
                <li style="margin-top: 15px; border-top: 1px solid #f0f0f0; padding-top: 12px;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 12.5px; cursor: pointer; display: flex; align-items: center; gap: 8px; padding: 8px 12px; width: 100%;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Orders Table -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 24px;">
            <h2 style="font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 18px; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px;">
                Order History ({{ $orders->total() }})
            </h2>

            @if($orders->count() > 0)
                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #cbd5e1;">
                            <th style="padding: 10px; text-align: left;">Order Number</th>
                            <th style="padding: 10px; text-align: left;">Date</th>
                            <th style="padding: 10px; text-align: left;">Payment</th>
                            <th style="padding: 10px; text-align: right;">Total Amount</th>
                            <th style="padding: 10px; text-align: center;">Delivery Status</th>
                            <th style="padding: 10px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 12px 10px; font-weight: 700; color: var(--otobi-red);">
                                    {{ $order->order_number }}
                                </td>
                                <td style="padding: 12px 10px; color: #666;">
                                    {{ $order->created_at->format('d M, Y') }}
                                </td>
                                <td style="padding: 12px 10px; text-transform: uppercase;">
                                    {{ $order->payment_method }}
                                </td>
                                <td style="padding: 12px 10px; text-align: right; font-weight: 700;">
                                    BDT {{ number_format($order->grand_total, 0) }}
                                </td>
                                <td style="padding: 12px 10px; text-align: center;">
                                    {!! $order->status_badge !!}
                                </td>
                                <td style="padding: 12px 10px; text-align: center;">
                                    <a href="{{ route('checkout.success', $order->order_number) }}" style="color: #0077b6; font-weight: 600; text-decoration: underline;">
                                        View Invoice
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 20px;">
                    {{ $orders->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: #777;">
                    <i class="fas fa-box-open" style="font-size: 36px; color: #ccc; margin-bottom: 10px;"></i>
                    <p>No orders placed yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
