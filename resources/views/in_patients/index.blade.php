@extends('layouts.app')

@section('title', 'In-Patients')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .row-hover:hover { background: rgba(204,236,238,.04); }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 anim-fade-up">
        {{-- Currently Admitted --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">{{ number_format($currentlyAdmitted) }}</p>
                <p class="text-xs text-slate-400 font-medium mt-1">Currently Admitted</p>
            </div>
        </div>
    </div>

    {{-- Search & Filter Toolbar --}}
    <form method="GET" action="{{ route('in_patients.index') }}"
          class="flex flex-wrap items-center gap-3 mb-4 anim-fade-up"
          style="animation-delay:.05s">

        {{-- Search input --}}
        <div class="relative flex-1 min-w-52">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 pointer-events-none"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by patient, ward, or bed…"
                class="w-full bg-wm-card border border-wm-navy/60 rounded-xl text-white text-sm
                       placeholder-slate-500 pl-9 pr-4 py-2.5
                       focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                       transition-all duration-200"
            >
        </div>

        {{-- Status filter --}}
        <select name="status"
                onchange="this.form.submit()"
                class="bg-wm-card border border-wm-navy/60 rounded-xl text-sm text-white
                       px-3 py-2.5 focus:outline-none focus:border-wm-cyan/60
                       focus:ring-2 focus:ring-wm-cyan/15 transition-all duration-200
                       appearance-none cursor-pointer pr-8"
                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right .75rem center;">
            <option value="" {{ !request('status') ? 'selected' : '' }}>All Records</option>
            <option value="admitted" {{ request('status') === 'admitted' ? 'selected' : '' }}>Admitted</option>
            <option value="discharged" {{ request('status') === 'discharged' ? 'selected' : '' }}>Discharged</option>
        </select>

        {{-- Search button --}}
        <button type="submit"
                class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                       px-4 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors duration-200 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            Search
        </button>

        {{-- Clear filters --}}
        @if (request()->hasAny(['search', 'status']))
            <a href="{{ route('in_patients.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400
                      hover:text-white transition-colors duration-200 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear
            </a>
        @endif

        {{-- Add button --}}
        <div class="ml-auto flex items-center gap-3 shrink-0">
            <a href="{{ route('in_patients.create') }}"
               class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                      px-5 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Admit Patient
            </a>
        </div>

    </form>

    {{-- Table Card --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.08s">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-wm-navy/60 bg-wm-navy/20">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Patient</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Ward & Bed</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Date Placed</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Date Leave</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Status</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold tracking-widest uppercase text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-wm-navy/30">
                    @forelse ($inPatients as $inPatient)
                    <tr class="row-hover transition-colors duration-150">

                        {{-- Patient --}}
                        <td class="px-5 py-4">
                            <p class="font-semibold text-white leading-snug">{{ $inPatient->patient->full_name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">#{{ $inPatient->patient->patient_number }}</p>
                        </td>

                        {{-- Ward & Bed --}}
                        <td class="px-5 py-4">
                            <p class="font-semibold text-white">{{ $inPatient->ward_number }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Bed #{{ $inPatient->bed_number }}</p>
                        </td>

                        {{-- Date Placed --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="font-semibold text-white">{{ $inPatient->date_placed->format('D, M j, Y') }}</p>
                        </td>

                        {{-- Date Leave --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($inPatient->actual_leave)
                                <p class="font-semibold text-white">{{ $inPatient->actual_leave->format('D, M j, Y') }}</p>
                            @else
                                <p class="text-slate-400">{{ $inPatient->date_leave ? $inPatient->date_leave->format('D, M j, Y') : 'N/A' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Expected</p>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($inPatient->actual_leave)
                                <span class="inline-flex items-center gap-1.5 bg-slate-500/10 text-slate-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    Discharged
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-cyan-500/10 text-cyan-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    Admitted
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1.5">
                                {{-- View --}}
                                <a href="{{ route('in_patients.show', $inPatient->appointment_number) }}"
                                   title="View in-patient record"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                          border-wm-cyan/25 text-wm-cyan hover:bg-wm-cyan/10
                                          hover:border-wm-cyan transition-all duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <p class="text-lg font-bold text-white mb-1">No In-Patient Records Found</p>
                            <p class="text-slate-400">
                                @if (request()->hasAny(['search', 'status']))
                                    Your search did not return any results.
                                @else
                                    There are no in-patient records yet.
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($inPatients->hasPages())
            <div class="px-6 py-4 border-t border-wm-navy/60">
                {{ $inPatients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection