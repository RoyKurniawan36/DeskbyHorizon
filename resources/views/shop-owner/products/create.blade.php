@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-5xl">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-4xl font-black text-text-950 mb-2">{{ __('add_new_product') }}</h1>
            <p class="text-text-500 font-medium">{{ __('create_product_with_details_pricing_and_inventory') }}</p>
        </div>
        <a href="{{ route('shop-owner.products') }}" class="text-sm font-bold text-text-400 hover:text-primary-600 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            {{ __('back_to_inventory') }}
        </a>
    </div>

    <form action="{{ route('shop-owner.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-background-50 border border-background-200 rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-background-900/5">
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-black text-text-950 uppercase tracking-widest mb-2 ml-1">{{ __('product_name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                               placeholder="e.g. Wireless Mechanical Keyboard"
                               class="w-full px-5 py-4 rounded-2xl border border-background-200 bg-background-100/50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all font-bold placeholder:text-text-400">
                        @error('name')
                            <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-black text-text-950 uppercase tracking-widest mb-2 ml-1">{{ __('description') }}</label>
                        <textarea name="description" id="description" rows="6" 
                                  placeholder="{{ __('describe_your_product_features_and_benefits') }}..."
                                  class="w-full px-5 py-4 rounded-2xl border border-background-200 bg-background-100/50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all leading-relaxed placeholder:text-text-400">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label for="category" class="block text-sm font-black text-text-950 uppercase tracking-widest mb-2 ml-1">{{ __('category') }} <span class="text-text-400 font-medium lowercase">({{ __('optional') }})</span></label>
                        <input type="text" name="category" id="category" value="{{ old('category') }}" 
                               placeholder="e.g. Keyboards, Monitors, Desk Accessories"
                               class="w-full px-5 py-4 rounded-2xl border border-background-200 bg-background-100/50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all font-bold placeholder:text-text-400">
                        @error('category')
                            <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-background-50 border border-background-200 rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-background-900/5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="price" class="block text-sm font-black text-text-950 uppercase tracking-widest mb-2 ml-1">{{ __('price') }} ($)</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-text-400 font-bold">$</span>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required 
                                       placeholder="0.00"
                                       class="w-full pl-10 pr-5 py-4 rounded-2xl border border-background-200 bg-background-100/50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all font-black text-xl placeholder:text-text-400">
                            </div>
                            @error('price')
                                <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-black text-text-950 uppercase tracking-widest mb-2 ml-1">{{ __('inventory_stock') }}</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" required 
                                   placeholder="0"
                                   class="w-full px-5 py-4 rounded-2xl border border-background-200 bg-background-100/50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all font-black text-xl placeholder:text-text-400">
                            @error('stock')
                                <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-background-50 border border-background-200 rounded-[2.5rem] p-6 shadow-xl shadow-background-900/5">
                    <label class="block text-sm font-black text-text-950 uppercase tracking-widest mb-4 ml-1">{{ __('product_media') }}</label>
                    
                    <div class="relative group rounded-[2rem] overflow-hidden bg-background-200 mb-6 aspect-square border-4 border-background-100 shadow-inner">
                        <div class="w-full h-full flex items-center justify-center text-text-400" id="image-preview">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>

                    <div x-data="{ fileName: '' }">
                        <input type="file" name="image" id="image" accept="image/*" class="hidden" 
                               @change="fileName = $event.target.files[0]?.name || ''; 
                                        if($event.target.files[0]) {
                                            const reader = new FileReader();
                                            reader.onload = (e) => {
                                                document.getElementById('image-preview').innerHTML = '<img src=\'' + e.target.result + '\' class=\'w-full h-full object-cover\'>';
                                            };
                                            reader.readAsDataURL($event.target.files[0]);
                                        }">
                        <label for="image" class="flex flex-col items-center justify-center w-full py-6 border-2 border-dashed border-background-200 rounded-2xl cursor-pointer hover:border-primary-500 hover:bg-primary-50/10 transition-all group">
                            <svg class="w-6 h-6 mb-2 text-text-400 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest text-text-500" x-text="fileName ? fileName : '{{ __('upload_image') }}'"></span>
                            <span class="text-[9px] text-text-400 uppercase tracking-tighter mt-1">PNG, JPG up to 2MB</span>
                        </label>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-5 rounded-[2rem] font-black uppercase tracking-widest text-sm shadow-xl shadow-primary-500/20 transition-all active:scale-95 flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    {{ __('create_product') }}
                </button>

                <a href="{{ route('shop-owner.products') }}" class="w-full bg-background-200 hover:bg-background-300 text-text-700 py-5 rounded-[2rem] font-black uppercase tracking-widest text-sm transition-all active:scale-95 flex items-center justify-center gap-3">
                    {{ __('cancel') }}
                </a>
            </div>
        </div>
    </form>
</div>
@endsection