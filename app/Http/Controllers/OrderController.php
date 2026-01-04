<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product.shop', 'approvals.shop')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['items.product.shop', 'approvals.shop']);

        return view('orders.show', compact('order'));
    }
    
    public function getStatus(Order $order){
        $this->authorize('view', $order);

        return response()->json([
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'status_updated_at' => $order->status_updated_at,
            'estimated_delivery_date' => $order->estimated_delivery_date
        ]);
    }
}