@extends('layouts.admin')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.plans.index') }}"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-block">
            Back to Plans
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4">
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">Edit Plan: {{ $plan->name }}</h1>

            <form action="{{ route('admin.plans.update', $plan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $plan->price) }}" step="0.01"
                        min="0"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                    @error('price')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">Currency:</label>
                    <input type="text" name="currency" id="currency" value="{{ old('currency', $plan->currency) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                    <p class="text-gray-500 text-xs mt-1">Use 3-letter currency code (e.g., USD, EUR, GBP)</p>
                    @error('currency')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="billing_period" class="block text-gray-700 text-sm font-bold mb-2">Billing Period:</label>
                    <select name="billing_period" id="billing_period"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="month" {{ old('billing_period', $plan->billing_period) == 'month' ? 'selected' : '' }}>
                            Monthly</option>
                        <option value="year" {{ old('billing_period', $plan->billing_period) == 'year' ? 'selected' : '' }}>
                            Yearly</option>
                    </select>
                    @error('billing_period')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="has_chat" id="has_chat" value="1" {{ old('has_chat', $plan->has_chat) ? 'checked' : '' }} class="mr-2">
                        <label for="has_chat" class="text-gray-700 text-sm font-bold">Has Chat Support</label>
                    </div>
                    @error('has_chat')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="has_company_list" id="has_company_list" value="1" {{ old('has_company_list', $plan->has_company_list) ? 'checked' : '' }} class="mr-2">
                        <label for="has_company_list" class="text-gray-700 text-sm font-bold">Has Company Listing</label>
                    </div>
                    @error('has_company_list')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="has_product_page" id="has_product_page" value="1" {{ old('has_product_page', $plan->has_product_page) ? 'checked' : '' }} class="mr-2">
                        <label for="has_product_page" class="text-gray-700 text-sm font-bold">Has Product Pages</label>
                    </div>
                    @error('has_product_page')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="has_wallet_system" id="has_wallet_system" value="1" {{ old('has_wallet_system', $plan->has_wallet_system) ? 'checked' : '' }} class="mr-2">
                        <label for="has_wallet_system" class="text-gray-700 text-sm font-bold">Has Wallet System</label>
                    </div>
                    @error('has_wallet_system')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} class="mr-2">
                        <label for="is_active" class="text-gray-700 text-sm font-bold">Is Active</label>
                    </div>
                    @error('is_active')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular', $plan->is_popular) ? 'checked' : '' }} class="mr-2">
                        <label for="is_popular" class="text-gray-700 text-sm font-bold">Is Popular</label>
                    </div>
                    @error('is_popular')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="bg-[#5BB85C] hover:bg-[#4a9d4a] text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection