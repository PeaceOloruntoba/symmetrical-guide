@extends('layouts.app')

@section('content')
    <div style="max-width: 500px; width: 100%;">
        <p class="index_title">Company Registration</p>
        <a class="index_link" href="{{ route('login') }}">Already have an account? <span class="index_span">Login</span></a>

        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

        <form class="mt-5" method="POST" action="{{ route('register.company') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Contact Person Name</label>
                <input type="text" class="form-control px-5 @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}" placeholder="Enter contact person name" required autocomplete="name"
                    autofocus>
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
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" class="form-control px-5 @error('company_name') is-invalid @enderror" id="company_name"
                    name="company_name" value="{{ old('company_name') }}" placeholder="Enter company name" required>
                @error('company_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control px-5 @error('phone') is-invalid @enderror" id="phone" name="phone"
                    value="{{ old('phone') }}" placeholder="Enter phone number">
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="website" class="form-label">Company Website</label>
                <input type="url" class="form-control px-5 @error('website') is-invalid @enderror" id="website"
                    name="website" value="{{ old('website') }}" placeholder="Enter company website">
                @error('website')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Company Address</label>
                <input type="text" class="form-control px-5 @error('address') is-invalid @enderror" id="address"
                    name="address" value="{{ old('address') }}" placeholder="Enter company address">
                @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Company Description</label>
                <textarea class="form-control px-5 @error('description') is-invalid @enderror" id="description"
                    name="description" rows="3" placeholder="Enter company description">{{ old('description') }}</textarea>
                @error('description')
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
            <button type="submit" class="btn index_submit">Register Company</button>
        </form>
    </div>
@endsection