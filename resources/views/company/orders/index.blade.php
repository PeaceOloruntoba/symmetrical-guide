@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">Orders</span>
                        <h1 class="mt-2 mb-0">{{ $company->company_name }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        @include('layouts.company-nav')

        <!-- Orders List -->
        <div class="card shadow-sm">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Order Filters -->
                <div class="mb-4">
                    <form action="{{ route('company.orders.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                    Processing</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped
                                </option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('company.orders.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                @if(isset($orders) && $orders->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h3>No Orders Yet</h3>
                        <p class="text-muted">You haven't received any orders yet.</p>
                    </div>
                @elseif(isset($orders))
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>{{ $order->user->name }}</td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>${{ number_format($order->total, 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ 
                                                                                            $order->status == 'pending' ? 'warning' :
                                    ($order->status == 'processing' ? 'info' :
                                        ($order->status == 'shipped' ? 'primary' :
                                            ($order->status == 'delivered' ? 'success' : 'danger'))) 
                                                                                        }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('company.orders.show', $order) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
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