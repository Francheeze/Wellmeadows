@extends('layouts.app')

@section('title', 'Exam Result — ' . $examResult->appointment_number)

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 anim-fade-up">
        <a href="{{ route('exam_results.index') }}" class="hover:text-wm-cyan transition-colors">Exam Results</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-400">{{ $examResult->appointment_number }}</span>
    </nav>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-2xl px-5 py-4 mb-6 anim-fade-up">
            <p class="text-sm font-semibold text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-500/10 border border-red-500/30 rounded-2xl px-5 py-4 mb-6 anim-fade-up">
            <p class="text-sm font-semibold text-red-400">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ── Hero Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-6 mb-6
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.03s">
        <div class="flex flex-wrap items-start justify-between gap-4">

            {{-- Result Info --}}
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0
                            {{ $examResult->result === 'Out-patient' ? 'bg-green-500/10 border border-green-500/20' : 'bg-purple-500/10 border border-purple-500/20' }}">
                    @if ($examResult->result === 'Out-patient')
                        <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold
                                     font-mono px-2.5 py-1 rounded-md tracking-wide">
                            {{ $examResult->appointment_number }}
                        </span>
                        @if ($examResult->result === 'Out-patient')
                            <span class="inline-flex items-center gap-1.5 bg-green-500/10 text-green-400
                                         text-xs font-semibold px-2.5 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Out-patient
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-purple-500/10 text-purple-400
                                         text-xs font-semibold px-2.5 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Waiting List
                            </span>
                        @endif
                    </div>
                    <p class="text-xl font-bold text-white mt-1">
                        Examined on {{ $examResult->examined_date->format('D, M j, Y') }}
                    </p>
                    <p class="text-sm text-slate-400">
                        Appointment: {{ $examResult->appointment->date_time->format('M j, Y g:i A') }}
                        &middot; {{ $examResult->appointment->examination_room }}
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('exam_results.edit', $examResult->appointment_number) }}"
                   class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/25
                          text-amber-400 text-sm font-semibold px-4 py-2.5 rounded-xl
                          hover:bg-amber-500/20 hover:border-amber-500/50 transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('exam_results.index') }}"
                   class="inline-flex items-center gap-2 bg-wm-navy/60 border border-wm-navy
                          text-slate-400 text-sm font-semibold px-4 py-2.5 rounded-xl
                          hover:text-white hover:border-slate-600 transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>

        </div>
    </div>

    {{-- ── Patient Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-5 mb-6
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.05s">
        <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">Patient</h2>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-lg font-bold text-white">
                    {{ $examResult->appointment->patient->full_name }}
                </p>
                <div class="flex flex-wrap items-center gap-3 mt-1.5">
                    <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold
                                 font-mono px-2.5 py-1 rounded-md">
                        {{ $examResult->appointment->patient->patient_number }}
                    </span>
                    <span class="text-slate-400 text-sm">
                        {{ $examResult->appointment->patient->age }} yrs
                        &middot; {{ $examResult->appointment->patient->sex }}
                    </span>
                </div>
                @if ($examResult->appointment->patient->localDoctor)
                    <p class="text-xs text-slate-500 mt-1.5">
                        Referred by
                        <span class="text-wm-cyan">{{ $examResult->appointment->patient->localDoctor->full_name }}</span>
                    </p>
                @endif
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('appointments.show', $examResult->appointment_number) }}"
                   class="inline-flex items-center gap-2 bg-wm-navy/60 border border-wm-navy
                          text-slate-400 text-sm font-semibold px-4 py-2.5 rounded-xl
                          hover:text-white hover:border-slate-600 transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    View Appointment
                </a>
                <a href="{{ route('patients.show', $examResult->appointment->patient->patient_number) }}"
                   class="inline-flex items-center gap-2 bg-wm-navy/60 border border-wm-navy
                          text-slate-400 text-sm font-semibold px-4 py-2.5 rounded-xl
                          hover:text-white hover:border-slate-600 transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View Profile
                </a>
            </div>
        </div>
    </div>

    {{-- ── Out-Patient Record ── --}}
    @if ($examResult->appointment->outPatient)
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-5 mb-6
                    shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.07s">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan">Out-Patient Record</h2>
                <a href="{{ route('out_patients.index') }}"
                   class="text-xs font-semibold text-wm-cyan hover:text-wm-cyan-dim transition-colors">
                    View all →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Classification</p>
                    <span class="inline-flex items-center gap-1.5 bg-green-500/10 text-green-400
                                 text-xs font-semibold px-2.5 py-1 rounded-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Out-patient
                    </span>
                </div>
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Appointment Date</p>
                    <p class="text-sm font-semibold text-white">
                        {{ \Carbon\Carbon::parse($examResult->appointment->outPatient->appointment_date_time)->format('M j, Y g:i A') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- ── In-Patient Record ── --}}
    @if ($examResult->appointment->inPatient)
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-5 anim-fade-up"
             style="animation-delay:.07s">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan">In-Patient Admission</h2>
                <a href="{{ route('in_patients.index') }}"
                   class="text-xs font-semibold text-wm-cyan hover:text-wm-cyan-dim transition-colors">
                    View all →
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Ward</p>
                    <p class="text-sm font-semibold text-white">{{ $examResult->appointment->inPatient->ward ?? '—' }}</p>
                </div>
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Bed</p>
                    <p class="text-sm font-semibold text-white">{{ $examResult->appointment->inPatient->bed_number ?? '—' }}</p>
                </div>
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Date Placed</p>
                    <p class="text-sm font-semibold text-white">
                        {{ isset($examResult->appointment->inPatient->date_placed)
                            ? \Carbon\Carbon::parse($examResult->appointment->inPatient->date_placed)->format('M j, Y')
                            : '—' }}
                    </p>
                </div>
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Expected Leave</p>
                    <p class="text-sm font-semibold text-white">
                        {{ isset($examResult->appointment->inPatient->date_leave)
                            ? \Carbon\Carbon::parse($examResult->appointment->inPatient->date_leave)->format('M j, Y')
                            : '—' }}
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection