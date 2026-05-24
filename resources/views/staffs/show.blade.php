@extends('layouts.app', ['module' => 'Staff and Department'])

@section('title', $staff->first_name . ' ' . $staff->last_name)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_40px_rgba(0,0,0,.4)] p-8 text-white">
            <div class="flex justify-between items-center mb-6 border-b border-wm-navy/60 pb-4">
                <h1 class="text-3xl font-bold">{{ $staff->first_name }} {{ $staff->last_name }}</h1>
                <a href="{{ route('staff.index') }}" class="text-cyan-300 hover:text-cyan-400 transition-colors">← Back to All Staff</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                <div>
                    <h2 class="text-xl font-semibold text-white mb-4">Personal Information</h2>
                    <div class="space-y-3 text-gray-300">
                        <p><strong class="font-medium text-gray-100">Staff Number:</strong> {{ $staff->staff_number }}</p>
                        <p><strong class="font-medium text-gray-100">Address:</strong> {{ $staff->address }}</p>
                        <p><strong class="font-medium text-gray-100">Telephone:</strong> {{ $staff->telephone_number }}</p>
                        <p><strong class="font-medium text-gray-100">Date of Birth:</strong> {{ $staff->date_of_birth?->format('F j, Y') }}</p>
                        <p><strong class="font-medium text-gray-100">Sex:</strong> {{ $staff->sex }}</p>
                        <p><strong class="font-medium text-gray-100">National Insurance Number:</strong> {{ $staff->nin }}</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-white mb-4">Employment Details</h2>
                    <div class="space-y-3 text-gray-300">
                        <p><strong class="font-medium text-gray-100">Department:</strong> {{ $staff->department->name ?? 'N/A' }}</p>
                        <p><strong class="font-medium text-gray-100">Position:</strong> {{ $staff->position }}</p>
                        <p><strong class="font-medium text-gray-100">Current Salary:</strong> ₱ {{ number_format($staff->current_salary, 2) }}</p>
                        <p><strong class="font-medium text-gray-100">Salary Scale:</strong> {{ $staff->salary_scale }}</p>
                        <p><strong class="font-medium text-gray-100">Hours Per Week:</strong> {{ $staff->hours_per_week }}</p>
                        <p><strong class="font-medium text-gray-100">Contract Type:</strong> {{ $staff->contract_type }}</p>
                        <p><strong class="font-medium text-gray-100">Payment Type:</strong> {{ $staff->payment_type }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <h2 class="text-xl font-semibold text-white mb-4">Qualifications</h2>
                @if ($staff->qualifications->count() > 0)
                    <ul class="list-disc list-inside space-y-2 text-gray-300">
                        @foreach ($staff->qualifications as $qualification)
                            <li><span class="text-gray-100">{{ $qualification->type }}</span> from {{ $qualification->institution }} ({{ $qualification->date->format('Y') }})</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400">No qualifications listed.</p>
                @endif
            </div>

            <div class="mt-10">
                <h2 class="text-xl font-semibold text-white mb-4">Work Experience</h2>
                @if ($staff->workExperiences->count() > 0)
                    <ul class="list-disc list-inside space-y-2 text-gray-300">
                        @foreach ($staff->workExperiences as $experience)
                            <li><span class="text-gray-100">{{ $experience->position }}</span> at {{ $experience->organization }} ({{ $experience->start_date?->format('Y') }} - {{ $experience->finish_date?->format('Y') ?? 'Present' }})</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400">No work experience listed.</p>
                @endif
            </div>
        </div>
    </div>
@endsection