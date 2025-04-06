@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Users</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($users as $user)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4">
                    <h2 class="text-xl font-semibold text-gray-700">{{ $user->name }}</h2>
                    <p class="text-gray-500 mb-2">{{ $user->email }}</p>
                    <a href="{{ route('admin.users.show', $user) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No users found.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection
