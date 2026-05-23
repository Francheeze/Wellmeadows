@extends('layouts.app')

@section('title', 'Add Exam Result')

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
        <span class="text-slate-400">Add Result</span>
    </nav>

    {{-- Page Header --}}
    <div class="mb-6 anim-fade-up" style="animation-delay:.03s">
        <h1 class="text-2xl font-bold text-white">Record Exam Result</h1>
        <p class="text-sm text-slate-400 mt-1">Record the examination outcome for a past appointment.</p>
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

    {{-- No appointments available --}}
    @if ($appointments->isEmpty())
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-12 text-center anim-fade-up"
             style="animation-delay:.06s">
            <svg class="w-14 h-14 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-slate-400 text-sm mb-4">No past appointments without a result are available.</p>
            <a href="{{ route('appointments.index') }}"
               class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                      px-5 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors">
                View Appointments
            </a>
        </div>
    @else

    {{-- Form Card --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl shadow-[0_8px_40px_rgba(0,0,0,.4)]
                anim-fade-up" style="animation-delay:.06s">

        <form action="{{ route('exam_results.store') }}" method="POST">
            @csrf

            {{-- Section: Appointment --}}
            <div class="px-6 py-5 border-b border-wm-navy/60">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">
                    Appointment
                </h2>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                        Select Appointment <span class="text-red-400">*</span>
                    </label>
                    <select name="appointment_number"
                            class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                   px-4 py-2.5 appearance-none cursor-pointer
                                   focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                   transition-all duration-200
                                   @error('appointment_number') border-red-500/60 @enderror"
                            style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right .75rem center;padding-right:2.5rem;">
                        <option value="">— Select Appointment —</option>
                        @foreach ($appointments as $appt)
                            <option value="{{ $appt->appointment_number }}"
                                {{ old('appointment_number') === $appt->appointment_number ? 'selected' : '' }}>
                                {{ $appt->appointment_number }} — {{ $appt->patient->last_name }}, {{ $appt->patient->first_name }}
                                ({{ $appt->date_time->format('M j, Y g:i A') }})
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_number')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-600 mt-1.5">Only past appointments without an existing result are shown.</p>
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
                            <option value="">— Select Result —</option>
                            <option value="Out-patient" {{ old('result') === 'Out-patient' ? 'selected' : '' }}>Out-patient</option>
                            <option value="WaitingList" {{ old('result') === 'WaitingList' ? 'selected' : '' }}>Waiting List (In-patient)</option>
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
                               value="{{ old('examined_date', date('Y-m-d')) }}"
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

                {{-- Info box --}}
                <div class="mt-4 bg-wm-navy/30 border border-wm-navy/60 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-400">
                        <span class="text-wm-cyan font-semibold">Out-patient</span> — Patient goes home; an out-patient visit record is created automatically.
                    </p>
                    <p class="text-xs text-slate-400 mt-1">
                        <span class="text-purple-400 font-semibold">Waiting List</span> — Patient requires admission; you will be redirected to complete the in-patient record.
                    </p>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-wm-navy/60
                        bg-wm-navy/10 rounded-b-2xl">
                <a href="{{ route('exam_results.index') }}"
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
                    Record Result
                </button>
            </div>

        </form>
    </div>
    @endif

</div>
@endsection