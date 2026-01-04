@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-bold text-text-950">{{ __('my_posts') }}</h1>
            <p class="text-text-500 mt-1">{{ __('manage_and_track_your_shared_setups') }}</p>
        </div>
        <a href="{{ route('posts.create') }}" class="group flex items-center bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl shadow-lg shadow-primary-500/20 transition-all active:scale-95">
            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="font-semibold">{{ __('create_post') }}</span>
        </a>
    </div>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
                <div class="group bg-background-50 border border-background-200 rounded-2xl shadow-sm overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="relative aspect-video overflow-hidden">
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute top-3 left-3 bg-background-950/40 backdrop-blur-md text-white px-3 py-1 rounded-full text-xs font-medium">
                            {{ $post->views }} {{ __('views') }}
                        </div>
                    </div>

                    <div class="p-6 flex-grow flex flex-col">
                        <h3 class="text-xl font-bold text-text-950 mb-2 truncate">{{ $post->title }}</h3>
                        <p class="text-sm text-text-500 mb-4 line-clamp-2 leading-relaxed">
                            {{ $post->description }}
                        </p>
                        
                        <div class="flex items-center space-x-4 mb-6 text-sm font-medium">
                            <span class="text-secondary-600 bg-secondary-50 px-2.5 py-1 rounded-lg">
                                {{ $post->products->count() }} {{ __('products') }}
                            </span>
                            <span class="text-text-400">
                                {{ $post->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <div class="mt-auto flex gap-2">
                            <a href="{{ route('posts.show', $post) }}" class="flex-1 text-center bg-background-100 text-text-800 py-2.5 rounded-xl font-semibold hover:bg-background-200 transition-colors">
                                {{ __('view') }}
                            </a>
                            <a href="{{ route('posts.edit', $post) }}" class="flex-1 text-center bg-primary-100 text-primary-700 py-2.5 rounded-xl font-semibold hover:bg-primary-200 transition-colors">
                                {{ __('edit') }}
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('confirm_delete') }}')" class="contents">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-accent-50 text-accent-600 rounded-xl hover:bg-accent-100 transition-colors" title="{{ __('delete') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    @else
        <div class="text-center py-20 bg-background-50 border-2 border-dashed border-background-200 rounded-3xl">
            <div class="w-20 h-20 bg-background-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-text-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-text-950 mb-2">{{ __('no_posts_yet') }}</h2>
            <p class="text-text-500 mb-8 max-w-sm mx-auto">{{ __('start_sharing_your_setup_with_the_community') }}</p>
            <a href="{{ route('posts.create') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary-500/20 transition-all">
                {{ __('create_first_post') }}
            </a>
        </div>
    @endif
</div>
@endsection