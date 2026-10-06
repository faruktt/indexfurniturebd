<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return redirect()->route('checkout');
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $quantity = (int) ($request->quantity ?? 1);
        $color = $request->color ?? ($product->colors->first()->name ?? null);
        $size = $request->size ?? ($product->sizes->first()->name ?? null);
        $cartKey = $product->id . '_' . ($color ?? 'none') . '_' . ($size ?? 'none');

        $price = (float) ($product->discount_price > 0 ? $product->discount_price : $product->price);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['subtotal'] = $cart[$cartKey]['quantity'] * $cart[$cartKey]['price'];
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'product_code' => $product->product_code,
                'image' => $product->image,
                'price' => $price,
                'color' => $color,
                'size' => $size,
                'quantity' => $quantity,
                'subtotal' => $price * $quantity,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart!',
                'cart_count' => count($cart),
                'cart_total' => array_sum(array_column($cart, 'subtotal')),
            ]);
        }

        return redirect()->route('checkout')->with('success', 'Product added to your cart.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->key])) {
            $cart[$request->key]['quantity'] = (int) $request->quantity;
            $cart[$request->key]['subtotal'] = $cart[$request->key]['quantity'] * $cart[$request->key]['price'];
            session()->put('cart', $cart);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'item_subtotal' => number_format($cart[$request->key]['subtotal'], 0),
                    'cart_count' => count($cart),
                    'cart_subtotal' => number_format(array_sum(array_column($cart, 'subtotal')), 0),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->key])) {
            unset($cart[$request->key]);
            session()->put('cart', $cart);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'cart_count' => count($cart),
                    'cart_subtotal' => number_format(array_sum(array_column($cart, 'subtotal')), 0),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
