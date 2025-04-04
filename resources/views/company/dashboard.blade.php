@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">Jobs Published</span>
                        <h1 class="mt-2 mb-0">{{ $company->company_name }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('company.products.index') }}">
                            <i class="fas fa-box me-2"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('company.orders.index') }}">
                            <i class="fas fa-shopping-cart me-2"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('company.credits.index') }}">
                            <i class="fas fa-coins me-2"></i> Credits
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('company.profile') }}">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Search and Upload Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="input-group" style="max-width: 500px;">
                <span class="input-group-text">Section 2</span>
                <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
            </div>
            <a href="{{ route('company.products.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i> Upload product
            </a>
        </div>

        <!-- Products Grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">
                                @if($product->categories->count() > 0)
                                    {{ $product->categories->first()->name }}
                                @else
                                    Uncategorized
                                @endif
                            </p>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('company.products.edit', $product->id) }}" class="btn btn-success">Edit</a>
                                <a href="{{ route('company.products.show', $product->id) }}"
                                    class="btn btn-secondary">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Example products if no products exist -->
            @if(count($products) == 0)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Rundofase</h5>
                            <p class="card-text text-muted">Syracuse, Connecticut</p>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('company.products.create') }}" class="btn btn-success">Create</a>
                                <button class="btn btn-secondary" disabled>Details</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Wade Warren</h5>
                            <p class="card-text text-muted">Lafayette, California</p>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('company.products.create') }}" class="btn btn-success">Create</a>
                                <button class="btn btn-secondary" disabled>Details</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Hooli</h5>
                            <p class="card-text text-muted">Kent, Utah</p>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('company.products.create') }}" class="btn btn-success">Create</a>
                                <button class="btn btn-secondary" disabled>Details</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Gekko & Co</h5>
                            <p class="card-text text-muted">Great Falls, Maryland</p>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('company.products.create') }}" class="btn btn-success">Create</a>
                                <button class="btn btn-secondary" disabled>Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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