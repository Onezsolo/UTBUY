<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        $orders = Order::with('user')
            ->withCount('items')
            ->latest()
            ->paginate(20);

        return view('admin.purchases', compact('orders', 'stats'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled'],
        ]);

        $order->update(['status' => $request->status]);
        $order->statusHistories()->create([
            'status_code' => $request->status,
            'changed_by' => $request->user()->id,
            'created_at' => now(),
        ]);

        return back()->with('status', 'Order status updated.');
    }
}
