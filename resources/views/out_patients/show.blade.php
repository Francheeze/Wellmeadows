@extends('layouts.app')

@section('title', 'Out-Patient Record — ' . $outPatient->appointment_number)

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .section-card { @apply bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_4px_24px_rgba(0,0,0,.3)]; }
    .row-hover:hover { background: rgba(204,236,238,.04); }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8 space-y-5">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 anim-fade-up">
        <a href="{{ route('out_patients.index') }}"
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-wm-cyan text-sm transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Out-Patients
        </a>
        <span class="text-slate-700">/</span>
        <span class="text-white text-sm font-semibold">{{ $outPatient->appointment_number }}</span>
    </div>

    {{-- Hero Card --}}
    <div class="section-card anim-fade-up" style="animation-delay:.04s">
        <div class="px-6 py-5 flex flex-wrap items-center justify-between gap-4">

            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-teal-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl font-bold text-white leading-none">{{ $outPatient->patient->full_name }}</h1>
                        <span class="bg-wm-cyan/10 text-wm-cyan text-xs font-bold font-mono px-2.5 py-1 rounded-md">
                            {{ $outPatient->appointment_number }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-teal-500/10 text-teal-400 text-xs font-semibold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Out-Patient
                        </span>
                    </div>
                    <p class="text-slate-400 text-sm mt-1.5">Patient # {{ $outPatient->patient_number }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('out_patients.edit', $outPatient->appointment_number) }}"
                   class="inline-flex items-center gap-2 border border-amber-500/30 text-amber-400 text-sm font-semibold
                          px-4 py-2 rounded-xl hover:bg-amber-500/10 hover:border-amber-500/60 transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('out_patients.destroy', $outPatient->appointment_number) }}"
                      method="POST"
                      onsubmit="return confirm('Delete this out-patient record? This cannot be undone.')">
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

        {{-- Appointment datetime strip --}}
        <div class="flex items-center gap-2 px-6 py-3.5 border-t border-wm-navy/40">
            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span class="text-sm text-slate-300">
                Appointment on
                <span class="font-semibold text-white">
                    {{ \Carbon\Carbon::parse($outPatient->appointment_date_time)->format('D, M d, Y \a\t g:i A') }}
                </span>
            </span>
        </div>
    </div>

    {{-- Patient Details Card --}}
    <div class="section-card anim-fade-up" style="animation-delay:.08s">
        <div class="flex items-center gap-3 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
            <div class="w-8 h-8 rounded-lg bg-wm-cyan/10 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="text-white font-semibold text-sm">Patient Information</h3>
            <a href="{{ route('patients.show', $outPatient->patient_number) }}"
               class="ml-auto text-xs font-semibold text-wm-cyan hover:text-wm-cyan-dim transition-colors">
                View Full Profile →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-wm-navy/40">
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Age</p>
                <p class="text-sm font-semibold text-white mt-0.5">{{ $outPatient->patient->age }} yrs</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Sex</p>
                <p class="text-sm font-semibold text-white mt-0.5">{{ $outPatient->patient->sex }}</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Telephone</p>
                <p class="text-sm font-semibold text-white mt-0.5 tabular-nums">{{ $outPatient->patient->telephone_number }}</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Referred By</p>
                <p class="text-sm font-semibold mt-0.5 {{ $outPatient->patient->localDoctor ? 'text-wm-cyan' : 'text-slate-500' }}">
                    {{ $outPatient->patient->localDoctor?->full_name ?? 'Walk-in' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Next of Kin Card --}}
    @if ($outPatient->patient->nextOfKins->count())
    <div class="section-card anim-fade-up" style="animation-delay:.11s">
        <div class="flex items-center gap-3 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-white font-semibold text-sm">Next of Kin</h3>
        </div>
        @foreach ($outPatient->patient->nextOfKins as $kin)
        <div class="flex items-start justify-between gap-3 px-5 py-3.5 row-hover
                    {{ !$loop->last ? 'border-b border-wm-navy/30' : '' }}">
            <div>
                <p class="text-sm font-semibold text-white">{{ $kin->full_name }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $kin->relationship }}</p>
            </div>
            <p class="text-xs text-slate-400 tabular-nums shrink-0">{{ $kin->telephone_number }}</p>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Exam Result Card --}}
    @if ($outPatient->appointment?->examResult)
    <div class="section-card anim-fade-up" style="animation-delay:.13s">
        <div class="flex items-center gap-3 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
            <div class="w-8 h-8 rounded-lg bg-teal-500/10 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-white font-semibold text-sm">Exam Result</h3>
        </div>
        <div class="px-5 py-4 flex items-center gap-4">
            <span class="inline-block bg-teal-500/10 text-teal-400 text-xs font-semibold px-2.5 py-1 rounded-md">
                {{ $outPatient->appointment->examResult->result }}
            </span>
            @if ($outPatient->appointment->examResult->examined_date)
                <span class="text-xs text-slate-500">
                    Examined: {{ \Carbon\Carbon::parse($outPatient->appointment->examResult->examined_date)->format('M d, Y') }}
                </span>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection