@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-5xl">
    <div class="mb-10">
        <h1 class="text-4xl font-bold text-text-950 mb-2">{{ __('my_orders') }}</h1>
        <p class="text-text-500 font-medium">{{ __('track_and_manage_your_recent_purchases') }}</p>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="group bg-background-50 border border-background-200 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="bg-background-100/50 px-6 py-4 border-b border-background-200 flex flex-wrap justify-between items-center gap-4">
                        <div class="flex items-center gap-6">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-text-400 font-bold">{{ __('order_number') }}</p>
                                <p class="font-mono font-bold text-text-900">#{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-text-400 font-bold">{{ __('date_placed') }}</p>
                                <p class="font-bold text-text-900">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-text-400 font-bold">{{ __('total_amount') }}</p>
                                <p class="font-bold text-primary-600">${{ number_format($order->total, 2) }}</p>
                            </div>
                        </div>

                        <div>
                            @php
                                $statusClasses = [
                                    'pending'   => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'completed' => 'bg-secondary-100 text-secondary-700 border-secondary-200',
                                    'cancelled' => 'bg-accent-100 text-accent-700 border-accent-200',
                                    'shipped'   => 'bg-primary-100 text-primary-700 border-primary-200',
                                    'approved'  => 'bg-accent-100 text-success-700 border-success-200',
                                ];
                                $currentClass = $statusClasses[$order->status] ?? 'bg-background-200 text-text-600';
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-tighter border {{ $currentClass }}">
                                {{ __($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                            <div class="flex flex-1 items-center gap-3 overflow-hidden">
                                @foreach($order->items->take(4) as $item)
                                    <div class="relative flex-shrink-0">
                                        @if($item->product->image)
                                            <img src="{{ Storage::url($item->product->image) }}" class="w-16 h-16 rounded-xl object-cover border border-background-200 bg-background-100">
                                        @else
                                            <div class="w-16 h-16 rounded-xl bg-background-200 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-text-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        <span class="absolute -top-2 -right-2 bg-background-950 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-background-50">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>
                                @endforeach
                                
                                @if($order->items->count() > 4)
                                    <div class="w-12 h-12 rounded-full bg-background-100 border border-background-200 flex items-center justify-center text-xs font-bold text-text-500">
                                        +{{ $order->items->count() - 4 }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex gap-3 w-full md:w-auto">
                                <a href="{{ route('orders.show', $order) }}" class="flex-1 md:flex-none text-center px-6 py-2.5 rounded-xl font-bold bg-background-100 text-text-800 hover:bg-background-200 transition-colors">
                                    {{ __('view_details') }}
                                </a>
                                @if($order->status === 'pending')
                                    <button class="flex-1 md:flex-none px-6 py-2.5 rounded-xl font-bold bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors">
                                        {{ __('track_order') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-24 bg-background-50 border border-background-200 rounded-[3rem]">
            <div class="w-24 h-24 bg-background-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-text-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-text-950 mb-2">{{ __('no_orders_yet') }}</h2>
            <p class="text-text-500 mb-10 max-w-xs mx-auto">{{ __('your_future_purchases_will_appear_here_ready_for_tracking') }}</p>
            <a href="{{ route('welcome') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-10 py-4 rounded-2xl font-bold shadow-xl shadow-primary-500/20 transition-all active:scale-95">
                {{ __('explore_setups') }}
            </a>
        </div>
    @endif
</div>
@endsection