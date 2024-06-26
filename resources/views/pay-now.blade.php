@extends('layouts.base')

@section('meta')
<title>Payment Requested by AAFT Online.</title>
<!-- <meta property="og:image" content="{{ asset('favicon.png') }}" />
<meta property="og:image:secure_url" content="{{ asset('favicon.png') }}" /> -->
<meta property="og:title" content="Payment Requested by AAFT Online" />
<meta property="og:description" content="Learn and master the craft of your dream career under experts with 30+ years of experience." />
<meta property="og:image" content="https://aaftonline.com/sales/public/aaftonline-logo.jpg" />
<style>
    main {
        left: 0px !important;
        width: 70% !important;
        margin: auto !important;
    }

    @media only screen and (max-width: 800px) {
        .col-sm-2 {
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
    @livewire('pay-now')
</section>
<section class="bottom">
        <p>Copyright ©2022 AAFT Elearn Private Limited. All rights reserved.</p>
        <p><a href="https://aaftonline.com/privacy-policy">Privacy & Legal Policies</a></p>
    </section>
@endsection


@section('styles')
<style>
    main{
        height: 100vh;
        max-height: 900px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .bottom{
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .table {
        width: 100%;
        margin: auto;
        margin-bottom: 30px;
    }
    .payment-form{
        width: 70%;
        margin: auto;
    }
    
    @media only screen and (max-width: 600px) {
        main{
            width: 95% !important;
            justify-content: inherit;
        }
        .payment-box {
            width: 100%;
        }
        .search-title{
            text-align: center;
        }
        .bottom{
            padding-top: 100px;
        }
        .payment-form {
            width: 100%;
        }
    }

    @media only screen and (max-width: 800px) {
        .table {
            width: 100%;
        }
    }

</style>
@endsection
