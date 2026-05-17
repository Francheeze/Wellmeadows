@extends('layouts.app')

@section('title', 'Register Patient')

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
    <a href="{{ route('patients.index') }}"
       class="inline-flex items-center gap-2 text-slate-400 text-sm font-medium
              hover:text-wm-cyan transition-colors duration-200 mb-8 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Patients
    </a>

    {{-- ── Page Header ── --}}
    <div class="mb-7 anim-fade-up">
        <p class="text-xs font-semibold tracking-widest uppercase text-wm-cyan-dim mb-1">
            Patient Management
        </p>
        <h1 class="text-3xl font-bold text-white tracking-tight">Register New Patient</h1>
    </div>

    {{-- ── Validation Errors ── --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-red-500/8 border border-red-500/30
                    text-red-400 text-sm px-4 py-4 rounded-2xl mb-6 anim-fade-up">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

    <form action="{{ route('patients.store') }}" method="POST" id="patientForm" class="space-y-5">
        @csrf

        {{-- ══════════════════════════════════════
             Section 1 · Personal Information
        ══════════════════════════════════════ --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Section 1</p>
                    <h2 class="text-sm font-bold text-white leading-none">Personal Information</h2>
                </div>
            </div>

            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Patient Number --}}
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="patient_number"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Patient Number <span class="text-wm-cyan">*</span>
                    </label>
                    <input
                        type="text"
                        id="patient_number"
                        name="patient_number"
                        value="{{ old('patient_number') }}"
                        placeholder="e.g. P-2024-001"
                        maxlength="20"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               {{ $errors->has('patient_number')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                    <p class="text-xs text-slate-500">Unique identifier — cannot be changed after registration.</p>
                    @error('patient_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- First Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="first_name"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        First Name <span class="text-wm-cyan">*</span>
                    </label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        placeholder="e.g. Maria"
                        maxlength="100"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               {{ $errors->has('first_name')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                    @error('first_name')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Last Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="last_name"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Last Name <span class="text-wm-cyan">*</span>
                    </label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        placeholder="e.g. Santos"
                        maxlength="100"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               {{ $errors->has('last_name')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                    @error('last_name')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Date of Birth --}}
                <div class="flex flex-col gap-1.5">
                    <label for="date_of_birth"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Date of Birth <span class="text-wm-cyan">*</span>
                    </label>
                    <input
                        type="date"
                        id="date_of_birth"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        max="{{ now()->subDay()->toDateString() }}"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               [color-scheme:dark]
                               {{ $errors->has('date_of_birth')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                    @error('date_of_birth')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Sex --}}
                <div class="flex flex-col gap-1.5">
                    <label for="sex"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Sex <span class="text-wm-cyan">*</span>
                    </label>
                    <select
                        id="sex"
                        name="sex"
                        class="wm-select w-full bg-wm-dark border rounded-xl text-white text-sm
                               px-4 py-2.5 focus:outline-none cursor-pointer transition-all duration-200
                               {{ $errors->has('sex')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                        <option value="" disabled {{ old('sex') ? '' : 'selected' }}>— Select sex —</option>
                        <option value="Male"   {{ old('sex') === 'Male'   ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other"  {{ old('sex') === 'Other'  ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('sex')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
             Section 2 · Contact Details
        ══════════════════════════════════════ --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up" style="animation-delay:.05s">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Section 2</p>
                    <h2 class="text-sm font-bold text-white leading-none">Contact Details</h2>
                </div>
            </div>

            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Address --}}
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="address"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Address <span class="text-wm-cyan">*</span>
                    </label>
                    <textarea
                        id="address"
                        name="address"
                        rows="2"
                        placeholder="Full residential address…"
                        maxlength="255"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none resize-none transition-all duration-200
                               {{ $errors->has('address')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >{{ old('address') }}</textarea>
                    @error('address')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Telephone --}}
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="telephone_number"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Telephone Number <span class="text-wm-cyan">*</span>
                    </label>
                    <div class="flex rounded-xl overflow-hidden border transition-all duration-200
                                {{ $errors->has('telephone_number')
                                    ? 'border-red-500/60 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/15'
                                    : 'border-wm-navy/70 focus-within:border-wm-cyan/60 focus-within:ring-2 focus-within:ring-wm-cyan/15' }}">
                        <span class="flex items-center px-3.5 bg-wm-navy/40 border-r border-wm-navy/70 shrink-0">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="telephone_number"
                            name="telephone_number"
                            value="{{ old('telephone_number') }}"
                            placeholder="e.g. 09171234567"
                            maxlength="20"
                            class="flex-1 min-w-0 bg-wm-dark text-white text-sm placeholder-slate-600
                                   px-3 py-2.5 focus:outline-none"
                        >
                    </div>
                    @error('telephone_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
             Section 3 · Registration Details
        ══════════════════════════════════════ --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up" style="animation-delay:.1s">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Section 3</p>
                    <h2 class="text-sm font-bold text-white leading-none">Registration Details</h2>
                </div>
            </div>

            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Date Registered --}}
                <div class="flex flex-col gap-1.5">
                    <label for="date_registered"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Date Registered <span class="text-wm-cyan">*</span>
                    </label>
                    <input
                        type="date"
                        id="date_registered"
                        name="date_registered"
                        value="{{ old('date_registered', now()->toDateString()) }}"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               [color-scheme:dark]
                               {{ $errors->has('date_registered')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                    @error('date_registered')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Marital Status --}}
                <div class="flex flex-col gap-1.5">
                    <label for="marital_status"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Marital Status <span class="text-wm-cyan">*</span>
                    </label>
                    <select
                        id="marital_status"
                        name="marital_status"
                        class="wm-select w-full bg-wm-dark border rounded-xl text-white text-sm
                               px-4 py-2.5 focus:outline-none cursor-pointer transition-all duration-200
                               {{ $errors->has('marital_status')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                        <option value="" disabled {{ old('marital_status') ? '' : 'selected' }}>— Select status —</option>
                        @foreach (['Single', 'Married', 'Divorced', 'Widowed', 'Separated'] as $status)
                            <option value="{{ $status }}"
                                    {{ old('marital_status') === $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('marital_status')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Referred By (nullable) --}}
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="referred_by"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Referred By
                        <span class="normal-case font-normal text-slate-600 ml-1">(optional)</span>
                    </label>
                    <select
                        id="referred_by"
                        name="referred_by"
                        class="wm-select w-full bg-wm-dark border rounded-xl text-white text-sm
                               px-4 py-2.5 focus:outline-none cursor-pointer transition-all duration-200
                               {{ $errors->has('referred_by')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                        <option value="">— Not referred / Walk-in —</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->clinic_number }}"
                                    {{ old('referred_by') === $doctor->clinic_number ? 'selected' : '' }}>
                                {{ $doctor->full_name }}
                                @if ($doctor->telephone_number)
                                    — {{ $doctor->telephone_number }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500">Select the local doctor who referred this patient, if applicable.</p>
                    @error('referred_by')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <a href="{{ route('patients.index') }}"
                   class="inline-flex items-center gap-2 border border-wm-navy/70 text-slate-400
                          text-sm font-semibold px-5 py-2.5 rounded-xl hover:border-slate-500
                          hover:text-white transition-all duration-200 no-underline">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                               px-6 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-all duration-200
                               disabled:opacity-60 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Register Patient
                </button>
            </div>

        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('patientForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="w-4 h-4 anim-spin" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
            </svg>
            Registering…`;
    });
</script>
@endpush