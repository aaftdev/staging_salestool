@extends('layouts.app')

@section('content')
<section class="container">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Sales</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">

        </div>
    </div>
    @livewire('sale.enrolled')
</section>
@endsection


@section('styles')
<style>
    .table thead tr th {
        font-size: 12px;
    }

    .table tbody tr td {
        font-size: 12px;
        color: grey;
    }

    .table tbody tr.bg-success{
        position: relative
    }

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
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
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