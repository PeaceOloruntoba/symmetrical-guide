@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-column">
                            <h1 class="display-5 fw-bold text-success mb-2" style="color: #31572C !important;">Company Registration</h1>
                        </div>
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
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="Enter contact person name" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" 
                                    name="company_name" value="{{ old('company_name') }}" placeholder="Enter company name" required>
                                @error('company_name')
                                    <span class="invalid-feedback">
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
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                                    name="description" placeholder="Enter company description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                                    name="password" placeholder="Enter your password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password-confirm" name="password_confirmation"
                                    placeholder="Enter your password again" required autocomplete="new-password">
                            </div>
                            <button type="submit" class="btn w-100 py-2" style="background-color: #5BB85C; color: white;">Register Company</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection