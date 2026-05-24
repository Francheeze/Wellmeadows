@extends('layouts.app')

@section('title', 'New Appointment')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin   { to { transform: rotate(360deg); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .anim-spin    { animation: spin .7s linear infinite; }

    select.wm-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }
    select.wm-select option { background-color: #032d4f; color: #ffffff; }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">

    {{-- ── Back Link ── --}}
    @if (request('patient_number'))
        <a href="{{ route('patients.show', request('patient_number')) }}"
           class="inline-flex items-center gap-2 text-slate-400 text-sm font-medium
                  hover:text-wm-cyan transition-colors duration-200 mb-8 group">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
            </svg>
            Back to Patient Profile
        </a>
    @else
        <a href="{{ route('appointments.index') }}"
           class="inline-flex items-center gap-2 text-slate-400 text-sm font-medium
                  hover:text-wm-cyan transition-colors duration-200 mb-8 group">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
            </svg>
            Back to Appointments
        </a>
    @endif

    {{-- ── Page Header ── --}}
    <div class="mb-7 anim-fade-up">
        <p class="text-xs font-semibold tracking-widest uppercase text-wm-cyan-dim mb-1">
            Patient Management
        </p>
        <h1 class="text-3xl font-bold text-white tracking-tight">New Appointment</h1>
    </div>

    {{-- ── Validation Errors ── --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-red-500/8 border border-red-500/30
                    text-red-400 text-sm px-4 py-4 rounded-2xl mb-6 anim-fade-up">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                 stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-0.5 text-red-400/90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('appointments.store') }}"
          method="POST"
          id="appointmentForm"
          class="space-y-5">
        @csrf

        {{-- ══════════════════════════════════════
             Section 1 · Appointment Details
        ══════════════════════════════════════ --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">
                        Section 1
                    </p>
                    <h2 class="text-sm font-bold text-white leading-none">Appointment Details</h2>
                </div>
            </div>

            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Appointment Number (+/- buttons) --}}
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="appointment_number"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Appointment Number <span class="text-wm-cyan">*</span>
                    </label>
                    <div class="flex rounded-xl overflow-hidden border transition-all duration-200
                                {{ $errors->has('appointment_number')
                                    ? 'border-red-500/60 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/15'
                                    : 'border-wm-navy/70 focus-within:border-wm-cyan/60 focus-within:ring-2 focus-within:ring-wm-cyan/15' }}">

                        {{-- Decrement --}}
                        <button type="button"
                                onclick="stepInput('appointment_number', -1)"
                                class="flex items-center justify-center px-4 bg-wm-navy/40
                                       border-r border-wm-navy/70 text-slate-400
                                       hover:text-white hover:bg-wm-navy/70 transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                 stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                            </svg>
                        </button>

                        <input
                            type="number"
                            id="appointment_number"
                            name="appointment_number"
                            value="{{ old('appointment_number', 1) }}"
                            min="1"
                            class="flex-1 min-w-0 bg-wm-dark text-white text-sm text-center
                                   placeholder-slate-600 px-3 py-2.5 focus:outline-none
                                   [appearance:textfield]
                                   [&::-webkit-outer-spin-button]:appearance-none
                                   [&::-webkit-inner-spin-button]:appearance-none"
                        >

                        {{-- Increment --}}
                        <button type="button"
                                onclick="stepInput('appointment_number', 1)"
                                class="flex items-center justify-center px-4 bg-wm-navy/40
                                       border-l border-wm-navy/70 text-slate-400
                                       hover:text-white hover:bg-wm-navy/70 transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                 stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>

                    </div>
                    <p class="text-xs text-slate-500">Unique number for this appointment.</p>
                    @error('appointment_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Date & Time --}}
                <div class="flex flex-col gap-1.5">
                    <label for="date_time"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Date & Time <span class="text-wm-cyan">*</span>
                    </label>
                    <input
                        type="datetime-local"
                        id="date_time"
                        name="date_time"
                        value="{{ old('date_time') }}"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               [color-scheme:dark]
                               {{ $errors->has('date_time')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                    @error('date_time')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Examination Room --}}
                <div class="flex flex-col gap-1.5">
                    <label for="examination_room"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Examination Room <span class="text-wm-cyan">*</span>
                    </label>
                    <input
                        type="text"
                        id="examination_room"
                        name="examination_room"
                        value="{{ old('examination_room') }}"
                        placeholder="e.g. Room 3A"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               {{ $errors->has('examination_room')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                    @error('examination_room')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════
             Section 2 · Patient & Staff
        ══════════════════════════════════════ --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up" style="animation-delay:.05s">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">
                        Section 2
                    </p>
                    <h2 class="text-sm font-bold text-white leading-none">Patient & Staff</h2>
                </div>
            </div>

            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Patient --}}
                <div class="flex flex-col gap-1.5">
                    <label for="patient_number"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Patient <span class="text-wm-cyan">*</span>
                    </label>
                    <select
                        id="patient_number"
                        name="patient_number"
                        class="wm-select w-full bg-wm-dark border rounded-xl text-white text-sm
                               px-4 py-2.5 focus:outline-none cursor-pointer transition-all duration-200
                               {{ $errors->has('patient_number')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                        <option value="" disabled
                                {{ old('patient_number', request('patient_number')) ? '' : 'selected' }}>
                            — Select a patient —
                        </option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->patient_number }}"
                                    {{ old('patient_number', request('patient_number')) === $patient->patient_number ? 'selected' : '' }}>
                                {{ $patient->last_name }}, {{ $patient->first_name }}
                                — {{ $patient->patient_number }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Staff Number (+/- buttons) --}}
                <div class="flex flex-col gap-1.5">
                    <label for="staff_number"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Staff Number <span class="text-wm-cyan">*</span>
                    </label>
                    <div class="flex rounded-xl overflow-hidden border transition-all duration-200
                                {{ $errors->has('staff_number')
                                    ? 'border-red-500/60 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/15'
                                    : 'border-wm-navy/70 focus-within:border-wm-cyan/60 focus-within:ring-2 focus-within:ring-wm-cyan/15' }}">

                        {{-- Decrement --}}
                        <button type="button"
                                onclick="stepInput('staff_number', -1)"
                                class="flex items-center justify-center px-4 bg-wm-navy/40
                                       border-r border-wm-navy/70 text-slate-400
                                       hover:text-white hover:bg-wm-navy/70 transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                 stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                            </svg>
                        </button>

                        <input
                            type="number"
                            id="staff_number"
                            name="staff_number"
                            value="{{ old('staff_number', 1) }}"
                            min="1"
                            class="flex-1 min-w-0 bg-wm-dark text-white text-sm text-center
                                   placeholder-slate-600 px-3 py-2.5 focus:outline-none
                                   [appearance:textfield]
                                   [&::-webkit-outer-spin-button]:appearance-none
                                   [&::-webkit-inner-spin-button]:appearance-none"
                        >

                        {{-- Increment --}}
                        <button type="button"
                                onclick="stepInput('staff_number', 1)"
                                class="flex items-center justify-center px-4 bg-wm-navy/40
                                       border-l border-wm-navy/70 text-slate-400
                                       hover:text-white hover:bg-wm-navy/70 transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                 stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>

                    </div>
                    <p class="text-xs text-slate-500">Enter the attending staff member's number.</p>
                    @error('staff_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- ── Footer / Submit ── --}}
            <div class="flex flex-wrap items-center justify-end gap-3 px-6 py-4
                        border-t border-wm-navy/60 bg-wm-navy/10">
                <a href="{{ request('patient_number') ? route('patients.show', request('patient_number')) : route('appointments.index') }}"
                   class="inline-flex items-center gap-2 border border-wm-navy/70 text-slate-400
                          text-sm font-semibold px-5 py-2.5 rounded-xl hover:border-slate-500
                          hover:text-white transition-all duration-200 no-underline">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                               px-6 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-all duration-200
                               disabled:opacity-60 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Book Appointment
                </button>
            </div>

        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function stepInput(id, delta) {
        const input = document.getElementById(id);
        const current = parseInt(input.value) || 0;
        const min = parseInt(input.min) || 1;
        const next = current + delta;
        if (next >= min) input.value = next;
    }

    document.getElementById('appointmentForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="w-4 h-4 anim-spin" fill="none" stroke="currentColor"
                 stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
            </svg>
            Booking…`;
    });
</script>
@endpush