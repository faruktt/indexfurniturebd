<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        // If cart is empty, auto-populate sample items matching the product catalog
        if (empty($cart)) {
            $sofa = Product::where('slug', 'like', '%sofa%')->first() ?? Product::first();
            $cabinet = Product::where('slug', 'like', '%cabinet%')->first() ?? Product::skip(1)->first();

            if ($sofa) {
                $cart[$sofa->id . '_Royal Purple_none'] = [
                    'id' => $sofa->id,
                    'title' => $sofa->title,
                    'slug' => $sofa->slug,
                    'product_code' => $sofa->product_code,
                    'image' => $sofa->image,
                    'price' => (float) $sofa->price,
                    'color' => 'Royal Purple',
                    'size' => null,
                    'quantity' => 1,
                    'subtotal' => (float) $sofa->price,
                ];
            }

            if ($cabinet) {
                $cart[$cabinet->id . '_American Teak-Beige_none'] = [
                    'id' => $cabinet->id,
                    'title' => $cabinet->title,
                    'slug' => $cabinet->slug,
                    'product_code' => $cabinet->product_code,
                    'image' => $cabinet->image,
                    'price' => (float) $cabinet->price,
                    'color' => 'American Teak-Beige',
                    'size' => null,
                    'quantity' => 1,
                    'subtotal' => (float) $cabinet->price,
                ];
            }

            session()->put('cart', $cart);
        }

        $shippingMethods = ShippingCharge::where('status', true)->get();
        $defaultShipping = $shippingMethods->firstWhere('key', 'inside_dhaka') ?? $shippingMethods->first();

        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $deliveryCharge = $defaultShipping ? (float) $defaultShipping->charge : 1094.00;

        $user = Auth::guard('web')->user();

        // Check if there is a coupon in session
        $appliedCoupon = session()->get('coupon');
        $discount = 0.0;

        if ($appliedCoupon) {
            $couponModel = Coupon::where('code', $appliedCoupon['code'])->first();
            if ($couponModel) {
                $calc = $couponModel->calculateDiscount($subtotal, $user ? $user->id : null, $user ? $user->email : null, $user ? $user->phone : null);
                if ($calc['valid']) {
                    $discount = (float) $calc['discount'];
                    // Refresh session data
                    session()->put('coupon', [
                        'id' => $couponModel->id,
                        'code' => $couponModel->code,
                        'type' => $couponModel->type,
                        'value' => (float) $couponModel->value,
                        'discount' => $discount,
                    ]);
                } else {
                    session()->forget('coupon');
                    $appliedCoupon = null;
                }
            } else {
                session()->forget('coupon');
                $appliedCoupon = null;
            }
        }

        $grandTotal = max(0, $subtotal - $discount + $deliveryCharge);

        return view('frontend.checkout.index', compact(
            'cart',
            'shippingMethods',
            'defaultShipping',
            'subtotal',
            'discount',
            'appliedCoupon',
            'deliveryCharge',
            'grandTotal',
            'user'
        ));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
            'delivery_charge' => 'nullable|numeric',
        ]);

        $cart = session()->get('cart', []);
        $subtotal = (float) array_sum(array_column($cart, 'subtotal'));

        if ($subtotal <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty. Please add items to apply a coupon.',
            ]);
        }

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code. Please verify and try again.',
            ]);
        }

        $user = Auth::guard('web')->user();
        $userId = $user ? $user->id : null;
        $email = $user ? $user->email : ($request->email ? trim($request->email) : null);
        $phone = $user ? $user->phone : ($request->phone ? trim($request->phone) : null);

        $calculation = $coupon->calculateDiscount($subtotal, $userId, $email, $phone);

        if (!$calculation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $calculation['message'],
            ]);
        }

        $discount = (float) $calculation['discount'];
        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float) $coupon->value,
            'discount' => $discount,
        ]);

        $deliveryCharge = $request->filled('delivery_charge') ? (float) $request->delivery_charge : 1094.00;
        $grandTotal = max(0, $subtotal - $discount + $deliveryCharge);

        return response()->json([
            'success' => true,
            'message' => 'Coupon "' . $coupon->code . '" applied successfully!',
            'coupon_code' => $coupon->code,
            'discount' => $discount,
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'grand_total' => $grandTotal,
            'formatted_discount' => 'BDT ' . number_format($discount, 0),
            'formatted_grand_total' => 'BDT ' . number_format($grandTotal, 0),
        ]);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('coupon');

        $cart = session()->get('cart', []);
        $subtotal = (float) array_sum(array_column($cart, 'subtotal'));
        $deliveryCharge = $request->filled('delivery_charge') ? (float) $request->delivery_charge : 1094.00;
        $grandTotal = $subtotal + $deliveryCharge;

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'discount' => 0,
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'grand_total' => $grandTotal,
            'formatted_grand_total' => 'BDT ' . number_format($grandTotal, 0),
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_no' => 'required|string|max:50',
            'country' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'area' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'street_address' => 'required|string',
            'delivery_method' => 'required|string',
            'payment_method' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();
        try {
            $user = Auth::guard('web')->user();

            // Auto-create user account if password provided
            if (!$user && $request->filled('password') && $request->filled('email')) {
                $existingUser = User::where('email', $request->email)->first();
                if (!$existingUser) {
                    $user = User::create([
                        'name' => $request->full_name,
                        'email' => $request->email,
                        'phone' => $request->contact_no,
                        'address' => $request->street_address,
                        'district' => $request->district,
                        'password' => Hash::make($request->password),
                        'status' => true,
                    ]);
                    Auth::guard('web')->login($user);
                }
            }

            // Find selected delivery method charge
            $shippingMethod = ShippingCharge::where('title', $request->delivery_method)->first();
            $deliveryCharge = $shippingMethod ? (float) $shippingMethod->charge : 1094.00;

            $subtotal = array_sum(array_column($cart, 'subtotal'));

            // Process Coupon Discount if any
            $couponSession = session()->get('coupon');
            $discount = 0.0;
            $appliedCouponModel = null;

            if ($couponSession) {
                $appliedCouponModel = Coupon::where('code', $couponSession['code'])->first();
                if ($appliedCouponModel) {
                    $userId = $user ? $user->id : null;
                    $calc = $appliedCouponModel->calculateDiscount($subtotal, $userId, $request->email, $request->contact_no);
                    if ($calc['valid']) {
                        $discount = (float) $calc['discount'];
                    } else {
                        // Coupon became invalid
                        session()->forget('coupon');
                        $appliedCouponModel = null;
                        $discount = 0.0;
                    }
                }
            }

            $grandTotal = max(0, $subtotal - $discount + $deliveryCharge);

            $orderNumber = 'INDEX-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user ? $user->id : null,
                'customer_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->contact_no,
                'country' => $request->country,
                'district' => $request->district,
                'area' => $request->area,
                'postal_code' => $request->postal_code,
                'address' => $request->street_address,
                'delivery_method' => $request->delivery_method,
                'delivery_charge' => $deliveryCharge,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'coupon_code' => $appliedCouponModel ? $appliedCouponModel->code : null,
                'grand_total' => $grandTotal,
                'order_status' => 'pending',
                'payment_status' => ($request->payment_method === 'cod') ? 'pending' : 'paid',
                'notes' => $request->notes ?? null,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['title'],
                    'product_code' => $item['product_code'] ?? null,
                    'product_image' => $item['image'] ?? null,
                    'color' => $item['color'] ?? null,
                    'size' => $item['size'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Record Coupon Usage & Increment count
            if ($appliedCouponModel && $discount > 0) {
                $appliedCouponModel->increment('used_count');

                CouponUsage::create([
                    'coupon_id' => $appliedCouponModel->id,
                    'user_id' => $user ? $user->id : null,
                    'order_id' => $order->id,
                    'email' => $request->email ? strtolower(trim($request->email)) : null,
                    'phone' => $request->contact_no ? trim($request->contact_no) : null,
                    'discount_amount' => $discount,
                ]);
            }

            DB::commit();

            session()->forget('cart');
            session()->forget('coupon');

            return redirect()->route('checkout.success', $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Order placement failed: ' . $e->getMessage());
        }
    }

    public function success($order_number)
    {
        $order = Order::with('items')->where('order_number', $order_number)->firstOrFail();
        return view('frontend.checkout.success', compact('order'));
    }
}
