@extends('layouts.app')

@section('content')
<section class="container">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Tally Student Master</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">

        </div>
    </div>
    @livewire('tally.student')
</section>
@endsection


@section('styles')
<style>
    label {
        font-size: 13px;
    }
</style>
@endsection