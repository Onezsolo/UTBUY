@extends('layouts.user', ['active' => 'profile'])

@section('title', 'My Profile - Grocery')

@section('page')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h1>
    <div class="bg-white rounded-xl card-shadow p-6 mb-6">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fas fa-user-edit text-primary"></i> Personal Information</h3>
        <form onsubmit="handleProfileUpdate(event)">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="John">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="Doe">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="john@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="+1 234 567 890">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="1990-05-15">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary">
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-lg font-semibold mt-6 hover:opacity-90 transition">Save Changes</button>
        </form>
    </div>
    <div class="bg-white rounded-xl card-shadow p-6 mb-6">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fas fa-map-marker-alt text-primary"></i> Delivery Address</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                <input type="text" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="123 Main Street, Apt 4B">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <input type="text" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="New York">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                <input type="text" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="NY">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ZIP Code</label>
                <input type="text" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="10001">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                <input type="text" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary" value="United States">
            </div>
        </div>
        <button class="btn-primary text-white px-6 py-2.5 rounded-lg font-semibold mt-6 hover:opacity-90 transition">Update Address</button>
    </div>
    <div class="bg-white rounded-xl card-shadow p-6">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fas fa-lock text-primary"></i> Change Password</h3>
        <form onsubmit="handlePasswordChange(event)">
            <div class="max-w-md space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary">
                </div>
                <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-lg font-semibold hover:opacity-90 transition">Update Password</button>
            </div>
        </form>
    </div>
@endsection
