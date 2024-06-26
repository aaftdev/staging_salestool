@extends('layouts.app')

@section('content')
<section class="container">
    <div class="mb-3 row">
        <div class="col-sm-6">
            <h1>Payment Check</h1>
        </div>
    </div>

    <form action="{{ route('admin.payment.check') }}" class="form">
        @csrf
        <div class="form-group">
            <label for="">Txnid</label>
            <input type="text" name="txnid" class="form-control" value="{{ old('txnid') }}">
        </div>
        <div class="form-group">
            <label for="" class="invisble">Submit</label>
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    
    
</section>
@endsection


@section('styles')
    <style>
        label{
            display: block;
        }
        .form{
            display: grid;
            grid-template-columns: 1fr 100px;
        }
        .form-control{
            width: 98%;
        }
        .invisble{
            visibility: hidden;
        }
    </style>
@endsection