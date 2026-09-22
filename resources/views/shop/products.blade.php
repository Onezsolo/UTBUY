@extends('layouts.shop')

@section('title', 'Products - Grocery')

@section('page')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <aside class="w-full md:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl card-shadow p-6 sticky top-40">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fas fa-filter text-primary"></i> Categories</h3>
                    <div class="space-y-2">
                        <a href="{{ route('products') }}" class="flex items-center justify-between text-sm text-gray-600 hover:text-primary">
                            <span>All Products</span>
                            <span class="text-xs text-gray-400">{{ $categories->sum('products_count') }}</span>
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ route('products', ['category' => $category->slug]) }}" class="flex items-center justify-between text-sm text-gray-600 hover:text-primary">
                                <span>{{ $category->name }}</span>
                                <span class="text-xs text-gray-400">{{ $category->products_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">All Products</h1>

                @if ($products->isEmpty())
                    <div class="bg-white rounded-xl card-shadow p-10 text-center text-gray-400">No products available yet.</div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($products as $product)
                            @php
                                $currentPrice = (float) $product->sale_price > 0 ? $product->sale_price : $product->price;
                                $hasSale = (float) $product->sale_price > 0;
                            @endphp
                            <div class="bg-white rounded-xl card-shadow overflow-hidden hover:shadow-lg transition group">
                                <a href="{{ route('products.show', $product) }}" class="block">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover">
                                    @else
                                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center"><i class="fas fa-box text-4xl text-gray-300"></i></div>
                                    @endif
                                </a>
                                <div class="p-4">
                                    <div class="text-xs text-gray-400">{{ $product->category->name ?? 'General' }}</div>
                                    <a href="{{ route('products.show', $product) }}" class="font-semibold text-gray-800 hover:text-primary text-sm line-clamp-2">{{ $product->name }}</a>
                                    <div class="flex items-baseline gap-2 mt-2">
                                        <span class="font-bold text-primary">₵{{ number_format((float) $currentPrice, 2) }}</span>
                                        @if ($hasSale)
                                            <span class="text-xs text-gray-400 line-through">₵{{ number_format((float) $product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-3">
                                        @csrf
                                        <button type="submit" class="btn-primary w-full text-white py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition"><i class="fas fa-shopping-cart mr-1"></i> Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">{{ $products->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
