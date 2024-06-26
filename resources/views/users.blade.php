@extends('layouts.app')

@section('content')
<section class="container">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Users</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary d-flex align-items-center justify-content-between shadow" data-bs-toggle="modal" data-bs-target="#UserCreateModal">
                <i class="bx bx-plus"></i> Add User
            </button>
        </div>
    </div>
    @livewire('user.index')
    @livewire('user.create')
    @livewire('user.edit')
</section>
@endsection