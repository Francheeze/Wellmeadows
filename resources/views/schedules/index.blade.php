@extends('layouts.app', ['module' => 'Schedules'])

@section('title', 'Staff Schedules')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 anim-fade-up">
    <div class="flex justify-between items-center mb-6">
       <h1 class="text-2xl font-bold text-white">Staff Schedules</h1>
        <a href="{{ route('schedules.create') }}" class="bg-wm-cyan hover:bg-wm-cyan-dim text-wm-dark font-bold py-2 px-4 rounded-lg transition-colors">
                + Add New Schedule
            </a>
    </div>

    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_40px_rgba(0,0,0,.4)]">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded m-6" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto px-8 pb-8 pt-8">
            <table class="min-w-full">
                <thead class="bg-gray-800/50">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Staff Name</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Department</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Start Time</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">End Time</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-wm-dark border-b border-gray-800/50">
                            <td class="py-3 px-4">{{ $schedule->staff->firstName ?? 'N/A' }} {{ $schedule->staff->lastName ?? '' }}</td>
                            <td class="py-3 px-4">{{ $schedule->department }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($schedule->start_time)->format('M d, Y, g:i A') }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($schedule->end_time)->format('M d, Y, g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-16 text-gray-400">
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