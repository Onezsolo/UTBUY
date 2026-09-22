@extends('layouts.simple')

@section('title', 'Cart - Grocery')

@section('page')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Shopping Cart</h1>

        @if (session('status'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4"><i class="fas fa-check-circle mr-1"></i> {{ session('status') }}</div>
        @endif

        @if ($items->isEmpty())
            <div class="bg-white rounded-xl card-shadow p-10 text-center">
                <p class="text-gray-500 mb-4">Your cart is empty.</p>
                <a href="{{ route('products') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold inline-block">Start Shopping</a>
            </div>
        @else
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <div class="divide-y divide-gray-100">
                    @foreach ($items as $item)
                        <div class="flex items-center gap-4 p-4">
                            @if ($item->product->image)
                                <img src="{{ asset('storage/'.$item->product->image) }}" class="w-16 h-16 rounded-lg object-cover">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-box text-gray-400"></i></div>
                            @endif
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">{{ $item->product->name }}</div>
                                <div class="text-sm text-gray-500">₵{{ number_format((float) $item->price_at_add, 2) }}</div>
                            </div>
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                @csrf @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-center focus:outline-none focus:border-primary">
                                <button type="submit" class="text-gray-500 text-xs hover:text-primary"><i class="fas fa-sync-alt"></i></button>
                            </form>
                            <div class="w-24 text-right font-semibold">₵{{ number_format($item->quantity * (float) $item->price_at_add, 2) }}</div>
                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-200 p-6 flex justify-between items-center">
                    @php $subtotal = (float) $items->sum(fn ($i) => $i->quantity * (float) $i->price_at_add); @endphp
                    <div class="text-lg font-bold text-gray-800">Total: <span class="text-primary">₵{{ number_format($subtotal, 2) }}</span></div>
                    <a href="{{ route('checkout') }}" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition">Proceed to Checkout</a>
                </div>
            </div>
        @endif
    </div>
@endsection
