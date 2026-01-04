<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShopController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $shops = Shop::where('is_active', true)
            ->with('user')
            ->withCount('products')
            ->latest()
            ->paginate(12);

        return view('shops.index', compact('shops'));
    }

    public function show(Shop $shop)
    {
        $shop->load('user', 'products');
        return view('shops.show', compact('shop'));
    }

    public function create()
    {
        $this->authorize('create', Shop::class);
        return view('shops.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Shop::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('shops', 'public');
        }

        Shop::create($validated);

        return redirect()->route('shop-owner.dashboard')->with('success', __('shop_created'));
    }

    public function edit(Shop $shop)
    {
        $this->authorize('update', $shop);
        return view('shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        $this->authorize('update', $shop);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            $validated['logo'] = $request->file('logo')->store('shops', 'public');
        }

        $shop->update($validated);

        return redirect()->route('shop-owner.dashboard')->with('success', __('shop_updated'));
    }

    public function destroy(Shop $shop)
    {
        $this->authorize('delete', $shop);

        if ($shop->logo) {
            Storage::disk('public')->delete($shop->logo);
        }

        $shop->delete();

        return redirect()->route('shops.index')->with('success', __('shop_deleted'));
    }
}