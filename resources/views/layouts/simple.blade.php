@extends('layouts.base')

@section('content')
    @include('partials.toast', ['class' => 'bg-primary text-white', 'message' => 'Item added to cart'])
    @include('partials.simple-header')
    @yield('page')
@endsection
