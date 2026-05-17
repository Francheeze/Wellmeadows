@extends('layouts.app', ['module' => 'Staff and Department'])

@section('title', $staff->firstName . ' ' . $staff->lastName)

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#1f3b5c]">{{ $staff->firstName }} {{ $staff->lastName }}</h1>
            <a href="{{ route('staff.index') }}" class="text-blue-600 hover:text-blue-800">← Back to All Staff</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-bold text-[#1f3b5c] mb-4">Personal Information</h2>
                <div class="space-y-2">
                    <p><strong>Staff Number:</strong> {{ $staff->staffNumber }}</p>
                    <p><strong>Address:</strong> {{ $staff->address }}</p>
                    <p><strong>Telephone:</strong> {{ $staff->telephoneNumber }}</p>
                    <p><strong>Date of Birth:</strong> {{ $staff->dateOfBirth->format('F j, Y') }}</p>
                    <p><strong>Sex:</strong> {{ $staff->sex }}</p>
                    <p><strong>National Insurance Number:</strong> {{ $staff->NIN }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-bold text-[#1f3b5c] mb-4">Employment Details</h2>
                <div class="space-y-2">
                    <p><strong>Department:</strong> {{ $staff->department }}</p>
                    <p><strong>Position:</strong> {{ $staff->position }}</p>
                    <p><strong>Current Salary:</strong> ₱ {{ number_format($staff->currentSalary, 2) }}</p>
                    <p><strong>Salary Scale:</strong> {{ $staff->salaryScale }}</p>
                    <p><strong>Hours Per Week:</strong> {{ $staff->hoursPerWeek }}</p>
                    <p><strong>Contract Type:</strong> {{ $staff->contractType }}</p>
                    <p><strong>Payment Type:</strong> {{ $staff->paymentType }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-bold text-[#1f3b5c] mb-4">Qualifications</h2>
            @if ($staff->qualifications->count() > 0)
                <ul class="list-disc list-inside">
                    @foreach ($staff->qualifications as $qualification)
                        <li>{{ $qualification->type }} from {{ $qualification->institution }} ({{ $qualification->date->format('Y') }})</li>
                    @endforeach
                </ul>
            @else
                <p>No qualifications listed.</p>
            @endif
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-bold text-[#1f3b5c] mb-4">Work Experience</h2>
            @if ($staff->workExperiences->count() > 0)
                <ul class="list-disc list-inside">
                    @foreach ($staff->workExperiences as $experience)
                        <li>{{ $experience->position }} at {{ $experience->organization }} ({{ $experience->startDate->format('Y') }} - {{ $experience->finishDate ? $experience->finishDate->format('Y') : 'Present' }})</li>
                    @endforeach
                </ul>
            @else
                <p>No work experience listed.</p>
            @endif
        </div>
    </div>
@endsection