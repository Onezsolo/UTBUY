@extends('layouts.base')

@section('title', 'Admin Forgot Password - Grocery')
@section('bodyClass', 'bg-slate-100 text-gray-800')

@section('content')
    <div id="toast" class="fixed top-4 right-4 z-[9999] hidden">
        <div class="toast bg-admin text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span id="toastMessage"></span>
        </div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 bg-admin rounded-lg flex items-center justify-center">
                        <i class="fas fa-leaf text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-admin">Grocery</span>
                </a>
                <div class="inline-flex items-center gap-2 bg-admin/10 text-admin text-xs font-semibold px-3 py-1 rounded-full mb-3">
                    <i class="fas fa-lock"></i> ADMIN PORTAL
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Reset Password</h2>
                <p class="text-gray-500 mt-2">Enter your admin email to receive a reset link</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <form onsubmit="handleAdminForgot(event)">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="email" required class="admin-input input-field w-full border border-gray-200 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-admin" placeholder="admin@example.com">
                        </div>
                    </div>
                    <button type="submit" class="admin-grad w-full text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">Send Reset Link</button>
                </form>
                <div class="mt-6 text-center">
                    <a href="{{ route('admin.login') }}" class="text-sm text-admin font-medium hover:underline flex items-center justify-center gap-1">
                        <i class="fas fa-arrow-left text-xs"></i> Back to Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showToast(msg) {
        var t = document.getElementById('toast');
        document.getElementById('toastMessage').textContent = msg;
        t.classList.remove('hidden');
        setTimeout(function(){ t.classList.add('hidden'); }, 3000);
    }
    function handleAdminForgot(e) {
        e.preventDefault();
        showToast('Reset link sent to your email!');
    }
</script>
@endpush
