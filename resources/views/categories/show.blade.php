@extends('layouts.app')

@section('content')
    <div class="py-6 flex flex-col gap-4">
        <div class="flex items-center gap-3">
            <x-back-button />
            <span class="text-2xl font-semibold text-gray-800">{{ $category->name }}</span>
        </div>
        <div class="flex flex-col gap-2">
            @if ($subcategories->isNotEmpty())
                {{-- <span class="text-xl font-semibold text-gray-700">Unterkategorien</span> --}}
                <div class="flex flex-col gap-3">
                    @forelse ($subcategories as $subcategory)
                        <div>
                            <span
                                class="text-lg font-semibold text-green-600 bg-gray-300">{{ $subcategory->name }}</span>
                            <div class="flex flex-row flex-wrap gap-4 mt-2">
                                @forelse ($subcategory->products as $product)
                                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5">
                                        <x-product-card :product="$product" />
                                    </div>
                                @empty
                                    <p class="text-gray-500">Keine Produkte in dieser Unterkategorie.</p>
                                @endforelse
                            </div>
                            @if ($subcategory->products->count() > 5)
                                <a href="{{ route('subcategories.show', $subcategory->id) }}"
                                    class="inline-block text-green-500 hover:underline mt-1">Mehr anzeigen</a>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500">Keine Unterkategorien gefunden für {{ $category->name }}.</p>
                    @endforelse
                </div>
            @endif
        </div>

        {{-- Remove or comment out this section as you are now displaying products by subcategory --}}
        {{-- <div>
            @if ($products->isNotEmpty())
                <h3 class="text-xl font-semibold text-gray-700">Produkte in {{ $category->name }}</h3>
                <div class="flex items-center gap-4">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div> --}}
    </div>
@endsection
