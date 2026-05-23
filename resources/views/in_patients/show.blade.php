@extends('layouts.app')

@section('title', 'In-Patient Record — ' . $inPatient->appointment_number)

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .section-card { @apply bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_4px_24px_rgba(0,0,0,.3)]; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8 space-y-5">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 anim-fade-up">
        <a href="{{ route('in_patients.index') }}"
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-wm-cyan text-sm transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            In-Patients
        </a>
        <span class="text-slate-700">/</span>
        <span class="text-white text-sm font-semibold">{{ $inPatient->appointment_number }}</span>
    </div>

    {{-- Hero Card --}}
    <div class="section-card anim-fade-up" style="animation-delay:.04s">
        <div class="px-6 py-5 flex flex-wrap items-center justify-between gap-4">

            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl font-bold text-white leading-none">{{ $inPatient->patient->full_name }}</h1>
                        <span class="bg-wm-cyan/10 text-wm-cyan text-xs font-bold font-mono px-2.5 py-1 rounded-md">
                            {{ $inPatient->appointment_number }}
                        </span>
                        @if ($inPatient->actual_leave)
                            <span class="inline-flex items-center gap-1.5 bg-slate-500/10 text-slate-400 text-xs font-semibold px-2.5 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Discharged
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-cyan-500/10 text-cyan-400 text-xs font-semibold px-2.5 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>Admitted
                            </span>
                        @endif
                    </div>
                    <p class="text-slate-400 text-sm mt-1.5">Patient # {{ $inPatient->patient_number }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                @if (!$inPatient->actual_leave)
                    <form action="{{ route('in_patients.discharge', $inPatient->appointment_number) }}"
                          method="POST"
                          onsubmit="return confirm('Discharge this patient today?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="inline-flex items-center gap-2 border border-emerald-500/30 text-emerald-400 text-sm font-semibold
                                       px-4 py-2 rounded-xl hover:bg-emerald-500/10 hover:border-emerald-500/60 transition-all duration-200
                                       cursor-pointer bg-transparent">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Discharge
                        </button>
                    </form>
                @endif
                <a href="{{ route('in_patients.edit', $inPatient->appointment_number) }}"
                   class="inline-flex items-center gap-2 border border-amber-500/30 text-amber-400 text-sm font-semibold
                          px-4 py-2 rounded-xl hover:bg-amber-500/10 hover:border-amber-500/60 transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('in_patients.destroy', $inPatient->appointment_number) }}"
                      method="POST"
                      onsubmit="return confirm('Delete this in-patient record? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 border border-red-500/30 text-red-400 text-sm font-semibold
                                   px-4 py-2 rounded-xl hover:bg-red-500/10 hover:border-red-500/60 transition-all duration-200
                                   cursor-pointer bg-transparent">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Detail grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-wm-navy/40 border-t border-wm-navy/40">
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Ward</p>
                <p class="text-sm font-semibold text-white mt-0.5">{{ $inPatient->ward_number }}</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Bed</p>
                <p class="text-sm font-semibold text-white mt-0.5">{{ $inPatient->bed_number }}</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Expected Stay</p>
                <p class="text-sm font-semibold text-white mt-0.5">{{ $inPatient->expected_stay }} day(s)</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Date Placed</p>
                <p class="text-sm font-semibold text-white mt-0.5">{{ $inPatient->date_placed->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 divide-x divide-wm-navy/40 border-t border-wm-navy/40">
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Expected Leave</p>
                <p class="text-sm font-medium mt-0.5 {{ $inPatient->date_leave ? 'text-white' : 'text-slate-500' }}">
                    {{ $inPatient->date_leave ? $inPatient->date_leave->format('M d, Y') : '—' }}
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Actual Leave</p>
                <p class="text-sm font-medium mt-0.5 {{ $inPatient->actual_leave ? 'text-emerald-400' : 'text-slate-500' }}">
                    {{ $inPatient->actual_leave ? $inPatient->actual_leave->format('M d, Y') : 'Still admitted' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Patient Info Card --}}
    <div class="section-card anim-fade-up" style="animation-delay:.08s">
        <div class="flex items-center gap-3 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
            <div class="w-8 h-8 rounded-lg bg-wm-cyan/10 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="text-white font-semibold text-sm">Patient Information</h3>
            <a href="{{ route('patients.show', $inPatient->patient_number) }}"
               class="ml-auto text-xs font-semibold text-wm-cyan hover:text-wm-cyan-dim transition-colors">
                View Full Profile →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-wm-navy/40">
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Age</p>
                <p class="text-sm font-semibold text-white mt-0.5">{{ $inPatient->patient->age }} yrs</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Sex</p>
                <p class="text-sm font-semibold text-white mt-0.5">{{ $inPatient->patient->sex }}</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Telephone</p>
                <p class="text-sm font-semibold text-white mt-0.5 tabular-nums">{{ $inPatient->patient->telephone_number }}</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Referred By</p>
                <p class="text-sm font-semibold mt-0.5 {{ $inPatient->patient->localDoctor ? 'text-wm-cyan' : 'text-slate-500' }}">
                    {{ $inPatient->patient->localDoctor?->full_name ?? 'Walk-in' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Exam Result Card --}}
    @if ($inPatient->appointment?->examResult)
    <div class="section-card anim-fade-up" style="animation-delay:.11s">
        <div class="flex items-center gap-3 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-white font-semibold text-sm">Exam Result</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500 mb-1">Result</p>
            <span class="inline-block bg-purple-500/10 text-purple-400 text-xs font-semibold px-2.5 py-1 rounded-md">
                {{ $inPatient->appointment->examResult->result }}
            </span>
            @if ($inPatient->appointment->examResult->examined_date)
                <p class="text-xs text-slate-500 mt-2">
                    Examined: {{ \Carbon\Carbon::parse($inPatient->appointment->examResult->examined_date)->format('M d, Y') }}
                </p>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection