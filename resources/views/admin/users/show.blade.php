@extends('layouts.admin')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-block">
            Back to Users
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-4">
        <h1 class="text-2xl font-semibold text-gray-800 mb-2">{{ $user->name }}</h1>
        <p class="text-gray-600 mb-2">Email: {{ $user->email }}</p>
        <p class="text-gray-600">Created At: {{ $user->created_at->format('M d, Y h:i A') }}</p>
        <p class="text-gray-600">Updated At: {{ $user->updated_at->format('M d, Y h:i A') }}</p>
    </div>
@endsection
