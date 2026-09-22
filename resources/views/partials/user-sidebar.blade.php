<aside class="lg:col-span-1">
    <div class="bg-white rounded-xl card-shadow p-6">
        <div class="text-center mb-6">
            <div class="w-20 h-20 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-user text-primary text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-800">John Doe</h3>
            <p class="text-sm text-gray-500">john@example.com</p>
        </div>
        <nav class="space-y-1">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 {{ $active === 'dashboard' ? 'bg-primary-light text-primary rounded-lg font-medium' : 'text-gray-600 hover:bg-gray-50 rounded-lg' }} text-sm"><i class="fas fa-th-large w-5"></i> Dashboard</a>
            <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-4 py-2.5 {{ $active === 'profile' ? 'bg-primary-light text-primary rounded-lg font-medium' : 'text-gray-600 hover:bg-gray-50 rounded-lg' }} text-sm"><i class="fas fa-user-circle w-5"></i> My Profile</a>
            <a href="{{ route('user.purchases') }}" class="flex items-center gap-3 px-4 py-2.5 {{ $active === 'purchases' ? 'bg-primary-light text-primary rounded-lg font-medium' : 'text-gray-600 hover:bg-gray-50 rounded-lg' }} text-sm"><i class="fas fa-shopping-bag w-5"></i> My Purchases</a>
            <a href="{{ route('user.transactions') }}" class="flex items-center gap-3 px-4 py-2.5 {{ $active === 'transactions' ? 'bg-primary-light text-primary rounded-lg font-medium' : 'text-gray-600 hover:bg-gray-50 rounded-lg' }} text-sm"><i class="fas fa-exchange-alt w-5"></i> Transactions</a>
            <a href="{{ route('user.wishlist') }}" class="flex items-center gap-3 px-4 py-2.5 {{ $active === 'wishlist' ? 'bg-primary-light text-primary rounded-lg font-medium' : 'text-gray-600 hover:bg-gray-50 rounded-lg' }} text-sm"><i class="fas fa-heart w-5"></i> Wishlist</a>
            <a href="{{ route('user.addresses') }}" class="flex items-center gap-3 px-4 py-2.5 {{ $active === 'addresses' ? 'bg-primary-light text-primary rounded-lg font-medium' : 'text-gray-600 hover:bg-gray-50 rounded-lg' }} text-sm"><i class="fas fa-map-marker-alt w-5"></i> Addresses</a>
            <a href="{{ route('user.settings') }}" class="flex items-center gap-3 px-4 py-2.5 {{ $active === 'settings' ? 'bg-primary-light text-primary rounded-lg font-medium' : 'text-gray-600 hover:bg-gray-50 rounded-lg' }} text-sm"><i class="fas fa-cog w-5"></i> Settings</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-red-500 hover:bg-red-50 rounded-lg text-sm w-full text-left"><i class="fas fa-sign-out-alt w-5"></i> Logout</button>
            </form>
        </nav>
    </div>
</aside>
