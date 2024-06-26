@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-2">Update Profile</h2>
    @if(Session::has('danger'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ Session::get('danger') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <form action="{{ route('admin.profile-update') }}" class="mt-2 row" method="POST">
        @csrf
        <div class="mb-3 col-sm-4 form-group">
            <label for="">Old Password</label>
            <input type="text" name="old_password" class="form-control @error('old_password') is-invalid @enderror" value="{{ old('old_password') }}">
            @error('old_password')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-4 form-group">
            <label for="">New Password</label>
            <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}">
            @error('password')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-4 form-group">
            <label for="">Re Password</label>
            <input type="text" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" value="{{ old('password_confirmation') }}">
            @error('password_confirmation')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-12 form-group d-flex justify-content-end">
            <button class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection