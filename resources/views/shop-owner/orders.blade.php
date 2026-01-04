@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-900">Shop Orders</h1>
        <p class="text-text-600 mt-2">Manage and approve orders for your shop</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-accent-50 border border-accent-200 rounded-lg">
            <ul class="text-accent-700 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 bg-primary-50 border border-primary-200 rounded-lg text-primary-700">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-12 bg-background-50 rounded-lg border border-background-200">
            <svg class="mx-auto h-12 w-12 text-text-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"></path>
            </svg>
            <p class="mt-4 text-text-600">No orders yet</p>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($orders as $order)
                <div class="bg-background-50 rounded-lg shadow hover:shadow-md transition-shadow border border-background-200">
                    <!-- Order Header -->
                    <div class="border-b border-background-200 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-text-900">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-text-600 mt-1">
                                    Customer: <span class="font-medium text-text-800">{{ $order->user->name }}</span>
                                </p>
                                <p class="text-sm text-text-600">
                                    Placed: {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Overall Status Badge -->
                                <div class="text-right">
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->status === 'pending')
                                        @elseif($order->status === 'approved')
                                        @elseif($order->status === 'paid')
                                        @elseif($order->status === 'shipped')
                                        @elseif($order->status === 'delivered')
                                        @else bg-background-200 text-text-800
                                        @endif
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="border-b border-background-200 p-6">
                        <h4 class="font-semibold text-text-900 mb-4">Order Items</h4>
                        <div class="space-y-3">
                            @forelse($order->items->where('shop_id', auth()->user()->shop->id) as $item)
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-text-900">{{ $item->product->name }}</p>
                                        <p class="text-sm text-text-600">
                                            {{ $item->quantity }} x ${{ number_format($item->price, 2) }}
                                        </p>
                                    </div>
                                    <p class="font-medium text-text-900">${{ number_format($item->quantity * $item->price, 2) }}</p>
                                </div>
                            @empty
                                <p class="text-text-600 text-sm">No items from your shop in this order</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Shop Approvals Status -->
                    @if($order->approvals->count() > 0)
                        <div class="border-b border-background-200 p-6">
                            <h4 class="font-semibold text-text-900 mb-4">Shop Approvals</h4>
                            <div class="space-y-3">
                                @foreach($order->approvals as $approval)
                                    <div class="flex items-center justify-between p-3 bg-background-100 rounded border border-background-200">
                                        <div>
                                            <p class="font-medium text-text-900">{{ $approval->shop->name }}</p>
                                            <p class="text-sm text-text-600">
                                                @if($approval->is_approved)
                                                    Approved on {{ $approval->approved_at->format('M d, Y') }}
                                                @else
                                                    Pending approval
                                                @endif
                                            </p>
                                        </div>
                                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                                            @if($approval->is_approved)
                                            @else bg-secondary-100 text-secondary-800
                                            @endif
                                        ">
                                            @if($approval->is_approved) ✓ Approved @else Pending @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Order Total & Payment -->
                    <div class="border-b border-background-200 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-text-600">Subtotal:</span>
                            <span class="text-text-900">${{ number_format($order->total * 0.9, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-text-600">Tax:</span>
                            <span class="text-text-900">${{ number_format($order->total * 0.1, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4 border-t border-background-200 pt-4">
                            <span class="font-semibold text-text-900">Total:</span>
                            <span class="text-lg font-bold text-primary-600">${{ number_format($order->total, 2) }}</span>
                        </div>
                        <p class="text-sm text-text-600">
                            Payment: <span class="font-medium text-text-800">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </p>
                    </div>

                    <!-- Shipping Address -->
                    <div class="border-b border-background-200 p-6">
                        <h4 class="font-semibold text-text-900 mb-2">Shipping Address</h4>
                        <p class="text-text-600 text-sm whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-6 bg-background-100 rounded-b-lg flex flex-col sm:flex-row gap-3">
                        <!-- Approve Button (for pending orders from this shop) -->
                        @if($order->status === 'pending')
                            @php
                                $shopApproval = $order->approvals->where('shop_id', auth()->user()->shop->id)->first();
                            @endphp

                            @if($shopApproval && !$shopApproval->is_approved)
                                <form action="{{ route('shop-owner.orders.approve', $shopApproval->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="w-full px-4 py-2 bg-primary-600 hover:bg-primary-700 text-background-50 font-medium rounded-lg transition-colors">
                                        ✓ Approve Order
                                    </button>
                                </form>
                            @elseif($shopApproval && $shopApproval->is_approved)
                                <div class="flex-1 px-4 py-2 bg-primary-100 text-primary-800 font-medium rounded-lg text-center border border-primary-200">
                                    ✓ You approved this order
                                </div>
                            @endif
                        @endif

                        <!-- View Details Link -->
                        <a href="{{ route('orders.show', $order->id) }}" class="flex-1 px-4 py-2 bg-secondary-600 hover:bg-secondary-700 text-background-50 font-medium rounded-lg text-center transition-colors">
                            View Details
                        </a>

                        <!-- Track Order Link -->
                        <a href="{{ route('orders.track', $order->id) }}" class="flex-1 px-4 py-2 bg-text-600 hover:bg-text-700 text-background-50 font-medium rounded-lg text-center transition-colors">
                            Track Order
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($approvals->hasPages())
            <div class="mt-8">
                {{ $approvals->links() }}
            </div>
        @endif
    @endif
</div>
@endsection