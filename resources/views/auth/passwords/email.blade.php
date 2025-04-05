@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-column">
                            <h1 class="display-5 fw-bold text-success mb-2" style="color: #31572C !important;">Reset
                                Password</h1>
                        </div>
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
                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" placeholder="Enter your email" required
                                    autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn w-100 py-2"
                                style="background-color: #5BB85C; color: white;">Send Password Reset Link</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .card {
                border-radius: 10px;
                border: none;
            }

            .index_submit {
                background-color: #4CAF50;
                border-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                width: 100%;
            }

            .index_submit:hover {
                background-color: #3e8e41;
                border-color: #3e8e41;
                color: white;
            }

            .index_span {
                color: #4CAF50;
            }
        </style>
    @endpush
@endsection