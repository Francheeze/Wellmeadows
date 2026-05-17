@extends('layouts.app', ['module' => 'Schedules'])

@section('title', 'Staff Schedules')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
       <h1 class="text-2xl font-bold !text-white">Staff Schedules</h1>
        <a href="{{ route('schedules.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            + Add New Schedule
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
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Name</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 border">{{ $schedule->staff->firstName ?? 'N/A' }} {{ $schedule->staff->lastName ?? '' }}</td>
                            <td class="px-4 py-3 border">{{ $schedule->department }}</td>
                            <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($schedule->start_time)->format('M d, Y, g:i A') }}</td>
                            <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($schedule->end_time)->format('M d, Y, g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">
                                No schedules found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection