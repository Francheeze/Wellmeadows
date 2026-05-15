@extends('layouts.app')

@section('title', 'Edit Patient Medication')

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
    .wm-select {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23CCECEE' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round' opacity='0.4'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }
    .locked-field {
        background-color: #021829;
        border: 1.5px solid rgba(204,236,238,.1);
        color: rgba(240,247,248,.35);
        cursor: not-allowed;
        border-radius: .75rem;
        padding: .625rem 1rem;
        font-size: .875rem;
        width: 100%;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#021829] text-[#f0f7f8]">
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back Link --}}
    <a href="{{ route('patient_medications.index') }}"
       class="inline-flex items-center gap-1.5 text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium mb-8 transition-colors no-underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Patient Medications
    </a>

    {{-- Page Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">Wellmeadows Hospital</p>
        <h1 class="text-3xl font-bold gradient-text leading-tight">Edit Medication Record</h1>
        <p class="text-[#CCECEE]/40 text-sm mt-1">
            Patient <span class="text-[#CCECEE]/70 font-semibold">{{ $medication->patient_number }}</span>
            &mdash;
            {{ $medication->pharmaceuticalItem->drug_name ?? $medication->drug_number }}
        </p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-medium p-4 rounded-xl mb-6 fade-in">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold mb-1">Please fix the following errors:</p>
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- ── MAIN EDIT FORM ── --}}
    <form action="{{ route('patient_medications.update') }}" method="POST" id="medForm">
        @csrf
        @method('PUT')

        {{-- Hidden composite PK fields --}}
        <input type="hidden" name="patient_number" value="{{ $medication->patient_number }}">
        <input type="hidden" name="drug_number"    value="{{ $medication->drug_number }}">
        <input type="hidden" name="start_date"     value="{{ $medication->start_date->toDateString() }}">

        {{-- ── Section 1: Locked Identity ── --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

            <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-[#03416E]/20">
                <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/8 border border-[#CCECEE]/15 flex items-center justify-center text-[#CCECEE]/50 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/40">Record Identity</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]/60">Locked — cannot be changed</h2>
                </div>
                <span class="text-xs font-bold tracking-wider uppercase text-amber-400/70 bg-amber-400/8 border border-amber-400/20 px-3 py-1 rounded-full">
                    Read-only
                </span>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5">

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Patient Number</label>
                    <div class="locked-field font-mono">{{ $medication->patient_number }}</div>
                    <p class="text-xs text-[#CCECEE]/25">Part of composite key</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Drug</label>
                    <div class="locked-field">{{ $medication->pharmaceuticalItem->drug_name ?? $medication->drug_number }}</div>
                    <p class="text-xs text-[#CCECEE]/25">Part of composite key</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/40">Start Date</label>
                    <div class="locked-field">{{ $medication->start_date->format('d M Y') }}</div>
                    <p class="text-xs text-[#CCECEE]/25">Part of composite key</p>
                </div>

            </div>

            {{-- Drug info strip --}}
            @if ($medication->pharmaceuticalItem)
            <div class="mx-6 mb-6">
                <div class="bg-sky-500/5 border border-sky-500/15 rounded-xl px-4 py-3 flex items-center gap-4 text-sm flex-wrap">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-sky-400/60">Dosage</span>
                        <span class="text-sky-300 font-semibold text-xs bg-sky-500/10 border border-sky-500/20 px-2.5 py-1 rounded-full">
                            {{ $medication->pharmaceuticalItem->dosage }}
                        </span>
                    </div>
                    <div class="w-px h-4 bg-[#CCECEE]/10 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-sky-400/60">Method</span>
                        <span class="text-sky-300/80 text-sm">{{ $medication->pharmaceuticalItem->method_of_administration }}</span>
                    </div>
                    <div class="w-px h-4 bg-[#CCECEE]/10 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-sky-400/60">Drug #</span>
                        <span class="font-mono text-sky-300/60 text-xs">{{ $medication->drug_number }}</span>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- ── Section 2: Editable Fields ── --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-t-2xl overflow-hidden shadow-2xl fade-in">

            <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-emerald-500/5">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Editable</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Prescription Details</h2>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Units per day --}}
                <div class="flex flex-col gap-1.5">
                    <label for="units_per_day" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Units per Day <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input
                        type="number"
                        id="units_per_day"
                        name="units_per_day"
                        value="{{ old('units_per_day', $medication->units_per_day) }}"
                        min="1"
                        placeholder="e.g. 2"
                        class="bg-[#021829] border {{ $errors->has('units_per_day') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    @error('units_per_day')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Finish Date --}}
                <div class="flex flex-col gap-1.5">
                    <label for="finish_date" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Finish Date
                        <span class="normal-case text-[#CCECEE]/30 font-normal ml-1">— optional</span>
                    </label>
                    <input
                        type="date"
                        id="finish_date"
                        name="finish_date"
                        value="{{ old('finish_date', $medication->finish_date?->toDateString()) }}"
                        min="{{ $medication->start_date->toDateString() }}"
                        class="bg-[#021829] border {{ $errors->has('finish_date') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    <p class="text-xs text-[#CCECEE]/40">
                        Must be on or after start date ({{ $medication->start_date->format('d M Y') }}).
                        Clear to mark as ongoing.
                    </p>
                    @error('finish_date')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Current status indicator --}}
            <div class="mx-6 mb-6">
                <div class="flex items-center gap-2 text-xs font-medium text-[#CCECEE]/40">
                    <span>Current status:</span>
                    @if ($medication->isActive())
                        <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-[#CCECEE]/5 border border-[#CCECEE]/15 text-[#CCECEE]/40 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#CCECEE]/30"></span>
                            Finished
                        </span>
                    @endif
                </div>
            </div>

        </div>
        {{-- medForm closes here — no footer inside it --}}
    </form>

    {{-- ── ACTION FOOTER — completely outside medForm ── --}}
    <div class="flex items-center justify-between gap-3 px-6 py-4
                bg-[#032d4f] border border-[#CCECEE]/15 border-t border-t-[#CCECEE]/10
                rounded-b-2xl shadow-2xl fade-in flex-wrap">

        {{-- Delete — its own separate form, no relation to medForm --}}
        <form action="{{ route('patient_medications.destroy') }}" method="POST"
              onsubmit="return confirm('Delete this medication record?\n\nPatient: {{ $medication->patient_number }}\nDrug: {{ addslashes($medication->pharmaceuticalItem->drug_name ?? $medication->drug_number) }}\n\nThis cannot be undone.')">
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

        {{-- Cancel + Save --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('patient_medications.index') }}"
               class="inline-flex items-center gap-2 bg-transparent border border-[#CCECEE]/20 text-[#CCECEE]/50 hover:border-[#CCECEE]/40 hover:text-[#CCECEE]/80 text-sm font-semibold px-5 py-2.5 rounded-xl transition no-underline">
                Cancel
            </a>
            {{-- form="medForm" links this button to the edit form above even though it's outside it --}}
            <button type="submit" form="medForm" id="submitBtn"
                    class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            </button>
        </div>

    </div>

</div>
</div>

<script>
    document.getElementById('medForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
            viewBox="0 0 24 24" style="animation:spin .7s linear infinite;">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
        </svg> Saving…`;
    });
</script>
@endsection