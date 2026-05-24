@extends('layouts.app')

@section('title', 'In-Patient Record #' . $inPatient->appointment_number)

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
    $isAdmitted   = $inPatient->isCurrentlyAdmitted();
    $lengthOfStay = $inPatient->length_of_stay;

    // Progress bar calculation
    $totalDays   = max($inPatient->expected_stay, 1);
    $elapsedDays = $inPatient->date_placed->diffInDays(
        $inPatient->actual_leave ?? now()
    );
    $pct = min(round(($elapsedDays / $totalDays) * 100), 100);
@endphp

<div class="min-h-screen bg-[#021829] text-[#f0f7f8]">
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back Link --}}
    <a href="{{ route('in_patients.index') }}"
       class="inline-flex items-center gap-1.5 text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium mb-8 transition-colors no-underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to In-Patients
    </a>

    {{-- Page Header --}}
    <div class="flex items-start justify-between gap-4 mb-8 flex-wrap">
        <div>
            <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">Wellmeadows Hospital</p>
            <h1 class="text-3xl font-bold gradient-text leading-tight">In-Patient Record</h1>
            <p class="text-[#CCECEE]/40 text-sm mt-1">
                {{ $inPatient->patient->first_name ?? '' }} {{ $inPatient->patient->last_name ?? '' }}
                &mdash; Appt. #{{ $inPatient->appointment_number }}
            </p>
        </div>

        {{-- Admission status badge --}}
        @if ($isAdmitted)
            <span class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-sm font-semibold px-4 py-2 rounded-full mt-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Currently Admitted
            </span>
        @else
            <span class="inline-flex items-center gap-2 bg-[#CCECEE]/5 border border-[#CCECEE]/15 text-[#CCECEE]/40 text-sm font-semibold px-4 py-2 rounded-full mt-1">
                <span class="w-2 h-2 rounded-full bg-[#CCECEE]/30"></span>
                Discharged
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

    {{-- ── Card 1: Patient Information ── --}}
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
            @if ($inPatient->patient)
                <a href="{{ route('patients.show', $inPatient->patient_number) }}"
                   class="text-xs font-semibold text-[#CCECEE]/50 hover:text-[#CCECEE] transition no-underline">
                    View Profile →
                </a>
            @endif
        </div>

        <div class="p-6 grid grid-cols-2 sm:grid-cols-3 gap-6">

            <div class="flex flex-col gap-1 sm:col-span-2">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Full Name</p>
                <p class="text-[#f0f7f8] font-semibold text-lg mt-1">
                    {{ $inPatient->patient->first_name ?? '—' }} {{ $inPatient->patient->last_name ?? '' }}
                </p>
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Patient No.</p>
                <p class="text-[#f0f7f8] font-mono font-bold mt-1">{{ $inPatient->patient_number }}</p>
            </div>

            @if ($inPatient->patient)
                <div class="flex flex-col gap-1">
                    <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Date of Birth</p>
                    <p class="text-[#CCECEE]/70 text-sm mt-1">
                        {{ $inPatient->patient->date_of_birth->format('d M Y') }}
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Telephone</p>
                    <p class="text-[#CCECEE]/70 text-sm mt-1">{{ $inPatient->patient->telephone_number }}</p>
                </div>

                @if ($inPatient->patient->localDoctor)
                    <div class="flex flex-col gap-1">
                        <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Referred By</p>
                        <p class="text-[#CCECEE]/70 text-sm mt-1">
                            {{ $inPatient->patient->localDoctor->full_name }}
                        </p>
                    </div>
                @endif
            @endif

        </div>

    </div>

    {{-- ── Card 2: Admission Details ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-violet-500/5">
            <div class="w-9 h-9 rounded-xl bg-violet-500/15 border border-violet-500/20 flex items-center justify-center text-violet-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Admission</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Ward & Bed Details</h2>
            </div>
        </div>

        <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-6">

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Appointment No.</p>
                <p class="text-[#f0f7f8] font-mono font-bold mt-1">{{ $inPatient->appointment_number }}</p>
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Ward</p>
                <p class="text-[#f0f7f8] font-semibold text-sm mt-1">{{ $inPatient->ward_number }}</p>
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Bed</p>
                <p class="text-[#f0f7f8] font-semibold text-sm mt-1">{{ $inPatient->bed_number }}</p>
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Expected Stay</p>
                <div class="flex items-end gap-1 mt-1">
                    <span class="text-[#f0f7f8] font-bold text-2xl leading-none">{{ $inPatient->expected_stay }}</span>
                    <span class="text-[#CCECEE]/40 text-xs mb-0.5">days</span>
                </div>
            </div>

        </div>

        {{-- Stay progress bar --}}
        <div class="mx-6 mb-6">
            <div class="flex items-center justify-between text-xs text-[#CCECEE]/40 mb-2">
                <span>Stay progress</span>
                <span>{{ $pct }}% &nbsp;({{ $elapsedDays }} / {{ $totalDays }} days)</span>
            </div>
            <div class="h-1.5 bg-[#CCECEE]/8 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500
                    {{ $pct >= 100 ? 'bg-amber-400' : 'bg-violet-400' }}"
                     style="width: {{ $pct }}%">
                </div>
            </div>
            @if ($pct >= 100 && $isAdmitted)
                <p class="text-xs text-amber-400/70 mt-1.5">Expected stay exceeded — consider reviewing discharge.</p>
            @endif
        </div>

    </div>

    {{-- ── Card 3: Dates ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-[#03416E]/20">
            <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/8 border border-[#CCECEE]/15 flex items-center justify-center text-[#CCECEE]/70 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Timeline</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Stay Dates</h2>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">

            {{-- Date Placed --}}
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Date Placed</p>
                <div class="flex items-center gap-2 mt-1">
                    <svg class="w-3.5 h-3.5 text-[#CCECEE]/30 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-[#f0f7f8] font-semibold text-sm">{{ $inPatient->date_placed->format('d M Y') }}</span>
                </div>
            </div>

            {{-- Expected Leave --}}
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Expected Leave</p>
                @if ($inPatient->date_leave)
                    <div class="flex items-center gap-2 mt-1">
                        <svg class="w-3.5 h-3.5 text-[#CCECEE]/30 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-[#CCECEE]/70 text-sm">{{ $inPatient->date_leave->format('d M Y') }}</span>
                    </div>
                @else
                    <span class="text-[#CCECEE]/25 text-sm mt-1">Not set</span>
                @endif
            </div>

            {{-- Actual Leave --}}
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Actual Leave</p>
                @if ($inPatient->actual_leave)
                    <div class="flex items-center gap-2 mt-1">
                        <svg class="w-3.5 h-3.5 text-[#CCECEE]/30 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-[#f0f7f8] font-semibold text-sm">{{ $inPatient->actual_leave->format('d M Y') }}</span>
                    </div>
                    <p class="text-[#CCECEE]/30 text-xs mt-0.5">
                        {{ $lengthOfStay }} {{ Str::plural('day', $lengthOfStay) }} total stay
                    </p>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold px-2.5 py-1 rounded-full mt-1 w-fit">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Still Admitted
                    </span>
                @endif
            </div>

        </div>

    </div>

    {{-- ── Card 4: Exam Result (from linked appointment) ── --}}
    @if ($inPatient->appointment?->examResult)
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-emerald-500/5">
            <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Examination</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Exam Result</h2>
            </div>
            <a href="{{ route('appointments.show', $inPatient->appointment_number) }}"
               class="text-xs font-semibold text-[#CCECEE]/50 hover:text-[#CCECEE] transition no-underline">
                View Appointment →
            </a>
        </div>

        <div class="p-6 flex items-center gap-8 flex-wrap">
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Result</p>
                <span class="inline-flex items-center gap-1.5 bg-violet-500/10 border border-violet-500/20 text-violet-300 text-xs font-semibold px-2.5 py-1 rounded-full mt-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                    Waiting List
                </span>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Examined Date</p>
                <p class="text-[#f0f7f8] font-semibold text-sm mt-1">
                    {{ $inPatient->appointment->examResult->examined_date->format('d M Y') }}
                </p>
            </div>
        </div>

    </div>
    @endif

    {{-- ── Action Footer ── --}}
    <div class="flex items-center justify-between gap-3 flex-wrap fade-in">

        {{-- Left: Delete + Discharge --}}
        <div class="flex items-center gap-3 flex-wrap">

            {{-- Delete --}}
            <form action="{{ route('in_patients.destroy', $inPatient->appointment_number) }}"
                  method="POST"
                  onsubmit="return confirm('Delete in-patient record for {{ addslashes($inPatient->patient->first_name ?? '') }} {{ addslashes($inPatient->patient->last_name ?? '') }}?\n\nThis cannot be undone.')">
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

            {{-- Discharge button — only shown if still admitted --}}
            @if ($isAdmitted)
                <form action="{{ route('in_patients.discharge', $inPatient->appointment_number) }}"
                      method="POST"
                      onsubmit="return confirm('Discharge this patient today?\n\nThis will set the actual leave date to today.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 hover:bg-emerald-500/20 hover:border-emerald-500/40 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Discharge Patient
                    </button>
                </form>
            @endif

        </div>

        {{-- Right: Edit --}}
        <a href="{{ route('in_patients.edit', $inPatient->appointment_number) }}"
           class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 no-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Record
        </a>

    </div>

</div>
</div>
@endsection