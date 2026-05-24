@extends('layouts.app')

@section('title', 'Exam Result — Appointment #' . $examResult->appointment_number)

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { background-color: #021829; font-family: 'DM Sans', sans-serif; }
    .gradient-text {
        background: linear-gradient(135deg, #f0f7f8 30%, #CCECEE);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeSlideIn .4s ease both; }
</style>
@endpush

@section('content')

@php
    $isAdmitted   = $examResult->isAdmitted();
    $appointment  = $examResult->appointment;
    $patient      = $appointment?->patient;
    $inPatient    = $appointment?->inPatient;
    $outPatient   = $appointment?->outPatient;
@endphp

<div class="min-h-screen bg-[#021829] text-[#f0f7f8]">
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back Link --}}
    <a href="{{ route('exam_results.index') }}"
       class="inline-flex items-center gap-1.5 text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium mb-8 transition-colors no-underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Exam Results
    </a>

    {{-- Page Header --}}
    <div class="flex items-start justify-between gap-4 mb-8 flex-wrap">
        <div>
            <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">
                Wellmeadows Hospital
            </p>
            <h1 class="text-3xl font-bold gradient-text leading-tight">Exam Result</h1>
            <p class="text-[#CCECEE]/40 text-sm mt-1">
                Appointment #{{ $examResult->appointment_number }}
                &mdash;
                {{ $patient?->first_name }} {{ $patient?->last_name }}
            </p>
        </div>

        {{-- Result badge --}}
        @if ($isAdmitted)
            <span class="inline-flex items-center gap-2 bg-violet-500/10 border border-violet-500/25 text-violet-300 text-sm font-semibold px-4 py-2 rounded-full mt-1">
                <span class="w-2 h-2 rounded-full bg-violet-400"></span>
                Waiting List
            </span>
        @else
            <span class="inline-flex items-center gap-2 bg-sky-500/10 border border-sky-500/25 text-sky-300 text-sm font-semibold px-4 py-2 rounded-full mt-1">
                <span class="w-2 h-2 rounded-full bg-sky-400"></span>
                Out-patient
            </span>
        @endif
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="fade-in flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="fade-in flex items-center gap-3 bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Card 1: Exam Result Details ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10
             {{ $isAdmitted ? 'bg-violet-500/5' : 'bg-sky-500/5' }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                 {{ $isAdmitted ? 'bg-violet-500/15 border border-violet-500/20 text-violet-400' : 'bg-sky-500/15 border border-sky-500/20 text-sky-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Examination</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Result Details</h2>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Appointment No.</p>
                <p class="text-[#f0f7f8] font-mono font-bold text-lg leading-none mt-1">
                    {{ $examResult->appointment_number }}
                </p>
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Result</p>
                <div class="mt-1">
                    @if ($isAdmitted)
                        <span class="inline-flex items-center gap-1.5 bg-violet-500/10 border border-violet-500/20 text-violet-300 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                            Waiting List
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-sky-500/10 border border-sky-500/20 text-sky-300 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                            Out-patient
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Examined Date</p>
                <p class="text-[#f0f7f8] font-semibold text-sm mt-1">
                    {{ $examResult->examined_date->format('d M Y') }}
                </p>
                <p class="text-[#CCECEE]/30 text-xs">{{ $examResult->examined_date->format('l') }}</p>
            </div>

        </div>

    </div>

    {{-- ── Card 2: Patient Information ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-[#03416E]/20">
            <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/8 border border-[#CCECEE]/15 flex items-center justify-center text-[#CCECEE]/70 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Identity</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Patient Information</h2>
            </div>
            @if ($patient)
                <a href="{{ route('patients.show', $patient->patient_number) }}"
                   class="text-xs font-semibold text-[#CCECEE]/50 hover:text-[#CCECEE] transition no-underline">
                    View Profile →
                </a>
            @endif
        </div>

        <div class="p-6 grid grid-cols-2 sm:grid-cols-3 gap-6">

            <div class="flex flex-col gap-1 sm:col-span-2">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Full Name</p>
                <p class="text-[#f0f7f8] font-semibold text-lg mt-1">
                    {{ $patient?->first_name ?? '—' }} {{ $patient?->last_name ?? '' }}
                </p>
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Patient No.</p>
                <p class="text-[#f0f7f8] font-mono font-bold mt-1">
                    {{ $appointment?->patient_number ?? '—' }}
                </p>
            </div>

            @if ($patient)
                <div class="flex flex-col gap-1">
                    <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Date of Birth</p>
                    <p class="text-[#CCECEE]/70 text-sm mt-1">
                        {{ $patient->date_of_birth->format('d M Y') }}
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Telephone</p>
                    <p class="text-[#CCECEE]/70 text-sm mt-1">{{ $patient->telephone_number }}</p>
                </div>

                @if ($patient->localDoctor)
                    <div class="flex flex-col gap-1">
                        <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Referred By</p>
                        <p class="text-[#CCECEE]/70 text-sm mt-1">
                            {{ $patient->localDoctor->full_name }}
                        </p>
                    </div>
                @endif
            @endif

        </div>

    </div>

    {{-- ── Card 3: Appointment Details ── --}}
    @if ($appointment)
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-[#03416E]/30">
            <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/10 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Linked</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Appointment Details</h2>
            </div>
            <a href="{{ route('appointments.show', $appointment->appointment_number) }}"
               class="text-xs font-semibold text-[#CCECEE]/50 hover:text-[#CCECEE] transition no-underline">
                View Appointment →
            </a>
        </div>

        <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-6">

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Date</p>
                <p class="text-[#f0f7f8] font-semibold text-sm mt-1">
                    {{ $appointment->date_time->format('d M Y') }}
                </p>
                <p class="text-[#CCECEE]/30 text-xs">{{ $appointment->date_time->format('h:i A') }}</p>
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Room</p>
                <span class="inline-block bg-[#03416E]/60 text-[#CCECEE]/80 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#CCECEE]/15 mt-1 w-fit">
                    {{ $appointment->examination_room }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Staff No.</p>
                <p class="text-[#CCECEE]/70 font-mono text-sm mt-1">{{ $appointment->staff_number }}</p>
            </div>

        </div>

    </div>
    @endif

    {{-- ── Card 4: Admission Record (if exists) ── --}}
    @if ($inPatient || $outPatient)
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10
             {{ $inPatient ? 'bg-violet-500/5' : 'bg-sky-500/5' }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                 {{ $inPatient ? 'bg-violet-500/15 border border-violet-500/20 text-violet-400' : 'bg-sky-500/15 border border-sky-500/20 text-sky-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Admission</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">
                    {{ $inPatient ? 'In-Patient Record' : 'Out-Patient Record' }}
                </h2>
            </div>
            @if ($inPatient)
                <a href="{{ route('in_patients.show', $inPatient->appointment_number) }}"
                   class="text-xs font-semibold text-[#CCECEE]/50 hover:text-[#CCECEE] transition no-underline">
                    View Record →
                </a>
            @elseif ($outPatient)
                <a href="{{ route('out_patients.show', $outPatient->appointment_number) }}"
                   class="text-xs font-semibold text-[#CCECEE]/50 hover:text-[#CCECEE] transition no-underline">
                    View Record →
                </a>
            @endif
        </div>

        @if ($inPatient)
        <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-6">
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Ward</p>
                <p class="text-[#f0f7f8] font-semibold text-sm mt-1">{{ $inPatient->ward_number }}</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Bed</p>
                <p class="text-[#f0f7f8] font-semibold text-sm mt-1">{{ $inPatient->bed_number }}</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Date Placed</p>
                <p class="text-[#CCECEE]/70 text-sm mt-1">{{ $inPatient->date_placed->format('d M Y') }}</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Status</p>
                @if ($inPatient->isCurrentlyAdmitted())
                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold px-2.5 py-1 rounded-full mt-1 w-fit">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Still Admitted
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-[#CCECEE]/5 border border-[#CCECEE]/15 text-[#CCECEE]/40 text-xs font-semibold px-2.5 py-1 rounded-full mt-1 w-fit">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#CCECEE]/30"></span>
                        Discharged
                    </span>
                @endif
            </div>
        </div>
        @endif

        @if ($outPatient)
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Patient No.</p>
                <p class="text-[#f0f7f8] font-mono font-bold mt-1">{{ $outPatient->patient_number }}</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Appointment Date</p>
                <p class="text-[#f0f7f8] font-semibold text-sm mt-1">
                    {{ $outPatient->appointment_date_time->format('d M Y, h:i A') }}
                </p>
            </div>
        </div>
        @endif

    </div>
    @endif

    {{-- ── Action Footer ── --}}
    <div class="flex items-center justify-between gap-3 flex-wrap fade-in">

        {{-- Delete --}}
        <form action="{{ route('exam_results.destroy', $examResult->appointment_number) }}"
              method="POST"
              onsubmit="return confirm('Delete this exam result?\n\nAppointment #{{ $examResult->appointment_number }}\n\nThis cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-transparent border border-red-400/20 text-red-400/60 hover:bg-red-400/10 hover:border-red-400/40 hover:text-red-400 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
        </form>

        {{-- Edit --}}
        <a href="{{ route('exam_results.edit', $examResult->appointment_number) }}"
           class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 no-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Result
        </a>

    </div>

</div>
</div>
@endsection