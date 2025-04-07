@extends('layouts.app')

@section('content')
    <div class="py-6">
        <x-back-button />
        @if (isset($subcategory))
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Produkte in {{ $subcategory->name }}</h2>
        @elseif (isset($query))
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Suchergebnisse für "{{ $query }}"</h2>
        @else
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Alle Produkte</h2> @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="text-gray-500">Keine Produkte gefunden.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>

        @if (isset($query) && $categories->isNotEmpty())
            <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-4">Kategorien passend zu Ihrer Suche</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($categories as $category)
                    <a href="{{ route('categories.show', $category->id) }}" class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
                        <h4 class="text-lg font-semibold text-indigo-600">{{ $category->name }}</h4>
                    </a>
                @endforeach
            </div>
        @endif

       @if (isset($subcategories) && $subcategories->isNotEmpty())
    <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-4">Weitere Unterkategorien</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach ($subcategories as $sub)
            <a href="{{ route('subcategories.show', $sub->id) }}" class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
                <h4 class="text-lg font-semibold text-green-600">{{ $sub->name }}</h4>
            </a>
        @endforeach
    </div>
@endif
    </div>
@endsection
