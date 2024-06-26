@extends('layouts.app')

@section('content')
<section class="container">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1>Complemantary Programs</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary d-flex align-items-center justify-content-between shadow" data-bs-toggle="modal" data-bs-target="#batchCreateModal">
                <i class="bx bx-plus"></i> Add Complemantary Program
            </button>
        </div>
    </div>
    @livewire('complemantries.index')
    @livewire('complemantries.create')
    @livewire('complemantries.edit')
</section>
@endsection