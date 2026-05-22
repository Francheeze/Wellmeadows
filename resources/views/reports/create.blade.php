@extends('layouts.app', ['module' => 'Staff and Department'])

@section('title', 'Report New Incident')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto my-8">
        <h1 class="text-2xl font-bold text-[#1f3b5c] mb-6">Report a New Incident</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
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
                    <label for="staff_id" class="block text-sm font-medium text-gray-700">Staff Member Involved</label>
                    <select id="staff_id" name="staff_id" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1f3b5c] focus:border-[#1f3b5c] sm:text-sm" required>
                        <option value="">Select a staff member</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->staffNumber }}">{{ $member->firstName }} {{ $member->lastName }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="incident_date" class="block text-sm font-medium text-gray-700">Date of Incident</label>
                    <input type="date" id="incident_date" name="incident_date" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1f3b5c] focus:border-[#1f3b5c] sm:text-sm" required>
                </div>

                <div>
                    <label for="incident_type" class="block text-sm font-medium text-gray-700">Incident Type</label>
                    <input type="text" id="incident_type" name="incident_type" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1f3b5c] focus:border-[#1f3b5c] sm:text-sm" required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description of Incident</label>
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1f3b5c] focus:border-[#1f3b5c] sm:text-sm" required></textarea>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full px-4 py-2 bg-[#1f3b5c] text-white rounded hover:bg-[#2a4d7a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1f3b5c]">
                    Submit Report
                </button>
            </div>
        </form>
    </div>
@endsection