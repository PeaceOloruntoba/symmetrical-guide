@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-400">
            <div class="p-2 text-center">
                <div
                    class="flex justify-center items-center w-16 h-16 mx-auto bg-orange-100 text-orange-500 rounded-full mb-2">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7m-4 3a4 4 0 01-8 0v4a4 4 0 018 0v-4z"></path>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-gray-800 mb-1">besten Preise</p>
                <p class="text-gray-600 text-sm">in Deutschland</p>
            </div>

            <div class="p-2 text-center">
                <div
                    class="flex justify-center items-center w-16 h-16 mx-auto bg-orange-100 text-orange-500 rounded-full mb-2">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-3-3v6m-2 2h10a2 2 0 002-2v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2"></path>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-gray-800 mb-1">Pünktliche</p>
                <p class="text-gray-600 text-sm">Lieferung</p>
            </div>

            <div class="p-2 text-center">
                <div
                    class="flex justify-center items-center w-16 h-16 mx-auto bg-yellow-100 text-yellow-500 rounded-full mb-2">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-gray-800 mb-1">3.000.000</p>
                <p class="text-gray-600 text-sm">Produkte</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category->id) }}"
                    class="block hover:bg-gray-400 transition duration-200 p-2 flex items-center text-decoration-none">
                    <span class="text-lg font-semibold text-gray-700">{{ $category->name }}</span>
                    <svg class="h-5 w-5 text-gray-500 inline-block ml-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @endforeach
        </div>
    </div>
@endsection
