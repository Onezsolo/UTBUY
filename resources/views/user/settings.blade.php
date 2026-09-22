@extends('layouts.user', ['active' => 'settings'])

@section('title', 'Settings - Grocery')

@section('page')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Account Settings</h1>

    <!-- Notifications -->
    <div class="bg-white rounded-xl card-shadow p-6 mb-6">
        <h3 class="font-bold text-lg mb-6 flex items-center gap-2"><i class="fas fa-bell text-primary"></i> Notifications</h3>
        <div class="space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-sm text-gray-800">Order Updates</h4>
                    <p class="text-xs text-gray-500">Get notified about your order status changes</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-sm text-gray-800">Promotional Emails</h4>
                    <p class="text-xs text-gray-500">Receive emails about deals and promotions</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Privacy -->
    <div class="bg-white rounded-xl card-shadow p-6 mb-6">
        <h3 class="font-bold text-lg mb-6 flex items-center gap-2"><i class="fas fa-shield-alt text-primary"></i> Privacy & Security</h3>
        <div class="space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-sm text-gray-800">Two-Factor Authentication</h4>
                    <p class="text-xs text-gray-500">Add an extra layer of security to your account</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-sm text-gray-800">Activity Log</h4>
                    <p class="text-xs text-gray-500">Keep track of your account activity</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Language & Currency -->
    <div class="bg-white rounded-xl card-shadow p-6 mb-6">
        <h3 class="font-bold text-lg mb-6 flex items-center gap-2"><i class="fas fa-globe text-primary"></i> Language & Currency</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                <select class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary text-sm">
                    <option>English (US)</option>
                    <option>English (UK)</option>
                    <option>Spanish</option>
                    <option>French</option>
                    <option>German</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                <select class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary text-sm">
                    <option selected>GHS - Ghana Cedi (₵)</option>
                    <option>USD - US Dollar ($)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-xl card-shadow p-6 border border-red-200">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2 text-red-500"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h3>
        <p class="text-sm text-gray-500 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
        <div class="flex flex-col sm:flex-row gap-3">
            <button class="border border-red-300 text-red-500 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-red-50 transition flex items-center justify-center gap-2">
                <i class="fas fa-download"></i> Export My Data
            </button>
            <button class="bg-red-500 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-red-600 transition flex items-center justify-center gap-2">
                <i class="fas fa-trash-alt"></i> Delete Account
            </button>
        </div>
    </div>
@endsection
