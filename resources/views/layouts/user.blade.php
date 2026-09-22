@extends('layouts.base')

@section('content')
    @include('partials.toast', ['class' => 'bg-primary text-white', 'message' => 'Item added to cart'])
    @include('partials.simple-header')

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            @include('partials.user-sidebar', ['active' => $active ?? ''])
            <div class="lg:col-span-3">
                @yield('page')
            </div>
        </div>
    </div>
@endsection
