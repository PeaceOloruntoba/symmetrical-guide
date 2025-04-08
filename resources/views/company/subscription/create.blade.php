@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">Subscription Plans</span>
                        <h1 class="mt-2 mb-0">{{ $company->company_name }}</h1>
                    </div>
                    <a href="{{ route('company.subscription.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Subscription
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        @include('layouts.company-nav')

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Subscription Plans -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Select Subscription Plan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($plans as $plan)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 {{ $plan->is_popular ? 'border-success' : '' }}">
                                        @if($plan->is_popular)
                                            <div class="card-header bg-success text-white text-center">
                                                <strong>MOST POPULAR</strong>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h4 class="card-title">{{ $plan->name }}</h4>
                                            <h2 class="text-success mb-4">${{ number_format($plan->price, 2) }}<small
                                                    class="text-muted">/{{ $plan->billing_period }}</small></h2>

                                            <ul class="list-group list-group-flush mb-4">
                                                @if($plan->has_chat)
                                                    <li class="list-group-item border-0 px-0">
                                                        <i class="fas fa-check-circle text-success me-2"></i> Chat Support
                                                    </li>
                                                @endif
                                                @if($plan->has_company_list)
                                                    <li class="list-group-item border-0 px-0">
                                                        <i class="fas fa-check-circle text-success me-2"></i> Company Listing
                                                    </li>
                                                @endif
                                                @if($plan->has_product_page)
                                                    <li class="list-group-item border-0 px-0">
                                                        <i class="fas fa-check-circle text-success me-2"></i> Product Pages
                                                    </li>
                                                @endif
                                                @if($plan->has_wallet_system)
                                                    <li class="list-group-item border-0 px-0">
                                                        <i class="fas fa-check-circle text-success me-2"></i> Wallet System
                                                    </li>
                                                @endif
                                            </ul>

                                            <form action="{{ route('company.subscription.checkout') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                                <button type="submit"
                                                    class="btn btn-{{ $plan->is_popular ? 'success' : 'outline-success' }} w-100">
                                                    Subscribe Now
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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