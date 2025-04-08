@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">控制面板</span>
                        <h1 class="mt-2 mb-0">{{ $company->company_name }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        @include('layouts.company-nav')

        <!-- Dashboard Content -->
        <div class="row">
            <!-- Stats Cards -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">产品总数</h6>
                                <h3 class="mb-0">{{ $totalProducts }}</h3>
                            </div>
                            <div class="bg-light rounded-circle p-3">
                                <i class="fas fa-box fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">订单总数</h6>
                                <h3 class="mb-0">{{ $totalOrders }}</h3>
                            </div>
                            <div class="bg-light rounded-circle p-3">
                                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">总收入</h6>
                                <h3 class="mb-0">${{ number_format($totalSales, 2) }}</h3>
                            </div>
                            <div class="bg-light rounded-circle p-3">
                                <i class="fas fa-dollar-sign fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">订阅计划</h6>
                                <h3 class="mb-0">{{ $activeSubscription->plan->name }}</h3>
                            </div>
                            <div class="bg-light rounded-circle p-3">
                                <i class="fas fa-credit-card fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">最近订单</h5>
                            <a href="{{ route('company.orders.index') }}" class="btn btn-sm btn-outline-primary">查看
                                全部</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentOrders->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p>暂无订单</p>
                            </div>
                        @else
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>订单编号</th>
                                                    <th>客户</th>
                                                    <th>日期</th>
                                                    <th>总额</th>
                                                    <th>状态</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentOrders as $order)
                                                                        <tr>
                                                                            <td>#{{ $order->id }}</td>
                                                                            <td>{{ $order->user->name }}</td>
                                                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                                            <td>${{ number_format($order->total, 2) }}</td>
                                                                            <td>
                                                                                <span
                                                                                    class="badge bg-{{
                                                    $order->status == 'pending' ? 'warning' :
                                                    ($order->status == 'processing' ? 'info' :
                                                        ($order->status == 'shipped' ? 'primary' :
                                                            ($order->status == 'delivered' ? 'success' : 'danger')))
                                                                                                                                                                                                                                                            }}">
                                                                                    {{ ucfirst($order->status) }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Popular Products -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">热门产品</h5>
                            <a href="{{ route('company.products.index') }}" class="btn btn-sm btn-outline-primary">查看
                                全部</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($popularProducts->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                <p>暂无产品</p>
                            </div>
                        @else
                                    <ul class="list-group list-group-flush">
                                        @foreach($popularProducts as $product)
                                                        <li class="list-group-item px-0">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    @if($product->images->count() > 0)
                                                                                                @php
                                                                                                    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                                                                                                @endphp
                                                                                                <img src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                                                                                    alt="{{ $product->name }}" class="rounded" width="50" height="50"
                                                                                                    style="object-fit: cover;">
                                                                    @else
                                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                            style="width: 50px; height: 50px;">
                                                                            <i class="fas fa-image text-muted"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                                                    <small class="text-muted">{{ $product->orders_count }} 个订单</small>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <span class="badge bg-success">${{ number_format($product->price, 2) }}</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                        @endforeach
                                    </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        @include('layouts.company-styles')
    @endpush
@endsection