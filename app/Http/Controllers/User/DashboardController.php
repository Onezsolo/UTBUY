<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $stats = [
            'orders' => Order::where('user_id', $user->id)->count(),
            'spent' => (float) Order::where('user_id', $user->id)->where('payment_status', 'paid')->sum('total'),
            'wishlist' => WishlistItem::where('user_id', $user->id)->count(),
        ];

        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentOrders', 'recentPayments'));
    }
}
