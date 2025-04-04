@extends('layouts.app')

@section('content')
    <div>
        <p class="index_title">Login</p>
        <a class="index_link" href="{{ route('register') }}">No account? <span class="index_span">Register</span></a>

        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

        <form class="mt-5" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control px-5 @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control px-5 @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Enter your password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="d-flex justify-content-between mb-5">
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input me-3" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <div>
                    <a href="{{ route('password.request') }}" id="index_forgetPasswordLink">Forgot Password?</a>
                </div>
            </div>
            <button type="submit" class="btn index_submit">Login</button>
        </form>
    </div>
@endsection