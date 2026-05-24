@extends('layouts.app')

@section('title', 'All Staff')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.css" />
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">
            @if(isset($department))
                Staff in {{ $department }}
            @else
                All Staff Members
            @endif
        </h1>
        <div class="flex items-center space-x-4">
            <form action="{{ route('staff.index') }}" method="GET" class="flex items-center">
                <input type="text" name="search" id="staff-search" class="w-full md:w-64 px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-l-md focus:outline-none focus:ring-cyan-500 focus:border-cyan-500" placeholder="Search staff by name..." value="{{ request('search') }}">
                <button type="submit" class="bg-cyan-300 hover:bg-cyan-400 text-gray-900 font-bold py-2 px-4 rounded-r-md">Search</button>
            </form>
            <a href="{{ route('staff.create') }}" class="bg-wm-cyan hover:bg-wm-cyan-dim text-wm-dark font-bold py-2 px-4 rounded-lg transition-colors">
                + Add New Staff
            </a>
        </div>
    </div>
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_40px_rgba(0,0,0,.4)]">
        <div class="overflow-x-auto px-8 pb-8 pt-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded m-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <table class="min-w-full">
                <thead class="bg-gray-800/50">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Staff No</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Full Name</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Position</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Department</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Telephone</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Salary</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Contract</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @forelse($staff as $member)
                        <tr class="hover:bg-wm-dark border-b border-gray-800/50">
                            <td class="py-3 px-4 text-center">{{ $member->staff_number }}</td>
                            <td class="py-3 px-4">{{ $member->first_name }} {{ $member->last_name }}</td>
                            <td class="py-3 px-4">{{ $member->position }}</td>
                            <td class="py-3 px-4">{{ $member->department->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $member->telephone_number }}</td>
                            <td class="py-3 px-4 text-right">₱{{ number_format($member->current_salary, 2) }}</td>
                            <td class="py-3 px-4">{{ $member->contract_type }}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('staff.edit', $member->staff_number) }}" class="text-blue-400 hover:text-blue-300 font-semibold">Edit</a>
                                    <form action="{{ route('staff.destroy', $member->staff_number) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-semibold ml-4">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-16 text-gray-400">
                                No staff members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6">
                {{ $staff->appends(request()->query())->links() }}
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
                        label: item.first_name + ' ' + item.last_name,
                        value: item.first_name + ' ' + item.last_name
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