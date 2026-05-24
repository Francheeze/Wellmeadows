@extends('layouts.app')

@section('title', 'Incident Reports')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 anim-fade-up">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Incident Reports</h1>
        <a href="{{ route('reports.create') }}" class="bg-wm-cyan hover:bg-wm-cyan-dim text-wm-dark font-bold py-2 px-4 rounded-lg transition-colors">
                + Add New Report
            </a>
    </div>

    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_40px_rgba(0,0,0,.4)]">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded m-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto px-8 pb-8 pt-8">
            <table class="min-w-full">
                <thead class="bg-gray-800/50">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Report ID</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Staff Member</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Date of Incident</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Incident Type</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Description</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-cyan-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @forelse($incidents as $incident)
                        <tr class="hover:bg-wm-dark border-b border-gray-800/50">
                            <td class="py-3 px-4">{{ $incident->id }}</td>
                            <td class="py-3 px-4">{{ $incident->staff->first_name ?? 'N/A' }} {{ $incident->staff->last_name ?? '' }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($incident->incident_date)->format('F d, Y') }}</td>
                            <td class="py-3 px-4">{{ $incident->incident_type }}</td>
                            <td class="py-3 px-4">{{ Str::limit($incident->description, 70) }}</td>
                            <td class="py-3 px-4">
                                <form action="{{ route('reports.destroy', $incident->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this incident report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400 font-semibold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16 text-gray-400">No incidents reported yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6">
            {{ $incidents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection