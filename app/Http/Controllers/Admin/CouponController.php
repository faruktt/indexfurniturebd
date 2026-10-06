<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::withCount('usages')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === '1');
        }

        $coupons = $query->paginate(15)->withQueryString();

        // Coupon Statistics
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('status', true)->count();
        $totalUses = Coupon::sum('used_count');
        $totalDiscountGiven = CouponUsage::sum('discount_amount');

        return view('admin.coupons.index', compact(
            'coupons',
            'totalCoupons',
            'activeCoupons',
            'totalUses',
            'totalDiscountGiven'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'user_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:500',
        ]);

        Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'value' => $request->value,
            'min_spend' => $request->filled('min_spend') ? $request->min_spend : null,
            'max_discount' => ($request->type === 'percent' && $request->filled('max_discount')) ? $request->max_discount : null,
            'usage_limit' => $request->filled('usage_limit') ? $request->usage_limit : null,
            'user_limit' => $request->filled('user_limit') ? $request->user_limit : 1,
            'start_date' => $request->start_date ?: null,
            'end_date' => $request->end_date ?: null,
            'status' => $request->has('status'),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'user_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:500',
        ]);

        $coupon->update([
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'value' => $request->value,
            'min_spend' => $request->filled('min_spend') ? $request->min_spend : null,
            'max_discount' => ($request->type === 'percent' && $request->filled('max_discount')) ? $request->max_discount : null,
            'usage_limit' => $request->filled('usage_limit') ? $request->usage_limit : null,
            'user_limit' => $request->filled('user_limit') ? $request->user_limit : 1,
            'start_date' => $request->start_date ?: null,
            'end_date' => $request->end_date ?: null,
            'status' => $request->has('status'),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->status = !$coupon->status;
        $coupon->save();

        return back()->with('success', 'Coupon status updated successfully.');
    }
}
