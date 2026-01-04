@extends('layouts.app')

@section('content')
@php
    $stages = [
        ['key' => 'packaging', 'label' => __('Packaging'), 'icon' => 'box'],
        ['key' => 'sorting',   'label' => __('Sorting'),   'icon' => 'sort'],
        ['key' => 'shipping',  'label' => __('Shipping'),  'icon' => 'truck'],
        ['key' => 'delivering','label' => __('Delivering'),'icon' => 'location'],
        ['key' => 'delivered', 'label' => __('Delivered'), 'icon' => 'check-circle'],
    ];
    
    $statusMap = [
        'pending' => -1, 'approved' => 0, 'paid' => 0, 'packaging' => 0,
        'sorting' => 1, 'shipping' => 2, 'delivering' => 3, 'delivered' => 4,
    ];
    
    $currentStageIndex = $statusMap[$order->status] ?? -1;
    $isDelivered = $order->status === 'delivered';
    $progressPercent = $currentStageIndex >= 0 ? (($currentStageIndex + 1) / count($stages)) * 100 : 0;

    // Transit Time Logic
    $createdAt = $order->created_at;
    $now = now();
    if ($createdAt->diffInMinutes($now) < 60) {
        $displayTime = $createdAt->diffInMinutes($now) . ' ' . __('mins');
    } elseif ($createdAt->diffInHours($now) < 24) {
        $displayTime = $createdAt->diffInHours($now) . ' ' . __('hours');
    } else {
        $displayTime = floor($createdAt->diffInDays($now)) . ' ' . __('days');
    }
@endphp

<div class="container mx-auto px-4 py-12 max-w-4xl">
    <div class="mb-8">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center text-text-500 hover:text-primary-600 transition-colors font-medium group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            {{ __('back_to_orders') }}
        </a>
    </div>

    <div class="bg-background-50 border border-background-200 rounded-3xl shadow-xl mb-8 p-6 md:p-10">
        <div class="mb-10 text-center md:text-left">
            <h2 class="text-2xl font-bold text-text-900">{{ __('order_status') }}</h2>
            <p class="text-text-500 font-medium" id="order-status-text">#{{ $order->order_number }} • {{ ucfirst(__($order->status)) }}</p>
        </div>

        <div class="relative mb-16 px-4">
            <div class="absolute top-6 left-8 right-8 h-1.5 bg-background-200 rounded-full overflow-hidden">
                <div class="h-full bg-primary-500 transition-all duration-1000 ease-in-out" style="width: {{ $progressPercent }}%"></div>
            </div>

            <div class="relative flex justify-between">
                @foreach($stages as $index => $stage)
                    @php
                        $isCompleted = $index <= $currentStageIndex;
                        $isCurrent = $index === $currentStageIndex;
                    @endphp
                    <div class="flex flex-col items-center flex-1">
                        <div class="relative z-10 w-12 h-12 rounded-full flex items-center justify-center transition-all duration-500 {{ $isCompleted ? ($isCurrent ? 'bg-primary-500 ring-4 ring-primary-100 scale-110' : 'bg-primary-600') : 'bg-background-300 text-text-400' }} text-white">
                            @include('partials.icons.' . $stage['icon'], ['class' => 'w-6 h-6'])
                            
                            @if($isCompleted && !$isCurrent)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full flex items-center justify-center text-[10px] animate-bounce">✓</span>
                            @endif
                        </div>
                        <div class="mt-4 text-center">
                            <span class="block text-xs md:text-sm font-bold {{ $isCompleted ? 'text-text-900' : 'text-text-400' }}">{{ $stage['label'] }}</span>
                            @if($isCurrent)
                                <span class="text-[10px] uppercase tracking-wider text-primary-500 font-black animate-pulse">{{ __('in_progress') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-background-100/50 p-6 rounded-2xl">
            @component('components.order-stat', ['label' => __('current_stage'), 'value' => $currentStageIndex >= 0 ? $stages[$currentStageIndex]['label'] : __('processing'), 'highlight' => true]) @endcomponent
            @component('components.order-stat', ['label' => __('est_delivery'), 'value' => $order->estimated_delivery_date ? \Carbon\Carbon::parse($order->estimated_delivery_date)->format('M d') : '3-5 Days']) @endcomponent
            @component('components.order-stat', ['label' => __('order_date'), 'value' => $order->created_at->format('d M')]) @endcomponent
            
            {{-- Fixed Transit Time Component --}}
            <div>
                <p class="text-[10px] text-text-400 uppercase tracking-widest font-black mb-1">{{ __('transit_time') }}</p>
                <p class="text-base md:text-lg font-bold text-text-900" id="live-transit-time">{{ $displayTime }}</p>
            </div>
        </div>
    </div>

    {{-- Order Items and Payment Details --}}
    <div class="bg-background-50 border border-background-200 rounded-3xl shadow-xl overflow-hidden">
        <div class="p-8 border-b border-background-100 flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-text-950">{{ __('order') }} <span class="text-primary-600">#{{ $order->order_number }}</span></h1>
                <p class="text-text-500">{{ $order->created_at->format('F d, Y • h:i A') }}</p>
            </div>
            <button onclick="window.print()" class="px-6 py-3 bg-background-200 hover:bg-background-300 rounded-xl transition-all font-bold text-xs uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                {{ __('print') }}
            </button>
            @if(app()->environment('local'))
                <div class="flex gap-2 items-center">
                    <form action="{{ route('orders.next-status', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-all font-bold text-xs uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-primary-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                            {{ __('Next Status') }}
                        </button>
                    </form>
            
                    <form action="{{ route('orders.reset-status', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-3 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-xl transition-all font-bold text-xs uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            {{ __('Reset') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="p-8">
            <div class="grid md:grid-cols-2 gap-12 mb-12">
                <section>
                    <h3 class="text-sm font-black uppercase tracking-widest text-text-400 mb-4">{{ __('shipping_to') }}</h3>
                    <div class="text-text-700 leading-relaxed p-4 bg-background-100/30 rounded-xl border border-dashed border-background-200">
                        {{ $order->shipping_address }}
                    </div>
                </section>
                <section>
                    <h3 class="text-sm font-black uppercase tracking-widest text-text-400 mb-4">{{ __('payment_details') }}</h3>
                    <p class="text-lg font-bold text-text-900">{{ __($order->payment_method) }}</p>
                    <p class="text-text-500 italic">{{ __('payment_status') }}: {{ ucfirst($order->status == 'paid' ? 'Paid' : 'Processed') }}</p>
                </section>
            </div>

            <div class="space-y-4 mb-10">
                <h3 class="text-sm font-black uppercase tracking-widest text-text-400 mb-6">{{ __('items') }}</h3>
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4 p-4 hover:bg-background-100/50 rounded-2xl transition-colors border border-transparent hover:border-background-200">
                        <img src="{{ $item->product->image ? Storage::url($item->product->image) : asset('placeholder.png') }}" class="w-16 h-16 object-cover rounded-lg bg-background-200">
                        <div class="flex-1">
                            <h4 class="font-bold text-text-900">{{ $item->product->name }}</h4>
                            <p class="text-sm text-text-500">{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</p>
                        </div>
                        <span class="font-black text-text-950">${{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="bg-primary-950 text-text-50 p-8 rounded-2xl">
                <div class="flex justify-between mb-2 opacity-70">
                    <span>{{ __('subtotal') }}</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
                <div class="flex justify-between mb-4 opacity-70 border-b border-primary-500/10 pb-4">
                    <span>{{ __('shipping') }}</span>
                    <span class="text-accent-100">{{ __('free') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold">{{ __('total_amount') }}</span>
                    <span class="text-3xl font-black text-primary-400">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStatus = "{{ $order->status }}";
    const statusUrl = "{{ route('orders.status.ajax', $order->id) }}";
    const orderCreatedAt = new Date("{{ $order->created_at->toIso8601String() }}");

    // 1. Live Transit Time Updater
    function updateTransitTime() {
        const now = new Date();
        const diffInMs = now - orderCreatedAt;
        const diffInMins = Math.floor(diffInMs / 60000);
        const diffInHours = Math.floor(diffInMs / 3600000);
        const diffInDays = Math.floor(diffInMs / 86400000);

        let display = "";
        if (diffInMins < 60) {
            display = `${diffInMins} mins`;
        } else if (diffInHours < 24) {
            display = `${diffInHours} hours`;
        } else {
            display = `${diffInDays} days`;
        }

        const element = document.getElementById('live-transit-time');
        if (element) element.innerText = display;
    }

    // 2. Status Polling Logic
    function checkStatus() {
        fetch(statusUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status !== currentStatus) {
                    window.location.reload(); 
                }
            });
    }

    // Initialize intervals
    const statusPoller = setInterval(checkStatus, 2000); // Poll status every 2s
    const timeUpdater = setInterval(updateTransitTime, 60000); // Update time every 1m

    // Initial run for time
    updateTransitTime();

    if (currentStatus === 'delivered') {
        clearInterval(statusPoller);
        clearInterval(timeUpdater);
    }
</script>
@endsection