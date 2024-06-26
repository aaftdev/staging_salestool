@extends('layouts.app')

@section('content')
<section class="container">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Direct Payment</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">

        </div>
    </div>
    @livewire('online-payment')
</section>
@endsection


@section('styles')
    <style>
        /* .table thead tr th{
            font-size: 12px;
        }
        .table tbody tr td{
            font-size: 12px;
        } */
        #sale-filter{
            grid-template-columns: repeat(6, 1fr) !important;
        }
        #verify_offer{
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 20;
            background-color: rgba(255,255,255,0.95);
            padding: 5rem 0rem;
        }
    </style>
@endsection


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('.copy-to-clip').click(function(){
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
