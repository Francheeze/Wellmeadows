@extends('layouts.app')

@section('content')
<div class="bg-gray-900 text-white min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-gray-800 shadow-lg rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-200 mb-6">Edit Department</h1>

            @if ($errors->any())
                <div class="bg-red-500 bg-opacity-25 border border-red-500 text-red-300 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your input.</span>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('department.update', $department->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-gray-400 text-sm font-bold mb-2">Department Name:</label>
                    <input type="text" name="name" id="name" class="w-full bg-gray-700 text-white px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('name', $department->name) }}" required>
                </div>
                <div class="mb-6">
                    <label for="address" class="block text-gray-400 text-sm font-bold mb-2">Description / Address:</label>
                    <input type="text" name="address" id="address" class="w-full bg-gray-700 text-white px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('address', $department->address) }}" required>
                </div>
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('department.index') }}" class="text-gray-400 hover:text-white">Cancel</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection