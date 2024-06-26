@extends('layouts.base')

@section('content')
<div class="log-container">
    <div class="log-container-left">
        <form method="POST" action="{{ route('login') }}">
            <div class="logo-box">
                <img src="{{ asset('logo-black.png') }}" alt="logo">
            </div>
            <h1>Welcome Back !</h1>
            <p>Sign in to continue to Aaftonline.</p>
            @csrf

            <div class="form-group">
                <i class="bx bx-user"></i>
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

            <div class="form-group">
                <i class='bx bx-lock'></i>
                <div>
                    <label for="password" class="col-md-12 col-form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

            <div class="row">
                <button type="submit" class="btn btn-danger">
                    {{ __('Log In') }}
                </button>

                @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    <i class="bx bx-lock"></i> {{ __('Forgot Your Password?') }}
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
