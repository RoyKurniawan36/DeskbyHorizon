@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-4xl font-black text-text-950 mb-2">{{ __('order_approvals') }}</h1>
            <p class="text-text-500 font-medium">{{ __('manage_customer_requests_and_confirm_fulfillment') }}</p>
        </div>
        <div class="flex items-center bg-background-50 border border-background-200 rounded-2xl p-1">
            <button class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest bg-background-950 text-white shadow-lg">
                {{ __('all_orders') }}
            </button>
            <button class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest text-text-400 hover:text-text-950 transition-colors">
                {{ __('pending') }}
            </button>
        </div>
    </div>

    @if($approvals->count() > 0)
        <div class="space-y-6">
            @foreach($approvals as $approval)
                <div class="bg-background-50 border {{ $approval->is_approved ? 'border-background-200 opacity-75' : 'border-primary-200 shadow-xl shadow-primary-500/5' }} rounded-[2.5rem] overflow-hidden transition-all">
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row gap-8">
                            <div class="lg:w-1/3">
                                <div class="flex items-center gap-4 mb-4">
                                    <span class="px-4 py-1 rounded-full bg-background-950 text-white text-[10px] font-black uppercase tracking-[0.2em]">
                                        #{{ $approval->order->order_number }}
                                    </span>
                                    @if($approval->is_approved)
                                        <span class="flex items-center gap-1.5 text-green-600 font-bold text-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            {{ __('processed') }}
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1.5 text-secondary-500 font-bold text-sm animate-pulse">
                                            <div class="w-2 h-2 rounded-full bg-secondary-500"></div>
                                            {{ __('awaiting_action') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="text-2xl font-black text-text-950 mb-1">{{ $approval->order->user->name }}</h3>
                                <p class="text-text-400 text-sm font-medium mb-4">{{ $approval->order->created_at->format('M d, Y • h:i A') }}</p>
                                
                                <div class="bg-background-100/50 rounded-2xl p-4 border border-background-200">
                                    <p class="text-[10px] font-black text-text-400 uppercase tracking-widest mb-1">{{ __('payout_amount') }}</p>
                                    <p class="text-2xl font-black text-primary-600">${{ number_format($approval->order->total, 2) }}</p>
                                </div>
                            </div>

                            <div class="lg:w-2/3 flex flex-col justify-between">
                                <div class="mb-6">
                                    <p class="text-[10px] font-black text-text-400 uppercase tracking-widest mb-4">{{ __('requested_items') }}</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($approval->order->items as $item)
                                            <div class="flex items-center gap-3 p-3 bg-background-100/30 rounded-xl border border-background-200">
                                                <div class="w-10 h-10 rounded-lg bg-background-200 flex-shrink-0 overflow-hidden">
                                                    @if($item->product->image)
                                                        <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-text-950 truncate">{{ $item->product->name }}</p>
                                                    <p class="text-xs text-text-500">Qty: {{ $item->quantity }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if(!$approval->is_approved)
                                    <form action="{{ route('shop-owner.orders.approve', $approval) }}" method="POST" class="mt-auto">
                                        @csrf
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <div class="flex-1">
                                                <input type="text" name="notes" placeholder="{{ __('internal_fulfillment_notes') }}..." 
                                                       class="w-full px-5 py-3 rounded-xl border border-background-200 bg-background-100/50 text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all">
                                            </div>
                                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-xl font-black uppercase tracking-widest text-xs shadow-lg shadow-primary-500/20 transition-all active:scale-95">
                                                {{ __('confirm_fulfillment') }}
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div class="mt-auto pt-4 border-t border-background-200 flex items-center justify-between text-xs font-medium text-text-400 italic">
                                        <p>{{ __('approved_by') }}: {{ $approval->approver->name }}</p>
                                        <p>{{ $approval->approved_at->format('M d, Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $approvals->links() }}
        </div>
    @else
        <div class="bg-background-50 border border-dashed border-background-200 rounded-[3rem] py-24 text-center">
            <div class="w-20 h-20 bg-background-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-text-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h3 class="text-xl font-black text-text-950 mb-2">{{ __('no_orders_yet') }}</h3>
            <p class="text-text-500">{{ __('orders_requiring_your_attention_will_appear_here') }}</p>
        </div>
    @endif
</div>
@endsection