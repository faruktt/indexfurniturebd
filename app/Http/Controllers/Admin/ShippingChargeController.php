<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShippingChargeController extends Controller
{
    public function index(Request $request)
    {
        $charges = ShippingCharge::all();
        $editCharge = null;
        if ($request->filled('edit')) {
            $editCharge = ShippingCharge::find($request->edit);
        }
        return view('admin.shipping.index', compact('charges', 'editCharge'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'charge' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        ShippingCharge::create([
            'title' => $request->title,
            'key' => Str::slug($request->title, '_'),
            'charge' => $request->charge,
            'description' => $request->description,
            'status' => $request->has('status'),
        ]);

        return back()->with('success', 'Shipping method created successfully.');
    }

    public function update(Request $request, ShippingCharge $shipping)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'charge' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $shipping->update([
            'title' => $request->title,
            'charge' => $request->charge,
            'description' => $request->description,
            'status' => $request->has('status'),
        ]);

        return back()->with('success', 'Shipping method updated successfully.');
    }

    public function destroy(ShippingCharge $shipping)
    {
        $shipping->delete();
        return back()->with('success', 'Shipping method deleted.');
    }
}
