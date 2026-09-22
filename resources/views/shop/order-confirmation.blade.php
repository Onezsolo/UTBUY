@extends('layouts.simple')

@section('title', 'Order Confirmation - Grocery')

@section('page')
    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="text-center mb-8">
            @if ($order->payment_status === 'paid')
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-check-circle text-3xl text-green-600"></i></div>
                <h1 class="text-3xl font-bold text-gray-800">Thank you for your order!</h1>
                <p class="text-gray-500 mt-2">Your payment was successful and your order is confirmed.</p>
            @elseif ($order->payment_status === 'failed')
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-times-circle text-3xl text-red-500"></i></div>
                <h1 class="text-3xl font-bold text-gray-800">Payment Failed</h1>
                <p class="text-gray-500 mt-2">Your payment could not be completed. Please try again.</p>
            @else
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-clock text-3xl text-yellow-500"></i></div>
                <h1 class="text-3xl font-bold text-gray-800">Order Placed</h1>
                <p class="text-gray-500 mt-2">Your order has been received and is awaiting payment.</p>
            @endif
        </div>

        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4 flex items-center gap-2"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="bg-white rounded-xl card-shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <div class="text-sm text-gray-500">Order Number</div>
                    <div class="font-bold text-gray-800">{{ $order->order_number }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="font-bold text-primary text-xl">₵{{ number_format((float) $order->total, 2) }}</div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4 mb-4">
                <h4 class="font-semibold text-gray-700 mb-2">Items</h4>
                <div class="space-y-2">
                    @foreach ($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item->name }} <span class="text-gray-400">× {{ $item->quantity }}</span></span>
                            <span>₵{{ number_format((float) $item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4 mb-4">
                <h4 class="font-semibold text-gray-700 mb-2">Delivery Address</h4>
                <p class="text-sm text-gray-600">{{ $order->delivery_full_name }}</p>
                <p class="text-sm text-gray-600">{{ $order->delivery_address_line_1 }}{{ $order->delivery_address_line_2 ? ', '.$order->delivery_address_line_2 : '' }}</p>
                <p class="text-sm text-gray-600">{{ $order->delivery_city }}, {{ $order->delivery_state }}, {{ $order->delivery_country }}</p>
                <p class="text-sm text-gray-600">{{ $order->delivery_phone }}</p>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('products') }}" class="btn-primary text-white px-6 py-2.5 rounded-lg text-sm font-semibold">Continue Shopping</a>
                @auth
                    <a href="{{ route('user.purchases') }}" class="border border-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50">View Orders</a>
                @endauth
            </div>
        </div>
    </div>
@endsection
