<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" 
      x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('title') }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-background-50 to-background-200 text-text-900 transition-colors duration-300">
    
    <div class="fixed top-4 right-4">
        <button @click="darkMode = !darkMode" class="p-2 rounded-full bg-background-200 text-text-700 hover:bg-background-300 transition-all">
            <span x-show="!darkMode">🌙</span>
            <span x-show="darkMode">☀️</span>
        </button>
    </div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <div class="mb-8 text-center">
            <a href="/">
                <h1 class="text-4xl font-bold text-text-950">Desk by Horizon</h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md bg-background-50 border border-background-200 shadow-2xl rounded-2xl overflow-hidden transition-all duration-300" 
             x-data="{ activeTab: '{{ request('mode') === 'register' ? 'register' : 'login' }}' }">
            
            <div class="flex border-b border-background-200 bg-background-100/50">
                <button @click="activeTab = 'login'" 
                    :class="activeTab === 'login' ? 'border-b-2 border-primary-500 text-primary-600 bg-background-50' : 'text-text-500 hover:text-text-700'" 
                    class="flex-1 py-4 px-6 text-center font-semibold transition-all duration-200">
                    {{ __('login') }}
                </button>
                <button @click="activeTab = 'register'" 
                    :class="activeTab === 'register' ? 'border-b-2 border-primary-500 text-primary-600 bg-background-50' : 'text-text-500 hover:text-text-700'" 
                    class="flex-1 py-4 px-6 text-center font-semibold transition-all duration-200">
                    {{ __('register') }}
                </button>
            </div>

            <div class="p-8">
                <div x-show="activeTab === 'login'" x-cloak x-transition>
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-text-700 mb-1">{{ __('email') }}</label>
                            <input type="email" name="email" required autofocus 
                                   class="w-full px-4 py-3 rounded-lg border border-background-300 bg-background-50 text-text-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-700 mb-1">{{ __('password') }}</label>
                            <input type="password" name="password" required 
                                   class="w-full px-4 py-3 rounded-lg border border-background-300 bg-background-50 text-text-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all">
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center text-sm text-text-600">
                                <input type="checkbox" name="remember" class="rounded border-background-300 text-primary-600 focus:ring-primary-500">
                                <span class="ml-2">{{ __('remember_me') }}</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 underline">{{ __('forgot_password?') }}</a>
                            @endif
                        </div>

                        <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-primary-500/30 transition-all transform active:scale-95">
                            {{ __('sign_in') }}
                        </button>
                    </form>
                </div>

                <div x-show="activeTab === 'register'" x-cloak x-transition>
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-text-700 mb-1">{{ __('full_name') }}</label>
                            <input type="text" name="name" required 
                                   class="w-full px-4 py-3 rounded-lg border border-background-300 bg-background-50 text-text-900 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-700 mb-1">{{ __('email_address') }}</label>
                            <input type="email" name="email" required 
                                   class="w-full px-4 py-3 rounded-lg border border-background-300 bg-background-50 text-text-900 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-text-700 mb-1">{{ __('password') }}</label>
                                <input type="password" name="password" required 
                                       class="w-full px-4 py-3 rounded-lg border border-background-300 bg-background-50 text-text-900 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-text-700 mb-1">{{ __('confirm') }}</label>
                                <input type="password" name="password_confirmation" required 
                                       class="w-full px-4 py-3 rounded-lg border border-background-300 bg-background-50 text-text-900 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-primary-500/30 transition-all transform active:scale-95">
                            {{ __('create_account') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>