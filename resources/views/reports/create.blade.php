@extends('layouts.app', ['module' => 'Staff and Department'])

@section('title', 'Report New Incident')

@push('styles')
<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>
@endpush

@section('content')
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_40px_rgba(0,0,0,.4)] p-8 max-w-2xl mx-auto my-8">
        <h1 class="text-2xl font-bold text-white mb-6">Report a New Incident</h1>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded relative mb-6" role="alert">
                <p class="font-bold mb-2">Please correct the errors below:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="staff_id" class="block text-sm font-medium text-gray-300">Staff Member Involved</label>
                    <select id="staff_id" name="staff_id" class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3" required>
                        <option value="">Select a staff member</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->staff_number }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="incident_date" class="block text-sm font-medium text-gray-300">Date of Incident</label>
                    <input type="date" id="incident_date" name="incident_date" class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3" required style="color-scheme: dark;">
                </div>

                <div>
                    <label for="incident_type" class="block text-sm font-medium text-gray-300">Incident Type</label>
                    <input type="text" id="incident_type" name="incident_type" class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3" required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300">Description of Incident</label>
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3" required></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-8">
                <a href="{{ route('reports') }}" class="text-gray-400 hover:text-white font-bold py-2 px-4">Cancel</a>
                <button type="submit" class="bg-cyan-300 hover:bg-cyan-400 text-gray-900 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    Submit Report
                </button>
            </div>
        </form>
    </div>
@endsection