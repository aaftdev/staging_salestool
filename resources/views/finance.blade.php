@extends('layouts.base')

@section('content')
<div class="img-box text-center mt-4">
    <img src="{{ asset('logo-black.png') }}" alt="logo" style="height: 50px;" class="img-fluid">
</div>
<section class="container mt-5">
    <div class="payment-box">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="mb-4">
                    @if (\App\Models\Program::find($sale->program_id))
                    {{\App\Models\Program::find($sale->program_id)->name}}
                    @endif Fees Payment</h1>
            </div>
        </div>

        @livewire('sale.finance', ['sale' => $sale], key($sale->id))
    </div>
</section>
@endsection


@section('styles')
    <style>
        .payment-box{
            width: 70%;
            margin: auto;

        }
        .table{
            width: 100%;
            margin: auto;
            margin-bottom: 30px;
        }
        @media only screen and (max-width: 600px){
            .payment-box{
                width: 100%;
            }
        }
        @media only screen and (max-width: 800px){
            .table{
                width: 100%;
            }
        }
    </style>
@endsection
