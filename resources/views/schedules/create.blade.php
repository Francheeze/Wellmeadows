@extends('layouts.app')

@section('title', 'Add New Schedule')

@section('styles')
<style>
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>
@endsection

@push('styles')
<style>
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_40px_rgba(0,0,0,.4)] p-8">
        <h1 class="text-2xl font-bold text-white mb-6">Add a New Schedule</h1>

        {{-- Display validation errors if any --}}
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded relative mb-6" role="alert">
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
                <label for="staff_id" class="block text-gray-300 text-sm font-bold mb-2">Staff Member:</label>
                <select name="staff_id" id="staff_id" class="bg-gray-800 border border-gray-600 text-white rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
                    <option value="">Select a staff member</option>
                    @foreach($staff as $staffMember)
                        <option value="{{ $staffMember->staffNumber }}" data-department="{{ $staffMember->department }}">{{ $staffMember->firstName }} {{ $staffMember->lastName }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Department --}}
            <div class="mb-4">
                <label for="department" class="block text-gray-300 text-sm font-bold mb-2">Department:</label>
                <select name="department" id="department" class="bg-gray-800 border border-gray-600 text-white rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
                    <option value="">Select a department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Start Time --}}
            <div class="mb-4">
                <label for="start_time" class="block text-gray-300 text-sm font-bold mb-2">Start Time:</label>
                <input type="datetime-local" name="start_time" id="start_time" class="bg-gray-800 border border-gray-600 text-white rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
            </div>

            {{-- End Time --}}
            <div class="mb-6">
                <label for="end_time" class="block text-gray-300 text-sm font-bold mb-2">End Time:</label>
                <input type="datetime-local" name="end_time" id="end_time" class="bg-gray-800 border border-gray-600 text-white rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-end">
                <a href="{{ route('schedules.index') }}" class="text-gray-400 hover:text-white mr-4">Cancel</a>
                <button type="submit" class="bg-cyan-300 hover:bg-cyan-400 text-gray-900 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    Add Schedule
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const staffSelect = document.getElementById('staff_id');
        const departmentSelect = document.getElementById('department');

        staffSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const department = selectedOption.getAttribute('data-department');
            
            if (department) {
                departmentSelect.value = department;
            } else {
                departmentSelect.value = '';
            }
        });
    });
</script>
@endpush

@endsection