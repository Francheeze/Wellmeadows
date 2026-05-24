@extends('layouts.app')

@section('title', 'Edit Next of Kin — ' . $nextOfKin->full_name)

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin   { to { transform: rotate(360deg); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .anim-spin    { animation: spin .7s linear infinite; }

    input:disabled {
        opacity: .55;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto px-6 py-8">

    {{-- ── Back Link ── --}}
    <a href="{{ route('patients.show', $patient->patient_number) }}"
       class="inline-flex items-center gap-2 text-slate-400 text-sm font-medium
              hover:text-wm-cyan transition-colors duration-200 mb-8 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Patient Profile
    </a>

    {{-- ── Page Header ── --}}
    <div class="mb-7 anim-fade-up">
        <p class="text-xs font-semibold tracking-widest uppercase text-wm-cyan-dim mb-1">
            Patient Management
        </p>
        <h1 class="text-3xl font-bold text-white tracking-tight">Edit Next of Kin</h1>

        {{-- Patient context pill --}}
        <div class="inline-flex items-center gap-2 mt-3 bg-wm-navy/60 border border-wm-navy
                    rounded-full px-3.5 py-1.5">
            <svg class="w-3.5 h-3.5 text-wm-cyan-dim shrink-0" fill="none" stroke="currentColor"
                 stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-xs text-slate-300 font-medium">{{ $patient->full_name }}</span>
            <span class="text-slate-600">·</span>
            <span class="text-xs font-mono text-wm-cyan font-bold">{{ $patient->patient_number }}</span>
        </div>

        {{-- Record ID pill --}}
        <span class="inline-flex items-center gap-2 ml-2 bg-amber-500/10 border border-amber-500/25
                     text-amber-400 text-xs font-semibold rounded-full px-3.5 py-1.5">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            ID: <span class="font-mono tracking-wider">{{ $nextOfKin->next_of_kin_id }}</span>
        </span>
    </div>

    {{-- ── Validation Errors ── --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-red-500/8 border border-red-500/30
                    text-red-400 text-sm px-4 py-4 rounded-2xl mb-6 anim-fade-up">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                 stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

    {{-- ── Form ── --}}
    <form action="{{ route('patients.next_of_kins.update', [$patient->patient_number, $nextOfKin]) }}"
          method="POST"
          id="nokForm">
        @csrf
        @method('PUT')

        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up">

            {{-- Card Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">
                        Editing Record
                    </p>
                    <h2 class="text-sm font-bold text-white leading-none">Next-of-Kin Details</h2>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Next of Kin ID (read-only) --}}
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Next-of-Kin ID
                    </label>
                    <input
                        type="text"
                        value="{{ $nextOfKin->next_of_kin_id }}"
                        disabled
                        class="w-full bg-wm-dark/60 border border-wm-navy/40 rounded-xl
                               text-slate-500 text-sm px-4 py-2.5 focus:outline-none"
                    >
                    <p class="text-xs text-slate-600">System-assigned ID — cannot be changed.</p>
                </div>

                {{-- Full Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="full_name"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Full Name <span class="text-amber-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        value="{{ old('full_name', $nextOfKin->full_name) }}"
                        placeholder="e.g. Juan Santos"
                        maxlength="100"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               {{ $errors->has('full_name')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-amber-400/60 focus:ring-2 focus:ring-amber-400/10' }}"
                    >
                    @error('full_name')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Relationship --}}
                <div class="flex flex-col gap-1.5">
                    <label for="relationship"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Relationship <span class="text-amber-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="relationship"
                        name="relationship"
                        value="{{ old('relationship', $nextOfKin->relationship) }}"
                        placeholder="e.g. Spouse, Parent, Sibling"
                        maxlength="50"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               {{ $errors->has('relationship')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-amber-400/60 focus:ring-2 focus:ring-amber-400/10' }}"
                    >
                    @error('relationship')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Telephone --}}
                <div class="flex flex-col gap-1.5">
                    <label for="telephone_number"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Telephone Number <span class="text-amber-400">*</span>
                    </label>
                    <div class="flex rounded-xl overflow-hidden border transition-all duration-200
                                {{ $errors->has('telephone_number')
                                    ? 'border-red-500/60 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/15'
                                    : 'border-wm-navy/70 focus-within:border-amber-400/60 focus-within:ring-2 focus-within:ring-amber-400/10' }}">
                        <span class="flex items-center px-3.5 bg-wm-navy/40 border-r border-wm-navy/70 shrink-0">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="telephone_number"
                            name="telephone_number"
                            value="{{ old('telephone_number', $nextOfKin->telephone_number) }}"
                            placeholder="e.g. 09171234567"
                            maxlength="20"
                            class="flex-1 min-w-0 bg-wm-dark text-white text-sm placeholder-slate-600
                                   px-3 py-2.5 focus:outline-none"
                        >
                    </div>
                    @error('telephone_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="flex flex-col gap-1.5">
                    <label for="address"
                           class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Address <span class="text-amber-400">*</span>
                    </label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        placeholder="Full residential address…"
                        maxlength="255"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none resize-none transition-all duration-200
                               {{ $errors->has('address')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-amber-400/60 focus:ring-2 focus:ring-amber-400/10' }}"
                    >{{ old('address', $nextOfKin->address) }}</textarea>
                    @error('address')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
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
            <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4
                        border-t border-wm-navy/60 bg-wm-navy/10">

                {{-- Last updated timestamp --}}
                <span class="hidden sm:flex items-center gap-1.5 text-xs text-slate-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Last updated: {{ $nextOfKin->updated_at->format('M d, Y h:i A') }}
                </span>

                <div class="flex items-center gap-3 ml-auto">
                    <a href="{{ route('patients.show', $patient->patient_number) }}"
                       class="inline-flex items-center gap-2 border border-wm-navy/70 text-slate-400
                              text-sm font-semibold px-5 py-2.5 rounded-xl hover:border-slate-500
                              hover:text-white transition-all duration-200 no-underline">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn"
                            class="inline-flex items-center gap-2 bg-amber-400 text-wm-dark text-sm font-bold
                                   px-6 py-2.5 rounded-xl hover:bg-amber-300 transition-all duration-200
                                   disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('nokForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="w-4 h-4 anim-spin" fill="none" stroke="currentColor"
                 stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
            </svg>
            Saving…`;
    });
</script>
@endpush