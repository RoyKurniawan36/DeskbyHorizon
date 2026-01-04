@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-3xl">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-4xl font-black text-text-950 mb-2">{{ __('edit_shop') }}</h1>
            <p class="text-text-500 font-medium">{{ __('refine_your_brand_identity_and_details') }}</p>
        </div>
        <div class="hidden md:block">
            <div class="w-12 h-12 bg-secondary-100 text-secondary-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
        </div>
    </div>

    <form action="{{ route('shops.update', $shop) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PATCH')

        <div class="bg-background-50 border border-background-200 rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-background-900/5">
            <div class="mb-8">
                <label for="name" class="block text-sm font-black text-text-950 uppercase tracking-widest mb-3 ml-1">{{ __('shop_name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $shop->name) }}" required 
                       class="w-full px-6 py-4 rounded-2xl border border-background-200 bg-background-100/50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all font-bold">
                @error('name')
                    <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="description" class="block text-sm font-black text-text-950 uppercase tracking-widest mb-3 ml-1">{{ __('description') }}</label>
                <textarea name="description" id="description" rows="5" required 
                          class="w-full px-6 py-4 rounded-2xl border border-background-200 bg-background-100/50 text-text-950 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all leading-relaxed">{{ old('description', $shop->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                <div>
                    <label class="block text-sm font-black text-text-950 uppercase tracking-widest mb-3 ml-1">{{ __('current_identity') }}</label>
                    <div class="relative group aspect-square rounded-[2rem] overflow-hidden border-4 border-background-100 bg-background-200 shadow-inner">
                        @if($shop->logo)
                            <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-500 to-secondary-600 text-white text-6xl font-black">
                                {{ substr($shop->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div x-data="{ fileName: '' }">
                    <label class="block text-sm font-black text-text-950 uppercase tracking-widest mb-3 ml-1">{{ __('update_logo') }}</label>
                    <div class="relative h-[calc(100%-2rem)]">
                        <input type="file" name="logo" id="logo" accept="image/*" class="hidden" @change="fileName = $event.target.files[0].name">
                        <label for="logo" class="flex flex-col items-center justify-center w-full aspect-square border-2 border-dashed border-background-200 bg-background-100/30 rounded-[2rem] cursor-pointer hover:border-primary-400 hover:bg-primary-50/10 transition-all group">
                            <div class="text-center px-4">
                                <svg class="w-10 h-10 mx-auto mb-3 text-text-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-xs font-bold text-text-500 break-all" x-text="fileName ? fileName : '{{ __('choose_new_file') }}'"></p>
                            </div>
                        </label>
                    </div>
                    @error('logo')
                        <p class="mt-2 text-sm text-accent-600 font-bold ml-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4">
            <a href="{{ route('shop-owner.dashboard') }}" class="w-full sm:flex-1 text-center px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs bg-background-200 text-text-700 hover:bg-background-300 transition-all active:scale-95">
                {{ __('cancel_changes') }}
            </a>
            <button type="submit" class="w-full sm:flex-[2] bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-primary-500/20 transition-all active:scale-95 group">
                <span class="flex items-center justify-center gap-2">
                    {{ __('save_shop_settings') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </span>
            </button>
        </div>
    </form>
</div>
@endsection