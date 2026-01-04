@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-7xl">
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-text-500 hover:text-primary-600 transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            {{ __('back_to_browse') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-8">
            <div class="relative group rounded-3xl overflow-hidden shadow-2xl bg-background-50 border border-background-200">
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover max-h-[600px]">
                
                <div class="absolute bottom-6 right-6 bg-background-950/40 backdrop-blur-xl border border-white/20 text-white px-5 py-2 rounded-2xl flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span class="font-bold tracking-wide">{{ number_format($post->views) }}</span>
                </div>
            </div>

            <div class="bg-background-50 border border-background-200 rounded-3xl p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-text-950 mb-4">{{ __('about_this_setup') }}</h2>
                <div class="prose prose-lg dark:prose-invert max-w-none text-text-700 leading-relaxed">
                    {{ $post->description }}
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-background-50 border border-background-200 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-background-100">
                    <div class="w-14 h-14 rounded-2xl bg-primary-100 border border-primary-200 flex items-center justify-center text-primary-700 text-lg font-bold shadow-sm">
                        {{ $post->user->getInitials() }}
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-text-400 mb-1">{{ __('posted_by') }}</p>
                        <h3 class="text-xl font-bold text-text-950">{{ $post->user->name }}</h3>
                        <p class="text-sm text-text-500">{{ $post->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <h1 class="text-3xl font-extrabold text-text-950 mb-6 leading-tight">{{ $post->title }}</h1>

                <div class="space-y-3">
                    @auth
                        @if(auth()->id() === $post->user_id)
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('posts.edit', $post) }}" class="flex justify-center items-center py-3 rounded-xl font-bold bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors">
                                    {{ __('edit') }}
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('confirm_delete') }}')" class="contents">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-3 rounded-xl font-bold bg-accent-50 text-accent-600 hover:bg-accent-100 transition-colors">
                                        {{ __('delete') }}
                                    </button>
                                </form>
                            </div>
                        @endif

                        <form action="{{ route('cart.addFromPost', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex justify-center items-center gap-3 bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-2xl font-bold shadow-xl shadow-primary-500/20 transition-all active:scale-95">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                {{ __('add_all_to_cart') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('auth.combined') }}" class="w-full block text-center bg-background-100 text-text-800 py-4 rounded-2xl font-bold hover:bg-background-200 transition-colors">
                            {{ __('login_to_purchase') }}
                        </a>
                    @endauth
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-4 px-2">
                    <h2 class="text-xl font-bold text-text-950">{{ __('gear_list') }}</h2>
                    <span class="bg-secondary-100 text-secondary-700 px-3 py-1 rounded-full text-xs font-bold">
                        {{ $post->products->count() }} {{ __('items') }}
                    </span>
                </div>
                
                <div class="space-y-3">
                    @foreach($post->products as $product)
                        <div class="group bg-background-50 border border-background-200 p-4 rounded-2xl flex items-center gap-4 transition-all hover:shadow-md hover:border-primary-200">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-xl object-cover bg-background-100 border border-background-200">
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-text-900 truncate">{{ $product->name }}</h4>
                                <p class="text-lg font-extrabold text-primary-600">${{ number_format($product->price, 2) }}</p>
                            </div>
                            @auth
                                <form action="{{ route('cart.addProduct', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2.5 rounded-xl bg-background-100 text-text-600 hover:bg-primary-600 hover:text-white transition-all shadow-sm" title="{{ __('add_to_cart') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </form>
                            @endauth
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection