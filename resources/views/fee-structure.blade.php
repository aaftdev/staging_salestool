@extends('layouts.app')

@section('content')
<section class="container">
    <div class="row mb-5">
        <div class="col-sm-6">
            <h1>Fee Structure</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary shadow d-flex align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target="#feeCreateModal">
                <i class="bx bx-plus"></i> Add Fee Structure
            </button>
        </div>
    </div>
    @livewire('fee-structure.index')
    @livewire('fee-structure.create')
    @livewire('fee-structure.edit')
</section>
@endsection

@section('styles')
    <style>
        #sale-filter{
            grid-template-columns: 1fr 1fr 50px;
        }
    </style>
@endsection