@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Shop Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($shop->logo)
                    <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-20 h-20 rounded-lg object-cover mr-4">
                @else
                    <div class="w-20 h-20 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold mr-4">
                        {{ substr($shop->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $shop->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('shop_owner_dashboard') }}</p>
                </div>
            </div>
            <a href="{{ route('shops.edit', $shop) }}" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-6 py-2 rounded-lg transition-all duration-200">
                {{ __('edit_shop') }}
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('total_products') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $products->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('pending_approvals') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingApprovals->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('shop_status') }}</p>
                    <p class="text-lg font-bold {{ $shop->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $shop->is_active ? __('active') : __('inactive') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('shop-owner.products') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('manage_products') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('add_edit_products') }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('shop-owner.orders') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('manage_orders') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('approve_orders') }}</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Products -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('recent_products') }}</h2>
            <a href="{{ route('shop-owner.products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                {{ __('add_product') }}
            </a>
        </div>

        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('product') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('price') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('stock') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('category') }}</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($products->take(5) as $product)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover mr-3">
                                        @endif
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">${{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $product->stock }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $product->category ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('shop-owner.products.edit', $product) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                        {{ __('edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <a href="{{ route('shop-owner.products') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                    {{ __('view_all_products') }} →
                </a>
            </div>
        @else
            <p class="text-center text-gray-600 dark:text-gray-400 py-8">{{ __('no_products_yet') }}</p>
        @endif
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('pending_order_approvals') }}</h2>

        @if($pendingApprovals->count() > 0)
            <div class="space-y-4">
                @foreach($pendingApprovals as $approval)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('order') }} #{{ $approval->order->order_number }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('customer') }}: {{ $approval->order->user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $approval->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($approval->order->total, 2) }}</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('shop-owner.orders') }}" class="flex-1 text-center bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                                {{ __('view_details') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('shop-owner.orders') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                    {{ __('view_all_orders') }} →
                </a>
            </div>
        @else
            <p class="text-center text-gray-600 dark:text-gray-400 py-8">{{ __('no_pending_approvals') }}</p>
        @endif
    </div>
</div>
@endsection