@extends('layouts.app')

@section('title', 'Medication Record')

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
    $compositeParams = [
        'patient_number' => $medication->patient_number,
        'drug_number'    => $medication->drug_number,
        'start_date'     => $medication->start_date->toDateString(),
    ];
    $drug   = $medication->pharmaceuticalItem;
    $active = $medication->isActive();
@endphp

<div class="min-h-screen bg-[#021829] text-[#f0f7f8]">
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back link --}}
    <a href="{{ route('patient_medications.index') }}"
       class="inline-flex items-center gap-1.5 text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium mb-8 transition-colors no-underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Patient Medications
    </a>

    {{-- Page header --}}
    <div class="flex items-start justify-between gap-4 mb-8 flex-wrap">
        <div>
            <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">Wellmeadows Hospital</p>
            <h1 class="text-3xl font-bold gradient-text leading-tight">Medication Record</h1>
            <p class="text-[#CCECEE]/40 text-sm mt-1">
                Patient
                <span class="font-mono text-[#CCECEE]/70 font-semibold">{{ $medication->patient_number }}</span>
                &mdash;
                {{ $drug->drug_name ?? $medication->drug_number }}
            </p>
        </div>

        {{-- Status badge --}}
        @if ($active)
            <span class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-sm font-semibold px-4 py-2 rounded-full mt-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Active
            </span>
        @else
            <span class="inline-flex items-center gap-2 bg-[#CCECEE]/5 border border-[#CCECEE]/15 text-[#CCECEE]/40 text-sm font-semibold px-4 py-2 rounded-full mt-1">
                <span class="w-2 h-2 rounded-full bg-[#CCECEE]/30"></span>
                Finished
            </span>
        @endif
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="fade-in flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Card 1: Patient & Drug Identity ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-[#03416E]/30">
            <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/10 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Identity</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Patient & Drug</h2>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">

            {{-- Patient Number --}}
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Patient Number</p>
                <p class="text-[#f0f7f8] font-mono font-bold text-lg">{{ $medication->patient_number }}</p>
                <p class="text-[#CCECEE]/30 text-xs">Composite key field</p>
            </div>

            {{-- Drug --}}
            <div class="flex flex-col gap-1 sm:col-span-2">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Drug</p>
                <p class="text-[#f0f7f8] font-semibold text-lg">{{ $drug->drug_name ?? '—' }}</p>
                <p class="text-[#CCECEE]/40 text-xs font-mono">{{ $medication->drug_number }}</p>
            </div>

        </div>

        {{-- Drug detail strip --}}
        @if ($drug)
        <div class="mx-6 mb-6 bg-sky-500/5 border border-sky-500/15 rounded-xl px-4 py-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-sky-400/60">Dosage</span>
                <span class="text-sky-300 font-semibold text-xs bg-sky-500/10 border border-sky-500/20 px-2.5 py-1 rounded-full">
                    {{ $drug->dosage }}
                </span>
            </div>
            <div class="w-px h-4 bg-[#CCECEE]/10 hidden sm:block"></div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-sky-400/60">Method</span>
                <span class="text-sky-300/80">{{ $drug->method_of_administration }}</span>
            </div>
            <div class="w-px h-4 bg-[#CCECEE]/10 hidden sm:block"></div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-sky-400/60">Supplier</span>
                <span class="text-sky-300/80">{{ $drug->supplier->supplier_name ?? '—' }}</span>
            </div>
        </div>
        @endif

    </div>

    {{-- ── Card 2: Prescription Details ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-emerald-500/5">
            <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Prescription</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Details</h2>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">

            {{-- Units per day --}}
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Units per Day</p>
                <div class="flex items-end gap-1.5 mt-1">
                    <span class="text-[#f0f7f8] font-bold text-3xl leading-none">{{ $medication->units_per_day }}</span>
                    <span class="text-[#CCECEE]/40 text-sm mb-0.5">/ day</span>
                </div>
            </div>

            {{-- Start date --}}
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Start Date</p>
                <div class="flex items-center gap-2 mt-1">
                    <svg class="w-4 h-4 text-[#CCECEE]/30 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-[#f0f7f8] font-semibold">{{ $medication->start_date->format('d M Y') }}</span>
                </div>
                <p class="text-[#CCECEE]/30 text-xs mt-0.5">Composite key field</p>
            </div>

            {{-- Finish date --}}
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Finish Date</p>
                @if ($medication->finish_date)
                    <div class="flex items-center gap-2 mt-1">
                        <svg class="w-4 h-4 text-[#CCECEE]/30 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-[#f0f7f8] font-semibold">{{ $medication->finish_date->format('d M Y') }}</span>
                    </div>
                    @php $days = $medication->start_date->diffInDays($medication->finish_date); @endphp
                    <p class="text-[#CCECEE]/30 text-xs mt-0.5">{{ $days }} {{ Str::plural('day', $days) }} course</p>
                @else
                    <div class="flex items-center gap-2 mt-1">
                        <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Ongoing
                        </span>
                    </div>
                    <p class="text-[#CCECEE]/30 text-xs mt-0.5">No end date set</p>
                @endif
            </div>

        </div>

        {{-- Duration progress bar (only when both dates are set) --}}
        @if ($medication->finish_date)
        @php
            $totalDays   = max($medication->start_date->diffInDays($medication->finish_date), 1);
            $elapsedDays = min($medication->start_date->diffInDays(now()), $totalDays);
            $pct         = round(($elapsedDays / $totalDays) * 100);
            $pct         = max(0, min(100, $pct));
        @endphp
        <div class="mx-6 mb-6">
            <div class="flex items-center justify-between text-xs text-[#CCECEE]/40 mb-2">
                <span>Course progress</span>
                <span>{{ $pct }}% &nbsp;({{ $elapsedDays }} / {{ $totalDays }} days)</span>
            </div>
            <div class="h-1.5 bg-[#CCECEE]/8 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500
                    {{ $pct >= 100 ? 'bg-[#CCECEE]/30' : 'bg-emerald-400' }}"
                     style="width: {{ $pct }}%">
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ── Action footer ── --}}
    <div class="flex items-center justify-between gap-3 flex-wrap fade-in">

        {{-- Delete --}}
        <form action="{{ route('patient_medications.destroy') }}" method="POST"
              onsubmit="return confirm('Delete this medication record?\n\nPatient: {{ $medication->patient_number }}\nDrug: {{ addslashes($drug->drug_name ?? $medication->drug_number) }}\n\nThis cannot be undone.')">
            @csrf
            @method('DELETE')
            <input type="hidden" name="patient_number" value="{{ $medication->patient_number }}">
            <input type="hidden" name="drug_number"    value="{{ $medication->drug_number }}">
            <input type="hidden" name="start_date"     value="{{ $medication->start_date->toDateString() }}">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-transparent border border-red-400/20 text-red-400/60 hover:bg-red-400/10 hover:border-red-400/40 hover:text-red-400 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete Record
            </button>
        </form>

        {{-- Edit --}}
        <a href="{{ route('patient_medications.edit', $compositeParams) }}"
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