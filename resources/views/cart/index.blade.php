@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 py-8 md:py-12 max-w-6xl bg-background-50 text-text-950">
    {{-- Header Section --}}
    <div class="mb-8 md:mb-12">
        <nav class="flex items-center gap-2 text-sm font-bold text-text-400 uppercase tracking-widest mb-6">
            <a href="{{ route('welcome') }}" class="hover:text-primary-600 transition-colors duration-300">{{ __('Shop') }}</a>
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7" stroke-width="3" />
            </svg>
            <span class="text-text-900 truncate">{{ __('Shopping Cart') }}</span>
        </nav>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1">
                <h1 class="text-3xl md:text-5xl font-black text-text-950 tracking-tighter mb-2">
                    {{ __('Your Cart') }}<span class="text-primary-600">.</span>
                </h1>
                <p class="text-text-600 text-base md:text-lg">
                    {{ $cartItems->count() }} {{ __('item(s)') }}
                </p>
            </div>
            
            <div class="bg-gradient-to-r from-background-100 to-background-50 px-6 py-4 rounded-2xl border border-background-200 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                    <div>
                        <p class="text-text-600 font-bold text-sm uppercase tracking-wider">
                            {{ __('Subtotal') }}
                        </p>
                        <p class="text-2xl md:text-3xl font-black text-text-950">
                            ${{ number_format($total, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Cart Items --}}
        <div class="lg:col-span-2">
            <div class="space-y-4">
                @foreach($cartItems as $item)
                <div class="group bg-background-100 border border-background-200 rounded-2xl p-4 md:p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col sm:flex-row gap-4">
                        {{-- Product Image --}}
                        <div class="sm:w-32 sm:flex-shrink-0">
                            <div class="aspect-square relative overflow-hidden rounded-xl bg-background-50">
                                @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                <div class="w-full h-full flex items-center justify-center bg-background-200">
                                    <svg class="w-12 h-12 text-text-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Product Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col h-full">
                                <div class="mb-2">
                                    <span class="inline-block text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-100 px-3 py-1 rounded-full">
                                        {{ $item->product->category ?? __('Premium') }}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-bold text-text-950 truncate">
                                    {{ $item->product->name }}
                                </h3>
                                
                                <p class="text-text-500 text-sm line-clamp-2 mb-4">
                                    {{ $item->product->description }}
                                </p>
                                
                                <div class="mt-auto">
                                    <div class="flex flex-wrap items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center bg-background-50 rounded-xl border border-background-300">
                                                @csrf @method('PATCH')
                                                <button type="button" 
                                                        onclick="updateQuantity(this, -1)" 
                                                        class="px-3 py-2 text-text-400 hover:text-primary-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <input type="number" 
                                                       name="quantity" 
                                                       value="{{ $item->quantity }}" 
                                                       min="1" 
                                                       max="99"
                                                       class="w-12 bg-transparent text-center font-bold text-text-950 focus:outline-none border-none"
                                                       onchange="this.form.submit()">
                                                <button type="button" 
                                                        onclick="updateQuantity(this, 1)" 
                                                        class="px-3 py-2 text-text-400 hover:text-primary-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2 text-text-400 hover:text-accent-600 hover:bg-accent-100 rounded-lg transition-colors duration-300"
                                                        onclick="return confirm('{{ __('Remove this item from cart?') }}')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-text-950">
                                                ${{ number_format($item->product->price * $item->quantity, 2) }}
                                            </p>
                                            <p class="text-sm text-text-400">
                                                ${{ number_format($item->product->price, 2) }} {{ __('each') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            {{-- Continue Shopping --}}
            <div class="mt-8">
                <a href="{{ route('welcome') }}" 
                   class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-bold transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Continue Shopping') }}
                </a>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="lg:sticky lg:top-8">
            <div class="bg-background-900 rounded-2xl p-6 text-background-50 shadow-xl">
                <h2 class="text-xl font-bold mb-6 pb-4 border-b border-background-800">
                    {{ __('Order Summary') }}
                </h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-background-300">{{ __('Subtotal') }}</span>
                        <span class="font-bold">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-background-300">{{ __('Shipping') }}</span>
                        <span class="font-bold text-secondary-400">{{ __('FREE') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-background-300">{{ __('Tax') }}</span>
                        <span class="font-bold">${{ number_format($total * 0.08, 2) }}</span>
                    </div>
                </div>
                
                <div class="border-t border-background-800 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold">{{ __('Total') }}</span>
                        <div class="text-right">
                            <p class="text-2xl font-black">
                                ${{ number_format($total * 1.08, 2) }}
                            </p>
                            <p class="text-sm text-background-300">
                                {{ __('including tax') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('checkout.index') }}" 
                   class="block w-full bg-primary-500 hover:bg-primary-600 text-background-950 text-center py-3 px-4 rounded-xl font-bold transition-all duration-300 hover:shadow-lg active:scale-95 mb-4">
                    {{ __('Proceed to Checkout') }}
                </a>
                
                <div class="text-center">
                    <p class="text-xs text-background-400 mb-2">
                        {{ __('30-day money-back guarantee') }}
                    </p>
                    <div class="flex items-center justify-center gap-2 text-background-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-xs">{{ __('Secure checkout') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- Empty Cart --}}
    <div class="max-w-md mx-auto text-center py-16 md:py-24">
        <div class="relative mb-8">
            <div class="w-32 h-32 mx-auto bg-primary-100 rounded-full flex items-center justify-center">
                <svg class="w-16 h-16 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
        
        <h2 class="text-2xl md:text-3xl font-bold text-text-950 mb-4">
            {{ __('Your cart is empty') }}
        </h2>
        <p class="text-text-600 mb-8 max-w-sm mx-auto">
            {{ __('Looks like you haven\'t added any items to your cart yet.') }}
        </p>
        
        <div class="space-y-4">
            <a href="{{ route('welcome') }}" 
               class="inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-background-50 px-8 py-3 rounded-xl font-bold transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                {{ __('Start Shopping') }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
            
            <div class="pt-8 border-t border-background-200">
                <p class="text-text-500 text-sm mb-4">{{ __('Popular categories') }}</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('welcome') }}?category=desks" 
                       class="px-4 py-2 bg-background-100 hover:bg-background-200 text-text-700 rounded-lg text-sm font-medium transition-colors duration-300">
                        {{ __('Desks') }}
                    </a>
                    <a href="{{ route('welcome') }}?category=chairs" 
                       class="px-4 py-2 bg-background-100 hover:bg-background-200 text-text-700 rounded-lg text-sm font-medium transition-colors duration-300">
                        {{ __('Chairs') }}
                    </a>
                    <a href="{{ route('welcome') }}?category=accessories" 
                       class="px-4 py-2 bg-background-100 hover:bg-background-200 text-text-700 rounded-lg text-sm font-medium transition-colors duration-300">
                        {{ __('Accessories') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function updateQuantity(button, change) {
    const form = button.closest('form');
    const input = form.querySelector('input[name="quantity"]');
    let newValue = parseInt(input.value) + change;
    
    if (newValue < 1) newValue = 1;
    if (newValue > 99) newValue = 99;
    
    input.value = newValue;
    
    // Submit form if quantity changed
    if (newValue !== parseInt(input.defaultValue)) {
        form.submit();
    }
}
</script>
@endpush
@endsection