@extends('layouts.app')

@section('content')
    <div class="py-6">
        <x-back-button />
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Alle Kategorien</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category->id) }}" class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
                    <h3 class="text-lg font-semibold text-indigo-600">{{ $category->name }}</h3>
                    </a>
            @endforeach
        </div>
    </div>
@endsection
