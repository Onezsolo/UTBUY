@extends('layouts.simple')

@section('title', 'Checkout - Grocery')

@section('page')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Checkout</h1>

        @if ($items->isEmpty())
            <div class="bg-white rounded-xl card-shadow p-10 text-center">
                <p class="text-gray-500 mb-4">Your cart is empty.</p>
                <a href="{{ route('products') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold inline-block">Start Shopping</a>
            </div>
        @else
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
                                <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <!-- Shipping Information -->
                        <div class="bg-white rounded-xl card-shadow p-6">
                            <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fas fa-map-marker-alt text-primary"></i> Shipping Information</h3>

                            @if ($addresses->isNotEmpty())
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Saved Address</label>
                                    <select id="savedAddress" onchange="fillAddress(this)" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary">
                                        @foreach ($addresses as $address)
                                            <option value="{{ $address->id }}"
                                                data-name="{{ $address->full_name }}"
                                                data-phone="{{ $address->phone }}"
                                                data-line1="{{ $address->address_line_1 }}"
                                                data-line2="{{ $address->address_line_2 }}"
                                                data-city="{{ $address->city }}"
                                                data-state="{{ $address->state }}"
                                                data-country="{{ $address->country }}"
                                                {{ $address->is_default ? 'selected' : '' }}>
                                                {{ $address->label }} — {{ $address->address_line_1 }}, {{ $address->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $defaultAddress->full_name ?? auth()->user()->name ?? '') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('full_name') border-red-500 @enderror">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $defaultAddress->phone ?? '') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('phone') border-red-500 @enderror">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $defaultAddress->city ?? '') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('city') border-red-500 @enderror">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                    <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1', $defaultAddress->address_line_1 ?? '') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('address_line_1') border-red-500 @enderror">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (optional)</label>
                                    <input type="text" name="address_line_2" id="address_line_2" value="{{ old('address_line_2', $defaultAddress->address_line_2 ?? '') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                    <input type="text" name="state" id="state" value="{{ old('state', $defaultAddress->state ?? '') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary @error('state') border-red-500 @enderror">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                    <input type="text" name="country" id="country" value="{{ old('country', $defaultAddress->country ?? 'Ghana') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white rounded-xl card-shadow p-6">
                            <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fas fa-credit-card text-primary"></i> Payment Method</h3>
                            <div class="space-y-3">
                                @forelse ($gateways as $gateway)
                                    <label class="flex items-center gap-3 p-4 border-2 {{ $loop->first ? 'border-primary bg-primary-light' : 'border-gray-200' }} rounded-lg cursor-pointer">
                                        <input type="radio" name="payment_method" value="{{ $gateway->code }}" {{ $loop->first ? 'checked' : '' }} required class="text-primary focus:ring-primary">
                                        <i class="{{ $gateway->code === 'paystack' ? 'fas fa-credit-card text-primary' : 'fas fa-money-bill-wave text-green-500' }} text-lg"></i>
                                        <div class="flex-1">
                                            <div class="font-semibold text-sm">{{ $gateway->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                @if ($gateway->code === 'paystack')
                                                    Pay securely with card or bank transfer@if ((float) $gateway->fee_percent > 0) · {{ (float) $gateway->fee_percent }}% fee applies@endif
                                                @else
                                                    Pay at doorstep
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-400">No payment methods available.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div>
                        <div class="bg-white rounded-xl card-shadow p-6 sticky top-40">
                            <h3 class="font-bold text-lg mb-4">Order Summary</h3>
                            <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                                @foreach ($items as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-700">{{ $item->product->name }} <span class="text-gray-400">× {{ $item->quantity }}</span></span>
                                        <span class="font-medium">₵{{ number_format($item->quantity * (float) $item->price_at_add, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @php $subtotal = (float) $items->sum(fn ($i) => $i->quantity * (float) $i->price_at_add); @endphp
                            <div class="border-t border-gray-200 pt-4 space-y-2 text-sm">
                                <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span class="font-medium">₵{{ number_format($subtotal, 2) }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Shipping</span><span class="font-medium text-green-600">Free</span></div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                    <span class="font-bold text-lg">Total</span>
                                    <span class="font-bold text-2xl text-primary">₵{{ number_format($subtotal, 2) }}</span>
                                </div>
                            </div>
                            <button type="submit" class="btn-primary w-full text-white py-3 rounded-lg font-semibold mt-6 hover:opacity-90 transition flex items-center justify-center gap-2">
                                <i class="fas fa-lock"></i> Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function fillAddress(select) {
        var opt = select.options[select.selectedIndex];
        if (!opt.value) return;
        document.getElementById('full_name').value = opt.dataset.name;
        document.getElementById('phone').value = opt.dataset.phone;
        document.getElementById('city').value = opt.dataset.city;
        document.getElementById('address_line_1').value = opt.dataset.line1;
        document.getElementById('address_line_2').value = opt.dataset.line2;
        document.getElementById('state').value = opt.dataset.state;
        document.getElementById('country').value = opt.dataset.country;
    }
</script>
@endpush
