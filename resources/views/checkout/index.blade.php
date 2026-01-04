@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-6xl">
    <div class="mb-10">
        <h1 class="text-4xl font-bold text-text-950 mb-2">{{ __('checkout') }}</h1>
        <p class="text-text-500 font-medium">{{ __('please_provide_your_delivery_and_payment_details') }}</p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-background-50 border border-background-200 rounded-3xl p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-text-950">{{ __('shipping_details') }}</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="shipping_address" class="block text-sm font-bold text-text-700 mb-2">{{ __('delivery_address') }}</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required 
                                      placeholder="{{ __('enter_full_street_address') }}"
                                      class="w-full px-5 py-3 rounded-xl border border-background-300 bg-background-50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="mt-2 text-sm text-accent-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-background-50 border border-background-200 rounded-3xl p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-secondary-100 text-secondary-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-text-950">{{ __('payment_method') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 bg-background-50 border border-background-200 rounded-2xl cursor-pointer hover:border-primary-400 transition-all has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/30">
                            <input type="radio" name="payment_method" value="cash_on_delivery" checked class="w-5 h-5 text-primary-600 border-background-300 focus:ring-primary-500">
                            <div class="ml-4">
                                <span class="block font-bold text-text-900">{{ __('cash_on_delivery') }}</span>
                                <span class="text-xs text-text-500">{{ __('pay_when_received') }}</span>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 bg-background-50 border border-background-200 rounded-2xl cursor-pointer hover:border-primary-400 transition-all has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/30">
                            <input type="radio" name="payment_method" value="bank_transfer" class="w-5 h-5 text-primary-600 border-background-300 focus:ring-primary-500">
                            <div class="ml-4">
                                <span class="block font-bold text-text-900">{{ __('bank_transfer') }}</span>
                                <span class="text-xs text-text-500">{{ __('secure_online_banking') }}</span>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="mt-2 text-sm text-accent-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-background-50 border border-background-200 rounded-3xl p-8 shadow-xl sticky top-24">
                    <h2 class="text-xl font-bold text-text-950 mb-6 pb-4 border-b border-background-100">{{ __('order_summary') }}</h2>
                    
                    <div class="space-y-4 mb-8 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between items-start gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-text-900 truncate">{{ $item->product->name }}</p>
                                    <p class="text-xs text-text-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <span class="text-sm font-bold text-text-800 whitespace-nowrap">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 pt-6 border-t border-background-100">
                        <div class="flex justify-between text-text-600">
                            <span class="text-sm font-medium">{{ __('subtotal') }}</span>
                            <span class="font-bold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-text-600">
                            <span class="text-sm font-medium">{{ __('shipping') }}</span>
                            <span class="text-secondary-600 font-bold uppercase text-xs">{{ __('free') }}</span>
                        </div>
                        <div class="flex justify-between items-end pt-2">
                            <span class="text-text-950 font-black text-lg">{{ __('total') }}</span>
                            <span class="text-3xl font-black text-primary-600 leading-none">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-8 bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-2xl font-bold shadow-xl shadow-primary-500/20 transition-all active:scale-95 flex items-center justify-center gap-2 group">
                        <span>{{ __('place_order') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    
                    <p class="text-center text-[10px] text-text-400 mt-4 uppercase tracking-tighter">
                        {{ __('by_placing_order_you_agree_to_terms') }}
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection