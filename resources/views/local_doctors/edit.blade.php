@extends('layouts.app')

@section('title', 'Edit Local Doctor')

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
        <a href="{{ route('local_doctors.index') }}" class="hover:text-wm-cyan transition-colors">Local Doctors</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('local_doctors.show', $localDoctor->clinic_number) }}"
           class="hover:text-wm-cyan transition-colors truncate max-w-[160px]">
            {{ $localDoctor->full_name }}
        </a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-400">Edit</span>
    </nav>

    {{-- Page Header --}}
    <div class="mb-6 anim-fade-up" style="animation-delay:.03s">
        <h1 class="text-2xl font-bold text-white">Edit Local Doctor</h1>
        <p class="text-sm text-slate-400 mt-1">Update information for <span class="text-white font-medium">{{ $localDoctor->full_name }}</span>.</p>
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

        <form action="{{ route('local_doctors.update', $localDoctor->clinic_number) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Section: Clinic Information --}}
            <div class="px-6 py-5 border-b border-wm-navy/60">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">
                    Clinic Information
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Clinic Number (read-only) --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Clinic #
                            <span class="ml-1 text-slate-600 font-normal normal-case tracking-normal">(cannot be changed)</span>
                        </label>
                        <div class="w-full bg-wm-navy/50 border border-wm-navy/40 rounded-xl text-slate-500
                                    text-sm px-4 py-2.5 font-mono cursor-not-allowed select-none">
                            {{ $localDoctor->clinic_number }}
                        </div>
                    </div>

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Full Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="full_name"
                               value="{{ old('full_name', $localDoctor->full_name) }}"
                               placeholder="Dr. Juan dela Cruz"
                               class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                      px-4 py-2.5 placeholder-slate-600
                                      focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                      transition-all duration-200
                                      @error('full_name') border-red-500/60 @enderror">
                        @error('full_name')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Section: Contact Details --}}
            <div class="px-6 py-5">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-wm-cyan mb-4">
                    Contact Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Telephone --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Telephone <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="telephone_number"
                               value="{{ old('telephone_number', $localDoctor->telephone_number) }}"
                               placeholder="09XX-XXX-XXXX"
                               class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                      px-4 py-2.5 placeholder-slate-600
                                      focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                      transition-all duration-200
                                      @error('telephone_number') border-red-500/60 @enderror">
                        @error('telephone_number')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                            Address <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="address"
                               value="{{ old('address', $localDoctor->address) }}"
                               placeholder="Clinic address"
                               class="w-full bg-wm-navy/30 border border-wm-navy/60 rounded-xl text-white text-sm
                                      px-4 py-2.5 placeholder-slate-600
                                      focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                                      transition-all duration-200
                                      @error('address') border-red-500/60 @enderror">
                        @error('address')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-wm-navy/60
                        bg-wm-navy/10 rounded-b-2xl">

                {{-- Delete button (separate form) --}}
                <form action="{{ route('local_doctors.destroy', $localDoctor->clinic_number) }}"
                      method="POST"
                      onsubmit="return confirm('Delete {{ addslashes($localDoctor->full_name) }}?\n\nPatients referred by this doctor will lose the referral link.\n\nThis cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-400
                                   hover:text-red-300 transition-colors duration-200 px-3 py-2 rounded-lg
                                   hover:bg-red-500/10 cursor-pointer bg-transparent border-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Doctor
                    </button>
                </form>

                <div class="flex items-center gap-3">
                    <a href="{{ route('local_doctors.show', $localDoctor->clinic_number) }}"
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