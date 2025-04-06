@extends('layouts.admin')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.companies.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-block">
            Back to Companies
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4">
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">{{ $company->company_name }}</h1>
            <p class="text-gray-600 mb-4">{{ $company->description }}</p>

            <h3 class="text-lg font-semibold text-gray-700 mb-2">Products</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($products as $product)
                    <div class="bg-gray-100 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-gray-600">{{ $product->name }}</h4>
                        <p class="text-gray-500">${{ $product->price }}</p>
                        </div>
                @empty
                    <p class="text-gray-500">No products found for this company.</p>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
