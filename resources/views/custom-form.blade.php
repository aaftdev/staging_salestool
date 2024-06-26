@extends('layouts.app')

@section('content')
<section class="container">
    {{-- <div class="mb-5 row">
        <div class="col-sm-4">
            <div class="p-3 py-4 rounded shadow d-flex align-items-center justify-content-between">
                <h4>Sales Count</h4>
                <h2>{{ \App\Models\Sale::get()->count() }}</h2>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="p-3 py-4 rounded shadow d-flex align-items-center justify-content-between">
                <h4>Total Payment</h4>
                <h2>{{ number_format(\App\Models\Payment::sum('paid')) }}</h2>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="p-3 py-4 rounded shadow d-flex align-items-center justify-content-between">
                <h4>Offer Letter Sent</h4>
                <h2>{{ \App\Models\Sale::where('mail_status', 1)->orWhere('offer_status',1)->get()->count() }}</h2>
            </div>
        </div>
    </div> --}}
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Custom Form</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">

        </div>
    </div>
    @livewire('custom-form')
</section>
@endsection