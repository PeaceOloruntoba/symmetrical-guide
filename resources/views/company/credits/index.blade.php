@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">Credits & Subscription</span>
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
                        @php
                            $activeSubscription = Auth::user()->activeSubscription();
                        @endphp

                        @if($activeSubscription)
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">Active</span>
                                <h5 class="mb-0">{{ $activeSubscription->plan->name }}</h5>
                            </div>
                            <p class="text-muted mt-2">
                                Expires:
                                {{ $activeSubscription->ends_at ? $activeSubscription->ends_at->format('M d, Y') : 'Never' }}
                            </p>
                        @else
                            <div class="d-flex align-items-center">
                                <span class="badge bg-danger me-2">Inactive</span>
                                <p class="mb-0">No active subscription</p>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('company.credits.purchase') }}" class="btn btn-success mt-3">
                            @if($activeSubscription)
                                <i class="fas fa-sync-alt me-2"></i> Manage Subscription
                            @else
                                <i class="fas fa-plus me-2"></i> Subscribe Now
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credit Balance Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Current Credit Balance</h4>
                        <h2 class="text-success">{{ number_format($totalCredits, 2) }} Credits</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credit History -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Credit History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($credits as $credit)
                                <tr>
                                    <td>{{ $credit->created_at->format('M d, Y') }}</td>
                                    <td>{{ $credit->description }}</td>
                                    <td>
                                        @if($credit->type == 'purchase')
                                            <span class="badge bg-success">Purchase</span>
                                        @elseif($credit->type == 'usage')
                                            <span class="badge bg-danger">Usage</span>
                                        @elseif($credit->type == 'refund')
                                            <span class="badge bg-info">Refund</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($credit->type) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end {{ $credit->type == 'usage' ? 'text-danger' : 'text-success' }}">
                                        {{ $credit->type == 'usage' ? '-' : '+' }}{{ number_format($credit->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No credit transactions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $credits->links() }}
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