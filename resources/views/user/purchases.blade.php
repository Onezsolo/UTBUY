@extends('layouts.user', ['active' => 'purchases'])

@section('title', 'My Purchases - Grocery')

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
        $paymentStatusColors = [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'paid' => 'bg-green-100 text-green-700',
            'failed' => 'bg-red-100 text-red-700',
            'refunded' => 'bg-green-100 text-green-700',
        ];
    @endphp

    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Purchases</h1>

    <div class="bg-white rounded-xl card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500 bg-gray-50">
                        <th class="p-4 font-medium">Order ID</th>
                        <th class="p-4 font-medium">Date</th>
                        <th class="p-4 font-medium">Items</th>
                        <th class="p-4 font-medium">Status</th>
                        <th class="p-4 font-medium">Payment</th>
                        <th class="p-4 font-medium text-right">Total</th>
                        <th class="p-4 font-medium text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium text-primary">{{ $order->order_number }}</td>
                            <td class="p-4 text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="p-4">{{ $order->items_count }}</td>
                            <td class="p-4"><span class="{{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span></td>
                            <td class="p-4"><span class="{{ $paymentStatusColors[$order->payment_status] ?? 'bg-gray-100 text-gray-700' }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ ucfirst($order->payment_status) }}</span></td>
                            <td class="p-4 text-right font-semibold">₵{{ number_format((float) $order->total, 2) }}</td>
                            <td class="p-4 text-center">
                                <a href="{{ route('user.purchases.show', $order) }}" class="text-primary text-sm font-medium hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-10 text-center text-gray-400">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200">{{ $orders->links() }}</div>
    </div>
@endsection
