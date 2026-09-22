<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'revenue' => (float) Order::where('payment_status', 'paid')->sum('total'),
            'orders' => Order::count(),
            'users' => User::where('role', 'customer')->count(),
            'products' => Product::count(),
        ];

        $recentOrders = Order::with('user')
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        $topProducts = Product::withSum('orderItems as sold', 'quantity')
            ->withSum('orderItems as revenue', 'subtotal')
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        $revenueByDay = $this->revenueByDay();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'revenueByDay'));
    }

    private function revenueByDay(): Collection
    {
        $days = collect(range(6, 0))->map(function (int $daysAgo) {
            $date = now()->subDays($daysAgo);

            return [
                'label' => $date->format('D'),
                'total' => (float) Order::where('payment_status', 'paid')
                    ->whereDate('created_at', $date->toDateString())
                    ->sum('total'),
            ];
        });

        $max = max($days->max('total'), 1);

        return $days->map(function (array $day) use ($max) {
            $day['height'] = (int) round(($day['total'] / $max) * 100);

            return $day;
        });
    }
}
