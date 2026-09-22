<div id="toast" class="fixed top-4 right-4 z-[9999] hidden">
    <div class="toast {{ $class ?? 'bg-primary text-white' }} px-6 py-3 rounded-lg shadow-lg flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage">{{ $message ?? 'Item added to cart' }}</span>
    </div>
</div>
