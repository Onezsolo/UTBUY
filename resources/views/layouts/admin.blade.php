@extends('layouts.base')

@section('bodyClass', 'bg-slate-100 text-gray-800')

@section('content')
    @include('partials.toast', ['class' => 'bg-admin text-white', 'message' => ''])

    <div class="flex min-h-screen">
        @include('partials.admin-sidebar', ['active' => $active ?? ''])

        <main class="flex-1 overflow-x-hidden">
            @yield('page')
        </main>
    </div>
@endsection
