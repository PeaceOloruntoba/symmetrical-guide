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
                <a href="{{ route('company.credits.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Credits
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
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Select Subscription Plan</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('company.credits.process') }}" id="payment-form">
                        @csrf
                        
                        <div class="mb-4">
                            <div class="row">
                                @foreach($creditPackages as $package)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 {{ old('package_id') == $package->id ? 'border-success' : '' }}">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="package_id" 
                                                        id="package-{{ $package->id }}" value="{{ $package->id }}"
                                                        {{ old('package_id') == $package->id ? 'checked' : '' }}
                                                        required>
                                                    <label class="form-check-label w-100" for="package-{{ $package->id }}">
                                                        <h5 class="mb-1">{{ $package->name }}</h5>
                                                        <p class="mb-1">{{ $package->credits }} Credits</p>
                                                        <h4 class="text-success mb-0">${{ number_format($package->price, 2) }}/month</h4>
                                                        @if($package->is_popular)
                                                            <span class="badge bg-warning mt-2">Popular</span>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('package_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3">Payment Information</h5>
                            <div class="mb-3">
                                <label for="card-element" class="form-label">Credit or debit card</label>
                                <div id="card-element" class="form-control p-3">
                                    <!-- Stripe Element will be inserted here -->
                                </div>
                                <div id="card-errors" class="text-danger mt-1" role="alert"></div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success" id="submit-button">
                                <i class="fas fa-credit-card me-2"></i> Subscribe Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Current Balance -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Current Balance</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-success mb-0">{{ number_format($totalCredits, 2) }} Credits</h2>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Subscription Benefits</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Access to dashboard
                        </li>
                        <li class="list-group-item px-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Manage your products
                        </li>
                        <li class="list-group-item px-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Track your orders
                        </li>
                        <li class="list-group-item px-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Receive credits for promotions
                        </li>
                        <li class="list-group-item px-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Priority customer support
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    @include('layouts.company-styles')
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Create a Stripe client
    const stripe = Stripe('{{ env('STRIPE_KEY') }}');
    const elements = stripe.elements();

    // Create an instance of the card Element
    const cardElement = elements.create('card');

    // Add an instance of the card Element into the `card-element` div
    cardElement.mount('#card-element');

    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        // Disable the submit button to prevent repeated clicks
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
        
        const {token, error} = await stripe.createToken(cardElement);
        
        if (error) {
            // Display error
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
            
            // Re-enable the submit button
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-credit-card me-2"></i> Subscribe Now';
        } else {
            // Send the token to your server
            stripeTokenHandler(token);
        }
    });
    
    // Submit the form with the token ID
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        
        // Submit the form
        form.submit();
    }
    
    // Update selected package styling
    const packageRadios = document.querySelectorAll('input[name="package_id"]');
    packageRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove border from all cards
            document.querySelectorAll('.card').forEach(card => {
                card.classList.remove('border-success');
            });
            
            // Add border to selected card
            if (this.checked) {
                this.closest('.card').classList.add('border-success');
            }
        });
    });
</script>
@endpush
@endsection 