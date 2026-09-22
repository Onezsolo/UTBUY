@extends('layouts.shop')

@section('title', 'Support - Grocery')

@section('page')
    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Support & Contact</h1>
            <p class="text-gray-500 mt-2">Reach us through any of the channels below.</p>
        </div>

        @if ($grouped['whatsapp_group']->isNotEmpty())
            <section class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fab fa-whatsapp text-green-600"></i> WhatsApp Groups</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($grouped['whatsapp_group'] as $link)
                        <a href="{{ $link->url }}" target="_blank" rel="noopener" class="bg-white rounded-xl card-shadow p-5 flex items-center gap-4 hover:shadow-lg transition">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center"><i class="{{ $link->icon ?: 'fab fa-whatsapp' }} text-green-600 text-xl"></i></div>
                            <div><div class="font-semibold text-gray-800">{{ $link->title }}</div><div class="text-sm text-gray-500">Join the group</div></div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($grouped['social']->isNotEmpty())
            <section class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-share-alt text-blue-600"></i> Social Media</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($grouped['social'] as $link)
                        <a href="{{ $link->url }}" target="_blank" rel="noopener" class="bg-white rounded-xl card-shadow p-5 flex items-center gap-4 hover:shadow-lg transition">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center"><i class="{{ $link->icon ?: 'fas fa-globe' }} text-blue-600 text-xl"></i></div>
                            <div class="font-semibold text-gray-800">{{ $link->title }}</div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($grouped['other']->isNotEmpty())
            <section>
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-circle-info text-primary"></i> Other Support</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($grouped['other'] as $link)
                        <a href="{{ $link->url }}" target="_blank" rel="noopener" class="bg-white rounded-xl card-shadow p-5 flex items-center gap-4 hover:shadow-lg transition">
                            <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center"><i class="{{ $link->icon ?: 'fas fa-link' }} text-primary text-xl"></i></div>
                            <div class="font-semibold text-gray-800">{{ $link->title }}</div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($grouped['whatsapp_group']->isEmpty() && $grouped['social']->isEmpty() && $grouped['other']->isEmpty())
            <div class="text-center text-gray-400 py-10">No support information available yet.</div>
        @endif
    </div>
@endsection
