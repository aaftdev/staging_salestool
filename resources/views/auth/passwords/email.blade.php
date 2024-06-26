@extends('layouts.base')

@section('content')
<div class="log-container">
    <div class="log-container-left">
        <form method="POST" action="{{ route('password.email') }}">
            <div class="logo-box">
                <img src="{{ asset('logo-black.png') }}" alt="logo">
            </div>
            <h1>Reset Password</h1>
            <p>Reset your password to Aaftonline.</p>
            @csrf

            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif

            <div class="form-group">
                <i class="bx bx-envelope"></i>
                <div>
                    <label for="email" class="col-md-12 col-form-label">{{ __('E-Mail Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>


            <div class="row">
                <button type="submit" class="btn btn-danger">
                    {{ __('Reset') }}
                </button>

                @if (Route::has('login'))
                <a class="btn btn-link" href="{{ route('login') }}">
                    {{ __("Don't have an account? Login") }}
                </a>
                @endif
            </div>
        </form>
    </div>
    <div class="log-container-right">
        <img src="{{ asset('login-bg.jpg') }}" alt="">
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection
