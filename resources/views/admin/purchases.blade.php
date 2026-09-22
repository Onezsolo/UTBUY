@extends('layouts.admin', ['active' => 'purchases'])

@section('title', 'Admin Purchases - Grocery')

@section('page')
    @php
        $statusOptions = ['pending', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled'];
        $statusSelectColors = [
            'pending' => 'bg-gray-50 text-gray-700',
            'confirmed' => 'bg-blue-50 text-blue-700',
            'processing' => 'bg-yellow-50 text-yellow-700',
            'shipped' => 'bg-blue-50 text-blue-700',
            'out_for_delivery' => 'bg-indigo-50 text-indigo-700',
            'delivered' => 'bg-green-50 text-green-700',
            'cancelled' => 'bg-red-50 text-red-700',
        ];
    @endphp

    <header class="bg-white shadow-sm px-6 py-4 sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800">Purchases / Orders</h1>
    </header>

    <div class="p-6">
        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4 flex items-center gap-2"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Total Orders</div><div class="text-xl font-bold">{{ $stats['total'] }}</div></div>
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Pending</div><div class="text-xl font-bold text-yellow-500">{{ $stats['pending'] }}</div></div>
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Shipped</div><div class="text-xl font-bold text-blue-500">{{ $stats['shipped'] }}</div></div>
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Delivered</div><div class="text-xl font-bold text-green-500">{{ $stats['delivered'] }}</div></div>
        </div>

        <div class="bg-white rounded-xl card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-500 bg-gray-50">
                            <th class="p-4 font-medium">Order ID</th>
                            <th class="p-4 font-medium">Customer</th>
                            <th class="p-4 font-medium">Date</th>
                            <th class="p-4 font-medium">Items</th>
                            <th class="p-4 font-medium">Status</th>
                            <th class="p-4 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-medium text-admin">{{ $order->order_number }}</td>
                                <td class="p-4"><div class="font-medium">{{ $order->user->name ?? 'Guest' }}</div><div class="text-xs text-gray-500">{{ $order->user->email ?? '—' }}</div></td>
                                <td class="p-4 text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="p-4">{{ $order->items_count }}</td>
                                <td class="p-4">
                                    <form method="POST" action="{{ route('admin.purchases.status', $order) }}">
                                        @csrf @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none {{ $statusSelectColors[$order->status] ?? '' }}">
                                            @foreach ($statusOptions as $option)
                                                <option value="{{ $option }}" {{ $order->status === $option ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="p-4 text-right font-bold">₵{{ number_format((float) $order->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-10 text-center text-gray-400">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200">{{ $orders->links() }}</div>
        </div>
    </div>
@endsection
