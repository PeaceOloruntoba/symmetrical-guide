@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Companies</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
        @forelse ($companies as $company)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4">
                    <h2 class="text-xl font-semibold text-gray-700">{{ $company->company_name }}</h2>
                    <p class="text-gray-500 mb-2">{{ Str::limit($company->description, 50) }}</p>
                    <a href="{{ route('admin.companies.show', $company) }}" class="inline-block border border-[#5BB85C] hover:bg-gray-200 text-[#5BB85C] font-bold py-2 px-4 rounded">
                        View Profile
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No companies found.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $companies->links() }}
    </div>
@endsection
