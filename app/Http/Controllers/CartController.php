<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CartController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function addProduct(Request $request, Product $product)
    {
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return redirect()->back()->with('success', __('added_to_cart'));
    }

    public function addFromPost(Post $post)
    {
        $products = $post->products;

        foreach ($products as $product) {
            $cartItem = CartItem::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }
        }

        return redirect()->route('cart.index')->with('success', __('products_added_to_cart'));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorize('update', $cartItem);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update($validated);

        return redirect()->back()->with('success', __('cart_updated'));
    }

    public function destroy(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);
        $cartItem->delete();

        return redirect()->back()->with('success', __('removed_from_cart'));
    }
}