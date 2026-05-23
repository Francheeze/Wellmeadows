@extends('layouts.app')

@section('title', $localDoctor->full_name)

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .row-hover:hover { background: rgba(204,236,238,.04); }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 anim-fade-up">
        <a href="{{ route('local_doctors.index') }}" class="hover:text-wm-cyan transition-colors">Local Doctors</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-400 truncate max-w-[200px]">{{ $localDoctor->full_name }}</span>
    </nav>

    {{-- ── Hero Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-6 mb-6
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.03s">
        <div class="flex flex-wrap items-start justify-between gap-4">

            {{-- Doctor Info --}}
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-wm-cyan/10 border border-wm-cyan/20
                            flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white leading-tight">{{ $localDoctor->full_name }}</h1>
                    <div class="flex flex-wrap items-center gap-3 mt-1.5">
                        <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold
                                     font-mono px-2.5 py-1 rounded-md tracking-wide">
                            {{ $localDoctor->clinic_number }}
                        </span>
                        <span class="text-slate-400 text-sm">{{ $localDoctor->telephone_number }}</span>
                    </div>
                    @if ($localDoctor->address)
                        <p class="text-slate-500 text-sm mt-1">{{ $localDoctor->address }}</p>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('local_doctors.edit', $localDoctor->clinic_number) }}"
                   class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/25
                          text-amber-400 text-sm font-semibold px-4 py-2.5 rounded-xl
                          hover:bg-amber-500/20 hover:border-amber-500/50 transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('local_doctors.index') }}"
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

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-5 pt-5 border-t border-wm-navy/60">
            <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                <p class="text-2xl font-bold text-white tabular-nums">
                    {{ $referredPatients->total() }}
                </p>
                <p class="text-xs text-slate-400 mt-0.5">Total Referred Patients</p>
            </div>
            <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                <p class="text-2xl font-bold text-white tabular-nums">
                    {{ $localDoctor->patients()->where('sex', 'Male')->count() }}
                </p>
                <p class="text-xs text-slate-400 mt-0.5">Male Patients</p>
            </div>
            <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                <p class="text-2xl font-bold text-white tabular-nums">
                    {{ $localDoctor->patients()->where('sex', 'Female')->count() }}
                </p>
                <p class="text-xs text-slate-400 mt-0.5">Female Patients</p>
            </div>
        </div>

    </div>

    {{-- ── Referred Patients Table ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.07s">

        <div class="flex items-center justify-between px-5 py-4 border-b border-wm-navy/60">
            <h2 class="text-sm font-bold text-white">
                Referred Patients
                <span class="ml-2 text-xs font-normal text-slate-500">({{ $referredPatients->total() }} total)</span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-wm-navy/60 bg-wm-navy/20">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400 whitespace-nowrap">Patient #</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Full Name</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Age</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Sex</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400 whitespace-nowrap">Registered</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold tracking-widest uppercase text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-wm-navy/30">
                    @forelse ($referredPatients as $patient)
                    <tr class="row-hover transition-colors duration-150">

                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold
                                         font-mono px-2.5 py-1 rounded-md tracking-wide">
                                {{ $patient->patient_number }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-semibold text-white leading-snug">
                                {{ $patient->last_name }}, {{ $patient->first_name }}
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5 truncate max-w-[180px]"
                               title="{{ $patient->address }}">
                                {{ $patient->address }}
                            </p>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-white font-medium tabular-nums">{{ $patient->age }}</span>
                            <span class="text-slate-500 text-xs ml-0.5">yrs</span>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($patient->sex === 'Male')
                                <span class="inline-flex items-center gap-1.5 bg-sky-500/10 text-sky-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Male
                                </span>
                            @elseif ($patient->sex === 'Female')
                                <span class="inline-flex items-center gap-1.5 bg-rose-500/10 text-rose-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Female
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-slate-500/10 text-slate-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Other
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-slate-400 text-xs">
                                {{ $patient->date_registered->format('M d, Y') }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('patients.show', $patient->patient_number) }}"
                               title="View patient profile"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                      border-wm-cyan/25 text-wm-cyan hover:bg-wm-cyan/10
                                      hover:border-wm-cyan transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="text-center py-12 px-4">
                                <svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-slate-500 text-sm">No patients referred by this doctor yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($referredPatients->hasPages())
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-t border-wm-navy/60">
            <span class="text-xs text-slate-500">
                Showing {{ $referredPatients->firstItem() }}–{{ $referredPatients->lastItem() }}
                of {{ number_format($referredPatients->total()) }} patients
            </span>
            <div class="[&_.pagination]:flex [&_.pagination]:gap-1
                        [&_.page-link]:flex [&_.page-link]:items-center [&_.page-link]:justify-center
                        [&_.page-link]:min-w-[2rem] [&_.page-link]:h-8 [&_.page-link]:px-2.5
                        [&_.page-link]:rounded-lg [&_.page-link]:text-xs [&_.page-link]:font-medium
                        [&_.page-link]:border [&_.page-link]:border-wm-navy/60
                        [&_.page-link]:text-slate-400 [&_.page-link]:bg-transparent [&_.page-link]:no-underline
                        [&_.page-link]:transition-all
                        [&_.page-link:hover]:bg-wm-cyan/10 [&_.page-link:hover]:border-wm-cyan/40 [&_.page-link:hover]:text-wm-cyan
                        [&_.active_.page-link]:bg-wm-cyan [&_.active_.page-link]:border-wm-cyan [&_.active_.page-link]:text-wm-dark [&_.active_.page-link]:font-bold
                        [&_.disabled_.page-link]:opacity-30 [&_.disabled_.page-link]:pointer-events-none">
                {{ $referredPatients->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection