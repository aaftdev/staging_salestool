@extends('layouts.app')

@section('content')
<section class="container">
    @livewire('sale.edit', ['sale' => $sale], key($sale->id))
</section>
@endsection


@section('styles')
    <style>
        .container{
            width: 80%;
        }
    </style>
@endsection