@extends('layouts.admin', ['active' => 'payment-gateway'])

@section('title', 'Admin Payment Gateway - Grocery')

@section('page')
    <header class="bg-white shadow-sm px-6 py-4 sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800">Payment Gateway</h1>
    </header>

    <div class="p-6 space-y-6">
        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4 flex items-center gap-2"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif

        @foreach ($gateways as $gateway)
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        @if ($gateway->code === 'paystack')
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-credit-card text-2xl text-blue-600"></i></div>
                            <div><div class="font-bold text-sm">{{ $gateway->name }}</div><div class="text-xs text-gray-500">Online card payment</div></div>
                        @else
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-money-bill-wave text-2xl text-green-600"></i></div>
                            <div><div class="font-bold text-sm">{{ $gateway->name }}</div><div class="text-xs text-gray-500">Pay at doorstep</div></div>
                        @endif
                    </div>
                    <span class="text-xs font-semibold {{ $gateway->is_enabled ? 'text-green-600' : 'text-gray-400' }}">{{ $gateway->is_enabled ? 'Enabled' : 'Disabled' }}</span>
                </div>

                <form method="POST" action="{{ route('admin.payment-gateway.update', $gateway) }}">
                    @csrf @method('PUT')

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
                            <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <label class="flex items-center gap-2 text-sm text-gray-700 mb-4">
                        <input type="checkbox" name="is_enabled" value="1" {{ $gateway->is_enabled ? 'checked' : '' }} class="rounded border-gray-300 text-admin focus:ring-admin"> Enable this payment method
                    </label>

                    @if ($gateway->code === 'paystack')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fee (%)</label>
                                <input type="number" step="0.01" min="0" max="100" name="fee_percent" value="{{ $gateway->fee_percent }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm @error('fee_percent') border-red-500 @enderror">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mode</label>
                                <select name="mode" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm">
                                    <option value="sandbox" {{ ($gateway->config['mode'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                    <option value="live" {{ ($gateway->config['mode'] ?? '') === 'live' ? 'selected' : '' }}>Live</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Public Key</label>
                                <input type="text" name="public_key" value="{{ $gateway->config['public_key'] ?? '' }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                                <input type="password" name="secret_key" value="{{ $gateway->config['secret_key'] ?? '' }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm">
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="bg-admin text-white px-6 py-2.5 rounded-lg text-sm font-medium mt-4 hover:bg-admin-light transition">Save Settings</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
