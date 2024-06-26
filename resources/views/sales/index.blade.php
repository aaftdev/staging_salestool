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
            <h1>Offers</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">

        </div>
    </div>
    @livewire('sale.index')
</section>
@endsection


@section('styles')
<style>
    label {
        font-size: 13px;
    }

    .modal.show .modal-dialog {
        transform: none;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 100vh !important;
    }
    #sale-filter{
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
    }

    @media only screen and (max-width:480px){
        #sale-filter{
            grid-template-columns: 1fr 1fr;
        }
    }
</style>
@endsection


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.copy-to-clip').click(function() {
            let link = $(this).data('label');
            navigator.clipboard.writeText(link)
                .then(() => {
                    alert("Link copied to clipboard...")
                })
                .catch(err => {
                    alert('Something went wrong', err);
                })
        });
    });

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("datefield").setAttribute("max", today);
    document.getElementById("datefield1").setAttribute("max", today);
</script>
@endsection