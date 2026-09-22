@extends('layouts.admin', ['active' => 'users'])

@section('title', 'Admin Users - Grocery')

@section('page')
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800">Users</h1>
        <button type="button" onclick="openUserModal()" class="bg-admin text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-admin-light transition flex items-center gap-2"><i class="fas fa-plus"></i> Add User</button>
    </header>

    <div class="p-6">
        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4 flex items-center gap-2"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Total Users</div><div class="text-xl font-bold">{{ $stats['total'] }}</div></div>
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Active</div><div class="text-xl font-bold text-green-500">{{ $stats['active'] }}</div></div>
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Suspended</div><div class="text-xl font-bold text-yellow-500">{{ $stats['suspended'] }}</div></div>
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Admins</div><div class="text-xl font-bold text-purple-500">{{ $stats['admins'] }}</div></div>
        </div>

        <div class="bg-white rounded-xl card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-500 bg-gray-50">
                            <th class="p-4 font-medium">User</th>
                            <th class="p-4 font-medium">Email</th>
                            <th class="p-4 font-medium">Role</th>
                            <th class="p-4 font-medium">Orders</th>
                            <th class="p-4 font-medium">Spent</th>
                            <th class="p-4 font-medium">Status</th>
                            <th class="p-4 font-medium">Joined</th>
                            <th class="p-4 font-medium text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4"><div class="flex items-center gap-3"><div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center"><i class="fas fa-user text-blue-600 text-sm"></i></div><span class="font-medium">{{ $user->name }}</span></div></td>
                                <td class="p-4 text-gray-600">{{ $user->email }}</td>
                                <td class="p-4">
                                    @if ($user->role === 'admin')
                                        <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full">Admin</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-1 rounded-full">Customer</span>
                                    @endif
                                </td>
                                <td class="p-4">{{ $user->orders_count }}</td>
                                <td class="p-4 font-medium">₵{{ number_format((float) ($user->spent ?? 0), 2) }}</td>
                                <td class="p-4">
                                    @if ($user->is_active)
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Active</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">Suspended</span>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="{{ $user->is_active ? 'text-yellow-500' : 'text-green-500' }} text-sm hover:underline mr-2" title="{{ $user->is_active ? 'Suspend' : 'Activate' }}"><i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i></button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete this user?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 text-sm hover:underline"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    @else
                                        <span class="text-gray-300 text-xs">You</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="p-10 text-center text-gray-400">No users yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200">{{ $users->links() }}</div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="userModal" class="fixed inset-0 bg-black/50 z-50 {{ $errors->any() ? '' : 'hidden' }} flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">Add New User</h3>
                <button type="button" onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-4">
                @csrf
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
                        <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('name') border-red-500 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('email') border-red-500 @enderror">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin">
                            <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('password') border-red-500 @enderror">
                        @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeUserModal()" class="border border-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-admin text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-admin-light transition">Add User</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openUserModal() { document.getElementById('userModal').classList.remove('hidden'); }
    function closeUserModal() { document.getElementById('userModal').classList.add('hidden'); }
</script>
@endpush
