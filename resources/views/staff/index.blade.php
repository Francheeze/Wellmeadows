@extends('layouts.app')

@section('title', 'All Staff')

@section('content-bg', 'bg-wm-dark') {{-- Use dark background from layout --}}

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.css" />
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-wm-card rounded-xl shadow-lg">
        {{-- Header --}}
        <div class="flex justify-between items-center p-6 border-b border-gray-700">
            <h1 class="text-2xl font-bold text-white">All Staff Members</h1>
            <div class="flex items-center space-x-4">
                <form action="{{ route('staff.index') }}" method="GET" class="flex items-center">
                        <input type="text" name="search" id="staff-search" class="w-full md:w-64 px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-l-md focus:outline-none focus:ring-cyan-500 focus:border-cyan-500" placeholder="Search staff by name..." value="{{ request('search') }}">
                        <button type="submit" class="bg-cyan-300 hover:bg-cyan-400 text-gray-900 font-bold py-2 px-4 rounded-r-md">Search</button>
                    </form>
                <a href="{{ route('staff.create') }}" class="bg-cyan-300 hover:bg-cyan-400 text-gray-900 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 whitespace-nowrap">
                    + Add New Staff
                </a>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-900/50 border border-green-600 text-green-300 p-4 mb-6 rounded-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Staff No</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Full Name</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Position</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Telephone</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Salary</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Contract</th>
                            <th class="text-center py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        @forelse($staff as $staffMember)
                            <tr class="hover:bg-wm-dark border-b border-gray-800">
                                <td class="py-3 px-4">{{ $staffMember->staffNumber }}</td>
                                <td class="py-3 px-4">{{ $staffMember->firstName }} {{ $staffMember->lastName }}</td>
                                <td class="py-3 px-4">{{ $staffMember->position }}</td>
                                <td class="py-3 px-4">{{ $staffMember->telephoneNumber }}</td>
                                <td class="py-3 px-4">₱{{ number_format($staffMember->currentSalary, 2) }}</td>
                                <td class="py-3 px-4">{{ $staffMember->contractType }}</td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('staff.edit', $staffMember->staffNumber) }}" class="text-blue-400 hover:text-blue-300 font-semibold">Edit</a>
                                    <form action="{{ route('staff.destroy', $staffMember->staffNumber) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-semibold ml-4">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-16">
                                    <p class="text-gray-400">No staff members found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchInput = document.getElementById('staff-search');
            var awesomplete = new Awesomplete(searchInput, {
                minChars: 2,
                list: [],
                data: function (item, input) {
                    return {
                        label: item.firstName + ' ' + item.lastName,
                        value: item.firstName + ' ' + item.lastName
                    };
                }
            });

            searchInput.addEventListener('keyup', function () {
                if (this.value.length < 2) return;

                fetch('{{ route('staff.autocomplete') }}?term=' + this.value)
                    .then(response => response.json())
                    .then(data => {
                        awesomplete.list = data;
                    });
            });
        });
    </script>
@endsection