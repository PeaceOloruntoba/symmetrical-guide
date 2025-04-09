@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-column">
                            <h1 class="display-5 fw-bold text-success mb-2" style="color: #31572C !important;">公司注册</h1>
                        </div>
                        <a class="index_link" href="{{ route('login') }}">已有账户？<span class="index_span">登录</span></a>

                        @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form class="mt-5" method="POST" action="{{ route('register.company') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">联系人姓名</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" placeholder="请输入联系人姓名" required
                                    autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">电子邮箱</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" placeholder="请输入您的电子邮箱" required
                                    autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="company_name" class="form-label">公司名称</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                    id="company_name" name="company_name" value="{{ old('company_name') }}"
                                    placeholder="请输入公司名称" required>
                                @error('company_name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">电话号码</label>
                                <input type="text" class="form-control px-5 @error('phone') is-invalid @enderror" id="phone"
                                    name="phone" value="{{ old('phone') }}" placeholder="请输入电话号码" required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="website" class="form-label">公司网站</label>
                                <input type="url" class="form-control px-5 @error('website') is-invalid @enderror"
                                    id="website" name="website" value="{{ old('website') }}" placeholder="请输入公司网站" required>
                                @error('website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">公司地址</label>
                                <input type="text" class="form-control px-5 @error('address') is-invalid @enderror"
                                    id="address" name="address" value="{{ old('address') }}" placeholder="请输入公司地址" required>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">公司描述</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" placeholder="请输入公司描述" rows="3"
                                    required>{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">密码</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="请输入您的密码" required
                                    autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">确认密码</label>
                                <input type="password" class="form-control" id="password-confirm"
                                    name="password_confirmation" placeholder="请再次输入您的密码" required
                                    autocomplete="new-password">
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="logo" class="form-label">公司标志</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                                        name="logo">
                                    <div class="form-text">支持的格式: JPEG, PNG, JPG, GIF (最大 2MB)</div>
                                    @error('logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="company_paper" class="form-label">公司证书</label>
                                    <input type="file" class="form-control @error('company_paper') is-invalid @enderror"
                                        id="company_paper" name="company_paper">
                                    <div class="form-text">支持的格式: PDF, DOC, DOCX (最大 5MB)</div>
                                    @error('company_paper')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="company_id" class="form-label">公司ID</label>
                                <input type="text" class="form-control @error('company_id') is-invalid @enderror"
                                    id="company_id" name="company_id" value="{{ old('company_id') }}" placeholder="请输入公司ID"
                                    required>
                                @error('company_id')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn w-100 py-2"
                                style="background-color: #5BB85C; color: white;">注册公司</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection