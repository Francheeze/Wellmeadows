@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 animate-fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
            <p class="text-cyan-300 uppercase tracking-wider text-sm">Wellmeadows Hospital</p>
            <h1 class="text-4xl font-bold text-white">Staff in {{ $department->name }}</h1>
        </div>
        <a href="{{ route('department.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
            Back to Departments
        </a>
    </div>

    <div class="bg-gray-800 shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            @if($staff->count() > 0)
                <table class="min-w-full leading-normal">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-600 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                Staff Number
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-600 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                Full Name
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-600 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                Position
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-600 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                Department
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        @foreach ($staff as $staffMember)
                            <tr class="hover:bg-gray-700">
                                <td class="px-5 py-5 border-b border-gray-600 text-sm">
                                    {{ $staffMember->staffNumber }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-600 text-sm">
                                    {{ $staffMember->firstName }} {{ $staffMember->lastName }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-600 text-sm">
                                    {{ $staffMember->position }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-600 text-sm">
                                    {{ $staffMember->department->name ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-10">
                    <p class="text-gray-400 text-lg">No staff found in this department.</p>
                </div>
            @endif
        </div>

        @if($staff->hasPages())
            <div class="px-5 py-5 bg-gray-800 border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $staff->links() }}
            </div>
        @endif
    </div>
</div>
@endsection