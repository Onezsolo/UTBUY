@extends('layouts.user', ['active' => 'addresses'])

@section('title', 'Addresses - Grocery')

@section('page')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Addresses</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your delivery addresses</p>
        </div>
        <button type="button" onclick="openAddressModal()" class="bg-primary text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New Address
        </button>
    </div>

    @if (session('status'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse ($addresses as $address)
            <div class="bg-white rounded-xl card-shadow p-6 relative {{ $address->is_default ? 'border-2 border-primary' : '' }}">
                @if ($address->is_default)
                    <span class="absolute top-4 right-4 bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full">Default</span>
                @endif
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas {{ $address->label === 'Office' ? 'fa-briefcase' : 'fa-home' }} text-primary"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-bold text-gray-800">{{ $address->label }}</h3>
                        </div>
                        <div class="text-sm text-gray-500 space-y-1 mb-4">
                            <p class="font-medium text-gray-700">{{ $address->full_name }}</p>
                            <p>{{ $address->address_line_1 }}{{ $address->address_line_2 ? ', '.$address->address_line_2 : '' }}</p>
                            <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                            <p>{{ $address->country }}</p>
                            <p class="text-gray-400">{{ $address->phone }}</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button type="button"
                                class="text-primary text-sm font-medium hover:underline"
                                data-id="{{ $address->id }}"
                                data-label="{{ $address->label }}"
                                data-name="{{ $address->full_name }}"
                                data-phone="{{ $address->phone }}"
                                data-email="{{ $address->email }}"
                                data-line1="{{ $address->address_line_1 }}"
                                data-line2="{{ $address->address_line_2 }}"
                                data-city="{{ $address->city }}"
                                data-state="{{ $address->state }}"
                                data-postal="{{ $address->postal_code }}"
                                data-country="{{ $address->country }}"
                                data-default="{{ $address->is_default ? '1' : '0' }}"
                                onclick="editAddress(this)">Edit</button>
                            @unless ($address->is_default)
                                <form method="POST" action="{{ route('user.addresses.default', $address) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-gray-500 text-sm font-medium hover:underline">Set as Default</button>
                                </form>
                            @endunless
                            <form method="POST" action="{{ route('user.addresses.destroy', $address) }}" class="inline" onsubmit="return confirm('Delete this address?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 text-sm font-medium hover:underline">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-6 flex flex-col items-center justify-center min-h-[200px]">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm">
                    <i class="fas fa-map-marker-alt text-2xl text-gray-400"></i>
                </div>
                <h3 class="font-semibold text-gray-500">No addresses yet</h3>
                <p class="text-sm text-gray-400 mt-1">Click "Add New Address" to save your first delivery address.</p>
            </div>
        @endforelse
    </div>

    <!-- Add / Edit Address Modal -->
    <div id="addressModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800" id="addressModalTitle">Add New Address</h3>
                <button type="button" onclick="closeAddressModal()" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('user.addresses.store') }}" id="addressForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="addressMethod" value="POST">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                        <input type="text" name="label" id="label" value="{{ old('label') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('label') border-red-500 @enderror" placeholder="Home, Office...">
                        @error('label')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('full_name') border-red-500 @enderror" placeholder="John Doe">
                        @error('full_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('phone') border-red-500 @enderror" placeholder="+1 234 567 890">
                        @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email (optional)</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('email') border-red-500 @enderror" placeholder="you@example.com">
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                        <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('address_line_1') border-red-500 @enderror" placeholder="123 Main Street">
                        @error('address_line_1')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (optional)</label>
                        <input type="text" name="address_line_2" id="address_line_2" value="{{ old('address_line_2') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" placeholder="Apt 4B">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('city') border-red-500 @enderror" placeholder="New York">
                        @error('city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <input type="text" name="state" id="state" value="{{ old('state') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('state') border-red-500 @enderror" placeholder="NY">
                        @error('state')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" placeholder="10001">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="country" id="country" value="{{ old('country', 'Ghana') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('country') border-red-500 @enderror" placeholder="Ghana">
                        @error('country')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_default" id="is_default" value="1" class="rounded border-gray-300 text-primary focus:ring-primary"> Set as default address
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAddressModal()" class="border border-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition">Save Address</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openAddressModal() {
        var form = document.getElementById('addressForm');
        form.reset();
        form.action = "{{ route('user.addresses.store') }}";
        document.getElementById('addressMethod').value = 'POST';
        document.getElementById('addressModalTitle').textContent = 'Add New Address';
        document.getElementById('addressModal').classList.remove('hidden');
    }

    function editAddress(btn) {
        var d = btn.dataset;
        var form = document.getElementById('addressForm');
        form.reset();
        form.action = "{{ route('user.addresses.update', '__ID__') }}".replace('__ID__', d.id);
        document.getElementById('addressMethod').value = 'PUT';
        document.getElementById('addressModalTitle').textContent = 'Edit Address';
        document.getElementById('label').value = d.label;
        document.getElementById('full_name').value = d.name;
        document.getElementById('phone').value = d.phone;
        document.getElementById('email').value = d.email;
        document.getElementById('address_line_1').value = d.line1;
        document.getElementById('address_line_2').value = d.line2;
        document.getElementById('city').value = d.city;
        document.getElementById('state').value = d.state;
        document.getElementById('postal_code').value = d.postal;
        document.getElementById('country').value = d.country;
        document.getElementById('is_default').checked = d.default === '1';
        document.getElementById('addressModal').classList.remove('hidden');
    }

    function closeAddressModal() {
        document.getElementById('addressModal').classList.add('hidden');
    }
</script>
@endpush
