@extends('layouts.app')

@section('title', 'Edit In-Patient Record')

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

    <a href="{{ route('in_patients.show', $inPatient->appointment_number) }}"
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
        <h1 class="text-3xl font-bold text-white tracking-tight">Edit In-Patient Record</h1>
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

    <form action="{{ route('in_patients.update', $inPatient->appointment_number) }}" method="POST" id="editForm" class="space-y-5">
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
                    {{ $inPatient->appointment_number }}
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
                        {{ $inPatient->appointment_number }}
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
                        {{ $inPatient->patient->full_name }} ({{ $inPatient->patient_number }})
                    </div>
                </div>

            </div>
        </div>

        {{-- Section 2: Ward & Dates (editable) --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up" style="animation-delay:.05s">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Section 2</p>
                    <h2 class="text-sm font-bold text-white leading-none">Ward, Bed & Dates</h2>
                </div>
            </div>

            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Ward --}}
                <div class="flex flex-col gap-1.5">
                    <label for="ward_number" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Ward Number <span class="text-wm-cyan">*</span>
                    </label>
                    <input type="text" id="ward_number" name="ward_number"
                           value="{{ old('ward_number', $inPatient->ward_number) }}"
                           placeholder="e.g. Ward A" maxlength="20"
                           class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                                  px-4 py-2.5 focus:outline-none transition-all duration-200
                                  {{ $errors->has('ward_number') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}">
                    @error('ward_number')<p class="flex items-center gap-1.5 text-xs text-red-400"><svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Bed --}}
                <div class="flex flex-col gap-1.5">
                    <label for="bed_number" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Bed Number <span class="text-wm-cyan">*</span>
                    </label>
                    <input type="text" id="bed_number" name="bed_number"
                           value="{{ old('bed_number', $inPatient->bed_number) }}"
                           placeholder="e.g. B-12" maxlength="20"
                           class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                                  px-4 py-2.5 focus:outline-none transition-all duration-200
                                  {{ $errors->has('bed_number') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}">
                    @error('bed_number')<p class="flex items-center gap-1.5 text-xs text-red-400"><svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Expected Stay --}}
                <div class="flex flex-col gap-1.5">
                    <label for="expected_stay" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Expected Stay (days) <span class="text-wm-cyan">*</span>
                    </label>
                    <input type="number" id="expected_stay" name="expected_stay"
                           value="{{ old('expected_stay', $inPatient->expected_stay) }}"
                           min="1"
                           class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                                  px-4 py-2.5 focus:outline-none transition-all duration-200
                                  {{ $errors->has('expected_stay') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}">
                    @error('expected_stay')<p class="flex items-center gap-1.5 text-xs text-red-400"><svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Date Placed --}}
                <div class="flex flex-col gap-1.5">
                    <label for="date_placed" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Date Placed <span class="text-wm-cyan">*</span>
                    </label>
                    <input type="date" id="date_placed" name="date_placed"
                           value="{{ old('date_placed', $inPatient->date_placed->format('Y-m-d')) }}"
                           class="w-full bg-wm-dark border rounded-xl text-white text-sm [color-scheme:dark]
                                  px-4 py-2.5 focus:outline-none transition-all duration-200
                                  {{ $errors->has('date_placed') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}">
                    @error('date_placed')<p class="flex items-center gap-1.5 text-xs text-red-400"><svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Expected Leave --}}
                <div class="flex flex-col gap-1.5">
                    <label for="date_leave" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Expected Leave <span class="normal-case font-normal text-slate-600 ml-1">(optional)</span>
                    </label>
                    <input type="date" id="date_leave" name="date_leave"
                           value="{{ old('date_leave', $inPatient->date_leave?->format('Y-m-d')) }}"
                           class="w-full bg-wm-dark border rounded-xl text-white text-sm [color-scheme:dark]
                                  px-4 py-2.5 focus:outline-none transition-all duration-200
                                  {{ $errors->has('date_leave') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}">
                    @error('date_leave')<p class="flex items-center gap-1.5 text-xs text-red-400"><svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Actual Leave --}}
                <div class="flex flex-col gap-1.5">
                    <label for="actual_leave" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Actual Leave <span class="normal-case font-normal text-slate-600 ml-1">(discharge date)</span>
                    </label>
                    <input type="date" id="actual_leave" name="actual_leave"
                           value="{{ old('actual_leave', $inPatient->actual_leave?->format('Y-m-d')) }}"
                           class="w-full bg-wm-dark border rounded-xl text-white text-sm [color-scheme:dark]
                                  px-4 py-2.5 focus:outline-none transition-all duration-200
                                  {{ $errors->has('actual_leave') ? 'border-red-500/60' : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}">
                    @error('actual_leave')<p class="flex items-center gap-1.5 text-xs text-red-400"><svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</p>@enderror
                </div>

            </div>

            {{-- Footer --}}
            <div class="flex flex-wrap items-center justify-end gap-3 px-6 py-4 border-t border-wm-navy/60 bg-wm-navy/10">
                <a href="{{ route('in_patients.show', $inPatient->appointment_number) }}"
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