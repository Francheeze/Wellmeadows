@extends('layouts.app')

@section('title', 'Edit Out-Patient Record')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin   { to { transform: rotate(360deg); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .anim-spin    { animation: spin .7s linear infinite; }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">

    <a href="{{ route('out_patients.show', $outPatient->appointment_number) }}"
       class="inline-flex items-center gap-2 text-slate-400 text-sm font-medium
              hover:text-wm-cyan transition-colors duration-200 mb-8 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Record
    </a>

    <div class="mb-7 anim-fade-up">
        <p class="text-xs font-semibold tracking-widest uppercase text-wm-cyan-dim mb-1">Patient Management</p>
        <h1 class="text-3xl font-bold text-white tracking-tight">Edit Out-Patient Record</h1>
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

    <form action="{{ route('out_patients.update', $outPatient->appointment_number) }}" method="POST" id="editForm" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Section 1: Locked Info --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up">
            <div class="flex items-center justify-between gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Section 1</p>
                        <h2 class="text-sm font-bold text-white leading-none">Appointment & Patient</h2>
                    </div>
                </div>
                <span class="bg-wm-cyan/10 text-wm-cyan text-xs font-bold font-mono px-3 py-1.5 rounded-lg shrink-0">
                    {{ $outPatient->appointment_number }}
                </span>
            </div>

            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Appointment (locked) --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold tracking-wide uppercase text-slate-400">Appointment #</label>
                    <div class="w-full bg-wm-navy/30 border border-wm-navy/40 rounded-xl text-slate-400 text-sm
                                font-mono px-4 py-2.5 flex items-center gap-2 cursor-not-allowed">
                        <svg class="w-3.5 h-3.5 text-slate-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        {{ $outPatient->appointment_number }}
                    </div>
                </div>

                {{-- Patient (locked) --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold tracking-wide uppercase text-slate-400">Patient</label>
                    <div class="w-full bg-wm-navy/30 border border-wm-navy/40 rounded-xl text-slate-400 text-sm
                                px-4 py-2.5 flex items-center gap-2 cursor-not-allowed">
                        <svg class="w-3.5 h-3.5 text-slate-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        {{ $outPatient->patient->full_name }} ({{ $outPatient->patient_number }})
                    </div>
                </div>

            </div>
        </div>

        {{-- Section 2: Editable Date/Time --}}
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
                           value="{{ old('appointment_date_time', \Carbon\Carbon::parse($outPatient->appointment_date_time)->format('Y-m-d\TH:i')) }}"
                           class="w-full bg-wm-dark border rounded-xl text-white text-sm [color-scheme:dark]
                                  px-4 py-2.5 focus:outline-none transition-all duration-200
                                  {{ $errors->has('appointment_date_time') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}">
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
                <a href="{{ route('out_patients.show', $outPatient->appointment_number) }}"
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
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('editForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4 anim-spin" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/></svg> Saving…`;
    });
</script>
@endpush