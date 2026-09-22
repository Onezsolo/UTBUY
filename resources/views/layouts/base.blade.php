<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grocery')</title>
    <meta name="theme-color" content="#0d7c66">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Grocery">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d7c66',
                        'primary-dark': '#0a6350',
                        'primary-light': '#e8f5f0',
                        secondary: '#f0fdf4',
                        accent: '#ff6b35',
                        'accent-light': '#fff0eb',
                        dark: '#1a1a2e',
                        admin: '#1e293b',
                        'admin-light': '#334155',
                        'admin-dark': '#0f172a',
                        'gray-50': '#f9fafb',
                        'gray-100': '#f3f4f6',
                        'gray-200': '#e5e7eb',
                        'gray-300': '#d1d5db',
                        'gray-400': '#9ca3af',
                        'gray-500': '#6b7280',
                        'gray-600': '#4b5563',
                        'gray-700': '#374151',
                        'gray-800': '#1f2937',
                        'gray-900': '#111827',
                    }
                }
            }
        }
    </script>
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="@yield('bodyClass', 'bg-gray-50 text-gray-800')">

    @yield('content')

    @php $popup = session('show_popup') ? \App\Models\PopupMessage::active()->first() : null; @endphp
    @if ($popup)
        <div id="popupOverlay" class="fixed inset-0 bg-black/60 z-[9999] flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative">
                <button type="button" onclick="closePopup()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4"><i class="fas fa-bell text-primary text-xl"></i></div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $popup->title }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $popup->message }}</p>
                <button type="button" onclick="closePopup()" class="bg-primary text-white w-full py-2.5 rounded-lg mt-6 font-semibold hover:opacity-90 transition">Got it</button>
            </div>
        </div>
        <script>function closePopup(){ document.getElementById('popupOverlay').style.display = 'none'; }</script>
    @endif

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js').then(function () {}).catch(function () {});
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
