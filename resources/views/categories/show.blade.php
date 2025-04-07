@extends('layouts.app')

@section('content')
    <div class="py-6 flex flex-col gap-4">
        <div class="flex items-center gap-3">
            <x-back-button />
            <span class="text-2xl font-semibold text-gray-800">{{ $category->name }}</span>
        </div>
        <div>
            @if ($subcategories->isNotEmpty())
                <span class="text-xl font-semibold text-gray-700 mb-4">Unterkategorien</span>
                <div class="flex flex-col gap-6 mb-8">
                    @forelse ($subcategories as $subcategory)
                        <div>
                            <span
                                class="text-lg font-semibold text-green-600 mb-2 bg-gray-300">{{ $subcategory->name }}</span>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                @forelse ($subcategory->products->take(4) as $product)
                                    <x-product-card :product="$product" />
                                @empty
                                    <p class="text-gray-500 col-span-2">Keine Produkte in dieser Unterkategorie.</p>
                                @endforelse
                            </div>
                            @if ($subcategory->products->count() > 4)
                                <a href="{{ route('subcategories.show', $subcategory->id) }}"
                                    class="inline-block mt-2 text-green-500 hover:underline">Mehr anzeigen</a>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500">Keine Unterkategorien gefunden für {{ $category->name }}.</p>
                    @endforelse
                </div>
            @endif
        </div>
        <div>
            @if ($products->isNotEmpty())
                <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-4">Produkte in {{ $category->name }}</h3>
                <div class="flex items-center gap-4">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
