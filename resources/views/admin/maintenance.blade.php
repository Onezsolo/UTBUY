@extends('layouts.base')

@section('title', 'Under Maintenance - Grocery')
@section('bodyClass', 'bg-slate-100 min-h-screen flex items-center justify-center p-4')

@push('styles')
<style>
    * { font-family: 'Inter', sans-serif; }
    .tool-icon { animation: float 3s ease-in-out infinite; }
    .tool-icon:nth-child(2) { animation-delay: 0.5s; }
    .tool-icon:nth-child(3) { animation-delay: 1s; }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
    .gear { animation: spin 8s linear infinite; }
    .gear-slow { animation: spin 12s linear infinite reverse; }
    @keyframes spin { 100% { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
    <div class="max-w-lg w-full text-center">
        <!-- Animated Icons -->
        <div class="flex justify-center gap-6 mb-8">
            <div class="tool-icon w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-tools text-4xl text-orange-500"></i>
            </div>
            <div class="tool-icon w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-wrench text-4xl text-blue-500"></i>
            </div>
            <div class="tool-icon w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-gear text-4xl text-gray-500 gear-slow"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-gear text-2xl text-orange-500 gear"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-3">We'll Be Right Back</h1>
            <p class="text-gray-500 mb-2 text-lg">Under Maintenance</p>
            <p class="text-gray-400 mb-8 leading-relaxed">
                We're currently performing scheduled maintenance to improve your experience.
                We'll be back online shortly. Thank you for your patience!
            </p>

            <!-- Estimated Time -->
            <div class="bg-gray-50 rounded-xl p-4 mb-8 inline-block">
                <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                    <i class="fas fa-clock text-primary"></i>
                    <span>Estimated downtime</span>
                </div>
                <div class="text-2xl font-bold text-primary">2 Hours</div>
                <div class="text-xs text-gray-400 mt-1">Starting from 02:00 AM UTC</div>
            </div>

            <!-- Progress Bar -->
            <div class="max-w-xs mx-auto mb-6">
                <div class="flex justify-between text-xs text-gray-500 mb-2">
                    <span>Progress</span>
                    <span id="progressPercent">65%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-primary h-3 rounded-full transition-all duration-1000" style="width: 65%"></div>
                </div>
            </div>

            <p class="text-sm text-gray-400">
                Need help? Contact us at <a href="mailto:support@grocery.com" class="text-primary font-medium hover:underline">support@grocery.com</a>
            </p>
        </div>

        <div class="mt-6 text-sm text-gray-400">
            &copy; 2026 Grocery. All Rights Reserved.
        </div>
    </div>
@endsection
