@extends('layouts.app')

@section('title', $patient->full_name)

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .row-hover:hover { background: rgba(204,236,238,.04); }
</style>
@endpush

@section('content')

@php
    $sexClasses = match($patient->sex) {
        'Male'   => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
        'Female' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
        default  => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
    };
    $maritalClasses = match($patient->marital_status) {
        'Married'   => 'bg-emerald-500/10 text-emerald-400',
        'Single'    => 'bg-slate-500/10 text-slate-400',
        'Divorced'  => 'bg-amber-500/10 text-amber-400',
        'Widowed'   => 'bg-purple-500/10 text-purple-400',
        'Separated' => 'bg-orange-500/10 text-orange-400',
        default     => 'bg-slate-500/10 text-slate-400',
    };
@endphp

<div class="max-w-5xl mx-auto px-6 py-8">

    {{-- ── Back Link ── --}}
    <a href="{{ route('patients.index') }}"
       class="inline-flex items-center gap-2 text-slate-400 text-sm font-medium
              hover:text-wm-cyan transition-colors duration-200 mb-8 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Patients
    </a>

    {{-- ── Hero Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl p-6 mb-5
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up">

        <div class="flex flex-wrap items-start justify-between gap-5">

            {{-- Left: avatar + name + badges --}}
            <div class="flex items-start gap-4 flex-1 min-w-0">
                <div class="w-14 h-14 rounded-2xl bg-wm-cyan/10 border border-wm-cyan/20
                            flex items-center justify-center text-wm-cyan shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold tracking-widest uppercase text-wm-cyan-dim mb-1">Patient Profile</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight mb-3">
                        {{ $patient->last_name }}, {{ $patient->first_name }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2">
                        {{-- Patient number --}}
                        <span class="inline-flex items-center gap-1.5 bg-wm-cyan/10 border border-wm-cyan/20
                                     text-wm-cyan text-xs font-bold font-mono px-2.5 py-1 rounded-md">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            {{ $patient->patient_number }}
                        </span>
                        {{-- Sex --}}
                        <span class="inline-flex items-center gap-1.5 border text-xs font-bold px-2.5 py-1 rounded-md {{ $sexClasses }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $patient->sex }}
                        </span>
                        {{-- Age --}}
                        <span class="inline-flex items-center gap-1 bg-wm-navy/60 border border-wm-navy
                                     text-slate-300 text-xs font-semibold px-2.5 py-1 rounded-md">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $patient->age }} yrs old
                        </span>
                        {{-- Marital status --}}
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-md {{ $maritalClasses }}">
                            {{ $patient->marital_status }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right: action buttons --}}
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                <a href="{{ route('patients.edit', $patient->patient_number) }}"
                   class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/30
                          text-amber-400 text-sm font-bold px-4 py-2.5 rounded-xl
                          hover:bg-amber-500/20 hover:border-amber-500/60 transition-all duration-200 no-underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('patients.destroy', $patient->patient_number) }}"
                      method="POST"
                      onsubmit="return confirmDelete(event, '{{ addslashes($patient->full_name) }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-red-500/8 border border-red-500/25
                                   text-red-400 text-sm font-bold px-4 py-2.5 rounded-xl cursor-pointer
                                   hover:bg-red-500/15 hover:border-red-500/50 transition-all duration-200 bg-transparent">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick stat strip --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-5 border-t border-wm-navy/60">
            <div class="text-center">
                <p class="text-xl font-bold text-white">{{ $patient->nextOfKins->count() }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Next of Kin</p>
            </div>
            <div class="text-center">
                <p class="text-xl font-bold text-white">{{ $patient->appointments->count() }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Appointments</p>
            </div>
            <div class="text-center">
                <p class="text-xl font-bold text-white">{{ $patient->inPatientRecords->count() }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Admissions</p>
            </div>
            <div class="text-center">
                <p class="text-xl font-bold text-white">{{ $patient->outPatientRecords->count() }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Out-Patient Visits</p>
            </div>
        </div>

    </div>

    {{-- ── 2-column info grid ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        {{-- Personal Information --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_4px_20px_rgba(0,0,0,.25)] anim-fade-up" style="animation-delay:.04s">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Personal Information</h3>
            </div>
            <div class="divide-y divide-wm-navy/30">

                <div class="flex items-start justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[120px] pt-0.5">Full Name</span>
                    <span class="text-sm font-medium text-white text-right">{{ $patient->full_name }}</span>
                </div>

                <div class="flex items-start justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[120px] pt-0.5">Date of Birth</span>
                    <div class="text-right">
                        <p class="text-sm font-medium text-white">{{ $patient->date_of_birth->format('F d, Y') }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $patient->age }} years old</p>
                    </div>
                </div>

                <div class="flex items-start justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[120px] pt-0.5">Sex</span>
                    <span class="inline-flex items-center gap-1.5 border text-xs font-bold px-2.5 py-1 rounded-md {{ $sexClasses }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $patient->sex }}
                    </span>
                </div>

                <div class="flex items-start justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[120px] pt-0.5">Marital Status</span>
                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-md {{ $maritalClasses }}">
                        {{ $patient->marital_status }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Contact & Registration --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_4px_20px_rgba(0,0,0,.25)] anim-fade-up" style="animation-delay:.06s">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Contact & Registration</h3>
            </div>
            <div class="divide-y divide-wm-navy/30">

                <div class="flex items-start justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[120px] pt-0.5">Telephone</span>
                    <span class="text-sm font-medium text-white tabular-nums text-right">{{ $patient->telephone_number }}</span>
                </div>

                <div class="flex items-start justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[120px] pt-0.5">Address</span>
                    <span class="text-sm text-slate-300 text-right leading-relaxed">{{ $patient->address }}</span>
                </div>

                <div class="flex items-start justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[120px] pt-0.5">Registered</span>
                    <span class="text-sm font-medium text-white text-right">{{ $patient->date_registered->format('F d, Y') }}</span>
                </div>

                <div class="flex items-start justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[120px] pt-0.5">Referred By</span>
                    @if ($patient->localDoctor)
                        <div class="text-right">
                            <p class="text-sm font-semibold text-white">{{ $patient->localDoctor->full_name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $patient->localDoctor->telephone_number }}</p>
                        </div>
                    @else
                        <span class="text-sm text-slate-600 italic">Walk-in / Not referred</span>
                    @endif
                </div>

            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════
         NEXT OF KIN
    ══════════════════════════════════════════════ --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                shadow-[0_4px_20px_rgba(0,0,0,.25)] mb-5 anim-fade-up" style="animation-delay:.08s">

        <div class="flex items-center justify-between px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zM21 10a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Next of Kin</h3>
                <span class="bg-wm-cyan/10 text-wm-cyan text-xs font-bold px-2 py-0.5 rounded-md">
                    {{ $patient->nextOfKins->count() }}
                </span>
            </div>
            <a href="{{ route('patients.next_of_kins.create', $patient->patient_number) }}"
               class="inline-flex items-center gap-1.5 bg-wm-cyan/10 border border-wm-cyan/25
                      text-wm-cyan text-xs font-bold px-3 py-1.5 rounded-lg
                      hover:bg-wm-cyan/20 hover:border-wm-cyan/50 transition-all duration-150 no-underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Next of Kin
            </a>
        </div>

        @if ($patient->nextOfKins->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-wm-navy/60 bg-wm-navy/10">
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Full Name</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Relationship</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Telephone</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Address</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold tracking-widest uppercase text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-wm-navy/30">
                        @foreach ($patient->nextOfKins as $kin)
                        <tr class="row-hover transition-colors duration-150">
                            <td class="px-5 py-3.5 font-semibold text-white">{{ $kin->full_name }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-block bg-wm-navy/60 border border-wm-navy
                                             text-wm-cyan-dim text-xs font-semibold px-2.5 py-1 rounded-md">
                                    {{ $kin->relationship }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-300 tabular-nums text-sm">{{ $kin->telephone_number ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-slate-400 text-xs max-w-[200px] truncate"
                                title="{{ $kin->address ?? '' }}">
                                {{ $kin->address ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('patients.next_of_kins.edit', [$patient->patient_number, $kin]) }}"
                                       class="inline-flex items-center justify-center w-7 h-7 rounded-lg border
                                              border-amber-500/25 text-amber-400 hover:bg-amber-500/10
                                              hover:border-amber-500/60 transition-all duration-150"
                                       title="Edit">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('patients.next_of_kins.destroy', [$patient->patient_number, $kin]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Remove {{ addslashes($kin->full_name) }} as next of kin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg border
                                                       border-red-500/25 text-red-400 hover:bg-red-500/10
                                                       hover:border-red-500/60 transition-all duration-150
                                                       cursor-pointer bg-transparent"
                                                title="Remove">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10 px-4">
                <svg class="w-10 h-10 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-slate-500 text-sm mb-3">No next-of-kin records on file.</p>
                <a href="{{ route('patients.next_of_kins.create', $patient->patient_number) }}"
                   class="inline-flex items-center gap-2 bg-wm-cyan/10 border border-wm-cyan/25
                          text-wm-cyan text-xs font-bold px-4 py-2 rounded-lg
                          hover:bg-wm-cyan/20 transition-all duration-150 no-underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Next of Kin
                </a>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════
         APPOINTMENTS
    ══════════════════════════════════════════════ --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                shadow-[0_4px_20px_rgba(0,0,0,.25)] mb-5 anim-fade-up" style="animation-delay:.1s">

        <div class="flex items-center justify-between px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Appointments</h3>
                <span class="bg-wm-cyan/10 text-wm-cyan text-xs font-bold px-2 py-0.5 rounded-md">
                    {{ $patient->appointments->count() }}
                </span>
            </div>
            <a href="{{ route('appointments.create', ['patient_number' => $patient->patient_number]) }}"
               class="inline-flex items-center gap-1.5 bg-wm-cyan/10 border border-wm-cyan/25
                      text-wm-cyan text-xs font-bold px-3 py-1.5 rounded-lg
                      hover:bg-wm-cyan/20 hover:border-wm-cyan/50 transition-all duration-150 no-underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                New Appointment
            </a>
        </div>

        @if ($patient->appointments->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-wm-navy/60 bg-wm-navy/10">
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Appt. #</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Date & Time</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Room</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Exam Result</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Admission</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold tracking-widest uppercase text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-wm-navy/30">
                        @foreach ($patient->appointments as $appt)
                        <tr class="row-hover transition-colors duration-150">
                            <td class="px-5 py-3.5">
                                <span class="inline-block bg-wm-navy/60 border border-wm-navy
                                             text-wm-cyan-dim text-xs font-bold font-mono
                                             px-2.5 py-1 rounded-md">
                                    {{ $appt->appointment_number }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                {{-- Adjust field name if needed: appointment_date, scheduled_at, date_time --}}
                                <p class="text-sm text-white font-medium">
                                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('h:i A') }}
                                </p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-300 text-sm">
                                {{-- Adjust field name if needed: examination_room, room_number --}}
                                {{ $appt->examination_room ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if ($appt->examResult)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-400
                                                 text-xs font-semibold px-2.5 py-1 rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        Recorded
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-slate-500/10 text-slate-500
                                                 text-xs font-semibold px-2.5 py-1 rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if ($appt->inPatient)
                                    <span class="inline-block bg-sky-500/10 text-sky-400 text-xs font-semibold px-2.5 py-1 rounded-md">
                                        In-Patient
                                    </span>
                                @elseif ($appt->outPatient)
                                    <span class="inline-block bg-purple-500/10 text-purple-400 text-xs font-semibold px-2.5 py-1 rounded-md">
                                        Out-Patient
                                    </span>
                                @else
                                    <span class="text-slate-600 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('appointments.show', $appt) }}"
                                       class="inline-flex items-center justify-center w-7 h-7 rounded-lg border
                                              border-wm-cyan/25 text-wm-cyan hover:bg-wm-cyan/10
                                              hover:border-wm-cyan transition-all duration-150"
                                       title="View appointment">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10 px-4">
                <svg class="w-10 h-10 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-slate-500 text-sm">No appointments on record for this patient.</p>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════
         IN-PATIENT RECORDS
    ══════════════════════════════════════════════ --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                shadow-[0_4px_20px_rgba(0,0,0,.25)] mb-5 anim-fade-up" style="animation-delay:.12s">

        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
            <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">In-Patient Admissions</h3>
            <span class="bg-wm-cyan/10 text-wm-cyan text-xs font-bold px-2 py-0.5 rounded-md">
                {{ $patient->inPatientRecords->count() }}
            </span>
        </div>

        @if ($patient->inPatientRecords->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-wm-navy/60 bg-wm-navy/10">
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Admission #</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Date Admitted</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Date Discharged</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Ward</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Bed</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-wm-navy/30">
                        @foreach ($patient->inPatientRecords as $record)
                        <tr class="row-hover transition-colors duration-150">
                            <td class="px-5 py-3.5">
                                <span class="inline-block bg-sky-500/10 text-sky-400 text-xs
                                             font-bold font-mono px-2.5 py-1 rounded-md">
                                    {{-- Adjust field: in_patient_number, admission_number --}}
                                    {{ $record->in_patient_number ?? $record->getKey() }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-sm text-white whitespace-nowrap">
                                {{-- Adjust field: date_admitted, admission_date --}}
                                {{ $record->date_admitted
                                    ? \Carbon\Carbon::parse($record->date_admitted)->format('M d, Y')
                                    : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-sm whitespace-nowrap">
                                {{-- Adjust field: date_discharged, discharge_date --}}
                                @if ($record->date_discharged)
                                    <span class="text-emerald-400">
                                        {{ \Carbon\Carbon::parse($record->date_discharged)->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-amber-400 text-xs font-semibold">Still admitted</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-300 text-sm">
                                {{-- Adjust field: ward_number, ward_id --}}
                                {{ $record->ward_number ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-300 text-sm">
                                {{-- Adjust field: bed_number, bed_id --}}
                                {{ $record->bed_number ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if (!$record->date_discharged)
                                    <span class="inline-flex items-center gap-1.5 bg-sky-500/10 text-sky-400
                                                 text-xs font-semibold px-2.5 py-1 rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-400
                                                 text-xs font-semibold px-2.5 py-1 rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        Discharged
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10 px-4">
                <svg class="w-10 h-10 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <p class="text-slate-500 text-sm">No in-patient admission records for this patient.</p>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════
         OUT-PATIENT RECORDS
    ══════════════════════════════════════════════ --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                shadow-[0_4px_20px_rgba(0,0,0,.25)] mb-5 anim-fade-up" style="animation-delay:.14s">

        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
            <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Out-Patient Visits</h3>
            <span class="bg-wm-cyan/10 text-wm-cyan text-xs font-bold px-2 py-0.5 rounded-md">
                {{ $patient->outPatientRecords->count() }}
            </span>
        </div>

        @if ($patient->outPatientRecords->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-wm-navy/60 bg-wm-navy/10">
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Visit #</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Date of Visit</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-wm-navy/30">
                        @foreach ($patient->outPatientRecords as $visit)
                        <tr class="row-hover transition-colors duration-150">
                            <td class="px-5 py-3.5">
                                <span class="inline-block bg-purple-500/10 text-purple-400 text-xs
                                             font-bold font-mono px-2.5 py-1 rounded-md">
                                    {{-- Adjust field: out_patient_number, visit_number --}}
                                    {{ $visit->out_patient_number ?? $visit->getKey() }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-sm text-white whitespace-nowrap">
                                {{-- Adjust field: date_visited, visit_date, date_time --}}
                                {{ isset($visit->date_visited)
                                    ? \Carbon\Carbon::parse($visit->date_visited)->format('M d, Y')
                                    : '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('out_patients.show', $visit) }}"
                                   class="inline-flex items-center justify-center w-7 h-7 rounded-lg border
                                          border-wm-cyan/25 text-wm-cyan hover:bg-wm-cyan/10
                                          hover:border-wm-cyan transition-all duration-150"
                                   title="View">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10 px-4">
                <svg class="w-10 h-10 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <p class="text-slate-500 text-sm">No out-patient visit records for this patient.</p>
            </div>
        @endif
    </div>

    {{-- ── Timestamps ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl
                shadow-[0_4px_20px_rgba(0,0,0,.25)] anim-fade-up" style="animation-delay:.16s">
        <div class="flex flex-wrap gap-6 px-5 py-4">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Record Created</p>
                <p class="text-sm text-white font-medium">{{ $patient->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Last Updated</p>
                <p class="text-sm text-white font-medium">{{ $patient->updated_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(e, name) {
        if (!confirm(
            `Delete patient "${name}"?\n\n` +
            `This will also delete their next-of-kin records.\n` +
            `Deletion is blocked if appointment, admission, or visit records exist.\n\n` +
            `This action cannot be undone.`
        )) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush