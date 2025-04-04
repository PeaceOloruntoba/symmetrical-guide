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

        @if (session('status'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-5" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-5">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control px-5 @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn index_submit">Send Password Reset Link</button>
        </form>
    </div>
@endsection