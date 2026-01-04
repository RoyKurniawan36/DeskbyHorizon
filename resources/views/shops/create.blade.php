@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-2xl">
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 text-primary-600 rounded-2xl mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <h1 class="text-4xl font-black text-text-950 mb-2">{{ __('create_your_shop') }}</h1>
        <p class="text-text-500 font-medium">{{ __('start_selling_your_curated_workspace_products_today') }}</p>
    </div>

    <form action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-background-50 border border-background-200 rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-background-900/5">
            <div class="mb-6">
                <label for="name" class="block text-sm font-black text-text-950 uppercase tracking-widest mb-2 ml-1">{{ __('shop_name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                       placeholder="e.g. Minimalist Setups Co."
                       class="w-full px-5 py-4 rounded-2xl border border-background-200 bg-background-100/50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all placeholder:text-text-400">
                @error('name')
                    <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-black text-text-950 uppercase tracking-widest mb-2 ml-1">{{ __('description') }}</label>
                <textarea name="description" id="description" rows="4" required 
                          placeholder="{{ __('tell_us_about_your_brand_and_what_you_offer') }}..."
                          class="w-full px-5 py-4 rounded-2xl border border-background-200 bg-background-100/50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all placeholder:text-text-400 leading-relaxed">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-2" x-data="{ fileName: '' }">
                <label class="block text-sm font-black text-text-950 uppercase tracking-widest mb-2 ml-1">{{ __('shop_logo') }} <span class="text-text-400 font-medium lowercase">({{ __('optional') }})</span></label>
                
                <div class="relative group">
                    <input type="file" name="logo" id="logo" accept="image/*" class="hidden" @change="fileName = $event.target.files[0].name">
                    <label for="logo" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-background-200 bg-background-100/30 rounded-[2rem] cursor-pointer group-hover:border-primary-400 group-hover:bg-primary-50/10 transition-all duration-300">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-text-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-sm font-bold text-text-500 group-hover:text-text-700" x-text="fileName ? fileName : '{{ __('click_to_upload_logo') }}'"></p>
                            <p class="text-[10px] text-text-400 uppercase tracking-tighter mt-1">PNG, JPG up to 2MB</p>
                        </div>
                    </label>
                </div>
                @error('logo')
                    <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('welcome') }}" class="flex-1 text-center px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs bg-background-200 text-text-700 hover:bg-background-300 transition-all active:scale-95">
                {{ __('cancel') }}
            </a>
            <button type="submit" class="flex-[2] bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-primary-500/20 transition-all active:scale-95 group">
                <span class="flex items-center justify-center gap-2">
                    {{ __('launch_shop') }}
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
            </button>
        </div>
    </form>
</div>
@endsection