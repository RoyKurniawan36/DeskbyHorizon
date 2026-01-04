@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-6xl">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-text-950 dark:text-white tracking-tight">{{ __('Profile Settings') }}</h1>
        <p class="text-text-500 dark:text-text-400 mt-2 text-lg">{{ __('Manage your account information, security, and preferences.') }}</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">
        <aside class="w-full lg:w-1/4">
            <nav class="flex flex-row lg:flex-col gap-2 p-1 bg-background-100 dark:bg-background-800 rounded-2xl lg:bg-transparent">
                <a href="#personal-info" class="flex-1 lg:flex-none flex items-center gap-3 px-5 py-3 rounded-xl bg-white dark:bg-primary-600 lg:bg-primary-600 text-primary-600 lg:text-white dark:text-white shadow-sm lg:shadow-md font-bold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="hidden sm:inline">{{ __('General') }}</span>
                </a>
                <a href="#security" class="flex-1 lg:flex-none flex items-center gap-3 px-5 py-3 rounded-xl text-text-600 dark:text-text-400 hover:bg-background-100 dark:hover:bg-background-800 font-bold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span class="hidden sm:inline">{{ __('Security') }}</span>
                </a>
                <a href="#danger-zone" class="flex-1 lg:flex-none flex items-center gap-3 px-5 py-3 rounded-xl text-accent-600 dark:text-accent-400 hover:bg-accent-50 dark:hover:bg-accent-900/20 font-bold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span class="hidden sm:inline">{{ __('Danger Zone') }}</span>
                </a>
            </nav>
        </aside>

        <div class="flex-grow space-y-10">
            
            <section id="personal-info" class="bg-white dark:bg-background-900 rounded-[2.5rem] border border-background-200 dark:border-background-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                <div class="p-8 md:p-12">
                    <div class="flex flex-col md:flex-row md:items-center gap-6 mb-10">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-3xl bg-primary-600 text-white flex items-center justify-center text-3xl font-black shadow-2xl shadow-primary-500/30 ring-4 ring-primary-50 dark:ring-background-800">
                                {{ auth()->user()->getInitials() }}
                            </div>
                            <button class="absolute -bottom-2 -right-2 p-2 bg-white dark:bg-background-800 rounded-xl shadow-lg border border-background-100 dark:border-background-700 text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-transform hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-text-900 dark:text-white">{{ __('Personal Information') }}</h2>
                            <p class="text-text-500 dark:text-text-400">{{ __('Update your public profile and contact address.') }}</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-black text-text-900 dark:text-text-300 uppercase tracking-widest ml-1">{{ __('Full Name') }}</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required 
                                       class="w-full px-6 py-4 rounded-2xl border-2 border-background-100 dark:border-background-800 bg-background-50 dark:bg-background-800 text-text-900 dark:text-white focus:border-primary-500 dark:focus:border-primary-500 focus:bg-white dark:focus:bg-background-900 outline-none transition-all font-medium">
                                @error('name') <p class="text-xs text-accent-600 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="text-sm font-black text-text-900 dark:text-text-300 uppercase tracking-widest ml-1">{{ __('Email Address') }}</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-6 py-4 rounded-2xl border-2 border-background-100 dark:border-background-800 bg-background-50 dark:bg-background-800 text-text-900 dark:text-white focus:border-primary-500 dark:focus:border-primary-500 focus:bg-white dark:focus:bg-background-900 outline-none transition-all font-medium">
                                @error('email') <p class="text-xs text-accent-600 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-background-100 dark:border-background-800">
                            @if (session('status') === 'profile-updated')
                                <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="text-secondary-600 dark:text-secondary-400 font-bold flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                    {{ __('Changes Saved') }}
                                </span>
                            @endif
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-10 py-4 rounded-2xl font-black shadow-lg shadow-primary-500/20 transition-all hover:-translate-y-1 active:scale-95">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <section id="security" class="bg-white dark:bg-background-900 rounded-[2.5rem] border border-background-200 dark:border-background-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                <div class="p-8 md:p-12">
                    <div class="mb-10">
                        <h2 class="text-2xl font-bold text-text-900 dark:text-white">{{ __('Security Settings') }}</h2>
                        <p class="text-text-500 dark:text-text-400">{{ __('Keep your account safe by using a strong password.') }}</p>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-xl">
                        @csrf
                        @method('put')

                        <div class="space-y-2">
                            <label for="current_password" class="text-sm font-black text-text-900 dark:text-text-300 uppercase tracking-widest ml-1">{{ __('Current Password') }}</label>
                            <input id="current_password" name="current_password" type="password" 
                                   class="w-full px-6 py-4 rounded-2xl border-2 border-background-100 dark:border-background-800 bg-background-50 dark:bg-background-800 text-text-900 dark:text-white outline-none focus:border-primary-500 focus:bg-white dark:focus:bg-background-900 transition-all font-medium">
                            @error('current_password') <p class="text-xs text-accent-600 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="text-sm font-black text-text-900 dark:text-text-300 uppercase tracking-widest ml-1">{{ __('New Password') }}</label>
                            <input id="password" name="password" type="password" 
                                   class="w-full px-6 py-4 rounded-2xl border-2 border-background-100 dark:border-background-800 bg-background-50 dark:bg-background-800 text-text-900 dark:text-white outline-none focus:border-primary-500 focus:bg-white dark:focus:bg-background-900 transition-all font-medium">
                            @error('password') <p class="text-xs text-accent-600 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="bg-background-950 dark:bg-primary-600 text-white px-8 py-4 rounded-2xl font-black hover:bg-primary-600 dark:hover:bg-primary-700 transition-all shadow-xl active:scale-95">
                                {{ __('Update Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <section id="danger-zone" class="bg-accent-50 dark:bg-accent-950/20 rounded-[2.5rem] border border-accent-100 dark:border-accent-900/50 p-8 md:p-12">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="max-w-md">
                        <h2 class="text-2xl font-bold text-accent-700 dark:text-accent-500 mb-2">{{ __('Delete Account') }}</h2>
                        <p class="text-accent-600 dark:text-accent-400 font-medium">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. This action cannot be undone.') }}
                        </p>
                    </div>

                    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('{{ __('Are you sure you want to permanently delete your account?') }}')">
                        @csrf
                        @method('delete')
                        <button type="submit" class="bg-accent-600 hover:bg-accent-700 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-lg shadow-accent-500/20 hover:-translate-y-1 active:scale-95">
                            {{ __('Delete Permanently') }}
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection