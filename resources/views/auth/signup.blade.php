@extends('layouts.guest')

@section('title', 'Sign Up - Grocery')

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
                <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
                <p class="text-gray-500 mt-2">Join us for fresh groceries delivered</p>
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
                <form method="POST" action="{{ route('signup.store') }}">
                    @csrf
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required class="input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-primary @error('first_name') border-red-500 @enderror" placeholder="John">
                            </div>
                            @error('first_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required class="input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-primary @error('last_name') border-red-500 @enderror" placeholder="Doe">
                            </div>
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required class="input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-primary @error('email') border-red-500 @enderror" placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required class="input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-primary @error('phone') border-red-500 @enderror" placeholder="+1 234 567 890">
                        </div>
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="password" name="password" required class="input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-primary @error('password') border-red-500 @enderror" placeholder="Min 8 characters">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="password" name="password_confirmation" required class="input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-primary @error('password_confirmation') border-red-500 @enderror" placeholder="Re-enter password">
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <label class="flex items-start gap-2 mb-6 text-sm text-gray-600">
                        <input type="checkbox" name="terms" value="1" required class="mt-1 rounded border-gray-300 text-primary focus:ring-primary @error('terms') border-red-500 @enderror">
                        <span>I agree to the <a href="#" class="text-primary font-medium">Terms & Conditions</a> and <a href="#" class="text-primary font-medium">Privacy Policy</a></span>
                    </label>
                    @error('terms')
                        <p class="mb-4 -mt-4 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn-primary w-full text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">Create Account</button>
                </form>
                <p class="text-center text-sm text-gray-500 mt-6">Already have an account? <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Sign In</a></p>
            </div>
        </div>
    </div>
@endsection
