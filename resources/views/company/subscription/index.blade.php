@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">Subscription</span>
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
                        <h4>Subscription Status</h4>
                        @if($activeSubscription)
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">Active</span>
                                <h5 class="mb-0">{{ $activeSubscription->plan->name }}</h5>
                            </div>
                            <p class="text-muted mt-2">
                                <strong>Started:</strong> {{ $activeSubscription->starts_at->format('M d, Y') }}<br>
                                <strong>Expires:</strong> {{ $activeSubscription->ends_at ? $activeSubscription->ends_at->format('M d, Y') : 'Never' }}<br>
                                <strong>Price:</strong> ${{ number_format($activeSubscription->plan->price, 2) }}/{{ $activeSubscription->plan->billing_period }}
                            </p>
                            
                            <h5 class="mt-4">Plan Features</h5>
                            <ul class="list-group list-group-flush">
                                @if($activeSubscription->plan->has_chat)
                                    <li class="list-group-item px-0">
                                        <i class="fas fa-check-circle text-success me-2"></i> Chat Support
                                    </li>
                                @endif
                                @if($activeSubscription->plan->has_company_list)
                                    <li class="list-group-item px-0">
                                        <i class="fas fa-check-circle text-success me-2"></i> Company Listing
                                    </li>
                                @endif
                                @if($activeSubscription->plan->has_product_page)
                                    <li class="list-group-item px-0">
                                        <i class="fas fa-check-circle text-success me-2"></i> Product Pages
                                    </li>
                                @endif
                                @if($activeSubscription->plan->has_wallet_system)
                                    <li class="list-group-item px-0">
                                        <i class="fas fa-check-circle text-success me-2"></i> Wallet System
                                    </li>
                                @endif
                            </ul>
                        @else
                            <div class="d-flex align-items-center">
                                <span class="badge bg-danger me-2">Inactive</span>
                                <p class="mb-0">No active subscription</p>
                            </div>
                            <p class="text-muted mt-2">
                                You need an active subscription to access dashboard, products, and orders.
                            </p>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('company.subscription.create') }}" class="btn btn-success mt-3">
                            @if($activeSubscription)
                                <i class="fas fa-sync-alt me-2"></i> Change Plan
                            @else
                                <i class="fas fa-plus me-2"></i> Subscribe Now
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription History -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Subscription History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Plan</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th class="text-end">Price</th>
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
                                            <span class="badge bg-success">Active</span>
                                        @elseif($subscription->status == 'canceled')
                                            <span class="badge bg-warning">Canceled</span>
                                        @else
                                            <span class="badge bg-secondary">Expired</span>
                                        @endif
                                    </td>
                                    <td class="text-end">${{ number_format($subscription->plan->price, 2) }}/{{ $subscription->plan->billing_period }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">No subscription history found</td>
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