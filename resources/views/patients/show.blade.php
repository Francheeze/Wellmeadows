@extends('layouts.app')

@section('title', $patient->full_name . ' — Patient Profile')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .section-card {
        @apply bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_4px_24px_rgba(0,0,0,.3)];
    }
    .section-header {
        @apply flex items-center justify-between gap-3 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20;
    }
    .detail-label {
        @apply text-xs font-semibold uppercase tracking-widest text-slate-500;
    }
    .detail-value {
        @apply text-sm font-medium text-white mt-0.5;
    }
    .row-hover:hover { background: rgba(204,236,238,.04); }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8 space-y-5">

    {{-- ── Breadcrumb ── --}}
    <div class="flex items-center gap-3 anim-fade-up">
        <a href="{{ route('patients.index') }}"
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-wm-cyan text-sm transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Patients
        </a>
        <span class="text-slate-700">/</span>
        <span class="text-white text-sm font-semibold">{{ $patient->full_name }}</span>
    </div>

    {{-- ── Profile Hero Card ── --}}
    <div class="section-card anim-fade-up" style="animation-delay:.04s">
        <div class="px-6 py-5 flex flex-wrap items-center justify-between gap-4">

            {{-- Avatar + Name --}}
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0
                            {{ $patient->sex === 'Male' ? 'bg-sky-500/10' : ($patient->sex === 'Female' ? 'bg-rose-500/10' : 'bg-slate-500/10') }}">
                    <svg class="w-7 h-7 {{ $patient->sex === 'Male' ? 'text-sky-400' : ($patient->sex === 'Female' ? 'text-rose-400' : 'text-slate-400') }}"
                         fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl font-bold text-white leading-none">{{ $patient->full_name }}</h1>
                        <span class="bg-wm-cyan/10 text-wm-cyan text-xs font-bold font-mono px-2.5 py-1 rounded-md">
                            {{ $patient->patient_number }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                        {{-- Age --}}
                        <span class="text-slate-400 text-sm">{{ $patient->age }} yrs</span>
                        <span class="text-slate-700">·</span>
                        {{-- Sex badge --}}
                        @if ($patient->sex === 'Male')
                            <span class="inline-flex items-center gap-1.5 bg-sky-500/10 text-sky-400 text-xs font-semibold px-2 py-0.5 rounded">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Male
                            </span>
                        @elseif ($patient->sex === 'Female')
                            <span class="inline-flex items-center gap-1.5 bg-rose-500/10 text-rose-400 text-xs font-semibold px-2 py-0.5 rounded">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Female
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-slate-500/10 text-slate-400 text-xs font-semibold px-2 py-0.5 rounded">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Other
                            </span>
                        @endif
                        <span class="text-slate-700">·</span>
                        {{-- Marital status --}}
                        @php
                            $statusClasses = match($patient->marital_status) {
                                'Married'   => 'bg-emerald-500/10 text-emerald-400',
                                'Single'    => 'bg-slate-500/10 text-slate-400',
                                'Divorced'  => 'bg-amber-500/10 text-amber-400',
                                'Widowed'   => 'bg-purple-500/10 text-purple-400',
                                'Separated' => 'bg-orange-500/10 text-orange-400',
                                default     => 'bg-slate-500/10 text-slate-400',
                            };
                        @endphp
                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded {{ $statusClasses }}">
                            {{ $patient->marital_status }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('patients.edit', $patient->patient_number) }}"
                   class="inline-flex items-center gap-2 border border-amber-500/30 text-amber-400 text-sm font-semibold
                          px-4 py-2 rounded-xl hover:bg-amber-500/10 hover:border-amber-500/60 transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('patients.destroy', $patient->patient_number) }}"
                      method="POST"
                      onsubmit="return confirmDelete(event, '{{ addslashes($patient->full_name) }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 border border-red-500/30 text-red-400 text-sm font-semibold
                                   px-4 py-2 rounded-xl hover:bg-red-500/10 hover:border-red-500/60 transition-all duration-200
                                   cursor-pointer bg-transparent">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>

        </div>

        {{-- Detail row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-wm-navy/40 border-t border-wm-navy/40">

            <div class="px-5 py-4">
                <p class="detail-label">Date of Birth</p>
                <p class="detail-value">{{ $patient->date_of_birth->format('M d, Y') }}</p>
            </div>

            <div class="px-5 py-4">
                <p class="detail-label">Telephone</p>
                <p class="detail-value tabular-nums">{{ $patient->telephone_number }}</p>
            </div>

            <div class="px-5 py-4">
                <p class="detail-label">Date Registered</p>
                <p class="detail-value">{{ $patient->date_registered->format('M d, Y') }}</p>
            </div>

            <div class="px-5 py-4">
                <p class="detail-label">Referred By</p>
                @if ($patient->localDoctor)
                    <p class="detail-value text-wm-cyan">{{ $patient->localDoctor->full_name }}</p>
                @else
                    <p class="detail-value text-slate-500">Walk-in / None</p>
                @endif
            </div>

        </div>

        {{-- Address --}}
        <div class="px-5 py-3.5 border-t border-wm-navy/40 flex items-start gap-2">
            <svg class="w-4 h-4 text-slate-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="text-sm text-slate-400">{{ $patient->address }}</span>
        </div>
    </div>

    {{-- ── Two-col layout for secondary sections ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- ── Next of Kin ── --}}
        <div class="section-card anim-fade-up" style="animation-delay:.08s">
            <div class="section-header">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-sm leading-none">Next of Kin</h3>
                        <p class="text-slate-500 text-xs mt-0.5">{{ $patient->nextOfKins->count() }} record(s)</p>
                    </div>
                </div>
                <a href="{{ route('next_of_kins.create', ['patient_number' => $patient->patient_number]) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-wm-cyan
                          hover:text-wm-cyan-dim transition-colors duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add
                </a>
            </div>

            @forelse ($patient->nextOfKins as $kin)
                <div class="flex items-start justify-between gap-3 px-5 py-3.5 row-hover
                            {{ !$loop->last ? 'border-b border-wm-navy/30' : '' }}">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-white leading-snug truncate">{{ $kin->full_name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $kin->relationship }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-xs text-slate-400 tabular-nums">{{ $kin->telephone_number }}</p>
                        <p class="text-xs text-slate-600 mt-0.5 max-w-[140px] truncate text-right" title="{{ $kin->address }}">
                            {{ $kin->address }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 px-4">
                    <svg class="w-10 h-10 text-slate-700 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-slate-600 text-sm">No next-of-kin records.</p>
                </div>
            @endforelse
        </div>

        {{-- ── Appointments ── --}}
        <div class="section-card anim-fade-up" style="animation-delay:.10s">
            <div class="section-header">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-sm leading-none">Appointments</h3>
                        <p class="text-slate-500 text-xs mt-0.5">{{ $patient->appointments->count() }} total</p>
                    </div>
                </div>
                <a href="{{ route('appointments.create', ['patient_number' => $patient->patient_number]) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-wm-cyan
                          hover:text-wm-cyan-dim transition-colors duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add
                </a>
            </div>

            @forelse ($patient->appointments->take(5) as $appt)
                <div class="flex items-center justify-between gap-3 px-5 py-3.5 row-hover
                            {{ !$loop->last ? 'border-b border-wm-navy/30' : '' }}">
                    <div class="min-w-0">
                        <p class="text-xs font-bold font-mono text-wm-cyan">{{ $appt->appointment_number }}</p>
                        <p class="text-sm text-white font-medium leading-snug mt-0.5">
                            {{ \Carbon\Carbon::parse($appt->appointment_datetime)->format('M d, Y · g:i A') }}
                        </p>
                        @if($appt->room)
                            <p class="text-xs text-slate-500 mt-0.5">Room {{ $appt->room }}</p>
                        @endif
                    </div>
                    <div class="shrink-0">
                        @php
                            $apptStatusClass = match(strtolower($appt->status ?? '')) {
                                'completed'  => 'bg-emerald-500/10 text-emerald-400',
                                'pending'    => 'bg-amber-500/10 text-amber-400',
                                'cancelled'  => 'bg-red-500/10 text-red-400',
                                default      => 'bg-slate-500/10 text-slate-400',
                            };
                        @endphp
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-md {{ $apptStatusClass }}">
                            {{ ucfirst($appt->status ?? 'N/A') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 px-4">
                    <svg class="w-10 h-10 text-slate-700 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <p class="text-slate-600 text-sm">No appointments on record.</p>
                </div>
            @endforelse

            @if ($patient->appointments->count() > 5)
                <div class="px-5 py-3 border-t border-wm-navy/40 text-center">
                    <a href="{{ route('appointments.index', ['patient_number' => $patient->patient_number]) }}"
                       class="text-xs text-wm-cyan hover:text-wm-cyan-dim font-semibold transition-colors">
                        View all {{ $patient->appointments->count() }} appointments →
                    </a>
                </div>
            @endif
        </div>

    </div>

    {{-- ── In-Patient Records ── --}}
    <div class="section-card anim-fade-up" style="animation-delay:.12s">
        <div class="section-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-sm leading-none">In-Patient Admissions</h3>
                    <p class="text-slate-500 text-xs mt-0.5">{{ $patient->inPatientRecords->count() }} record(s)</p>
                </div>
            </div>
        </div>

        @if ($patient->inPatientRecords->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-wm-navy/40 bg-wm-navy/10">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">Ward & Bed</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">Date Placed</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">Date Leave</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-wm-navy/30">
                    @foreach ($patient->inPatientRecords as $record)
                    <tr class="row-hover">
                        <td class="px-5 py-3.5 text-white">
                            @if($record->ward)
                                <span class="font-medium">{{ $record->ward }}</span>
                                @if($record->bed)
                                    <span class="text-slate-500"> / {{ $record->bed }}</span>
                                @endif
                            @else
                                <span class="text-slate-500">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-slate-300 whitespace-nowrap">
                            {{ $record->date_placed ? \Carbon\Carbon::parse($record->date_placed)->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-slate-300 whitespace-nowrap">
                            {{ $record->date_leave ? \Carbon\Carbon::parse($record->date_leave)->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $inStatusClass = match(strtolower($record->status ?? '')) {
                                    'admitted'    => 'bg-purple-500/10 text-purple-400',
                                    'discharged'  => 'bg-emerald-500/10 text-emerald-400',
                                    default       => 'bg-slate-500/10 text-slate-400',
                                };
                            @endphp
                            <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-md {{ $inStatusClass }}">
                                {{ ucfirst($record->status ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-10 px-4">
                <svg class="w-10 h-10 text-slate-700 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <p class="text-slate-600 text-sm">No in-patient admissions on record.</p>
            </div>
        @endif
    </div>

    {{-- ── Out-Patient Records ── --}}
    <div class="section-card anim-fade-up" style="animation-delay:.14s">
        <div class="section-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-sm leading-none">Out-Patient Visits</h3>
                    <p class="text-slate-500 text-xs mt-0.5">{{ $patient->outPatientRecords->count() }} record(s)</p>
                </div>
            </div>
        </div>

        @if ($patient->outPatientRecords->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-wm-navy/40 bg-wm-navy/10">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">Appointment #</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">Appointment Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-wm-navy/30">
                    @foreach ($patient->outPatientRecords as $outRecord)
                    <tr class="row-hover">
                        <td class="px-5 py-3.5">
                            @if($outRecord->appointment_number)
                                <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold font-mono px-2.5 py-1 rounded-md">
                                    {{ $outRecord->appointment_number }}
                                </span>
                            @else
                                <span class="text-slate-500">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-slate-300 whitespace-nowrap">
                            {{ $outRecord->appointment_date
                                ? \Carbon\Carbon::parse($outRecord->appointment_date)->format('M d, Y')
                                : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-10 px-4">
                <svg class="w-10 h-10 text-slate-700 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-slate-600 text-sm">No out-patient visits on record.</p>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(e, name) {
        if (!confirm(`Delete patient "${name}"?\n\nThis will also remove their next-of-kin records.\nAppointments, admissions, and visit records will block deletion.\n\nThis action cannot be undone.`)) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush