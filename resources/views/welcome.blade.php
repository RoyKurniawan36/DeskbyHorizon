@extends('layouts.app') 

@section('content')
<div class="relative overflow-hidden bg-background-50 border-b border-background-200 transition-colors duration-500">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        
        <div class="absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full bg-primary-500 opacity-20 blur-[100px]"></div>
        <div class="absolute right-0 bottom-0 -z-10 h-[400px] w-[400px] rounded-full bg-secondary-500 opacity-10 blur-[120px]"></div>
    </div>

    <div class="container mx-auto px-4 pt-24 pb-32 text-center relative z-10">
        <span class="inline-block py-1 px-3 rounded-full bg-primary-100 text-primary-700 text-sm font-semibold mb-6 border border-primary-200">
            {{ __('discover_the_best_workspaces') }}
        </span>

        <h1 class="text-5xl md:text-7xl font-black text-text-950 mb-8 leading-[1.1] tracking-tighter">
            {!! __('welcome_hero_title', [
                'inspiration' => '<span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-accent-500">' . __('inspiration') . '</span>'
            ]) !!}
        </h1>

        <p class="text-lg md:text-xl mb-10 text-text-600 max-w-2xl mx-auto leading-relaxed">
            {{ __('welcome_subtitle') }}
        </p>

        @guest
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('auth.combined') }}"
               class="bg-primary-600 text-background-50 px-8 py-4 rounded-2xl font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:shadow-primary-500/50 transition-all duration-300 transform hover:-translate-y-1 active:scale-95">
                {{ __('get_started') }}
            </a>
            <a href="#featured"
               class="bg-background-50 text-text-700 border border-background-300 px-8 py-4 rounded-2xl font-bold hover:bg-background-100 transition-all duration-300">
                {{ __('browse_setups') }}
            </a>
        </div>
        @endguest
    </div>
</div>

<div id="featured" class="container mx-auto px-4 py-20">
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4 border-b border-background-200 pb-6">
        <div>
            <h2 class="text-3xl font-bold text-text-900 tracking-tight">
                {{ __('featured_setups') }}
            </h2>
            <p class="text-text-600 mt-2">
                {{ __('hand_picked_setups_from_our_community') }}
            </p>
        </div>
        @auth
        <a href="{{ route('posts.create') }}"
           class="inline-flex items-center gap-2 bg-text-950 hover:bg-text-800 text-background-50 px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>{{ __('share_setup') }}</span>
        </a>
        @endauth
    </div>

    @if($posts->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($posts as $post)
        <div class="group bg-background-50 rounded-3xl border border-background-200 shadow-sm hover:shadow-xl hover:shadow-primary-500/10 transition-all duration-500 flex flex-col hover:-translate-y-2 overflow-hidden relative"
             x-data="{ isLoading: false }">
            
            <div class="relative overflow-hidden aspect-[4/3] bg-background-100">
                <img src="{{ Storage::url($post->image) }}"
                     alt="{{ $post->title }}"
                     loading="lazy"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />

                <div class="absolute top-4 right-4 bg-background-50/60 backdrop-blur-md text-background-50 px-3 py-1 rounded-full text-xs font-medium border border-background-700 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ number_format($post->views) }}
                </div>

                <a href="{{ route('posts.show', $post) }}" class="absolute inset-0"></a>
            </div>

            <div class="p-5 flex-grow flex flex-col">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-lg font-bold text-text-900 group-hover:text-primary-600 transition-colors line-clamp-1">
                        <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    </h3>
                </div>

                <p class="text-sm text-text-600 mb-6 line-clamp-2">
                    {{ $post->description }}
                </p>

                <div class="mt-auto pt-4 border-t border-background-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 border border-background-100 shadow-sm flex items-center justify-center text-background-950 text-[10px] font-bold">
                            {{ $post->user->getInitials() }}
                        </div>
                        <span class="text-sm font-semibold text-text-700">{{ $post->user->name }}</span>
                    </div>

                    <div class="flex items-center gap-1 text-xs font-bold text-secondary-700 bg-secondary-100 px-2.5 py-1 rounded-md border border-secondary-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        {{ $post->products->count() }}
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-2">
                    <a href="{{ route('posts.show', $post) }}"
                       class="flex items-center justify-center bg-background-100 text-text-700 py-2.5 rounded-xl text-sm font-semibold hover:bg-background-200 hover:text-text-900 transition-colors border border-background-200">
                        {{ __('view') }}
                    </a>
                    @auth
                    <form action="{{ route('cart.addFromPost', $post) }}" method="POST" class="w-full"
                          @submit="isLoading = true">
                        @csrf
                        <button type="submit"
                                :disabled="isLoading"
                                :class="{ 'opacity-70 cursor-not-allowed': isLoading }"
                                class="w-full flex items-center justify-center bg-primary-600 text-background-50 py-2.5 rounded-xl text-sm font-semibold hover:bg-primary-700 shadow-md shadow-primary-500/20 transition-all active:scale-95 disabled:active:scale-100">
                            <span x-show="!isLoading">{{ __('add_items') }}</span>
                            <span x-show="isLoading" x-cloak>
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m0-20a8 8 0 110 16 8 8 0 010-16z"></path>
                                </svg>
                            </span>
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-16 text-center">{{ $posts->links() }}</div>

    @else
    <div class="text-center py-24 bg-background-50 rounded-[2rem] border border-dashed border-background-300 shadow-sm max-w-2xl mx-auto">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 rotate-3">
            <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-text-900 mb-2">
            {{ __('No setups found') }}
        </h3>
        <p class="text-text-600 mb-8">
            {{ __('Be the first to share your workspace configuration with the community.') }}
        </p>
        @auth
        <a href="{{ route('posts.create') }}"
           class="inline-block bg-primary-600 text-background-50 px-6 py-3 rounded-xl font-semibold shadow-lg shadow-primary-500/20 hover:bg-primary-700 transition-all active:scale-95">
            {{ __('Create Post') }}
        </a>
        @endauth
    </div>
    @endif
</div>
@endsection