@extends('layouts.guest')

@section('title', 'Order Success - Grocery')

@section('page')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
        <div class="max-w-lg w-full text-center">
            <div class="bg-white rounded-2xl card-shadow p-12">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check text-green-500 text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Order Placed Successfully!</h1>
                <p class="text-gray-500 mb-6">Thank you for your purchase. Your order has been confirmed and will be delivered soon.</p>
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="text-sm text-gray-500 mb-1">Order Number</div>
                    <div class="text-xl font-bold text-primary">#GR-2026-78542</div>
                </div>
                <div class="space-y-3 text-left mb-8">
                    <div class="flex items-center gap-3 text-sm">
                        <i class="fas fa-envelope text-primary"></i>
                        <span class="text-gray-600">Confirmation email sent to <strong>john@example.com</strong></span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i class="fas fa-truck text-primary"></i>
                        <span class="text-gray-600">Estimated delivery: <strong>July 28-30, 2026</strong></span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i class="fas fa-credit-card text-primary"></i>
                        <span class="text-gray-600">Payment: <strong>Card ending in 4242</strong></span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('user.dashboard') }}" class="flex-1 border-2 border-primary text-primary py-3 rounded-lg font-semibold hover:bg-primary-light transition">
                        <i class="fas fa-user mr-1"></i> My Dashboard
                    </a>
                    <a href="{{ route('home') }}" class="flex-1 btn-primary text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                        <i class="fas fa-shopping-bag mr-1"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
