@extends('layouts.app')

@section('title', 'Add New Schedule')

@section('content')
<div class="p-8">
    <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Add a New Schedule</h1>

        {{-- Display validation errors if any --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('schedules.store') }}" method="POST">
            @csrf

            {{-- Staff Member --}}
            <div class="mb-4">
                <label for="staff_id" class="block text-gray-700 text-sm font-bold mb-2">Staff Member:</label>
                <select name="staff_id" id="staff_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select a staff member</option>
                    @foreach($staff as $staffMember)
                        <option value="{{ $staffMember->staffNumber }}" data-department="{{ $staffMember->department }}">{{ $staffMember->firstName }} {{ $staffMember->lastName }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Department --}}
            <div class="mb-4">
                <label for="department" class="block text-gray-700 text-sm font-bold mb-2">Department:</label>
                <select name="department" id="department" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select a department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Start Time --}}
            <div class="mb-4">
                <label for="start_time" class="block text-gray-700 text-sm font-bold mb-2">Start Time:</label>
                <input type="datetime-local" name="start_time" id="start_time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            {{-- End Time --}}
            <div class="mb-6">
                <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">End Time:</label>
                <input type="datetime-local" name="end_time" id="end_time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-end">
                <a href="{{ route('schedules.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancel</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Add Schedule
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('staff_id').addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        var department = selectedOption.getAttribute('data-department');
        if (department) {
            document.getElementById('department').value = department;
        }
    });
</script>
@endpush

@endsection