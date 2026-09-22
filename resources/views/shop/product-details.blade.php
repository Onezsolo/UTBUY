@extends('layouts.shop')

@section('title', $product->name.' - Grocery')

@section('page')
    @php
        $currentPrice = (float) $product->sale_price > 0 ? $product->sale_price : $product->price;
        $hasSale = (float) $product->sale_price > 0;
        if ($product->stock_quantity <= 0) {
            $stockState = 'out';
        } elseif ($product->max_stock > 0 && ($product->stock_quantity / $product->max_stock) < 0.2) {
            $stockState = 'low';
        } else {
            $stockState = 'in';
        }
    @endphp

    <div class="max-w-7xl mx-auto px-4 py-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('products') }}" class="hover:text-primary">Products</a>
            @if ($product->category)
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('products', ['category' => $product->category->slug]) }}" class="hover:text-primary">{{ $product->category->name }}</a>
            @endif
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-800 font-medium">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl card-shadow p-8 flex items-center justify-center">
                @if ($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="max-h-96 object-contain">
                @else
                    <div class="text-center">
                        <div class="text-9xl text-gray-300 mb-4"><i class="fas fa-box-open"></i></div>
                    </div>
                @endif
            </div>

            <div>
                <div class="flex items-center gap-2 mb-2">
                    @if ($stockState === 'out')
                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">Out of Stock</span>
                    @elseif ($stockState === 'low')
                        <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">Low Stock</span>
                    @else
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">In Stock</span>
                    @endif
                    @if ($hasSale)
                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">SALE</span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>

                @if ((float) $product->rating_count > 0)
                    <div class="flex items-center gap-2 mb-4">
                        <div class="text-yellow-400 text-sm">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas {{ $i <= round((float) $product->rating_avg) ? 'fa-star' : 'fa-star text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">({{ number_format((float) $product->rating_avg, 1) }}) {{ $product->rating_count }} reviews</span>
                    </div>
                @endif

                <div class="flex items-baseline gap-3 mb-4">
                    <span class="text-3xl font-bold text-primary">₵{{ number_format((float) $currentPrice, 2) }}</span>
                    @if ($hasSale)
                        <span class="text-lg text-gray-400 line-through">₵{{ number_format((float) $product->price, 2) }}</span>
                    @endif
                </div>

                @if ($product->short_description)
                    <p class="text-gray-600 mb-4 leading-relaxed">{{ $product->short_description }}</p>
                @endif

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-orange-700"><i class="fas fa-warehouse mr-1"></i> Stock Status</span>
                        <span class="text-sm font-bold text-orange-700">{{ $product->stock_quantity }} items left</span>
                    </div>
                    @if ($product->max_stock > 0)
                        <div class="w-full bg-orange-200 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ min(100, ($product->stock_quantity / $product->max_stock) * 100) }}%"></div>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('cart.add', $product) }}" class="flex items-center gap-4 mb-6">
                    @csrf
                    <span class="text-sm font-medium text-gray-700">Quantity:</span>
                    <input type="number" name="quantity" value="1" min="1" class="w-20 border border-gray-200 rounded-lg px-3 py-2 text-center focus:outline-none focus:border-primary">
                    @if ($product->unit)
                        <span class="text-sm text-gray-500">{{ $product->unit }}</span>
                    @endif
                    <button type="submit" class="btn-primary flex-1 text-white py-3 rounded-lg font-semibold flex items-center justify-center gap-2 hover:opacity-90 transition">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </form>

                @if ($product->description)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Description & Specifications</h3>
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if ($related->isNotEmpty())
            <section class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Related Products</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($related as $item)
                        @php $itemPrice = (float) $item->sale_price > 0 ? $item->sale_price : $item->price; @endphp
                        <a href="{{ route('products.show', $item) }}" class="bg-white rounded-xl card-shadow p-4 hover:shadow-lg transition group">
                            @if ($item->image)
                                <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="w-full h-28 object-cover rounded-lg mb-3">
                            @else
                                <div class="w-full h-28 bg-gray-100 rounded-lg flex items-center justify-center mb-3"><i class="fas fa-box text-2xl text-gray-300"></i></div>
                            @endif
                            <div class="font-semibold text-sm text-gray-800 line-clamp-2">{{ $item->name }}</div>
                            <div class="font-bold text-primary text-sm mt-1">₵{{ number_format((float) $itemPrice, 2) }}</div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
