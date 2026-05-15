@extends('layouts.app')

@section('title', 'Add Patient Medication')

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
        <h1 class="text-3xl font-bold gradient-text leading-tight">Add Patient Medication</h1>
        <p class="text-[#CCECEE]/40 text-sm mt-1">Record a new drug prescription for a patient.</p>
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

    <form action="{{ route('patient_medications.store') }}" method="POST" id="medForm">
        @csrf

        {{-- ── Section 1: Patient & Drug ── --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

            {{-- Section header --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-[#03416E]/30">
                <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/10 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Step 1</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Patient & Drug</h2>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Patient Number --}}
                <div class="flex flex-col gap-1.5">
                    <label for="patient_number" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Patient Number <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input
                        type="text"
                        id="patient_number"
                        name="patient_number"
                        value="{{ old('patient_number') }}"
                        placeholder="e.g. P1001"
                        class="bg-[#021829] border {{ $errors->has('patient_number') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    <p class="text-xs text-[#CCECEE]/40">Enter the patient's unique ID number.</p>
                    @error('patient_number')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Drug --}}
                <div class="flex flex-col gap-1.5">
                    <label for="drug_number" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Drug <span class="text-[#CCECEE]">*</span>
                    </label>
                    <select
                        id="drug_number"
                        name="drug_number"
                        class="wm-select bg-[#021829] border {{ $errors->has('drug_number') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                        onchange="updateDrugInfo(this)"
                    >
                        <option value="" disabled {{ old('drug_number') ? '' : 'selected' }}>— Select a drug —</option>
                        @foreach ($drugs as $drug)
                            <option
                                value="{{ $drug->drug_number }}"
                                data-dosage="{{ $drug->dosage }}"
                                data-method="{{ $drug->method_of_administration }}"
                                {{ old('drug_number') == $drug->drug_number ? 'selected' : '' }}
                            >
                                #{{ $drug->drug_number }} — {{ $drug->drug_name }} ({{ $drug->dosage }})
                            </option>
                        @endforeach
                    </select>
                    @error('drug_number')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Drug Info Preview --}}
            <div id="drugInfoPreview" class="mx-6 mb-6 hidden">
                <div class="bg-sky-500/5 border border-sky-500/15 rounded-xl px-4 py-3 flex items-center gap-4 text-sm flex-wrap">
                    <div class="flex items-center gap-2 text-sky-300/70">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-xs font-semibold uppercase tracking-wider text-sky-400/60">Dosage</span>
                    </div>
                    <span id="previewDosage" class="text-sky-300 font-semibold text-xs bg-sky-500/10 border border-sky-500/20 px-2.5 py-1 rounded-full">—</span>
                    <div class="w-px h-4 bg-[#CCECEE]/10 hidden sm:block"></div>
                    <div class="flex items-center gap-2 text-sky-300/70">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="text-xs font-semibold uppercase tracking-wider text-sky-400/60">Method</span>
                    </div>
                    <span id="previewMethod" class="text-sky-300/80 text-sm">—</span>
                </div>
            </div>
        </div>

        {{-- ── Section 2: Prescription Details ── --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl fade-in">

            <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-emerald-500/5">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Step 2</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Prescription Details</h2>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5">

                {{-- Units per day --}}
                <div class="flex flex-col gap-1.5">
                    <label for="units_per_day" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Units per Day <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input
                        type="number"
                        id="units_per_day"
                        name="units_per_day"
                        value="{{ old('units_per_day', 1) }}"
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

                {{-- Start Date --}}
                <div class="flex flex-col gap-1.5">
                    <label for="start_date" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Start Date <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ old('start_date', date('Y-m-d')) }}"
                        class="bg-[#021829] border {{ $errors->has('start_date') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    <p class="text-xs text-[#CCECEE]/40">Also part of the unique record key.</p>
                    @error('start_date')
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
                        value="{{ old('finish_date') }}"
                        class="bg-[#021829] border {{ $errors->has('finish_date') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    <p class="text-xs text-[#CCECEE]/40">Leave blank if ongoing.</p>
                    @error('finish_date')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Footer / Submit --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-[#CCECEE]/10 bg-[#03416E]/10">
                <a href="{{ route('patient_medications.index') }}"
                   class="inline-flex items-center gap-2 bg-transparent border border-[#CCECEE]/20 text-[#CCECEE]/50 hover:border-[#CCECEE]/40 hover:text-[#CCECEE]/80 text-sm font-semibold px-5 py-2.5 rounded-xl transition no-underline">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Medication
                </button>
            </div>
        </div>

    </form>
</div>
</div>

<script>
    // Show drug info preview when a drug is selected
    function updateDrugInfo(select) {
        const option  = select.options[select.selectedIndex];
        const preview = document.getElementById('drugInfoPreview');
        if (!option || !option.value) { preview.classList.add('hidden'); return; }
        document.getElementById('previewDosage').textContent = option.dataset.dosage || '—';
        document.getElementById('previewMethod').textContent = option.dataset.method || '—';
        preview.classList.remove('hidden');
    }

    // Enforce finish_date >= start_date on the client side
    document.getElementById('start_date').addEventListener('change', function () {
        document.getElementById('finish_date').min = this.value;
    });

    // Disable submit button on submit to prevent double-posting
    document.getElementById('medForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
            viewBox="0 0 24 24" style="animation:spin .7s linear infinite;">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
        </svg> Saving…`;
    });

    // Trigger preview on page load if old() value is present (validation redirect)
    document.addEventListener('DOMContentLoaded', function () {
        const sel = document.getElementById('drug_number');
        if (sel.value) updateDrugInfo(sel);
        const startDate = document.getElementById('start_date').value;
        if (startDate) document.getElementById('finish_date').min = startDate;
    });
</script>
@endsection