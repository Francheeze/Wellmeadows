@extends('layouts.app')

@section('content')
<div class="py-10">
    <div class="max-w-2xl mx-auto bg-wm-card border border-wm-navy/60 rounded-2xl shadow-[0_8px_40px_rgba(0,0,0,.4)] p-8">
        <h1 class="text-3xl font-bold text-white mb-8">Add New Department</h1>

        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('department.store') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label for="name" class="block text-gray-300 text-sm font-bold mb-2">Department Name:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
            </div>
            <div class="mb-8">
                <label for="address" class="block text-gray-300 text-sm font-bold mb-2">Description / Address:</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
            </div>
            <div class="flex items-center justify-end space-x-6">
                <a href="{{ route('department.index') }}" class="text-gray-400 hover:text-white font-bold py-2 px-4">Cancel</a>
                <button type="submit" class="bg-wm-cyan hover:bg-wm-cyan-dim text-wm-dark font-bold py-2 px-4 rounded-lg transition-colors">
                    Save Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection