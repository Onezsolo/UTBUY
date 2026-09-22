@extends('layouts.user', ['active' => 'wishlist'])

@section('title', 'Wishlist - Grocery')

@section('page')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Wishlist</h1>
            <p class="text-sm text-gray-500 mt-1">7 items saved</p>
        </div>
        <a href="{{ route('products') }}" class="bg-primary text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Browse Products
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Wishlist Item 1 -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden hover:shadow-lg transition">
            <div class="p-6 flex items-center justify-center h-36 bg-gray-50 relative">
                <i class="fas fa-drumstick-bite text-5xl text-red-500"></i>
                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">20% OFF</span>
                <button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                    <i class="fas fa-heart text-sm"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-xs text-gray-400 mb-1">Meat</div>
                <h4 class="font-semibold text-sm text-gray-800 mb-2">Farm Fresh Beef Premium Cube</h4>
                <div class="flex items-center gap-1 text-yellow-400 text-xs mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    <span class="text-gray-400 ml-1">(128)</span>
                </div>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-lg font-bold text-primary">$18.00</span>
                    <span class="text-xs text-gray-400 line-through">$22.50</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 bg-primary text-white py-2 rounded-lg text-sm font-semibold hover:bg-primary-dark transition flex items-center justify-center gap-1">
                        <i class="fas fa-cart-plus text-xs"></i> Add to Cart
                    </button>
                    <button class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition">
                        <i class="fas fa-trash-alt text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Wishlist Item 2 -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden hover:shadow-lg transition">
            <div class="p-6 flex items-center justify-center h-36 bg-gray-50 relative">
                <i class="fas fa-fish text-5xl text-blue-500"></i>
                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">15% OFF</span>
                <button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                    <i class="fas fa-heart text-sm"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-xs text-gray-400 mb-1">Fish</div>
                <h4 class="font-semibold text-sm text-gray-800 mb-2">Rui Fresh Local Cultured (2-2.99 kg)</h4>
                <div class="flex items-center gap-1 text-yellow-400 text-xs mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <span class="text-gray-400 ml-1">(67)</span>
                </div>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-lg font-bold text-primary">$14.00</span>
                    <span class="text-xs text-gray-400 line-through">$16.50</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 bg-primary text-white py-2 rounded-lg text-sm font-semibold hover:bg-primary-dark transition flex items-center justify-center gap-1">
                        <i class="fas fa-cart-plus text-xs"></i> Add to Cart
                    </button>
                    <button class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition">
                        <i class="fas fa-trash-alt text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Wishlist Item 3 -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden hover:shadow-lg transition">
            <div class="p-6 flex items-center justify-center h-36 bg-gray-50 relative">
                <i class="fas fa-bottle-droplet text-5xl text-yellow-500"></i>
                <button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                    <i class="fas fa-heart text-sm"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-xs text-gray-400 mb-1">Oil</div>
                <h4 class="font-semibold text-sm text-gray-800 mb-2">Premium Sunarshine Rice Bran Oil 5Lt</h4>
                <div class="flex items-center gap-1 text-yellow-400 text-xs mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                    <span class="text-gray-400 ml-1">(156)</span>
                </div>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-lg font-bold text-primary">$12.50</span>
                    <span class="text-xs text-gray-400 line-through">$15.00</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 bg-primary text-white py-2 rounded-lg text-sm font-semibold hover:bg-primary-dark transition flex items-center justify-center gap-1">
                        <i class="fas fa-cart-plus text-xs"></i> Add to Cart
                    </button>
                    <button class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition">
                        <i class="fas fa-trash-alt text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Wishlist Item 4 -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden hover:shadow-lg transition">
            <div class="p-6 flex items-center justify-center h-36 bg-gray-50 relative">
                <i class="fas fa-seedling text-5xl text-yellow-600"></i>
                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">14% OFF</span>
                <button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                    <i class="fas fa-heart text-sm"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-xs text-gray-400 mb-1">Grains</div>
                <h4 class="font-semibold text-sm text-gray-800 mb-2">Sugandhi Chaal Chingri Rice 5kg</h4>
                <div class="flex items-center gap-1 text-yellow-400 text-xs mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <span class="text-gray-400 ml-1">(201)</span>
                </div>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-lg font-bold text-primary">$24.00</span>
                    <span class="text-xs text-gray-400 line-through">$28.00</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 bg-primary text-white py-2 rounded-lg text-sm font-semibold hover:bg-primary-dark transition flex items-center justify-center gap-1">
                        <i class="fas fa-cart-plus text-xs"></i> Add to Cart
                    </button>
                    <button class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition">
                        <i class="fas fa-trash-alt text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Wishlist Item 5 -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden hover:shadow-lg transition">
            <div class="p-6 flex items-center justify-center h-36 bg-gray-50 relative">
                <i class="fas fa-drumstick-bite text-5xl text-orange-500"></i>
                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">11% OFF</span>
                <button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                    <i class="fas fa-heart text-sm"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-xs text-gray-400 mb-1">Meat</div>
                <h4 class="font-semibold text-sm text-gray-800 mb-2">Broiler Chicken Premium (Without Skin)</h4>
                <div class="flex items-center gap-1 text-yellow-400 text-xs mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    <span class="text-gray-400 ml-1">(89)</span>
                </div>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-lg font-bold text-primary">$13.30</span>
                    <span class="text-xs text-gray-400 line-through">$15.00</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 bg-primary text-white py-2 rounded-lg text-sm font-semibold hover:bg-primary-dark transition flex items-center justify-center gap-1">
                        <i class="fas fa-cart-plus text-xs"></i> Add to Cart
                    </button>
                    <button class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition">
                        <i class="fas fa-trash-alt text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Wishlist Item 6 -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden hover:shadow-lg transition">
            <div class="p-6 flex items-center justify-center h-36 bg-gray-50 relative">
                <i class="fas fa-apple-alt text-5xl text-red-500"></i>
                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">15% OFF</span>
                <button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                    <i class="fas fa-heart text-sm"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-xs text-gray-400 mb-1">Fruits</div>
                <h4 class="font-semibold text-sm text-gray-800 mb-2">Fresh Organic Apples 1kg</h4>
                <div class="flex items-center gap-1 text-yellow-400 text-xs mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    <span class="text-gray-400 ml-1">(234)</span>
                </div>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-lg font-bold text-primary">$8.50</span>
                    <span class="text-xs text-gray-400 line-through">$10.00</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 bg-primary text-white py-2 rounded-lg text-sm font-semibold hover:bg-primary-dark transition flex items-center justify-center gap-1">
                        <i class="fas fa-cart-plus text-xs"></i> Add to Cart
                    </button>
                    <button class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition">
                        <i class="fas fa-trash-alt text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
