@extends('layouts.app')

@section('content')
    <div>
        <p class="index_title">Reset Password</p>
        <a class="index_link" href="{{ route('login') }}">Back to <span class="index_span">Login</span></a>

        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

        <form class="mt-5" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control px-5 @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ $email ?? old('email') }}" placeholder="Enter your email" required autocomplete="email"
                    autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control px-5 @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Enter new password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="password-confirm" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control px-5" id="password-confirm" name="password_confirmation"
                    placeholder="Enter new password again" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn index_submit">Reset Password</button>
        </form>
    </div>
@endsection