@extends('layouts.guest')

@section('title', 'Login - Grocery')

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
                <h2 class="text-3xl font-bold text-gray-800">Welcome Back</h2>
                <p class="text-gray-500 mt-2">Sign in to your account to continue</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8">
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus class="input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-primary @error('email') border-red-500 @enderror" placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="password" name="password" required class="input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-primary @error('password') border-red-500 @enderror" placeholder="Enter your password">
                            <button type="button" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary"> Remember me
                        </label>
                        <a href="{{ route('forgot-password') }}" class="text-sm text-primary font-medium hover:underline">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn-primary w-full text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">Sign In</button>
                </form>
                <p class="text-center text-sm text-gray-500 mt-6">Don't have an account? <a href="{{ route('signup') }}" class="text-primary font-semibold hover:underline">Sign Up</a></p>
            </div>
        </div>
    </div>
@endsection
