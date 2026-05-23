@extends('layouts.app')

@section('title', 'Add Appointment')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 anim-fade-up">
        <a href="{{ route('appointments.index') }}" class="hover:text-wm-cyan transition-colors">Appointments</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-400">Add Appointment</span>
    </nav>

    {{-- Page Header --}}
    <div class="mb-6 anim-fade-up" style="animation-delay:.03s">
        <h1 class="text-2xl font-bold text-white">Schedule Appointment</h1>
        <p class="text-sm text-slate-400 mt-1">Create a new patient appointment record.</p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 rounded-2xl px-5 py-4 mb-6 anim-fade-up">
            <p class="text-sm font-semibold text-red-400 mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-xs text-red-300">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl shadow-[0_8px_40px_rgba(0,0,0,.4)]
                anim-fade-up" style="animation-delay:.06s">

        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

            {{-- Section: Appointment Info --}}
            <div class="px-6 py-5 border-b border-wm-navy/60">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">
                    Appointment Information
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Appointment Number --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Appointment # <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="appointment_number"
                               value="{{ old('appointment_number') }}"
                               placeholder="e.g. APT-001"
                               class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                      px-4 py-2.5 placeholder-slate-600
                                      focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                      transition-all duration-200
                                      @error('appointment_number') border-red-500/60 @enderror">
                        @error('appointment_number')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Patient --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Patient <span class="text-red-400">*</span>
                        </label>
                        <select name="patient_number"
                                class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                       px-4 py-2.5 appearance-none cursor-pointer
                                       focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                       transition-all duration-200
                                       @error('patient_number') border-red-500/60 @enderror"
                                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right .75rem center;padding-right:2.5rem;">
                            <option value="">— Select Patient —</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->patient_number }}"
                                    {{ old('patient_number') === $patient->patient_number ? 'selected' : '' }}>
                                    {{ $patient->last_name }}, {{ $patient->first_name }}
                                    ({{ $patient->patient_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_number')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Section: Schedule Details --}}
            <div class="px-6 py-5">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">
                    Schedule Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Staff Number --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Staff # <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="staff_number"
                               value="{{ old('staff_number') }}"
                               placeholder="e.g. STF-001"
                               class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                      px-4 py-2.5 placeholder-slate-600
                                      focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                      transition-all duration-200
                                      @error('staff_number') border-red-500/60 @enderror">
                        @error('staff_number')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Examination Room --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Examination Room <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="examination_room"
                               value="{{ old('examination_room') }}"
                               placeholder="e.g. Room 3A"
                               class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                      px-4 py-2.5 placeholder-slate-600
                                      focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                      transition-all duration-200
                                      @error('examination_room') border-red-500/60 @enderror">
                        @error('examination_room')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date & Time --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Date & Time <span class="text-red-400">*</span>
                        </label>
                        <input type="datetime-local" name="date_time"
                               value="{{ old('date_time') }}"
                               class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                      px-4 py-2.5
                                      focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                      transition-all duration-200
                                      @error('date_time') border-red-500/60 @enderror"
                               style="color-scheme: dark;">
                        @error('date_time')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-wm-navy/60
                        bg-wm-navy/10 rounded-b-2xl">
                <a href="{{ route('appointments.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400
                          hover:text-white transition-colors duration-200 px-4 py-2.5">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                               px-6 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Schedule Appointment
                </button>
            </div>

        </form>
    </div>

</div>
@endsection