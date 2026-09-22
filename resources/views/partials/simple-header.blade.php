@php $announcements = \App\Models\Announcement::active()->get(); $cartCount = \App\Models\Cart::totalItems(); @endphp
<header id="mainHeader" class="bg-white shadow-sm sticky top-0 z-50">
    @if ($announcements->isNotEmpty())
        <div class="bg-primary text-white text-sm py-2 announcement-marquee overflow-hidden whitespace-nowrap">
            <div class="announcement-track inline-block">
                @foreach (range(1, 2) as $loop)
                    @foreach ($announcements as $announcement)
                        <span class="inline-flex items-center mx-8"><i class="fas fa-bullhorn mr-2 text-green-200"></i>{{ $announcement->message }}</span>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <i class="fas fa-leaf text-white text-lg"></i>
                </div>
                <span class="text-2xl font-bold text-primary">Grocery</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('support') }}" class="text-gray-600 hover:text-primary transition items-center gap-1 hidden md:flex">
                    <i class="fas fa-headset"></i>
                    <span class="text-sm font-medium">Support</span>
                </a>
                <a href="{{ route('cart') }}" class="relative text-gray-600 hover:text-primary transition">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    @if ($cartCount > 0)
                        <span id="cartCount" class="absolute -top-2 -right-2 bg-accent text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</header>
<style>
    .announcement-marquee { overflow: hidden; }
    .announcement-track { display: inline-block; white-space: nowrap; animation: marquee 30s linear infinite; }
    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
</style>
