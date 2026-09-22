@extends('layouts.shop')

@section('title', 'Grocery - Fresh Groceries Delivered')

@section('page')
    <div id="page-index" class="page active">
        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 py-6">
            <div class="hero-gradient rounded-3xl p-8 md:p-12 lg:p-16 relative overflow-hidden">
                <!-- Background decorative elements -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
                <div class="absolute top-1/4 right-1/3 w-4 h-4 bg-yellow-300 rounded-full animate-pulse"></div>
                <div class="absolute bottom-1/3 right-1/4 w-3 h-3 bg-green-300 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                <div class="absolute top-1/3 left-1/2 w-2 h-2 bg-orange-300 rounded-full animate-pulse" style="animation-delay: 1s;"></div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-5 gap-8 items-center">
                    <!-- Left Content -->
                    <div class="lg:col-span-3 text-white">
                        <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-green-100 px-4 py-1.5 rounded-full text-sm font-medium mb-6">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            Free delivery on orders over $50
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                            Fresh Groceries,<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-200 to-yellow-200">Delivered Fast</span>
                        </h1>
                        <p class="text-green-100 text-lg mb-8 leading-relaxed max-w-xl">
                            From farm-fresh produce to premium pantry staples — order online and get everything delivered to your doorstep in hours.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('products') }}" class="inline-flex bg-white text-primary px-8 py-3.5 rounded-full font-semibold hover:bg-green-50 hover:shadow-lg transition-all items-center justify-center gap-2 text-lg">
                                Shop now <i class="fas fa-arrow-right text-sm"></i>
                            </a>
                            <a href="{{ route('products') }}" class="inline-flex border-2 border-white/30 text-white px-8 py-3.5 rounded-full font-semibold hover:bg-white/10 hover:border-white/60 transition-all items-center justify-center gap-2 text-lg">
                                <i class="fas fa-play-circle"></i> How it works
                            </a>
                        </div>
                        <!-- Trust badges -->
                        <div class="flex flex-wrap gap-6 mt-10">
                            <div class="flex items-center gap-2 text-green-200 text-sm">
                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center"><i class="fas fa-truck-fast"></i></div>
                                <span>Same-day delivery</span>
                            </div>
                            <div class="flex items-center gap-2 text-green-200 text-sm">
                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center"><i class="fas fa-shield-halved"></i></div>
                                <span>Quality guarantee</span>
                            </div>
                            <div class="flex items-center gap-2 text-green-200 text-sm">
                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center"><i class="fas fa-rotate-left"></i></div>
                                <span>Easy returns</span>
                            </div>
                        </div>
                    </div>
                    <!-- Right Product Showcase -->
                    <div class="lg:col-span-2 flex justify-center lg:justify-end">
                        <img src="https://pluspng.com/img-png/png-shampoo-shampoo-png-1134.png" alt="Featured Product" class="w-64 md:w-80 lg:w-96 object-contain drop-shadow-2xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- Promo Cards -->
        <section class="max-w-7xl mx-auto px-4 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 flex items-center gap-4 border border-red-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-md"><i class="fas fa-percent text-2xl text-red-500"></i></div>
                    <div>
                        <span class="text-xs text-red-500 font-semibold uppercase tracking-wider">Get up to</span>
                        <h3 class="text-2xl font-extrabold text-gray-800">30% OFF</h3>
                        <p class="text-sm text-gray-500">Selected fresh grocery items</p>
                        <a href="{{ route('products') }}" class="text-primary text-sm font-semibold mt-1 inline-block">Shop Now <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl p-6 flex items-center gap-4 border border-green-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-md"><i class="fas fa-box-open text-2xl text-primary"></i></div>
                    <div>
                        <span class="text-xs text-primary font-semibold uppercase tracking-wider">Summer Fest</span>
                        <h3 class="text-2xl font-extrabold text-gray-800">COMBO</h3>
                        <p class="text-sm text-gray-500">Special combo packs for summer</p>
                        <a href="{{ route('products') }}" class="text-primary text-sm font-semibold mt-1 inline-block">Shop Now <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 flex items-center gap-4 border border-orange-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-md"><i class="fas fa-shipping-fast text-2xl text-orange-500"></i></div>
                    <div>
                        <span class="text-xs text-orange-500 font-semibold uppercase tracking-wider">Free</span>
                        <h3 class="text-2xl font-extrabold text-gray-800">DELIVERY</h3>
                        <p class="text-sm text-gray-500">On orders over $50</p>
                        <a href="{{ route('products') }}" class="text-primary text-sm font-semibold mt-1 inline-block">Shop Now <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories -->
        <section class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Shop by Category</h2>
                <a href="{{ route('products') }}" class="text-primary font-semibold text-sm flex items-center gap-1 hover:gap-2 transition-all">All categories <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="grid grid-cols-3 md:grid-cols-5 gap-4">
                @forelse ($categories as $category)
                    <a href="{{ route('products', ['category' => $category->slug]) }}" class="bg-white rounded-2xl p-6 text-center card-shadow cursor-pointer hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        @if ($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}" class="w-16 h-16 rounded-full object-cover mx-auto mb-3">
                        @else
                            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-green-100 transition">
                                <i class="fas fa-folder text-2xl text-primary group-hover:scale-110 transition-transform"></i>
                            </div>
                        @endif
                        <h4 class="font-semibold text-sm text-gray-700">{{ $category->name }}</h4>
                        <p class="text-xs text-gray-400 mt-1">{{ $category->products_count }} items</p>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-400 py-8">No categories yet.</div>
                @endforelse
            </div>
        </section>

        <!-- Trending Products -->
        <section class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Trending Products</h2>
                <a href="{{ route('products') }}" class="text-primary font-semibold text-sm flex items-center gap-1 hover:gap-2 transition-all">See more <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @forelse ($products as $product)
                    @php $p = (float) $product->sale_price > 0 ? $product->sale_price : $product->price; @endphp
                    <div class="bg-white rounded-xl card-shadow p-4 hover:shadow-lg transition">
                        <a href="{{ route('products.show', $product) }}">
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-28 object-cover rounded-lg mb-3">
                            @else
                                <div class="w-full h-28 bg-gray-100 rounded-lg flex items-center justify-center mb-3"><i class="fas fa-box text-2xl text-gray-300"></i></div>
                            @endif
                        </a>
                        <a href="{{ route('products.show', $product) }}" class="font-semibold text-sm text-gray-800 line-clamp-2">{{ $product->name }}</a>
                        <div class="font-bold text-primary text-sm mt-1">₵{{ number_format((float) $p, 2) }}</div>
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-2">
                            @csrf
                            <button type="submit" class="btn-primary w-full text-white py-1.5 rounded-lg text-xs font-semibold hover:opacity-90 transition">Add to Cart</button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-400 py-8">No products yet.</div>
                @endforelse
            </div>
        </section>

        <!-- Special Offers -->
        <section class="max-w-7xl mx-auto px-4 py-8">
            <div class="hero-gradient rounded-2xl p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Special Offers for the Weekend</h2>
                        <p class="text-green-100 text-sm mt-1">Limited time offers on your favorite groceries</p>
                    </div>
                    <div class="flex gap-2 mt-4 md:mt-0">
                        <div class="bg-white/20 text-white px-3 py-1 rounded-lg text-center">
                            <div class="text-lg font-bold">05</div>
                            <div class="text-xs">Days</div>
                        </div>
                        <div class="bg-white/20 text-white px-3 py-1 rounded-lg text-center">
                            <div class="text-lg font-bold">19</div>
                            <div class="text-xs">Hrs</div>
                        </div>
                        <div class="bg-white/20 text-white px-3 py-1 rounded-lg text-center">
                            <div class="text-lg font-bold">37</div>
                            <div class="text-xs">Min</div>
                        </div>
                        <div class="bg-white/20 text-white px-3 py-1 rounded-lg text-center">
                            <div class="text-lg font-bold">12</div>
                            <div class="text-xs">Sec</div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse ($saleProducts as $product)
                        @php $sp = (float) $product->sale_price > 0 ? $product->sale_price : $product->price; @endphp
                        <div class="bg-white rounded-xl p-4 hover:shadow-lg transition">
                            <a href="{{ route('products.show', $product) }}">
                                @if ($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-28 object-cover rounded-lg mb-3">
                                @else
                                    <div class="w-full h-28 bg-gray-100 rounded-lg flex items-center justify-center mb-3"><i class="fas fa-box text-2xl text-gray-300"></i></div>
                                @endif
                            </a>
                            <a href="{{ route('products.show', $product) }}" class="font-semibold text-sm text-gray-800 line-clamp-2">{{ $product->name }}</a>
                            <div class="flex items-baseline gap-2 mt-1">
                                <span class="font-bold text-primary text-sm">₵{{ number_format((float) $sp, 2) }}</span>
                                <span class="text-xs text-gray-400 line-through">₵{{ number_format((float) $product->price, 2) }}</span>
                            </div>
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-2">
                                @csrf
                                <button type="submit" class="btn-primary w-full text-white py-1.5 rounded-lg text-xs font-semibold hover:opacity-90 transition">Add to Cart</button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-white/80 py-8">No special offers right now.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- You Might Need -->
        <section class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">You Might Need</h2>
                <a href="{{ route('products') }}" class="text-primary font-semibold text-sm flex items-center gap-1 hover:gap-2 transition-all">See more <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @forelse ($products as $product)
                    @php $p = (float) $product->sale_price > 0 ? $product->sale_price : $product->price; @endphp
                    <div class="bg-white rounded-xl card-shadow p-4 hover:shadow-lg transition">
                        <a href="{{ route('products.show', $product) }}">
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-28 object-cover rounded-lg mb-3">
                            @else
                                <div class="w-full h-28 bg-gray-100 rounded-lg flex items-center justify-center mb-3"><i class="fas fa-box text-2xl text-gray-300"></i></div>
                            @endif
                        </a>
                        <a href="{{ route('products.show', $product) }}" class="font-semibold text-sm text-gray-800 line-clamp-2">{{ $product->name }}</a>
                        <div class="font-bold text-primary text-sm mt-1">₵{{ number_format((float) $p, 2) }}</div>
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-2">
                            @csrf
                            <button type="submit" class="btn-primary w-full text-white py-1.5 rounded-lg text-xs font-semibold hover:opacity-90 transition">Add to Cart</button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-400 py-8">No products yet.</div>
                @endforelse
            </div>
        </section>

        @php $gateways = \App\Models\PaymentGateway::where('is_enabled', true)->orderBy('sort_order')->get(); @endphp
        @if ($gateways->isNotEmpty())
            <section class="max-w-7xl mx-auto px-4 py-8">
                <div class="bg-white rounded-2xl card-shadow p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Payment Methods</h2>
                    <div class="flex flex-wrap justify-center gap-6">
                        @foreach ($gateways as $gateway)
                            <div class="flex items-center gap-3 border border-gray-200 rounded-xl px-6 py-4">
                                <i class="{{ $gateway->code === 'paystack' ? 'fas fa-credit-card text-blue-600' : 'fas fa-money-bill-wave text-green-600' }} text-3xl"></i>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $gateway->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $gateway->code === 'paystack' ? 'Credit & Debit Cards' : 'Pay at doorstep' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                                <i class="fas fa-leaf text-white"></i>
                            </div>
                            <span class="text-xl font-bold text-primary">Grocery</span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-500">
                            <p><i class="fas fa-phone-alt mr-2 text-primary"></i> +880123456789</p>
                            <p><i class="fas fa-envelope mr-2 text-primary"></i> grocery@gmail.com</p>
                            <p><i class="fas fa-clock mr-2 text-primary"></i> 9 AM - 10 PM</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="{{ route('products') }}" class="hover:text-primary transition">Offers</a></li>
                            <li><a href="#" class="hover:text-primary transition">New Arrival</a></li>
                            <li><a href="#" class="hover:text-primary transition">Brands</a></li>
                            <li><a href="#" class="hover:text-primary transition">Blogs</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-4">Help</h4>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="#" class="hover:text-primary transition">Contact Us</a></li>
                            <li><a href="{{ route('user.dashboard') }}" class="hover:text-primary transition">My Account</a></li>
                            <li><a href="#" class="hover:text-primary transition">My List</a></li>
                            <li><a href="{{ route('cart') }}" class="hover:text-primary transition">Order History</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-4">Policy</h4>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="#" class="hover:text-primary transition">Terms & Conditions</a></li>
                            <li><a href="#" class="hover:text-primary transition">Shipping & Delivery</a></li>
                            <li><a href="#" class="hover:text-primary transition">Refund and Return Policy</a></li>
                            <li><a href="#" class="hover:text-primary transition">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-200 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">Install App</span>
                        <button id="installAppBtn" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-xs flex items-center gap-2" style="display: none;">
                            <i class="fas fa-download text-lg"></i> Install App
                        </button>
                        <span id="installHint" class="text-xs text-gray-400">Add this site to your home screen for an app-like experience.</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">Payment Methods</span>
                        <div class="flex gap-2">
                            <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                            <i class="fab fa-cc-mastercard text-2xl text-red-500"></i>
                            <i class="fab fa-cc-paypal text-2xl text-blue-500"></i>
                            <i class="fab fa-cc-amex text-2xl text-blue-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500">Stay Connected</span>
                        <a href="#" class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center hover:bg-primary-dark transition"><i class="fab fa-facebook-f text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center hover:bg-primary-dark transition"><i class="fab fa-twitter text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center hover:bg-primary-dark transition"><i class="fab fa-instagram text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center hover:bg-primary-dark transition"><i class="fab fa-linkedin-in text-sm"></i></a>
                    </div>
                </div>
                <p class="text-center text-sm text-gray-400 mt-6">&copy; 2026 Grocery. All Rights Reserved.</p>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
<script>
    let deferredPrompt = null;
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
        var btn = document.getElementById('installAppBtn');
        if (btn) { btn.style.display = 'inline-flex'; }
        var hint = document.getElementById('installHint');
        if (hint) { hint.style.display = 'none'; }
    });
    window.addEventListener('appinstalled', function () {
        deferredPrompt = null;
        var btn = document.getElementById('installAppBtn');
        if (btn) { btn.style.display = 'none'; }
    });
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('installAppBtn');
        if (btn) {
            btn.addEventListener('click', async function () {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    btn.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush
