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
    <style>
        [x-cloak] { display: none !important; }
        .theme-transition {
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                border-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body
    x-data="appState()"
    @scroll.window="handleScroll()"
    class="theme-transition font-sans antialiased bg-background-50 text-text-900 flex flex-col min-h-screen"
>
    <header
        class="fixed top-0 w-full z-50 transition-all duration-300 border-b"
        :class="isScrolled 
        ? 'bg-background-50/90 backdrop-blur-md py-3 border-background-200 shadow-sm' 
        : 'bg-transparent py-6 border-transparent'"
    >
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center">
                <a
                    href="{{ route('welcome') }}"
                    class="flex items-center space-x-3 group"
                >
                    <div
                        class="w-11 h-11 bg-primary-500 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:rotate-6 transition-transform duration-300"
                    >
                        <span class="text-white font-black text-2xl">D</span>
                    </div>
                    <span class="text-2xl font-black tracking-tight text-text-950">
                        Desk<span class="text-primary-600">Horizon</span>
                    </span>
                </a>

                <div class="hidden lg:flex items-center space-x-10">
                    <nav class="flex items-center space-x-2">
                        <x-nav-link
                            href="{{ route('welcome') }}"
                            :active="request()->routeIs('welcome')"
                            class="px-4 py-2 rounded-xl hover:bg-background-100 transition-all font-bold text-sm uppercase tracking-wide"
                        >
                            {{ __('home') }}
                        </x-nav-link>

                        @auth
                        <x-nav-link
                            href="{{ route('posts.index') }}"
                            :active="request()->routeIs('posts.*')"
                            class="px-4 py-2 rounded-xl hover:bg-background-100 transition-all font-bold text-sm uppercase tracking-wide"
                            >{{ __('my_posts') }}</x-nav-link
                        >
                        <x-nav-link
                            href="{{ route('shops.index') }}"
                            :active="request()->routeIs('shops.*')"
                            class="px-4 py-2 rounded-xl hover:bg-background-100 transition-all font-bold text-sm uppercase tracking-wide"
                            >{{ __('shops') }}</x-nav-link
                        >
                        <x-nav-link
                            href="{{ route('orders.index') }}"
                            :active="request()->routeIs('orders.*')"
                            class="px-4 py-2 rounded-xl hover:bg-background-100 transition-all font-bold text-sm uppercase tracking-wide"
                            >{{ __('orders') }}</x-nav-link
                        >
                        @endauth
                    </nav>

                    <div class="flex items-center space-x-4 border-l border-background-200 pl-8">
                        <button
                            @click="darkMode = !darkMode"
                            class="p-2.5 rounded-xl bg-background-100 text-text-600 hover:text-primary-600 hover:ring-2 hover:ring-primary-500/20 transition-all focus:outline-none"
                        >
                            <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg x-show="darkMode" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 12.728L12 12z"></path>
                            </svg>
                        </button>

                        @guest
                        <a
                            href="{{ route('auth.combined') }}"
                            class="bg-text-950 text-text-300 px-7 py-3 rounded-xl font-bold transition-all hover:translate-y-[-2px] hover:shadow-xl hover:shadow-primary-500/20 active:translate-y-0"
                        >
                            {{ __('login_register') }}
                        </a>
                        @else
                        <a
                            href="{{ route('cart.index') }}"
                            class="relative p-2.5 rounded-xl bg-background-100 text-text-600 hover:text-primary-600 transition-all"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-accent-500 border-2 border-background-50"></span>
                            </span>
                        </a>

                        <div class="relative" x-data="{ profileOpen: false }">
                            <button
                                @click="profileOpen = !profileOpen"
                                @click.away="profileOpen = false"
                                class="flex items-center space-x-3 p-1.5 pr-4 rounded-2xl bg-background-100 hover:ring-2 hover:ring-primary-500/20 transition-all"
                            >
                                <div class="w-9 h-9 rounded-xl bg-primary-600 flex items-center justify-center text-white shadow-inner overflow-hidden">
                                    @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover" />
                                    @else
                                    <span class="font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="flex flex-col items-start">
                                    <span class="text-xs font-bold text-text-400 uppercase tracking-tighter leading-none mb-0.5">Member</span>
                                    <span class="text-sm font-black text-text-900">{{ auth()->user()->name }}</span>
                                </div>
                                <svg class="w-4 h-4 text-text-400 transition-transform duration-300" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div
                                x-show="profileOpen"
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                class="absolute right-0 mt-3 w-64 bg-background-50 border border-background-200 rounded-3xl shadow-2xl py-4 z-50 overflow-hidden"
                            >
                                <div class="px-6 py-4 mb-2 bg-background-100">
                                    <p class="text-xs font-bold text-text-400 uppercase tracking-widest">{{ __('Account Settings') }}</p>
                                    <p class="text-sm font-bold truncate mt-1">{{ auth()->user()->email }}</p>
                                </div>

                                <div class="px-2 space-y-1">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm font-bold text-text-700 rounded-2xl hover:bg-primary-500 hover:text-white transition-all group">
                                        <svg class="w-5 h-5 mr-3 opacity-50 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ __('Profile') }}
                                    </a>

                                    @if(auth()->user()->isShopOwner())
                                    <a href="{{ route('shop-owner.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-bold text-text-700 rounded-2xl hover:bg-primary-500 hover:text-white transition-all group">
                                        <svg class="w-5 h-5 mr-3 opacity-50 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        {{ __('Shop Dashboard') }}
                                    </a>
                                    @endif
                                </div>

                                <div class="mt-4 px-6 pt-4 border-t border-background-200">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center text-sm font-black text-accent-600 hover:text-accent-700 transition-colors">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            {{ __('Sign Out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endguest
                    </div>
                </div>

                <button
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="lg:hidden p-3 rounded-2xl bg-background-100 text-text-600 transition-all active:scale-90"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!isMobileMenuOpen" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        <path x-show="isMobileMenuOpen" x-cloak d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div
            x-show="isMobileMenuOpen"
            x-cloak
            @click.away="isMobileMenuOpen = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-8"
            class="lg:hidden fixed inset-x-4 top-[88px] bg-background-50 border border-background-200 rounded-3xl p-6 shadow-2xl z-40 space-y-6"
        >
            <nav class="flex flex-col space-y-2">
                <a href="{{ route('welcome') }}" class="px-5 py-4 rounded-2xl bg-primary-600 text-white font-black">{{ __('Home') }}</a>
                @auth
                <a href="{{ route('posts.index') }}" class="px-5 py-4 rounded-2xl font-bold text-text-900 hover:bg-background-100 transition-colors">{{ __('My Posts') }}</a>
                <a href="{{ route('cart.index') }}" class="px-5 py-4 rounded-2xl font-bold text-text-900 hover:bg-background-100 transition-colors">{{ __('Cart') }}</a>
                <a href="{{ route('shops.index') }}" class="px-5 py-4 rounded-2xl font-bold text-text-900 hover:bg-background-100 transition-colors">{{ __('Shops') }}</a>
                @endauth
            </nav>
            <div class="pt-4 border-t border-background-200 flex flex-col space-y-4">
                @guest
                <a href="{{ route('auth.combined') }}" class="w-full text-center bg-text-950 text-white py-4 rounded-2xl font-black shadow-xl">{{ __('Get Started') }}</a>
                @else
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full py-4 text-accent-600 font-black">{{ __('Logout') }}</button>
                </form>
                @endguest
            </div>
        </div>
    </header>

    <main class="flex-grow pt-32 pb-12">
        <div class="container mx-auto px-4 lg:px-8">
            @if(session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8"
                class="fixed bottom-10 right-1/2 translate-x-1/2 lg:translate-x-0 lg:right-10 z-[100] bg-secondary-600 text-white px-8 py-5 rounded-3xl shadow-2xl flex items-center space-x-4 cursor-pointer min-w-[320px]"
                @click="show = false"
            >
                <div class="bg-white/20 p-2 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest opacity-80 leading-none mb-1">Success</p>
                    <p class="font-black">{{ session('success') }}</p>
                </div>
            </div>
            @endif 
            @yield('content')
        </div>
    </main>

    <footer class="bg-background-100 border-t border-background-200 pt-24 pb-12 transition-colors duration-500">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
                <div class="col-span-1 md:col-span-2 space-y-8">
                    <a href="#" class="text-3xl font-black text-text-800 tracking-tighter">
                        Desk<span class="text-primary-950">Horizon</span>
                    </a>
                    <p class="text-text-600 max-w-sm leading-relaxed text-lg font-medium">
                        The world's premier community for workspace inspiration and professional productivity tools.
                    </p>
                    <div class="flex space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-background-200 hover:bg-primary-500 hover:text-white transition-all cursor-pointer flex items-center justify-center">
                            <span class="font-bold text-xs">IG</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-background-200 hover:bg-primary-500 hover:text-white transition-all cursor-pointer flex items-center justify-center">
                            <span class="font-bold text-xs">TW</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="font-black uppercase tracking-[0.2em] text-[11px] text-text-400 mb-8">{{ __('Platform') }}</h4>
                    <ul class="space-y-5 font-bold text-text-700">
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Browse Setups</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Marketplace</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Creators</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-black uppercase tracking-[0.2em] text-[11px] text-text-400 mb-8">{{ __('Company') }}</h4>
                    <ul class="space-y-5 font-bold text-text-700">
                        <li><a href="#" class="hover:text-primary-600 transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center pt-12 border-t border-background-200 gap-8">
                <p class="text-text-400 text-sm font-bold tracking-tight">
                    © {{ date('Y') }} DeskHorizon. Crafted for Creators.
                </p>
                <div class="flex items-center space-x-6">
                    <div class="flex bg-background-200 rounded-2xl p-1.5 shadow-inner">
                        <a href="{{ route('language.switch', 'en') }}" class="px-5 py-2 text-xs font-black rounded-xl transition-all {{ app()->getLocale() == 'en' ? 'bg-background-50 text-primary-500 shadow-sm' : 'text-text-500 hover:text-text-700' }}">EN</a>
                        <a href="{{ route('language.switch', 'id') }}" class="px-5 py-2 text-xs font-black rounded-xl transition-all {{ app()->getLocale() == 'id' ? 'bg-background-50 text-primary-500 shadow-sm' : 'text-text-500 hover:text-text-700' }}">ID</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script defer>
        function appState() {
            return {
                isScrolled: false,
                isMobileMenuOpen: false,
                handleScroll() {
                    this.isScrolled = window.pageYOffset > 20;
                },
            };
        }
    </script>
</body>
</html>