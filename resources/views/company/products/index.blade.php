@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">产品</span>
                        <h1 class="mt-2 mb-0">{{ $company->company_name }}</h1>
                    </div>
                    <a href="{{ route('company.products.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i> 添加新产品
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        @include('layouts.company-nav')

        <!-- Products List -->
        <div class="card shadow-sm">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($products->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h3>暂无产品</h3>
                        <p class="text-muted">开始添加您的产品以向客户展示。</p>
                        <a href="{{ route('company.products.create') }}" class="btn btn-success mt-3">
                            <i class="fas fa-plus me-2"></i> 添加您的第一个产品
                        </a>
                    </div>
                @else
                    <div class="row">
                        @foreach($products as $product)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="position-relative">
                                            @if($product->images->count() > 0)
                                                                @php
                                                                    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                                                                @endphp
                                                                <img src="{{ asset('storage/' . $primaryImage->image_path) }}" class="card-img-top"
                                                                    alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                            @else
                                                <div class="bg-light text-center py-5" style="height: 200px;">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                    <p class="mt-2 text-muted">No Image</p>
                                                </div>
                                            @endif

                                            <div class="position-absolute top-0 end-0 p-2">
                                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                    {{ $product->is_active ? '激活' : '未激活' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title">{{ $product->name }}</h5>
                                            <p class="card-text text-muted">
                                                @if($product->categories->count() > 0)
                                                    {{ $product->categories->first()->name }}
                                                @else
                                                    Uncategorized
                                                @endif
                                            </p>
                                            <p class="card-text">
                                                <strong>€{{ number_format($product->price, 2) }}</strong>
                                            </p>
                                            <p class="card-text">
                                                {{ Str::limit($product->description, 100) }}
                                            </p>
                                        </div>

                                        <div class="card-footer bg-white border-top-0">
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('company.products.edit', $product) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit me-1"></i> 编辑
                                                </a>
                                                <form action="{{ route('company.products.destroy', $product) }}" method="POST"
                                                    onsubmit="return confirm('您确定要删除此产品吗？');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash me-1"></i> 删除
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Font Awesome for icons -->
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .nav-pills .nav-link.active {
                background-color: #4CAF50;
                border-bottom: 3px solid #4CAF50;
            }

            .nav-pills .nav-link {
                color: #495057;
            }

            .btn-success {
                background-color: #4CAF50;
                border-color: #4CAF50;
            }

            .btn-success:hover {
                background-color: #3e8e41;
                border-color: #3e8e41;
            }
        </style>
    @endpush
@endsection