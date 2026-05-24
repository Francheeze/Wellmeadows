@extends('layouts.app')

@section('content')
<div class="bg-gray-900 text-white min-h-screen">
    <div class="container mx-auto px-4 py-8 animate-fade-in">
        <div class="flex justify-between items-center mb-8">
        <div>
            <p class="text-cyan-300 uppercase tracking-wider text-sm">Wellmeadows Hospital</p>
            <h1 class="text-3xl font-bold text-white">Departments</h1>
        </div>
        <a href="{{ route('department.create') }}" class="bg-wm-cyan hover:bg-wm-cyan-dim text-wm-dark font-bold py-2 px-4 rounded-lg transition-colors">
            + Add New Department
        </a>
    </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <!-- Total Staff Card -->
            <a href="{{ route('staff.index') }}" class="bg-wm-card shadow-lg rounded-xl p-4 flex flex-col items-center justify-center hover:bg-wm-dark transition-colors">
                <h3 class="text-lg font-semibold text-gray-300">Total Staff</h3>
                <p class="text-3xl font-bold text-white">{{ $totalStaff }}</p>
                <svg class="h-8 w-8 text-blue-500 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.124-1.28-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.124-1.28.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </a>

            <!-- Department Cards -->
            @foreach ($all_departments as $dept)
                <a href="{{ route('department.show', $dept->id) }}" class="bg-wm-card shadow-lg rounded-xl p-4 flex flex-col items-center justify-center hover:bg-wm-dark transition-colors">
                    <h3 class="text-lg font-semibold text-gray-300">{{ $dept->name }}</h3>
                    <p class="text-3xl font-bold text-white">{{ $dept->staff_count }}</p>
                    <svg class="h-8 w-8 text-blue-500 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        {!! $departmentIcons[$dept->name] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />' !!}
                    </svg>
                </a>
            @endforeach
        </div>

        <div class="bg-gray-800 shadow-lg rounded-lg p-6">
            <!-- Action Bar -->
            <div class="flex justify-between items-center mb-4">
                <form action="{{ route('department.index') }}" method="GET" class="flex items-center space-x-4 w-1/2">
                    <input type="text" name="search" placeholder="Search departments by name..." class="w-full bg-gray-700 text-white px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Search</button>
                </form>
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-700 text-white font-bold py-2 px-4 rounded-md">
                        Total: {{ $departments->total() }}
                    </div>

                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-500 bg-opacity-25 border border-green-500 text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 bg-opacity-25 border border-red-500 text-red-300 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Departments Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-800 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-800 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Address</th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-800 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                            <tr class="hover:bg-gray-700">
                                <td class="px-5 py-5 border-b border-gray-700 text-sm">
                                    <p class="text-gray-200 whitespace-no-wrap">{{ $department->name }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 text-sm">
                                    <p class="text-gray-200 whitespace-no-wrap">{{ $department->address }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 text-sm text-right">
                                    <div class="flex items-center justify-end space-x-4">
                                        <a href="{{ route('department.edit', $department) }}" class="text-blue-400 hover:text-blue-300">Edit</a>
                                        <form action="{{ route('department.destroy', $department) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-16">
                                    <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6" />
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-300">No departments registered yet.</h3>
                                    <div class="mt-6">
                                        <a href="{{ route('department.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-bold rounded-md text-gray-800 bg-cyan-300 hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-cyan-500">
                                            + Register First Department
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($departments) && $departments instanceof \Illuminate\Pagination\LengthAwarePaginator && $departments->hasPages())
                <div class="mt-6">
                    {{ $departments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection