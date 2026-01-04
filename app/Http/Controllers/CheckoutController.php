<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('cart_empty'));
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|in:cash_on_delivery,bank_transfer',
        ]);

        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product.shop')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('cart_empty'));
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        DB::transaction(function () use ($validated, $cartItems, $total) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'total' => $total,
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
            ]);

            // Create order approvals for each shop
            $shopIds = $cartItems->pluck('product.shop_id')->unique()->filter();
            foreach ($shopIds as $shopId) {
                OrderApproval::create([
                    'order_id' => $order->id,
                    'shop_id' => $shopId,
                    'is_approved' => false,
                ]);
            }

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            CartItem::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('orders.index')->with('success', __('order_placed'));
    }
}