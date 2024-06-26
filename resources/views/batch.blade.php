@extends('layouts.app')

@section('content')
<section class="container">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1>Batches</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
            <!-- Button trigger modal -->
            @if (Auth::user()->user_type === 'admin')
            <button type="button" class="btn btn-primary d-flex align-items-center justify-content-between shadow" data-bs-toggle="modal" data-bs-target="#batchCreateModal">
                <i class="bx bx-plus"></i> Add Batch
            </button>
            @endif
        </div>
    </div>
    @livewire('batch.index')
    @livewire('batch.create')
    @livewire('batch.edit')
</section>
@endsection


@section('styles')
    <style>
        #sale-filter{
            grid-template-columns: 1fr 1fr 1fr 50px;
        }
    </style>
@endsection