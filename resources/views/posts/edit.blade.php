@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-3xl">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-text-950 mb-2">{{ __('edit_post') }}</h1>
            <p class="text-text-500">{{ __('update_your_setup_details_and_products') }}</p>
        </div>
        <a href="{{ route('posts.index') }}" class="text-primary-600 font-semibold hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            {{ __('back_to_list') }}
        </a>
    </div>

    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" 
          class="bg-background-50 border border-background-200 rounded-3xl shadow-xl overflow-hidden transition-all duration-300">
        @csrf
        @method('PATCH')

        <div class="p-8 space-y-8">
            <div>
                <label for="title" class="block text-sm font-bold text-text-700 mb-2">{{ __('title') }}</label>
                <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required 
                       class="w-full px-5 py-3 rounded-xl border border-background-300 bg-background-50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all">
                @error('title')
                    <p class="mt-2 text-sm text-accent-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-text-700 mb-2">{{ __('description') }}</label>
                <textarea name="description" id="description" rows="5" required 
                          class="w-full px-5 py-3 rounded-xl border border-background-300 bg-background-50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all">{{ old('description', $post->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-accent-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-text-700 mb-2">{{ __('current_setup_photo') }}</label>
                    <div class="relative rounded-2xl overflow-hidden border border-background-200 shadow-inner">
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-40 object-cover">
                        <div class="absolute inset-0 bg-background-950/20"></div>
                    </div>
                </div>
                <div>
                    <label for="image" class="block text-sm font-bold text-text-700 mb-2">{{ __('replace_photo') }} ({{ __('optional') }})</label>
                    <input type="file" name="image" id="image" accept="image/*" 
                           class="w-full px-5 py-3 rounded-xl border-2 border-dashed border-background-300 bg-background-100/50 text-text-600 cursor-pointer hover:border-primary-500 transition-colors file:hidden">
                    <p class="text-xs text-text-400 mt-2 italic">{{ __('leave_blank_to_keep_current') }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-text-700 mb-3">{{ __('tagged_products') }}</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-80 overflow-y-auto p-2 border border-background-200 rounded-2xl bg-background-100/30">
                    @foreach($products as $product)
                        <label class="group relative flex items-center p-4 bg-background-50 border rounded-xl cursor-pointer transition-all {{ in_array($product->id, $post->products->pluck('id')->toArray()) ? 'border-primary-500 ring-2 ring-primary-500/10' : 'border-background-200 hover:border-primary-300' }}">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}" 
                                   {{ in_array($product->id, $post->products->pluck('id')->toArray()) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded border-background-300 text-primary-600 focus:ring-primary-500 transition-all">
                            <div class="ml-4">
                                <p class="font-bold text-text-900 group-hover:text-primary-700 transition-colors">{{ $product->name }}</p>
                                <p class="text-sm text-secondary-600 font-semibold">${{ number_format($product->price, 2) }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-background-100 px-8 py-6 flex items-center justify-between gap-4">
            <button type="button" onclick="history.back()"
               class="flex-1 text-center py-3 px-6 rounded-xl font-bold text-text-600 hover:bg-background-200 transition-all">
                {{ __('cancel') }}
            </button>
            <button type="submit" 
                    class="flex-[2] bg-primary-600 hover:bg-primary-700 text-white py-3 px-6 rounded-xl font-bold shadow-lg shadow-primary-500/30 transform active:scale-95 transition-all">
                {{ __('update_setup') }}
            </button>
        </div>
    </form>
</div>
@endsection