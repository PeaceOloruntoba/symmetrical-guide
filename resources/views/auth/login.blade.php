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
                            <h1 class="display-5 fw-bold text-success mb-2" style="color: #31572C !important;">登录</h1>
                        </div>
                        <a class="index_link" href="{{ route('register') }}">没有账户？<span
                                class="index_span">注册</span></a>

                        @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form class="mt-5" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">电子邮箱</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" placeholder="请输入您的电子邮箱" required
                                    autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">密码</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="请输入您的密码" required
                                    autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div style="display: flex; align-items: center;">
                                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} 
                                           style="width: auto; height: auto; margin: 0; position: relative; top: 1px;">
                                    <span style="font-size: 16px; color: #6C757D; margin-left: 8px;">记住我</span>
                                </div>
                                <a href="{{ route('password.request') }}"
                                    style="color: #5BB85C; text-decoration: none; font-size: 16px;">忘记密码？</a>
                            </div>
                            <button type="submit" class="btn w-100 py-2"
                                style="background-color: #5BB85C; color: white;">登录</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card {
            border-radius: 10px;
            border: none;
        }

        .index_title {
            font-size: 40px;
            font-weight: 700;
            color: #31572C;
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
        
        @media (max-width: 768px) {
            .index_title {
                font-size: 32px;
            }
        }
    </style>
@endpush