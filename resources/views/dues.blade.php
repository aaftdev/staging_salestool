@extends('layouts.app')

@section('content')
<section class="container">
    @livewire('sale.due')
</section>
@endsection


@section('styles')
    <style>
        #sale-filter{
            grid-template-columns: repeat(5, 1fr);
        }
        .generate-btn{
            display: flex;
            justify-content: flex-end;
        }
        #popup .container{
            background-color: #fff;
            color: #333;
            padding: 1rem;
            border-radius: 0.5rem;
        }
    </style>
@endsection