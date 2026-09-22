@extends('layouts.admin', ['active' => 'dashboard'])

@section('title', 'Admin Dashboard - Grocery')

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

    <!-- Top Bar -->
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-40">
        <div class="flex items-center gap-4">
            <button class="lg:hidden text-gray-600"><i class="fas fa-bars text-xl"></i></button>
            <h1 class="text-xl font-bold text-gray-800">Dashboard Overview</h1>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative">
                <i class="fas fa-bell text-gray-500 text-lg cursor-pointer hover:text-admin transition"></i>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-admin rounded-full flex items-center justify-center">
                    <i class="fas fa-user-tie text-white text-sm"></i>
                </div>
                <div class="hidden md:block">
                    <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">Administrator</div>
                </div>
            </div>
        </div>
    </header>

    <div class="p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-dollar-sign text-blue-600 text-lg"></i></div>
                </div>
                <div class="text-2xl font-bold text-gray-800">₵{{ number_format($stats['revenue'], 2) }}</div>
                <div class="text-sm text-gray-500">Total Revenue</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-shopping-bag text-green-600 text-lg"></i></div>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['orders']) }}</div>
                <div class="text-sm text-gray-500">Total Orders</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center"><i class="fas fa-users text-purple-600 text-lg"></i></div>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['users']) }}</div>
                <div class="text-sm text-gray-500">Total Users</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center"><i class="fas fa-box text-orange-600 text-lg"></i></div>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['products']) }}</div>
                <div class="text-sm text-gray-500">Products Listed</div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-xl card-shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg">Revenue Overview</h3>
                    <span class="text-xs text-gray-400">Last 7 days</span>
                </div>
                <div class="flex items-end gap-3 h-64">
                    @foreach ($revenueByDay as $day)
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-admin rounded-t-lg" style="height: {{ $day['height'] }}%"></div>
                            <span class="text-xs text-gray-500">{{ $day['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="font-bold text-lg mb-4">Top Products</h3>
                <div class="space-y-4">
                    @forelse ($topProducts as $product)
                        <div class="flex items-center gap-3">
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-box text-gray-400"></i></div>
                            @endif
                            <div class="flex-1 min-w-0"><div class="text-sm font-medium truncate">{{ $product->name }}</div><div class="text-xs text-gray-500">{{ $product->sold ?? 0 }} sold</div></div>
                            <span class="font-bold text-sm text-green-600">₵{{ number_format((float) ($product->revenue ?? 0), 2) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No sales recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="bg-white rounded-xl card-shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">Recent Orders</h3>
                <a href="{{ route('admin.purchases') }}" class="text-primary text-sm font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-500">
                            <th class="pb-3 font-medium">Order ID</th>
                            <th class="pb-3 font-medium">Customer</th>
                            <th class="pb-3 font-medium">Items</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="py-3 font-medium text-admin">{{ $order->order_number }}</td>
                                <td class="py-3 text-gray-600">{{ $order->user->name ?? 'Guest' }}</td>
                                <td class="py-3 text-gray-600">{{ $order->items_count }}</td>
                                <td class="py-3"><span class="{{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span></td>
                                <td class="py-3 text-right font-semibold">₵{{ number_format((float) $order->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
