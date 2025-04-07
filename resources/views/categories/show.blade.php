@extends('layouts.app')

@section('content')
    <div class="py-6">
        <x-back-button />
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ $category->name }}</h2>

        @forelse ($subcategories as $subcategory)
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ $subcategory->name }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-4">
                    @forelse ($subcategory->products->take(6) as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <p class="text-gray-500 col-span-full">Keine Produkte in dieser Unterkategorie.</p>
                    @endforelse
                    @if ($subcategory->products->count() > 6)
                        <a href="{{ route('subcategories.show', $subcategory->id) }}" class="inline-block mt-2 text-green-500 hover:underline">Mehr anzeigen</a>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500">Keine Unterkategorien gefunden für {{ $category->name }}.</p>
        @endforelse
    </div>
@endsection
