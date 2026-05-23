@extends('layouts.app')

@section('title', 'Edit Patient — ' . $patient->full_name)

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }

    .field-label {
        @apply block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1.5;
    }
    .field-input {
        @apply w-full bg-wm-dark border border-wm-navy/60 rounded-xl text-white text-sm
               px-4 py-2.5 placeholder-slate-600
               focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
               transition-all duration-200;
    }
    .field-select {
        @apply w-full bg-wm-dark border border-wm-navy/60 rounded-xl text-white text-sm
               px-4 py-2.5
               focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
               transition-all duration-200 appearance-none cursor-pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .85rem center;
        padding-right: 2.5rem;
    }
    .field-error {
        @apply text-xs text-red-400 mt-1.5 flex items-center gap-1;
    }
    .field-readonly {
        @apply w-full bg-wm-navy/30 border border-wm-navy/40 rounded-xl text-slate-400 text-sm
               font-mono px-4 py-2.5 cursor-not-allowed select-none;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">

    {{-- ── Breadcrumb / Header ── --}}
    <div class="flex items-center gap-3 mb-6 anim-fade-up">
        <a href="{{ route('patients.index') }}"
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-wm-cyan text-sm transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Patients
        </a>
        <span class="text-slate-700">/</span>
        <a href="{{ route('patients.show', $patient->patient_number) }}"
           class="text-slate-400 hover:text-wm-cyan text-sm transition-colors duration-200">
            {{ $patient->full_name }}
        </a>
        <span class="text-slate-700">/</span>
        <span class="text-white text-sm font-semibold">Edit</span>
    </div>

    {{-- ── Form Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl shadow-[0_8px_40px_rgba(0,0,0,.4)]
                overflow-hidden anim-fade-up" style="animation-delay:.05s">

        {{-- Card Header --}}
        <div class="flex items-center justify-between gap-4 px-6 py-5 border-b border-wm-navy/60 bg-wm-navy/20">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-white font-bold text-base leading-none">Edit Patient Record</h2>
                    <p class="text-slate-500 text-xs mt-1">Patient # is locked and cannot be changed.</p>
                </div>
            </div>
            {{-- Patient number badge --}}
            <span class="bg-wm-cyan/10 text-wm-cyan text-xs font-bold font-mono px-3 py-1.5 rounded-lg shrink-0">
                {{ $patient->patient_number }}
            </span>
        </div>

        <form action="{{ route('patients.update', $patient->patient_number) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            {{-- ── Section: Identification ── --}}
            <div class="mb-6">
                <h3 class="text-wm-cyan text-xs font-bold uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="w-4 h-px bg-wm-cyan/40 block"></span>
                    Identification
                    <span class="flex-1 h-px bg-wm-navy/60 block"></span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    {{-- Patient Number (read-only) --}}
                    <div>
                        <label class="field-label">Patient Number</label>
                        <div class="field-readonly flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-slate-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            {{ $patient->patient_number }}
                        </div>
                    </div>

                    {{-- First Name --}}
                    <div>
                        <label class="field-label" for="first_name">
                            First Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="first_name" name="first_name"
                               value="{{ old('first_name', $patient->first_name) }}"
                               placeholder="Juan"
                               class="field-input @error('first_name') border-red-500/60 @enderror">
                        @error('first_name')
                            <p class="field-error">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <label class="field-label" for="last_name">
                            Last Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="last_name" name="last_name"
                               value="{{ old('last_name', $patient->last_name) }}"
                               placeholder="Dela Cruz"
                               class="field-input @error('last_name') border-red-500/60 @enderror">
                        @error('last_name')
                            <p class="field-error">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Section: Personal Details ── --}}
            <div class="mb-6">
                <h3 class="text-wm-cyan text-xs font-bold uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="w-4 h-px bg-wm-cyan/40 block"></span>
                    Personal Details
                    <span class="flex-1 h-px bg-wm-navy/60 block"></span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    {{-- Date of Birth --}}
                    <div>
                        <label class="field-label" for="date_of_birth">
                            Date of Birth <span class="text-red-400">*</span>
                        </label>
                        <input type="date" id="date_of_birth" name="date_of_birth"
                               value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}"
                               max="{{ now()->subDay()->format('Y-m-d') }}"
                               class="field-input @error('date_of_birth') border-red-500/60 @enderror">
                        @error('date_of_birth')
                            <p class="field-error">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Sex --}}
                    <div>
                        <label class="field-label" for="sex">
                            Sex <span class="text-red-400">*</span>
                        </label>
                        <select id="sex" name="sex"
                                class="field-select @error('sex') border-red-500/60 @enderror">
                            <option value="Male"   {{ old('sex', $patient->sex) === 'Male'   ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $patient->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other"  {{ old('sex', $patient->sex) === 'Other'  ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('sex')
                            <p class="field-error">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Marital Status --}}
                    <div>
                        <label class="field-label" for="marital_status">
                            Marital Status <span class="text-red-400">*</span>
                        </label>
                        <select id="marital_status" name="marital_status"
                                class="field-select @error('marital_status') border-red-500/60 @enderror">
                            <option value="Single"    {{ old('marital_status', $patient->marital_status) === 'Single'    ? 'selected' : '' }}>Single</option>
                            <option value="Married"   {{ old('marital_status', $patient->marital_status) === 'Married'   ? 'selected' : '' }}>Married</option>
                            <option value="Divorced"  {{ old('marital_status', $patient->marital_status) === 'Divorced'  ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed"   {{ old('marital_status', $patient->marital_status) === 'Widowed'   ? 'selected' : '' }}>Widowed</option>
                            <option value="Separated" {{ old('marital_status', $patient->marital_status) === 'Separated' ? 'selected' : '' }}>Separated</option>
                        </select>
                        @error('marital_status')
                            <p class="field-error">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Section: Contact & Registration ── --}}
            <div class="mb-6">
                <h3 class="text-wm-cyan text-xs font-bold uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="w-4 h-px bg-wm-cyan/40 block"></span>
                    Contact & Registration
                    <span class="flex-1 h-px bg-wm-navy/60 block"></span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Address --}}
                    <div class="sm:col-span-2">
                        <label class="field-label" for="address">
                            Address <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="address" name="address"
                               value="{{ old('address', $patient->address) }}"
                               placeholder="Full home address"
                               class="field-input @error('address') border-red-500/60 @enderror">
                        @error('address')
                            <p class="field-error">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Telephone --}}
                    <div>
                        <label class="field-label" for="telephone_number">
                            Telephone Number <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="telephone_number" name="telephone_number"
                               value="{{ old('telephone_number', $patient->telephone_number) }}"
                               placeholder="e.g. 09XX-XXX-XXXX"
                               class="field-input @error('telephone_number') border-red-500/60 @enderror">
                        @error('telephone_number')
                            <p class="field-error">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Date Registered --}}
                    <div>
                        <label class="field-label" for="date_registered">
                            Date Registered <span class="text-red-400">*</span>
                        </label>
                        <input type="date" id="date_registered" name="date_registered"
                               value="{{ old('date_registered', $patient->date_registered->format('Y-m-d')) }}"
                               class="field-input @error('date_registered') border-red-500/60 @enderror">
                        @error('date_registered')
                            <p class="field-error">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Referred By --}}
                    <div class="sm:col-span-2">
                        <label class="field-label" for="referred_by">
                            Referred By
                            <span class="text-slate-600 font-normal normal-case tracking-normal ml-1">(optional)</span>
                        </label>
                        <select id="referred_by" name="referred_by"
                                class="field-select @error('referred_by') border-red-500/60 @enderror">
                            <option value="">— No referral / Walk-in —</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->clinic_number }}"
                                        {{ old('referred_by', $patient->referred_by) === $doctor->clinic_number ? 'selected' : '' }}>
                                    {{ $doctor->full_name }}
                                    @if($doctor->telephone_number)
                                        ({{ $doctor->telephone_number }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('referred_by')
                            <p class="field-error">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Actions ── --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-wm-navy/60">
                <a href="{{ route('patients.show', $patient->patient_number) }}"
                   class="inline-flex items-center gap-2 border border-wm-navy/60 text-slate-400 text-sm font-semibold
                          px-5 py-2.5 rounded-xl hover:border-slate-500 hover:text-white transition-all duration-200">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                               px-6 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
            </div>

        </form>
    </div>

</div>
@endsection