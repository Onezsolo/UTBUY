<aside class="w-64 admin-grad text-white flex-shrink-0 hidden lg:block">
    <div class="p-6">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 mb-8">
            <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                <i class="fas fa-leaf text-white"></i>
            </div>
            <span class="text-xl font-bold">Grocery Admin</span>
        </a>
        <nav class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ $active === 'dashboard' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-th-large w-5"></i> Dashboard</a>
            <a href="{{ route('admin.purchases') }}" class="sidebar-link {{ $active === 'purchases' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-shopping-bag w-5"></i> Purchases</a>
            <a href="{{ route('admin.transactions') }}" class="sidebar-link {{ $active === 'transactions' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-exchange-alt w-5"></i> Transactions</a>
            <a href="{{ route('admin.products') }}" class="sidebar-link {{ $active === 'products' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-box w-5"></i> Products</a>
            <a href="{{ route('admin.categories') }}" class="sidebar-link {{ $active === 'categories' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-tags w-5"></i> Categories</a>
            <a href="{{ route('admin.payment-gateway') }}" class="sidebar-link {{ $active === 'payment-gateway' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-credit-card w-5"></i> Payment Gateway</a>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ $active === 'users' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-users w-5"></i> Users</a>
            <a href="{{ route('admin.support') }}" class="sidebar-link {{ $active === 'support' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-headset w-5"></i> Support</a>
            <a href="{{ route('admin.announcements') }}" class="sidebar-link {{ $active === 'announcements' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-bullhorn w-5"></i> Announcements</a>
            <a href="{{ route('admin.popup-messages') }}" class="sidebar-link {{ $active === 'popup-messages' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-bell w-5"></i> Popup Messages</a>
            <a href="{{ route('admin.system-settings') }}" class="sidebar-link {{ $active === 'system-settings' ? 'active' : 'text-gray-300' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm"><i class="fas fa-cog w-5"></i> System Settings</a>
        </nav>
    </div>
    <div class="absolute bottom-0 w-64 p-6 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-red-400 hover:bg-red-500/10 transition w-full text-left">
                <i class="fas fa-sign-out-alt w-5"></i> Logout
            </button>
        </form>
    </div>
</aside>
