<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $statuses = OrderStatus::where('status', true)->orderBy('order', 'asc')->get();

        $counts = [
            'all' => Order::count(),
        ];
        foreach ($statuses as $st) {
            $counts[$st->slug] = Order::where('order_status', $st->slug)->count();
        }

        return view('admin.orders.index', compact('orders', 'counts', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        $statuses = OrderStatus::where('status', true)->orderBy('order', 'asc')->get();
        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validStatuses = OrderStatus::where('status', true)->pluck('slug')->toArray();
        if (empty($validStatuses)) {
            $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        }

        $request->validate([
            'order_status' => 'required|in:' . implode(',', $validStatuses),
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order->update([
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function invoice(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.invoice', compact('order'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}
