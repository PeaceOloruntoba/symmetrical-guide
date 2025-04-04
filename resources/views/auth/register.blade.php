@extends('layouts.app')

@section('content')
    <div>
        <p class="index_title">Register</p>
        <a class="index_link" href="{{ route('login') }}">Already have an account? <span class="index_span">Login</span></a>

        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

        <form class="mt-5" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control px-5 @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}" placeholder="Enter your name" required autocomplete="name" autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control px-5 @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control px-5 @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Enter your password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <input type="password" class="form-control px-5" id="password-confirm" name="password_confirmation"
                    placeholder="Enter your password again" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn index_submit">Register</button>
        </form>
    </div>
@endsection