@props(['product'])

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <a href="{{ route('products.show', $product->id) }}" class="block">
        <img class="w-full h-48 object-cover" src="{{ $product->image_url ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ \Illuminate\Support\Str::limit($product->name, 50) }}</h3>
            <p class="mt-2 text-gray-600">{{ number_format($product->price, 2) }} €</p>
            @if ($product->shipping_cost)
                <p class="text-sm text-gray-500">{{ number_format($product->shipping_cost, 2) }} € Versand</p>
            @else
                <p class="text-sm text-gray-500">Versandkosten prüfen</p>
            @endif
        </div>
    </a>
</div>
