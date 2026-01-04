@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    {{-- Header Section --}}
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h1 class="text-4xl font-black text-text-950 tracking-tight">{{ __('all_shops') }}</h1>
            <p class="text-text-500 font-medium max-w-xl leading-relaxed">
                {{ __('discover_independent_creators_and_brands_shaping_the_future_of_workspaces') }}
            </p>
        </div>
        
        <form action="{{ route('shops.index') }}" method="GET" class="relative group">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-text-400 group-focus-within:text-primary-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" 
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="{{ __('search_shops') }}..." 
                   class="pl-10 pr-4 py-3 rounded-xl border border-background-200 bg-background-50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none w-full md:w-72 shadow-sm">
        </form>
    </div>

    @if($shops->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($shops as $shop)
                <article class="group relative bg-background-50 border border-background-200 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    {{-- Image/Logo Header --}}
                    <div class="h-44 relative overflow-hidden">
                        @if($shop->logo)
                            <img src="{{ Storage::url($shop->logo) }}" 
                                 alt="{{ $shop->name }}" 
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center relative">
                                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
                                <span class="text-6xl font-black text-white/30 tracking-tighter">{{ $shop->name[0] }}</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-4 right-4 bg-background-950/40 backdrop-blur-md border border-white/20 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest z-10">
                            {{ $shop->products_count }} {{ trans_choice('product|products', $shop->products_count) }}
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6">
                        <div class="mb-3">
                            <h3 class="text-xl font-bold text-text-950 group-hover:text-primary-600 transition-colors leading-tight">
                                <a href="{{ route('shops.show', $shop) }}">{{ $shop->name }}</a>
                            </h3>
                        </div>

                        <p class="text-sm text-text-500 mb-6 line-clamp-2 min-h-[2.5rem] leading-relaxed">
                            {{ $shop->description }}
                        </p>

                        <div class="flex items-center justify-between pt-5 border-t border-background-100">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-secondary-100 flex items-center justify-center text-[10px] font-bold text-secondary-700 border border-secondary-200">
                                    {{ $shop->user->name[0] }}
                                </div>
                                <span class="text-xs font-bold text-text-400 uppercase tracking-tight">{{ $shop->user->name }}</span>
                            </div>

                            <a href="{{ route('shops.show', $shop) }}" 
                               class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-500/20 transition-all active:scale-95 group-hover:rotate-6"
                               aria-label="{{ __('view_shop') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-16">
            {{ $shops->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-20 bg-background-50 border border-background-200 rounded-[3rem] shadow-inner max-w-4xl mx-auto">
            <div class="w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-8 transform rotate-12">
                <svg class="w-12 h-12 text-text-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-text-950 mb-4">{{ __('no_shops_found') }}</h2>
            
            <div class="flex flex-col items-center gap-6">
                @if(auth()->check() && auth()->user()->isShopOwner())
                    <p class="text-text-500 max-w-sm mx-auto leading-relaxed">
                        {{ __('you_need_to_open_a_shop') }}
                    </p>
                    <a href="{{ route('shops.create') }}" class="inline-flex items-center gap-3 bg-primary-600 hover:bg-primary-700 text-white px-8 py-3.5 rounded-2xl font-bold transition-all duration-300 shadow-xl shadow-primary-500/25 hover:-translate-y-1 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        {{ __('create_shop') }}
                    </a>
                @else
                    <p class="text-text-500 max-w-sm mx-auto">
                        {{ __('it_seems_there_are_no_shops_registered_at_the_moment') }}
                    </p>
                    @if(request('search'))
                        <a href="{{ route('shops.index') }}" class="text-primary-600 font-bold hover:underline">
                            {{ __('clear_search_filters') }}
                        </a>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
@endsection