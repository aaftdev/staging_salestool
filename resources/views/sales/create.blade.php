@extends('layouts.app')

@section('content')
<section class="container">
    @livewire('sale.create')
</section>
@endsection

@section('styles')
    <style>
        .container{
            width: 80%;
        }
    </style>
@endsection