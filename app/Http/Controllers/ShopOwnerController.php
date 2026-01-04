<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShopOwnerController extends Controller
{
    use AuthorizesRequests;

    public function dashboard()
    {
        $user = auth()->user();

        if (!$user->hasShop()) {
            return redirect()->route('shops.create')->with('info', __('create_shop_first'));
        }

        $shop = $user->shop;
        $products = $shop->products()->latest()->paginate(10);
        $pendingApprovals = OrderApproval::where('shop_id', $shop->id)
            ->where('is_approved', false)
            ->with('order.user')
            ->latest()
            ->paginate(10);

        return view('shop-owner.dashboard', compact('shop', 'products', 'pendingApprovals'));
    }

    public function products()
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('shops.create');
        }

        $products = $shop->products()->latest()->paginate(15);

        return view('shop-owner.products.index', compact('shop', 'products'));
    }

    public function createProduct()
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('shops.create');
        }

        return view('shop-owner.products.create', compact('shop'));
    }

    public function storeProduct(Request $request)
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('shops.create');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['shop_id'] = $shop->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('shop-owner.products')->with('success', __('product_created'));
    }

    public function editProduct(Product $product)
    {
        $this->authorize('update', $product);
        $shop = auth()->user()->shop;

        return view('shop-owner.products.edit', compact('shop', 'product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('shop-owner.products')->with('success', __('product_updated'));
    }

    public function destroyProduct(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('shop-owner.products')->with('success', __('product_deleted'));
    }

    public function orders()
    {
        $shop = auth()->user()->shop;
    
        if (!$shop) {
            return redirect()->route('shops.create');
        }
    
        // Get all order approvals for this shop with eager loaded relationships
        $approvals = OrderApproval::where('shop_id', $shop->id)
            ->with(['order.user', 'order.items.product', 'order.approvals'])
            ->latest()
            ->paginate(15);
    
        // Extract orders from approvals and remove duplicates
        $orders = $approvals->map(function($approval) {
            return $approval->order;
        })->unique('id')->values();
    
        return view('shop-owner.orders', compact('shop', 'orders', 'approvals'));
    }

    public function approveOrder(Request $request, OrderApproval $approval)
    {
        $shop = auth()->user()->shop;

        if ($approval->shop_id !== $shop->id) {
            abort(403);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $approval->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        $order = $approval->order;
        if ($order->isFullyApproved()) {
            $order->update(['status' => 'approved']);
        }

        return redirect()->back()->with('success', __('order_approved'));
    }
}