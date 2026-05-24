@extends('layouts.app')

@section('title', 'Record Exam Result')

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
    <a href="{{ route('exam_results.index') }}"
       class="inline-flex items-center gap-1.5 text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium mb-8 transition-colors no-underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Exam Results
    </a>

    {{-- Page Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">Wellmeadows Hospital</p>
        <h1 class="text-3xl font-bold gradient-text leading-tight">Record Exam Result</h1>
        <p class="text-[#CCECEE]/40 text-sm mt-1">
            Select a past appointment and record the examination outcome.
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

    {{-- Empty state — no eligible appointments --}}
    @if ($appointments->isEmpty())
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl p-12 text-center fade-in">
            <svg class="w-12 h-12 mx-auto text-[#CCECEE]/15 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-[#CCECEE]/50 font-semibold text-sm mb-1">No appointments available</p>
            <p class="text-[#CCECEE]/30 text-xs">
                All past appointments already have exam results recorded, or there are no past appointments yet.
            </p>
            <a href="{{ route('appointments.index') }}"
               class="inline-flex items-center gap-2 mt-6 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200">
                View Appointments
            </a>
        </div>
    @else

    <form action="{{ route('exam_results.store') }}" method="POST" id="examForm">
        @csrf

        {{-- ── Section 1: Appointment Selection ── --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

            <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-[#03416E]/30">
                <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/10 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Step 1</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Select Appointment</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="flex flex-col gap-1.5">
                    <label for="appointment_number" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Appointment <span class="text-[#CCECEE]">*</span>
                    </label>
                    <select
                        id="appointment_number"
                        name="appointment_number"
                        onchange="updateAppointmentPreview(this)"
                        class="wm-select bg-[#021829] border {{ $errors->has('appointment_number') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                        <option value="" disabled {{ old('appointment_number') ? '' : 'selected' }}>— Select an appointment —</option>
                        @foreach ($appointments as $appt)
                            <option
                                value="{{ $appt->appointment_number }}"
                                data-patient="{{ $appt->patient->first_name ?? '' }} {{ $appt->patient->last_name ?? '' }}"
                                data-patient-number="{{ $appt->patient_number }}"
                                data-date="{{ $appt->date_time->format('d M Y, h:i A') }}"
                                data-room="{{ $appt->examination_room }}"
                                data-staff="{{ $appt->staff_number }}"
                                {{ old('appointment_number') == $appt->appointment_number ? 'selected' : '' }}
                            >
                                #{{ $appt->appointment_number }}
                                — {{ $appt->patient->first_name ?? '' }} {{ $appt->patient->last_name ?? '' }}
                                ({{ $appt->patient_number }})
                                — {{ $appt->date_time->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-[#CCECEE]/40">Only past appointments without an existing result are shown.</p>
                    @error('appointment_number')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Appointment preview strip --}}
                <div id="appointmentPreview" class="mt-4 hidden">
                    <div class="bg-[#CCECEE]/5 border border-[#CCECEE]/10 rounded-xl px-4 py-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#CCECEE]/40">Patient</span>
                            <span id="previewPatient" class="text-[#f0f7f8] font-semibold text-xs"></span>
                            <span id="previewPatientNo" class="text-[#CCECEE]/40 font-mono text-xs"></span>
                        </div>
                        <div class="w-px h-4 bg-[#CCECEE]/10 hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#CCECEE]/40">Date</span>
                            <span id="previewDate" class="text-[#CCECEE]/70 text-xs"></span>
                        </div>
                        <div class="w-px h-4 bg-[#CCECEE]/10 hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#CCECEE]/40">Room</span>
                            <span id="previewRoom" class="text-[#CCECEE]/70 text-xs font-mono"></span>
                        </div>
                        <div class="w-px h-4 bg-[#CCECEE]/10 hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#CCECEE]/40">Staff</span>
                            <span id="previewStaff" class="text-[#CCECEE]/70 text-xs font-mono"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Section 2: Result Details ── --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-t-2xl overflow-hidden shadow-2xl fade-in">

            <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-emerald-500/5">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Step 2</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Examination Outcome</h2>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Result --}}
                <div class="flex flex-col gap-1.5">
                    <label for="result" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Result <span class="text-[#CCECEE]">*</span>
                    </label>
                    <select
                        id="result"
                        name="result"
                        onchange="updateResultHint(this)"
                        class="wm-select bg-[#021829] border {{ $errors->has('result') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                        <option value="" disabled {{ old('result') ? '' : 'selected' }}>— Select result —</option>
                        <option value="Out-patient" {{ old('result') === 'Out-patient' ? 'selected' : '' }}>Out-patient</option>
                        <option value="WaitingList" {{ old('result') === 'WaitingList' ? 'selected' : '' }}>Waiting List (Admit)</option>
                    </select>
                    @error('result')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Result hint --}}
                    <div id="resultHint" class="hidden mt-1">
                        <p id="resultHintText" class="text-xs"></p>
                    </div>
                </div>

                {{-- Examined Date --}}
                <div class="flex flex-col gap-1.5">
                    <label for="examined_date" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Examined Date <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input
                        type="date"
                        id="examined_date"
                        name="examined_date"
                        value="{{ old('examined_date', now()->toDateString()) }}"
                        class="bg-[#021829] border {{ $errors->has('examined_date') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    @error('examined_date')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

        </div>

        {{-- ── Action Footer — outside form sections ── --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4
                    bg-[#032d4f] border border-[#CCECEE]/15 border-t border-t-[#CCECEE]/10
                    rounded-b-2xl shadow-2xl fade-in">
            <a href="{{ route('exam_results.index') }}"
               class="inline-flex items-center gap-2 bg-transparent border border-[#CCECEE]/20 text-[#CCECEE]/50 hover:border-[#CCECEE]/40 hover:text-[#CCECEE]/80 text-sm font-semibold px-5 py-2.5 rounded-xl transition no-underline">
                Cancel
            </a>
            <button type="submit" form="examForm" id="submitBtn"
                    class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Save Result
            </button>
        </div>

    </form>
    @endif

</div>
</div>

<script>
    function updateAppointmentPreview(select) {
        const option  = select.options[select.selectedIndex];
        const preview = document.getElementById('appointmentPreview');
        if (!option || !option.value) { preview.classList.add('hidden'); return; }

        document.getElementById('previewPatient').textContent    = option.dataset.patient;
        document.getElementById('previewPatientNo').textContent  = '(' + option.dataset.patientNumber + ')';
        document.getElementById('previewDate').textContent       = option.dataset.date;
        document.getElementById('previewRoom').textContent       = option.dataset.room;
        document.getElementById('previewStaff').textContent      = option.dataset.staff;
        preview.classList.remove('hidden');
    }

    function updateResultHint(select) {
        const hint     = document.getElementById('resultHint');
        const hintText = document.getElementById('resultHintText');
        if (!select.value) { hint.classList.add('hidden'); return; }

        if (select.value === 'Out-patient') {
            hintText.textContent   = 'An out-patient record will be created automatically.';
            hintText.className     = 'text-xs text-sky-400/70';
        } else {
            hintText.textContent   = 'You will be redirected to complete the in-patient admission details.';
            hintText.className     = 'text-xs text-violet-400/70';
        }
        hint.classList.remove('hidden');
    }

    document.getElementById('examForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
            viewBox="0 0 24 24" style="animation:spin .7s linear infinite;">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
        </svg> Saving…`;
    });

    // Trigger preview on page load if old() restored a value
    document.addEventListener('DOMContentLoaded', function () {
        const apptSel = document.getElementById('appointment_number');
        if (apptSel?.value) updateAppointmentPreview(apptSel);

        const resultSel = document.getElementById('result');
        if (resultSel?.value) updateResultHint(resultSel);
    });
</script>
@endsection