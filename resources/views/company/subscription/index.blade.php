@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">订阅</span>
                        <h1 class="mt-2 mb-0">{{ $company->company_name }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        @include('layouts.company-nav')

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Subscription Status Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>订阅状态</h4>
                        @if($activeSubscription)
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">有效</span>
                                <h5 class="mb-0">{{ $activeSubscription->plan->name }}</h5>
                            </div>
                            <p class="text-muted mt-2">
                                <strong>开始日期:</strong> {{ $activeSubscription->starts_at->format('M d, Y') }}<br>
                                <strong>到期日期:</strong>
                                {{ $activeSubscription->ends_at ? $activeSubscription->ends_at->format('M d, Y') : '永不' }}<br>
                                <strong>价格:</strong>
                                €{{ number_format($activeSubscription->plan->price, 2) }}/{{ $activeSubscription->plan->billing_period }}
                            </p>

                            <h5 class="mt-4">计划特点</h5>
                            <ul class="list-group list-group-flush">
                                @if($activeSubscription->plan->has_chat)
                                    <li class="list-group-item px-0">
                                        <i class="fas fa-check-circle text-success me-2"></i> 聊天支持
                                    </li>
                                @endif
                                @if($activeSubscription->plan->has_company_list)
                                    <li class="list-group-item px-0">
                                        <i class="fas fa-check-circle text-success me-2"></i> 公司列表
                                    </li>
                                @endif
                                @if($activeSubscription->plan->has_product_page)
                                    <li class="list-group-item px-0">
                                        <i class="fas fa-check-circle text-success me-2"></i> 产品页面
                                    </li>
                                @endif
                                @if($activeSubscription->plan->has_wallet_system)
                                    <li class="list-group-item px-0">
                                        <i class="fas fa-check-circle text-success me-2"></i> 钱包系统
                                    </li>
                                @endif
                            </ul>
                        @else
                            <div class="d-flex align-items-center">
                                <span class="badge bg-danger me-2">未激活</span>
                                <p class="mb-0">没有有效订阅</p>
                            </div>
                            <p class="text-muted mt-2">
                                您需要有效的订阅才能访问控制面板、产品和订单。
                            </p>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('company.subscription.create') }}" class="btn btn-success mt-3">
                            @if($activeSubscription)
                                <i class="fas fa-sync-alt me-2"></i> 更改计划
                            @else
                                <i class="fas fa-plus me-2"></i> 立即订阅
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription History -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">订阅历史</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>计划</th>
                                <th>开始日期</th>
                                <th>结束日期</th>
                                <th>状态</th>
                                <th class="text-end">价格</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(Auth::user()->subscriptions()->with('plan')->latest()->get() as $subscription)
                                <tr>
                                    <td>{{ $subscription->plan->name }}</td>
                                    <td>{{ $subscription->starts_at->format('M d, Y') }}</td>
                                    <td>{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : 'Never' }}</td>
                                    <td>
                                        @if($subscription->status == 'active' && (!$subscription->ends_at || $subscription->ends_at->isFuture()))
                                            <span class="badge bg-success">有效</span>
                                        @elseif($subscription->status == 'canceled')
                                            <span class="badge bg-warning">已取消</span>
                                        @else
                                            <span class="badge bg-secondary">已过期</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        €{{ number_format($subscription->plan->price, 2) }}/{{ $subscription->plan->billing_period }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">未找到订阅历史</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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