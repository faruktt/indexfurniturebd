<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        $recentOrders = Order::with('items')->where('user_id', $user->id)->latest()->take(5)->get();
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)->sum('grand_total');

        return view('frontend.customer.dashboard', compact('user', 'recentOrders', 'totalOrders', 'totalSpent'));
    }

    public function orders()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::with('items')->where('user_id', $user->id)->latest()->paginate(10);
        return view('frontend.customer.orders', compact('user', 'orders'));
    }

    public function orderDetails($order_number)
    {
        $user = Auth::guard('web')->user();
        $order = Order::with('items.product')->where('user_id', $user->id)->where('order_number', $order_number)->firstOrFail();
        return view('frontend.customer.order_details', compact('user', 'order'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'district' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'district' => $request->district,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
