@extends('layouts.app')

@section('title', 'All Staff')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">
            @if(isset($department))
                Staff in {{ $department }}
            @else
                All Staff Members
            @endif
        </h1>
        <a href="{{ route('staff.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            + Add New Staff
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff No</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telephone</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salary</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract</th>
                        <th class="px-4 py-3 border text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($staff as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 border text-center">{{ $member->staffNumber }}</td>
                            <td class="px-4 py-3 border">{{ $member->firstName }} {{ $member->lastName }}</td>
                            <td class="px-4 py-3 border">{{ $member->position }}</td>
                            <td class="px-4 py-3 border">{{ $member->telephoneNumber }}</td>
                            <td class="px-4 py-3 border text-right">₱{{ number_format($member->currentSalary, 2) }}</td>
                            <td class="px-4 py-3 border">{{ $member->contractType }}</td>
                            <td class="px-4 py-3 border">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('staff.edit', $member->staffNumber) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form action="{{ route('staff.destroy', $member->staffNumber) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">
                                No staff members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection