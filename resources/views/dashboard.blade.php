@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
<section class="container" id="dashboard">
    @livewire('dashboard')
</section>
@endsection
