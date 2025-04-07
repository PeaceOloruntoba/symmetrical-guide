@extends('layouts.app')

@section('content')
    <div class="py-6">
        <x-back-button />
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Alle Kategorien</h2>
        <div class="mt-8 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category->id) }}"
                    class="block hover:bg-gray-400 transition duration-200 p-2 flex items-center text-decoration-none">
                    <span class="text-lg font-semibold text-gray-700">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection



@extends('layouts.app')
