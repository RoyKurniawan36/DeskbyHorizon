@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-3xl">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-text-950 mb-2">{{ __('create_post') }}</h1>
        <p class="text-text-500">{{ __('share_your_setup_and_inspire_the_community') }}</p>
    </div>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" 
          class="bg-background-50 border border-background-200 rounded-3xl shadow-xl overflow-hidden transition-all duration-300">
        @csrf

        <div class="p-8 space-y-6">
            <div>
                <label for="title" class="block text-sm font-bold text-text-700 mb-2">{{ __('title') }}</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                       placeholder="e.g., Minimalist Oak Workspace"
                       class="w-full px-5 py-3 rounded-xl border border-background-300 bg-background-50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all">
                @error('title')
                    <p class="mt-2 text-sm text-accent-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-text-700 mb-2">{{ __('description') }}</label>
                <textarea name="description" id="description" rows="5" required 
                          placeholder="{{ __('describe_your_setup_details') }}"
                          class="w-full px-5 py-3 rounded-xl border border-background-300 bg-background-50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-accent-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-bold text-text-700 mb-2">{{ __('setup_photo') }}</label>
                <div class="relative group">
                    <input type="file" name="image" id="image" accept="image/*" required 
                           class="w-full px-5 py-3 rounded-xl border-2 border-dashed border-background-300 bg-background-100/50 text-text-600 cursor-pointer hover:border-primary-500 transition-colors file:hidden">
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-primary-600 font-semibold">
                        {{ __('upload_image') }}
                    </div>
                </div>
                @error('image')
                    <p class="mt-2 text-sm text-accent-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-3">
                    <label class="block text-sm font-bold text-text-700">{{ __('select_products_in_setup') }}</label>
                    <span class="text-xs font-bold uppercase tracking-widest text-primary-600 bg-primary-50 px-2 py-1 rounded">
                        {{ $products->count() }} {{ __('available') }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-80 overflow-y-auto p-2 border border-background-200 rounded-2xl bg-background-100/30">
                    @foreach($products as $product)
                        <label class="group relative flex items-center p-4 bg-background-50 border border-background-200 rounded-xl cursor-pointer hover:border-primary-400 hover:shadow-md transition-all">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}" 
                                   class="w-5 h-5 rounded border-background-300 text-primary-600 focus:ring-primary-500 transition-all">
                            <div class="ml-4">
                                <p class="font-bold text-text-900 group-hover:text-primary-700 transition-colors">{{ $product->name }}</p>
                                <p class="text-sm text-secondary-600 font-semibold">${{ number_format($product->price, 2) }}</p>
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293l-4 4a1 1 0 01-1.414 0l-2-2a1 1 0 111.414-1.414L9 10.586l3.293-3.293a1 1 0 111.414 1.414z"></path></svg>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('products')
                    <p class="mt-2 text-sm text-accent-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-background-100 px-8 py-6 flex items-center gap-4">
            <a href="{{ route('posts.index') }}" 
               class="flex-1 text-center py-3 px-6 rounded-xl font-bold text-text-600 hover:bg-background-200 transition-all">
                {{ __('cancel') }}
            </a>
            <button type="submit" 
                    class="flex-[2] bg-primary-600 hover:bg-primary-700 text-white py-3 px-6 rounded-xl font-bold shadow-lg shadow-primary-500/30 transform active:scale-95 transition-all">
                {{ __('publish_setup') }}
            </button>
        </div>
    </form>
</div>
@endsection