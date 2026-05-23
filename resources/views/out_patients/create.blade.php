@extends('layouts.app')

@section('title', 'Add Out-Patient Record')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin   { to { transform: rotate(360deg); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .anim-spin    { animation: spin .7s linear infinite; }

    select.wm-select {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }
    select.wm-select option { background-color: #032d4f; color: #fff; }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">

    <a href="{{ route('out_patients.index') }}"
       class="inline-flex items-center gap-2 text-slate-400 text-sm font-medium
              hover:text-wm-cyan transition-colors duration-200 mb-8 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Out-Patients
    </a>

    <div class="mb-7 anim-fade-up">
        <p class="text-xs font-semibold tracking-widest uppercase text-wm-cyan-dim mb-1">Patient Management</p>
        <h1 class="text-3xl font-bold text-white tracking-tight">Add Out-Patient Record</h1>
    </div>

    @if ($errors->any())
        <div class="flex items-start gap-3 bg-red-500/8 border border-red-500/30
                    text-red-400 text-sm px-4 py-4 rounded-2xl mb-6 anim-fade-up">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-0.5 text-red-400/90">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('out_patients.store') }}" method="POST" id="outForm" class="space-y-5">
        @csrf

        {{-- Section 1: Appointment & Patient --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Section 1</p>
                    <h2 class="text-sm font-bold text-white leading-none">Appointment & Patient</h2>
                </div>
            </div>

            <div class="px-6 py-6 grid grid-cols-1 gap-5">

                {{-- Appointment --}}
                <div class="flex flex-col gap-1.5">
                    <label for="appointment_number" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Appointment <span class="text-wm-cyan">*</span>
                    </label>
                    <select name="appointment_number"
                            class="wm-select w-full bg-wm-dark border rounded-xl text-white text-sm
                                   px-4 py-2.5 focus:outline-none cursor-pointer transition-all duration-200
                                   {{ $errors->has('appointment_number') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                            id="appointment_number" onchange="fillPatient(this)">
                        <option value="" disabled {{ old('appointment_number') ? '' : 'selected' }}>— Select appointment (Out-patient) —</option>
                        @foreach ($appointments as $appt)
                            <option value="{{ $appt->appointment_number }}"
                                    data-patient="{{ $appt->patient_number }}"
                                    data-datetime="{{ \Carbon\Carbon::parse($appt->date_time)->format('Y-m-d\TH:i') }}"
                                    {{ old('appointment_number') === $appt->appointment_number ? 'selected' : '' }}>
                                {{ $appt->appointment_number }} — {{ $appt->patient->full_name }}
                                ({{ \Carbon\Carbon::parse($appt->date_time)->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500">Only appointments with an Out-patient exam result are shown.</p>
                    @error('appointment_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Patient --}}
                <div class="flex flex-col gap-1.5">
                    <label for="patient_number" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Patient <span class="text-wm-cyan">*</span>
                    </label>
                    <select id="patient_number" name="patient_number"
                            class="wm-select w-full bg-wm-dark border rounded-xl text-white text-sm
                                   px-4 py-2.5 focus:outline-none cursor-pointer transition-all duration-200
                                   {{ $errors->has('patient_number') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}">
                        <option value="" disabled {{ old('patient_number') ? '' : 'selected' }}>— Select patient —</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->patient_number }}"
                                    {{ old('patient_number') === $patient->patient_number ? 'selected' : '' }}>
                                {{ $patient->last_name }}, {{ $patient->first_name }} ({{ $patient->patient_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Section 2: Appointment Date/Time --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up" style="animation-delay:.05s">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Section 2</p>
                    <h2 class="text-sm font-bold text-white leading-none">Appointment Date & Time</h2>
                </div>
            </div>

            <div class="px-6 py-6">
                <div class="flex flex-col gap-1.5">
                    <label for="appointment_date_time" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Date & Time <span class="text-wm-cyan">*</span>
                    </label>
                    <input type="datetime-local" id="appointment_date_time" name="appointment_date_time"
                           value="{{ old('appointment_date_time') }}"
                           class="w-full bg-wm-dark border rounded-xl text-white text-sm [color-scheme:dark]
                                  px-4 py-2.5 focus:outline-none transition-all duration-200
                                  {{ $errors->has('appointment_date_time') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}">
                    <p class="text-xs text-slate-500">Auto-filled when you select an appointment above.</p>
                    @error('appointment_date_time')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex flex-wrap items-center justify-end gap-3 px-6 py-4 border-t border-wm-navy/60 bg-wm-navy/10">
                <a href="{{ route('out_patients.index') }}"
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
                    Add Record
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function fillPatient(sel) {
        const opt = sel.options[sel.selectedIndex];
        // Fill patient
        const patSel = document.getElementById('patient_number');
        for (let o of patSel.options) {
            if (o.value === opt.dataset.patient) { o.selected = true; break; }
        }
        // Fill datetime
        const dt = opt.dataset.datetime;
        if (dt) document.getElementById('appointment_date_time').value = dt;
    }

    document.getElementById('outForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4 anim-spin" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/></svg> Saving…`;
    });
</script>
@endpush