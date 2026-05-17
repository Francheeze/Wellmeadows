@extends('layouts.app')

@section('title', 'All Staff')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-700">All Staff Members</h2>
            <a href="{{ route('staff.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">+ Add New Staff</a>
        </div>
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Staff No</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Full Name</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Position</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Telephone</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Salary</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Contract</th>
                            <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($staff as $staffMember)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-3 px-4">{{ $staffMember->staff_no }}</td>
                                <td class="py-3 px-4">{{ $staffMember->full_name }}</td>
                                <td class="py-3 px-4">{{ $staffMember->position }}</td>
                                <td class="py-3 px-4">{{ $staffMember->telephone }}</td>
                                <td class="py-3 px-4">₱{{ number_format($staffMember->salary, 2) }}</td>
                                <td class="py-3 px-4">{{ $staffMember->contract_type }}</td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('staff.edit', $staffMember->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                                    <form action="{{ route('staff.destroy', $staffMember->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection