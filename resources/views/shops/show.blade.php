@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="relative mb-16">
        <div class="absolute inset-0 h-48 bg-gradient-to-r from-primary-600/10 to-secondary-600/10 rounded-[3rem] -z-10 blur-3xl"></div>
        
        <div class="bg-background-50 border border-background-200 rounded-[3rem] p-8 md:p-12 shadow-xl shadow-background-900/5">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <div class="relative">
                    @if($shop->logo)
                        <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" 
                             class="w-32 h-32 md:w-40 md:h-40 rounded-[2.5rem] object-cover border-4 border-background-50 shadow-2xl">
                    @else
                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-[2.5rem] bg-gradient-to-br from-primary-600 to-secondary-600 flex items-center justify-center text-white text-5xl font-black shadow-2xl">
                            {{ substr($shop->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="absolute -bottom-2 -right-2 bg-primary-500 text-white p-2 rounded-full border-4 border-background-50 shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.64.304 1.24.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812z"></path></svg>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                        <h1 class="text-4xl md:text-5xl font-black text-text-950">{{ $shop->name }}</h1>
                        <span class="inline-flex items-center px-4 py-1 rounded-full bg-secondary-100 text-secondary-700 text-xs font-black uppercase tracking-widest border border-secondary-200">
                            {{ $shop->products->count() }} {{ __('products') }}
                        </span>
                    </div>
                    <p class="text-lg text-text-600 mb-6 max-w-2xl leading-relaxed">{{ $shop->description }}</p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-6 text-sm">
                        <div class="flex items-center gap-2 text-text-400">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="font-bold text-text-950">{{ $shop->user->name }}</span>
                            <span class="opacity-50 lowercase tracking-tighter">{{ __('store_owner') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-text-400">
                            <svg class="w-5 h-5 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-bold text-text-950">{{ $shop->created_at->format('M Y') }}</span>
                            <span class="opacity-50 lowercase tracking-tighter">{{ __('joined') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-12">
        <h2 class="text-3xl font-black text-text-950 mb-8 px-4 flex items-center gap-3">
            {{ __('curated_collection') }}
            <div class="h-1 flex-1 bg-background-200 rounded-full opacity-50"></div>
        </h2>

        @if($shop->products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($shop->products as $product)
                    <div class="group bg-background-50 border border-background-200 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500">
                        <div class="relative h-64 overflow-hidden bg-background-100">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-text-300">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif

                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest backdrop-blur-md {{ $product->stock > 0 ? 'bg-background-950/40 text-white' : 'bg-accent-500 text-white' }}">
                                    {{ $product->stock > 0 ? __('in_stock') : __('out_of_stock') }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-lg font-bold text-text-950 mb-2 group-hover:text-primary-600 transition-colors truncate">
                                {{ $product->name }}
                            </h3>
                            <p class="text-sm text-text-500 line-clamp-2 mb-6 h-10 leading-relaxed">
                                {{ $product->description }}
                            </p>

                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-text-400 uppercase tracking-tighter">{{ __('price') }}</span>
                                    <span class="text-2xl font-black text-primary-600">${{ number_format($product->price, 2) }}</span>
                                </div>

                                @auth
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.addProduct', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-4 rounded-2xl bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-500/20 transition-all active:scale-90 group/btn">
                                                <svg class="w-6 h-6 group-hover/btn:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-background-50 border border-background-200 rounded-[3rem]">
                <p class="text-text-500 text-lg">{{ __('this_shop_has_no_products_yet') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection