@extends('layouts.app')

@section('title', 'Appointment ' . $appointment->appointment_number)

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 anim-fade-up">
        <a href="{{ route('appointments.index') }}" class="hover:text-wm-cyan transition-colors">Appointments</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-400">{{ $appointment->appointment_number }}</span>
    </nav>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-2xl px-5 py-4 mb-6 anim-fade-up">
            <p class="text-sm font-semibold text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-500/10 border border-red-500/30 rounded-2xl px-5 py-4 mb-6 anim-fade-up">
            <p class="text-sm font-semibold text-red-400">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ── Hero Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-6 mb-6
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.03s">
        <div class="flex flex-wrap items-start justify-between gap-4">

            {{-- Appointment Info --}}
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20
                            flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold
                                     font-mono px-2.5 py-1 rounded-md tracking-wide">
                            {{ $appointment->appointment_number }}
                        </span>
                        {{-- Status Badge --}}
                        @if ($appointment->examResult)
                            <span class="inline-flex items-center gap-1.5 bg-green-500/10 text-green-400
                                         text-xs font-semibold px-2.5 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Examined
                            </span>
                        @elseif ($appointment->date_time->isPast())
                            <span class="inline-flex items-center gap-1.5 bg-amber-500/10 text-amber-400
                                         text-xs font-semibold px-2.5 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Pending Result
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-blue-500/10 text-blue-400
                                         text-xs font-semibold px-2.5 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Upcoming
                            </span>
                        @endif
                    </div>
                    <p class="text-xl font-bold text-white mt-1">
                        {{ $appointment->date_time->format('D, M j, Y') }}
                    </p>
                    <p class="text-sm text-slate-400">
                        {{ $appointment->date_time->format('g:i A') }} &middot; {{ $appointment->examination_room }}
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 shrink-0">
                @if (!$appointment->examResult)
                    <a href="{{ route('appointments.edit', $appointment->appointment_number) }}"
                       class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/25
                              text-amber-400 text-sm font-semibold px-4 py-2.5 rounded-xl
                              hover:bg-amber-500/20 hover:border-amber-500/50 transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('appointments.index') }}"
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

        {{-- Details Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-5 pt-5 border-t border-wm-navy/60">
            <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                <p class="text-xs text-slate-500 mb-1">Staff #</p>
                <p class="text-sm font-semibold text-white font-mono">{{ $appointment->staff_number }}</p>
            </div>
            <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                <p class="text-xs text-slate-500 mb-1">Room</p>
                <p class="text-sm font-semibold text-white">{{ $appointment->examination_room }}</p>
            </div>
            <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                <p class="text-xs text-slate-500 mb-1">Date</p>
                <p class="text-sm font-semibold text-white">{{ $appointment->date_time->format('M j, Y') }}</p>
            </div>
            <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                <p class="text-xs text-slate-500 mb-1">Time</p>
                <p class="text-sm font-semibold text-white">{{ $appointment->date_time->format('g:i A') }}</p>
            </div>
        </div>
    </div>

    {{-- ── Patient Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-5 mb-6
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.05s">
        <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">Patient</h2>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-lg font-bold text-white">{{ $appointment->patient->full_name }}</p>
                <div class="flex flex-wrap items-center gap-3 mt-1.5">
                    <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold
                                 font-mono px-2.5 py-1 rounded-md">
                        {{ $appointment->patient->patient_number }}
                    </span>
                    <span class="text-slate-400 text-sm">{{ $appointment->patient->age }} yrs &middot; {{ $appointment->patient->sex }}</span>
                    @if ($appointment->patient->telephone_number)
                        <span class="text-slate-400 text-sm">{{ $appointment->patient->telephone_number }}</span>
                    @endif
                </div>
                @if ($appointment->patient->localDoctor)
                    <p class="text-xs text-slate-500 mt-1.5">
                        Referred by <span class="text-wm-cyan">{{ $appointment->patient->localDoctor->full_name }}</span>
                    </p>
                @endif
            </div>
            <a href="{{ route('patients.show', $appointment->patient->patient_number) }}"
               class="inline-flex items-center gap-2 bg-wm-navy/60 border border-wm-navy
                      text-slate-400 text-sm font-semibold px-4 py-2.5 rounded-xl
                      hover:text-white hover:border-slate-600 transition-all duration-150 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View Profile
            </a>
        </div>
    </div>

    {{-- ── Exam Result ── --}}
    @if ($appointment->examResult)
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-5 mb-6
                    shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.07s">
            <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">Exam Result</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Result</p>
                    <span class="inline-flex items-center gap-1.5 text-sm font-bold
                        {{ $appointment->examResult->result === 'Out-patient' ? 'text-emerald-400' : 'text-purple-400' }}">
                        {{ $appointment->examResult->result }}
                    </span>
                </div>
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Examined Date</p>
                    <p class="text-sm font-semibold text-white">
                        {{ \Carbon\Carbon::parse($appointment->examResult->examined_date)->format('M j, Y') }}
                    </p>
                </div>
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Classification</p>
                    <p class="text-sm font-semibold text-white">
                        {{ $appointment->examResult->result === 'Out-patient' ? 'Out-Patient Visit' : 'In-Patient (Waiting List)' }}
                    </p>
                </div>
            </div>
        </div>
    @else
        {{-- ── Record Exam Result Form ── --}}
        @if ($appointment->date_time->isPast())
            <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-5 mb-6
                        shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.07s">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">Record Exam Result</h2>
                <form action="{{ route('appointments.record_result', $appointment->appointment_number) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">

                        {{-- Result --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                                Result <span class="text-red-400">*</span>
                            </label>
                            <select name="result"
                                    class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                           px-4 py-2.5 appearance-none cursor-pointer
                                           focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                           transition-all duration-200"
                                    style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right .75rem center;padding-right:2.5rem;">
                                <option value="">— Select Result —</option>
                                <option value="Out-patient">Out-patient</option>
                                <option value="WaitingList">Waiting List (In-patient)</option>
                            </select>
                        </div>

                        {{-- Examined Date --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                                Examined Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="examined_date"
                                   value="{{ date('Y-m-d') }}"
                                   class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                          px-4 py-2.5
                                          focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                          transition-all duration-200"
                                   style="color-scheme: dark;">
                        </div>

                        {{-- Submit --}}
                        <div>
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-wm-cyan
                                           text-wm-dark text-sm font-bold px-6 py-2.5 rounded-xl
                                           hover:bg-wm-cyan-dim transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Record Result
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        @else
            <div class="bg-blue-500/5 border border-blue-500/20 rounded-2xl px-6 py-4 mb-6 anim-fade-up"
                 style="animation-delay:.07s">
                <p class="text-sm text-blue-300">
                    This appointment is upcoming — exam result can be recorded after
                    <span class="font-semibold text-white">{{ $appointment->date_time->format('M j, Y g:i A') }}</span>.
                </p>
            </div>
        @endif
    @endif

    {{-- ── Out-Patient Record ── --}}
    @if ($appointment->outPatient)
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-5 mb-6
                    shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.09s">
            <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-3">Out-Patient Record</h2>
            <p class="text-sm text-slate-400">
                Patient was classified as an <span class="text-emerald-400 font-semibold">Out-patient</span>.
                Appointment date: <span class="text-white">{{ \Carbon\Carbon::parse($appointment->outPatient->appointment_date_time)->format('M j, Y g:i A') }}</span>
            </p>
        </div>
    @endif

    {{-- ── In-Patient Record ── --}}
    @if ($appointment->inPatient)
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-6 py-5 anim-fade-up"
             style="animation-delay:.09s">
            <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-3">In-Patient Admission</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Ward</p>
                    <p class="text-sm font-semibold text-white">{{ $appointment->inPatient->ward ?? '—' }}</p>
                </div>
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Bed</p>
                    <p class="text-sm font-semibold text-white">{{ $appointment->inPatient->bed_number ?? '—' }}</p>
                </div>
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Date Placed</p>
                    <p class="text-sm font-semibold text-white">
                        {{ isset($appointment->inPatient->date_placed)
                            ? \Carbon\Carbon::parse($appointment->inPatient->date_placed)->format('M j, Y')
                            : '—' }}
                    </p>
                </div>
                <div class="bg-wm-navy/30 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mb-1">Expected Leave</p>
                    <p class="text-sm font-semibold text-white">
                        {{ isset($appointment->inPatient->date_leave)
                            ? \Carbon\Carbon::parse($appointment->inPatient->date_leave)->format('M j, Y')
                            : '—' }}
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection