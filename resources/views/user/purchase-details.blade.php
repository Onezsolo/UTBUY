@extends('layouts.user', ['active' => 'purchases'])

@section('title', 'Order Details - Grocery')

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

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Order {{ $order->order_number }}</h1>
        <a href="{{ route('user.purchases') }}" class="text-primary text-sm font-medium hover:underline"><i class="fas fa-arrow-left mr-1"></i> Back to Orders</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="font-bold text-lg mb-4">Items</h3>
                <div class="space-y-3">
                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-3">
                            @if ($item->image)
                                <img src="{{ asset('storage/'.$item->image) }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-box text-gray-400"></i></div>
                            @endif
                            <div class="flex-1">
                                <div class="font-medium text-gray-800">{{ $item->name }}</div>
                                <div class="text-xs text-gray-500">₵{{ number_format((float) $item->price, 2) }} × {{ $item->quantity }}</div>
                            </div>
                            <div class="font-semibold">₵{{ number_format((float) $item->subtotal, 2) }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 pt-4 mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span>₵{{ number_format((float) $order->subtotal, 2) }}</span></div>
                    @if ((float) $order->payment_fee > 0)
                        <div class="flex justify-between"><span class="text-gray-600">Payment Fee</span><span>₵{{ number_format((float) $order->payment_fee, 2) }}</span></div>
                    @endif
                    <div class="flex justify-between font-bold text-base border-t border-gray-100 pt-2"><span>Total</span><span class="text-primary">₵{{ number_format((float) $order->total, 2) }}</span></div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="font-bold text-lg mb-4">Status</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-600">Order Status</span><span class="{{ $statusColors[$order->status] ?? '' }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Payment Status</span><span class="{{ $paymentStatusColors[$order->payment_status] ?? '' }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ ucfirst($order->payment_status) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Payment Method</span><span class="font-medium">{{ $order->payment_method === 'paystack' ? 'Paystack' : 'Cash on Delivery' }}</span></div>
                </div>
            </div>

            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="font-bold text-lg mb-4">Delivery Address</h3>
                <p class="text-sm text-gray-600">{{ $order->delivery_full_name }}</p>
                <p class="text-sm text-gray-600">{{ $order->delivery_address_line_1 }}{{ $order->delivery_address_line_2 ? ', '.$order->delivery_address_line_2 : '' }}</p>
                <p class="text-sm text-gray-600">{{ $order->delivery_city }}, {{ $order->delivery_state }}, {{ $order->delivery_country }}</p>
                <p class="text-sm text-gray-600">{{ $order->delivery_phone }}</p>
            </div>
        </div>
    </div>
@endsection
