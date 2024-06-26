@extends('layouts.base')

@section('meta')
<title>Payment for {{ \App\Models\Program::find($sale->program_id)->name }} requested by AAFT Online.</title>
<!-- <meta property="og:image" content="{{ asset('favicon.png') }}" /> -->
<!-- <meta property="og:image:secure_url" content="{{ asset('favicon.png') }}" /> -->
<!-- <meta property="og:image" content="https://aaftonline.com/sales/public/aaftonline-logo.jpg" /> -->
<meta property="og:title" content="Payment Requested by AAFT Online" />
<meta property="og:description" content="Learn and master the craft of your dream career under experts with 30+ years of experience." />
<meta property="og:image" content="https://aaftonline.com/sales/public/aaftonline-logo.jpg" />
<style>
    main{
        left: 0px !important;
        width: 95% !important;
        margin: auto !important;
    }
    @media only screen and (max-width: 800px){
        .col-sm-2{
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
    }
</style>
@endsection

@section('content')
<div class="mt-4 text-center img-box">
    <img src="{{ asset('logo-black.png') }}" alt="logo" style="height: 50px;" class="img-fluid">
</div>
<section class="container mt-5">
    <div class="payment-box">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="mb-4">@if (\App\Models\Program::find($sale->program_id))
                    {{ \App\Models\Program::find($sale->program_id)->name }}
                @endif Fees Payment</h1>
            </div>
        </div>

        @if ($sale->status !== 2)
            @livewire('sale.payment', ['sale' => $sale], key($sale->id))
        @else
        <div class="text-center alert alert-primary" role="alert">
            Offer Expired !!
          </div>
        @endif
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
