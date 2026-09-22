@extends('layouts.user', ['active' => 'dashboard'])

@section('title', 'Dashboard - Grocery')

@section('page')
    @php
        $statusColors = [
            'pending' => 'bg-gray-100 text-gray-700',
            'confirmed' => 'bg-blue-100 text-blue-700',
            'processing' => 'bg-yellow-100 text-yellow-700',
            'shipped' => 'bg-blue-100 text-blue-700',
            'out_for_delivery' => 'bg-indigo-100 text-indigo-700',
            'delivered' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
        ];
    @endphp

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Overview</h1>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl card-shadow p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><i class="fas fa-shopping-bag text-blue-500 text-lg"></i></div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['orders']) }}</div>
            <div class="text-sm text-gray-500">Total Orders</div>
        </div>
        <div class="bg-white rounded-xl card-shadow p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-dollar-sign text-green-500 text-lg"></i></div>
            </div>
            <div class="text-2xl font-bold text-gray-800">₵{{ number_format($stats['spent'], 2) }}</div>
            <div class="text-sm text-gray-500">Total Spent</div>
        </div>
        <div class="bg-white rounded-xl card-shadow p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center"><i class="fas fa-heart text-purple-500 text-lg"></i></div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['wishlist']) }}</div>
            <div class="text-sm text-gray-500">Wishlist Items</div>
        </div>
    </div>
    <!-- Recent Orders -->
    <div class="bg-white rounded-xl card-shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Recent Orders</h3>
            <a href="{{ route('user.purchases') }}" class="text-primary text-sm font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500">
                        <th class="pb-3 font-medium">Order ID</th>
                        <th class="pb-3 font-medium">Date</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td class="py-3 font-medium text-primary">{{ $order->order_number }}</td>
                            <td class="py-3 text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-3"><span class="{{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span></td>
                            <td class="py-3 text-right font-semibold">₵{{ number_format((float) $order->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-400">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl card-shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Recent Transactions</h3>
            <a href="{{ route('user.transactions') }}" class="text-primary text-sm font-medium">View All</a>
        </div>
        <div class="space-y-3">
            @forelse ($recentPayments as $payment)
                @php $isRefund = $payment->status === 'refunded'; @endphp
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 {{ $isRefund ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center"><i class="fas {{ $isRefund ? 'fa-arrow-up text-green-500' : 'fa-arrow-down text-red-500' }}"></i></div>
                        <div>
                            <div class="font-medium text-sm">Order #{{ $payment->order->order_number ?? $payment->id }}</div>
                            <div class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y') }} · {{ ucfirst($payment->method) }}</div>
                        </div>
                    </div>
                    <span class="font-bold {{ $isRefund ? 'text-green-500' : 'text-red-500' }}">{{ $isRefund ? '+' : '-' }}₵{{ number_format((float) $payment->amount, 2) }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 text-center">No transactions yet.</p>
            @endforelse
        </div>
    </div>
@endsection
