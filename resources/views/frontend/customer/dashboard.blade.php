@extends('frontend.layouts.app')

@section('title', 'My Account | ' . \App\Models\SiteSetting::get('site_name', 'Indexfurniture') . ' Furniture')

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
                    <a href="{{ route('customer.dashboard') }}" style="display: block; padding: 8px 12px; background: #f8fafc; color: var(--otobi-red); font-weight: 600; border-radius: 3px;">
                        <i class="fas fa-tachometer-alt" style="margin-right: 8px;"></i> Dashboard
                    </a>
                </li>
                <li style="margin-bottom: 8px;">
                    <a href="{{ route('customer.orders') }}" style="display: block; padding: 8px 12px; color: #555555; border-radius: 3px;">
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

        <!-- Main Content Area -->
        <div>
            <!-- Stats -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="font-size: 11px; text-transform: uppercase; color: #666; font-weight: 600;">Total Orders</div>
                    <div style="font-size: 24px; font-weight: 700; color: #111; margin-top: 4px;">{{ $totalOrders }}</div>
                </div>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="font-size: 11px; text-transform: uppercase; color: #666; font-weight: 600;">Total Spent</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--otobi-red); margin-top: 4px;">BDT {{ number_format($totalSpent, 0) }}</div>
                </div>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="font-size: 11px; text-transform: uppercase; color: #666; font-weight: 600;">Account Status</div>
                    <div style="font-size: 14px; font-weight: 700; color: #16a34a; margin-top: 10px;">
                        <i class="fas fa-check-circle"></i> Active Customer
                    </div>
                </div>
            </div>

            <!-- Recent Orders Card -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 20px; margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px;">
                    <h2 style="font-size: 14px; font-weight: 700; text-transform: uppercase;">Recent Orders</h2>
                    <a href="{{ route('customer.orders') }}" style="font-size: 12px; color: #0077b6;">View All &gt;</a>
                </div>

                @if($recentOrders->count() > 0)
                    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 8px 10px; text-align: left;">Order #</th>
                                <th style="padding: 8px 10px; text-align: left;">Date</th>
                                <th style="padding: 8px 10px; text-align: left;">Items</th>
                                <th style="padding: 8px 10px; text-align: right;">Total</th>
                                <th style="padding: 8px 10px; text-align: center;">Status</th>
                                <th style="padding: 8px 10px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $ro)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 10px; font-weight: 600; color: var(--otobi-red);">{{ $ro->order_number }}</td>
                                    <td style="padding: 10px; color: #666;">{{ $ro->created_at->format('M d, Y') }}</td>
                                    <td style="padding: 10px;">{{ $ro->items->count() }} items</td>
                                    <td style="padding: 10px; text-align: right; font-weight: 600;">BDT {{ number_format($ro->grand_total, 0) }}</td>
                                    <td style="padding: 10px; text-align: center;">{!! $ro->status_badge !!}</td>
                                    <td style="padding: 10px; text-align: center;">
                                        <a href="{{ route('customer.orders.details', $ro->order_number) }}" style="color: #0077b6; font-weight: 600;">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color: #777; font-size: 12.5px; text-align: center; padding: 20px;">You have not placed any orders yet.</p>
                @endif
            </div>

            <!-- Profile Info Form -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 20px;">
                <h2 style="font-size: 14px; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px;">
                    Personal Information &amp; Address
                </h2>

                <form action="{{ route('customer.profile.update') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <label style="display: block; font-size: 11.5px; margin-bottom: 4px; font-weight: 600;">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" required style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 12.5px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 11.5px; margin-bottom: 4px; font-weight: 600;">Contact Phone</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" required style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 12.5px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <label style="display: block; font-size: 11.5px; margin-bottom: 4px; font-weight: 600;">District</label>
                            <input type="text" name="district" value="{{ $user->district }}" style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 12.5px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 11.5px; margin-bottom: 4px; font-weight: 600;">Change Password (optional)</label>
                            <input type="password" name="password" placeholder="Leave empty to keep current" style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 12.5px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 11.5px; margin-bottom: 4px; font-weight: 600;">Default Delivery Address</label>
                        <textarea name="address" style="width: 100%; height: 60px; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 12.5px;">{{ $user->address }}</textarea>
                    </div>

                    <button type="submit" style="padding: 8px 22px; background: #0077b6; color: #ffffff; border: none; border-radius: 3px; font-weight: 600; font-size: 12px; cursor: pointer;">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
