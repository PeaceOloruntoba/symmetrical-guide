@extends('layouts.app')

@section('content')
    <div class="py-6">
        <x-back-button />
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ $category->name }} - Unterkategorien</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse ($subcategories as $subcategory)
                <a href="{{ route('subcategories.show', $subcategory->id) }}" class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
                    <h3 class="text-lg font-semibold text-green-600">{{ $subcategory->name }}</h3>
                    </a>
            @empty
                <p class="text-gray-500">Keine Unterkategorien gefunden für {{ $category->name }}.</p>
            @endforelse
        </div>

        @if (isset($products) && $products->isNotEmpty())
            <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-4">Produkte in {{ $category->name }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
