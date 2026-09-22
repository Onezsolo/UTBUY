<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(): View
    {
        $orders = Order::where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->paginate(20);

        return view('user.purchases', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load(['items', 'payments']);

        return view('user.purchase-details', compact('order'));
    }
}
