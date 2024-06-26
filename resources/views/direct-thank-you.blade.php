@extends('layouts.base')

@section('content')
<div class="container pt-5" id="thankyou">
    <div class="check-icon">
        <i class='bx bx-check-circle'></i>
    </div>
    <h1 class="title">
        Payment Complete
    </h1>
    <p class="caption">
        Thank you, your fees has been received !!
    </p>
    <div class="description">
        <h4>Payment Details</h4>
        <table class="table">
            <tr>
                <th>Transaction ID</th>
                <td>{{$payment->txnid}}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $payment->name }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{$payment->contact}}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><i class="bx bx-rupee"></i> {{ $payment->paid }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ Carbon\Carbon::parse($payment->created_at)->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <th>State</th>
                <td>{{$payment->state }}</td>
            </tr>
            <tr>
                <th>Program Name</th>
                <td>{{ \App\Models\Program::find($payment->course)->name }}</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td>Success</td>
            </tr>
        </table>
    </div>
    <div class="text-center">
        <button id="btn" onclick="print()">Print</button>
    </div>
</div>
@endsection



@section('scripts')
<script>
    print() {
        $('#thankyou').print();
    }

</script>
@endsection
