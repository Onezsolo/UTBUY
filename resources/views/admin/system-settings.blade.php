@extends('layouts.admin', ['active' => 'system-settings'])

@section('title', 'Admin System Settings - Grocery')

@section('page')
    <header class="bg-white shadow-sm px-6 py-4 sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800">System Settings</h1>
    </header>

    <div class="p-6 space-y-6">
        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4 flex items-center gap-2"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif

        <div class="bg-white rounded-xl card-shadow p-6">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fas fa-sliders-h text-primary"></i> General Settings</h3>
            <form method="POST" action="{{ route('admin.system-settings.update') }}">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
                        <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $settings['store.name'] ?? 'UTBUY') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm @error('store_name') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site Tagline</label>
                        <input type="text" name="store_tagline" value="{{ old('store_tagline', $settings['store.tagline'] ?? '') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Admin Email</label>
                        <input type="email" name="store_email" value="{{ old('store_email', $settings['store.email'] ?? '') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm @error('store_email') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Support Phone</label>
                        <input type="text" name="store_phone" value="{{ old('store_phone', $settings['store.phone'] ?? '') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site Address</label>
                        <input type="text" name="store_address" value="{{ old('store_address', $settings['store.address'] ?? '') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <select name="store_currency" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin text-sm">
                            @foreach (['GHS' => 'GHS - Ghana Cedi (₵)', 'NGN' => 'NGN - Nigerian Naira (₦)', 'USD' => 'USD - US Dollar ($)'] as $code => $label)
                                <option value="{{ $code }}" {{ ($settings['store.currency'] ?? 'GHS') === $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="bg-admin text-white px-6 py-2.5 rounded-lg text-sm font-medium mt-6 hover:bg-admin-light transition">Save Changes</button>
            </form>
        </div>

        <div class="bg-white rounded-xl card-shadow p-6 border border-orange-200">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2 text-orange-500"><i class="fas fa-tools"></i> Maintenance Mode</h3>
            <a href="{{ route('admin.maintenance') }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 text-sm font-medium hover:text-blue-700 hover:underline"><i class="fas fa-external-link-alt"></i> Preview Maintenance Page</a>
            <p class="text-xs text-gray-400 mt-1">Use <code class="bg-gray-100 px-1 rounded">php artisan down</code> / <code class="bg-gray-100 px-1 rounded">php artisan up</code> to toggle maintenance mode.</p>
        </div>
    </div>
@endsection
