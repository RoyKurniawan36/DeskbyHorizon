@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-7xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-text-950 mb-2">{{ __('product_inventory') }}</h1>
            <p class="text-text-500 font-medium">{{ __('manage_your_product_catalog_and_stock_levels') }}</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('shop-owner.dashboard') }}" class="text-sm font-bold text-text-400 hover:text-primary-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('back_to_dashboard') }}
            </a>
            <a href="{{ route('shop-owner.products.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-primary-500/20 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                {{ __('add_product') }}
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-[2rem] p-6 shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $products->total() }}</p>
            <p class="text-sm font-bold text-white/80 uppercase tracking-wider">{{ __('total_products') }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-[2rem] p-6 shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $products->where('stock', '>', 10)->count() }}</p>
            <p class="text-sm font-bold text-white/80 uppercase tracking-wider">{{ __('in_stock') }}</p>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-[2rem] p-6 shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $products->whereBetween('stock', [1, 10])->count() }}</p>
            <p class="text-sm font-bold text-white/80 uppercase tracking-wider">{{ __('low_stock') }}</p>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-[2rem] p-6 shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $products->where('stock', 0)->count() }}</p>
            <p class="text-sm font-bold text-white/80 uppercase tracking-wider">{{ __('out_of_stock') }}</p>
        </div>
    </div>

    @if($products->count() > 0)
        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-background-50 border border-background-200 rounded-[2rem] overflow-hidden shadow-xl shadow-background-900/5 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <!-- Product Image -->
                    <div class="relative aspect-square bg-background-200">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-text-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <!-- Stock Badge -->
                        <div class="absolute top-3 right-3">
                            @if($product->stock > 10)
                                <span class="px-3 py-1 bg-green-500 text-white text-xs font-black uppercase tracking-wider rounded-full shadow-lg">
                                    {{ __('in_stock') }}
                                </span>
                            @elseif($product->stock > 0)
                                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-black uppercase tracking-wider rounded-full shadow-lg">
                                    {{ __('low_stock') }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-500 text-white text-xs font-black uppercase tracking-wider rounded-full shadow-lg">
                                    {{ __('out_of_stock') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-5">
                        <div class="mb-3">
                            @if($product->category)
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-600">{{ $product->category }}</span>
                            @endif
                            <h3 class="text-lg font-black text-text-950 mb-1 truncate">{{ $product->name }}</h3>
                            <p class="text-sm text-text-500 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                        </div>

                        <div class="flex items-end justify-between mb-4 pt-3 border-t border-background-200">
                            <div>
                                <p class="text-xs font-bold text-text-400 uppercase tracking-wider mb-1">{{ __('price') }}</p>
                                <p class="text-2xl font-black text-primary-600">${{ number_format($product->price, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-text-400 uppercase tracking-wider mb-1">{{ __('stock') }}</p>
                                <p class="text-2xl font-black text-text-950">{{ $product->stock }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('shop-owner.products.edit', $product) }}" 
                               class="flex-1 bg-primary-600 hover:bg-primary-700 text-white text-center py-3 rounded-xl font-black uppercase tracking-widest text-[10px] transition-all active:scale-95">
                                {{ __('edit') }}
                            </a>
                            <form action="{{ route('shop-owner.products.destroy', $product) }}" method="POST" class="flex-1" onsubmit="return confirm('{{ __('confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-accent-600 hover:bg-accent-700 text-white py-3 rounded-xl font-black uppercase tracking-widest text-[10px] transition-all active:scale-95">
                                    {{ __('delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-background-50 border-2 border-dashed border-background-200 rounded-[3rem] p-16 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-primary-100 text-primary-600 rounded-3xl mb-6">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-text-950 mb-3">{{ __('no_products_yet') }}</h3>
            <p class="text-text-500 mb-8 max-w-md mx-auto">{{ __('start_building_your_catalog_by_adding_your_first_product') }}</p>
            <a href="{{ route('shop-owner.products.create') }}" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-primary-500/20 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                {{ __('add_your_first_product') }}
            </a>
        </div>
    @endif
</div>
@endsection