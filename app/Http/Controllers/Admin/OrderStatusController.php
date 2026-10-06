<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderStatusController extends Controller
{
    public function index(Request $request)
    {
        $statuses = OrderStatus::withCount('orders')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $editStatus = null;
        if ($request->filled('edit')) {
            $editStatus = OrderStatus::find($request->edit);
        }

        return view('admin.orders.statuses', compact('statuses', 'editStatus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:order_statuses,slug',
            'bg_color' => 'nullable|string|max:50',
            'text_color' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
        ]);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        // Ensure unique slug
        $baseSlug = $slug;
        $counter = 1;
        while (OrderStatus::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $isDefault = $request->has('is_default');
        if ($isDefault) {
            OrderStatus::where('is_default', true)->update(['is_default' => false]);
        }

        OrderStatus::create([
            'name' => $request->name,
            'slug' => $slug,
            'bg_color' => $request->bg_color ?: '#f1f5f9',
            'text_color' => $request->text_color ?: '#334155',
            'order' => $request->order ?? 0,
            'is_default' => $isDefault,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.order-statuses.index')->with('success', 'Order status created successfully.');
    }

    public function update(Request $request, OrderStatus $order_status)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:order_statuses,slug,' . $order_status->id,
            'bg_color' => 'nullable|string|max:50',
            'text_color' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
        ]);

        $oldSlug = $order_status->slug;
        $newSlug = Str::slug($request->slug);

        $isDefault = $request->has('is_default');
        if ($isDefault && !$order_status->is_default) {
            OrderStatus::where('is_default', true)->update(['is_default' => false]);
        }

        $order_status->update([
            'name' => $request->name,
            'slug' => $newSlug,
            'bg_color' => $request->bg_color ?: '#f1f5f9',
            'text_color' => $request->text_color ?: '#334155',
            'order' => $request->order ?? 0,
            'is_default' => $isDefault,
            'status' => $request->has('status'),
        ]);

        // If slug changed, update associated orders so their status string matches
        if ($oldSlug !== $newSlug) {
            Order::where('order_status', $oldSlug)->update(['order_status' => $newSlug]);
        }

        return redirect()->route('admin.order-statuses.index')->with('success', 'Order status updated successfully.');
    }

    public function destroy(OrderStatus $order_status)
    {
        $orderCount = Order::where('order_status', $order_status->slug)->count();

        if ($orderCount > 0) {
            return back()->with('error', "Cannot delete '{$order_status->name}' because {$orderCount} order(s) currently have this status. Please update those orders before deleting.");
        }

        $order_status->delete();

        return redirect()->route('admin.order-statuses.index')->with('success', 'Order status deleted successfully.');
    }

    public function toggle(OrderStatus $order_status)
    {
        $order_status->status = !$order_status->status;
        $order_status->save();

        return back()->with('success', 'Order status visibility toggled.');
    }
}
