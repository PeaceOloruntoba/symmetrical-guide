@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">订单详情</span>
                        <h1 class="mt-2 mb-0">{{ $company->company_name }}</h1>
                    </div>
                    <a href="{{ route('company.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> 返回订单列表
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        @include('layouts.company-nav')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">订单 #{{ $order->id }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted">订单信息</h6>
                                <p class="mb-1"><strong>订单日期:</strong>
                                    {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                <p class="mb-0">
                                    <strong>状态:</strong>
                                    <span class="badge bg-{{ 
                                                    $order->status == 'pending' ? 'warning' :
        ($order->status == 'processing' ? 'info' :
            ($order->status == 'shipped' ? 'primary' :
                ($order->status == 'delivered' ? 'success' : 'danger'))) 
                                                }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">客户信息</h6>
                                <p class="mb-1"><strong>姓名:</strong> {{ $order->user->name }}</p>
                                <p class="mb-0"><strong>邮箱:</strong> {{ $order->user->email }}</p>
                            </div>
                        </div>

                        <h6 class="text-muted mb-3">订单项目</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>产品</th>
                                        <th>价格</th>
                                        <th>数量</th>
                                        <th class="text-end">总计</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderItems as $item)
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            @if($item->product && $item->product->images->count() > 0)
                                                                                                                    @php
                                                                                                                        $primaryImage = $item->product->images->firstWhere('is_primary', true) ?? $item->product->images->first();
                                                                                                                    @endphp
                                                                                                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                                                                                                        alt="{{ $item->product_name }}" class="rounded me-3" width="50"
                                                                                                                        height="50" style="object-fit: cover;">
                                                                            @else
                                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                                                    style="width: 50px; height: 50px;">
                                                                                    <i class="fas fa-image text-muted"></i>
                                                                                </div>
                                                                            @endif
                                                                            <div>
                                                                                <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                                                @if($item->product)
                                                                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>${{ number_format($item->price, 2) }}</td>
                                                                    <td>{{ $item->quantity }}</td>
                                                                    <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                                                </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>小计:</strong></td>
                                        <td class="text-end">${{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">更新订单状态</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('company.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label">状态</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>待处理
                                    </option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                        处理中</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>已发货
                                    </option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>已送达
                                    </option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>已取消
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">更新状态</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">配送地址</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->address ?? '未提供配送地址' }}</p>
                    </div>
                </div>
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