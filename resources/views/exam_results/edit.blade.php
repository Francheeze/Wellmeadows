@extends('layouts.app')

@section('title', 'Edit Exam Result')

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
        <a href="{{ route('exam_results.index') }}" class="hover:text-wm-cyan transition-colors">Exam Results</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('exam_results.show', $examResult->appointment_number) }}"
           class="hover:text-wm-cyan transition-colors">
            {{ $examResult->appointment_number }}
        </a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-400">Edit</span>
    </nav>

    {{-- Page Header --}}
    <div class="mb-6 anim-fade-up" style="animation-delay:.03s">
        <h1 class="text-2xl font-bold text-white">Edit Exam Result</h1>
        <p class="text-sm text-slate-400 mt-1">
            Updating result for
            <span class="text-white font-medium">{{ $examResult->appointment->patient->full_name }}</span>
            — appointment <span class="text-wm-cyan font-mono">{{ $examResult->appointment_number }}</span>.
        </p>
    </div>

    {{-- Flash error --}}
    @if (session('error'))
        <div class="bg-red-500/10 border border-red-500/30 rounded-2xl px-5 py-4 mb-6 anim-fade-up">
            <p class="text-sm font-semibold text-red-400">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Downstream warning --}}
    @if ($examResult->appointment->inPatient || $examResult->appointment->outPatient)
        <div class="bg-amber-500/10 border border-amber-500/30 rounded-2xl px-5 py-4 mb-6 anim-fade-up">
            <p class="text-sm font-semibold text-amber-400">⚠ Downstream record exists</p>
            <p class="text-xs text-amber-300/70 mt-1">
                A linked {{ $examResult->appointment->inPatient ? 'in-patient' : 'out-patient' }} record exists.
                Changing the result type will be blocked — only the examined date can be updated.
            </p>
        </div>
    @endif

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

        <form action="{{ route('exam_results.update', $examResult->appointment_number) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Section: Appointment (read-only) --}}
            <div class="px-6 py-5 border-b border-wm-navy/60">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">
                    Appointment
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Appointment # --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Appointment #
                            <span class="ml-1 text-slate-600 font-normal normal-case tracking-normal">(cannot be changed)</span>
                        </label>
                        <div class="w-full bg-wm-navy/50 border border-wm-navy/40 rounded-xl text-slate-500
                                    text-sm px-4 py-2.5 font-mono cursor-not-allowed select-none">
                            {{ $examResult->appointment_number }}
                        </div>
                    </div>

                    {{-- Patient (read-only) --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Patient</label>
                        <div class="w-full bg-wm-navy/50 border border-wm-navy/40 rounded-xl text-slate-500
                                    text-sm px-4 py-2.5 cursor-not-allowed select-none">
                            {{ $examResult->appointment->patient->full_name }}
                            ({{ $examResult->appointment->patient->patient_number }})
                        </div>
                    </div>

                </div>
            </div>

            {{-- Section: Result Details --}}
            <div class="px-6 py-5">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">
                    Result Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Result --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Result <span class="text-red-400">*</span>
                        </label>
                        <select name="result"
                                class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                       px-4 py-2.5 appearance-none cursor-pointer
                                       focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                       transition-all duration-200
                                       @error('result') border-red-500/60 @enderror"
                                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right .75rem center;padding-right:2.5rem;">
                            <option value="Out-patient"
                                {{ old('result', $examResult->result) === 'Out-patient' ? 'selected' : '' }}>
                                Out-patient
                            </option>
                            <option value="WaitingList"
                                {{ old('result', $examResult->result) === 'WaitingList' ? 'selected' : '' }}>
                                Waiting List (In-patient)
                            </option>
                        </select>
                        @error('result')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Examined Date --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Examined Date <span class="text-red-400">*</span>
                        </label>
                        <input type="date" name="examined_date"
                               value="{{ old('examined_date', $examResult->examined_date->format('Y-m-d')) }}"
                               class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                      px-4 py-2.5
                                      focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                      transition-all duration-200
                                      @error('examined_date') border-red-500/60 @enderror"
                               style="color-scheme: dark;">
                        @error('examined_date')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-wm-navy/60
                        bg-wm-navy/10 rounded-b-2xl">

                {{-- Delete --}}
                <form action="{{ route('exam_results.destroy', $examResult->appointment_number) }}"
                      method="POST"
                      onsubmit="return confirm('Delete exam result for {{ addslashes($examResult->appointment_number) }}?\n\nThis cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-400
                                   hover:text-red-300 transition-colors duration-200 px-3 py-2 rounded-lg
                                   hover:bg-red-500/10 cursor-pointer bg-transparent border-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Result
                    </button>
                </form>

                <div class="flex items-center gap-3">
                    <a href="{{ route('exam_results.show', $examResult->appointment_number) }}"
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
                        Save Changes
                    </button>
                </div>

            </div>

        </form>
    </div>

</div>
@endsection