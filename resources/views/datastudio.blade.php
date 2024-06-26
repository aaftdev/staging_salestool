@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
<section class="container" id="dashboard">
    <iframe src="https://datastudio.google.com/embed/reporting/b324118e-b172-4493-a401-a93509484cbb/page/oCusC" frameborder="0" style="border:0; width: 100%; height: 80vh;" allowfullscreen></iframe>
</section>
@endsection
