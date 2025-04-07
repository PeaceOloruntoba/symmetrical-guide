@extends('layouts.app')

@section('content')
    <div class="py-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Willkommen bei Germanware</h2>
        <p class="text-gray-600">Entdecken Sie unsere hochwertigen Produkte.</p>

        <div class="mt-8">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Beliebte Kategorien</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($categories as $category)
                    <a href="{{ route('categories.show', $category->id) }}" class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
                        <h4 class="text-lg font-semibold text-indigo-600">{{ $category->name }}</h4>
                        </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection


