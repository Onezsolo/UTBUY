@extends('layouts.guest')

@section('title', 'Forgot Password - Grocery')

@section('page')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                        <i class="fas fa-leaf text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-primary">Grocery</span>
                </a>
                <h2 class="text-3xl font-bold text-gray-800">Forgot Password</h2>
                <p class="text-gray-500 mt-2">Enter your email to reset your password</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <form onsubmit="handleForgotPassword(event)">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="email" required class="input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-primary" placeholder="you@example.com">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary w-full text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">Send Reset Link</button>
                </form>
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-primary font-medium hover:underline flex items-center justify-center gap-1">
                        <i class="fas fa-arrow-left text-xs"></i> Back to Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
