@extends('layouts.app')

@section('title', 'Edit Requisition')

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
    @keyframes fadeSlideIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes rowSlideIn  { from { opacity:0; transform:translateY(6px);  } to { opacity:1; transform:translateY(0); } }
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
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back Link --}}
    <a href="{{ route('requisitions.index') }}"
       class="inline-flex items-center gap-1.5 text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium mb-8 transition-colors no-underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Requisitions
    </a>

    {{-- Page Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">Wellmeadows Hospital</p>
        <h1 class="text-3xl font-bold gradient-text leading-tight mb-2">Edit Requisition</h1>
        <span class="inline-flex items-center gap-1.5 bg-[#CCECEE]/10 border border-[#CCECEE]/20 text-[#CCECEE] text-xs font-semibold px-3 py-1 rounded-full">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            Requisition #{{ $requisition->requisition_number }}
        </span>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-medium p-4 rounded-xl mb-6">
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

    <form action="{{ route('requisitions.update', $requisition->requisition_number) }}"
          method="POST" id="reqForm">
        @csrf
        @method('PUT')

        {{-- ── Section 1: Requisition Details ── --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-[#03416E]/30">
                <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/10 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Section 1</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Requisition Details</h2>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Requisition Number (read-only) --}}
                <div class="sm:col-span-2 flex flex-col gap-1.5">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">Requisition Number</label>
                        <span class="inline-flex items-center gap-1 bg-white/5 border border-white/10 text-[#CCECEE]/40 text-xs font-semibold px-2 py-0.5 rounded">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            Read-only
                        </span>
                    </div>
                    <input type="number" value="{{ $requisition->requisition_number }}" disabled
                           class="bg-[#021829]/60 border border-[#CCECEE]/10 text-[#CCECEE]/40 text-sm rounded-xl px-4 py-2.5 w-full cursor-not-allowed">
                    <p class="text-xs text-[#CCECEE]/30">The requisition number cannot be changed after creation.</p>
                </div>

                {{-- Date Ordered --}}
                <div class="flex flex-col gap-1.5">
                    <label for="date_ordered" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Date Ordered <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input type="date" id="date_ordered" name="date_ordered"
                           value="{{ old('date_ordered', \Carbon\Carbon::parse($requisition->date_ordered)->format('Y-m-d')) }}"
                           class="bg-[#021829] border {{ $errors->has('date_ordered') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] text-sm rounded-xl px-4 py-2.5 w-full outline-none transition">
                    @error('date_ordered')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Staff Number --}}
                <div class="flex flex-col gap-1.5">
                    <label for="staff_number" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Staff Number <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input type="number" id="staff_number" name="staff_number"
                           value="{{ old('staff_number', $requisition->staff_number) }}" placeholder="e.g. 101" min="1"
                           class="bg-[#021829] border {{ $errors->has('staff_number') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl px-4 py-2.5 w-full outline-none transition">
                    @error('staff_number')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Ward Number --}}
                <div class="flex flex-col gap-1.5">
                    <label for="ward_number" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Ward Number <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input type="number" id="ward_number" name="ward_number"
                           value="{{ old('ward_number', $requisition->ward_number) }}" placeholder="e.g. 5" min="1"
                           class="bg-[#021829] border {{ $errors->has('ward_number') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl px-4 py-2.5 w-full outline-none transition">
                    @error('ward_number')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ── Section 2: Drug Items ── --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-emerald-500/5">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Section 2 — Optional</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Drug Items</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                    <div class="flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-emerald-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        Pharmaceutical Items
                        <span id="drugCount" class="bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-2 py-0.5 rounded">0</span>
                    </div>
                    <button type="button" onclick="addDrugRow()"
                            class="inline-flex items-center gap-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-3 py-1.5 rounded-lg transition hover:-translate-y-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Drug
                    </button>
                </div>

                <div class="flex flex-col gap-3" id="drugItemsList">
                    @forelse ($requisition->drugItems as $drug)
                    <div class="grid grid-cols-[1fr_140px_36px] gap-3 items-end bg-[#021829]/60 border border-[#CCECEE]/10 rounded-xl p-3" id="drug-row-{{ $loop->index }}" style="animation:rowSlideIn .2s ease both;">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#CCECEE]/40 mb-1.5">Drug</p>
                            <select name="drug_items[{{ $loop->index }}][drug_number]" class="wm-select bg-[#021829] border border-[#CCECEE]/20 text-[#f0f7f8] text-sm rounded-xl px-3 py-2 w-full outline-none focus:border-[#CCECEE]/50 transition">
                                <option value="" disabled>— Select drug —</option>
                                @foreach ($pharmaceuticalItems as $pharma)
                                    <option value="{{ $pharma->drug_number }}" {{ $drug->drug_number == $pharma->drug_number ? 'selected' : '' }}>
                                        #{{ $pharma->drug_number }} — {{ $pharma->drug_name }} {{ $pharma->dosage ? '('.$pharma->dosage.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#CCECEE]/40 mb-1.5">Qty Required</p>
                            <input type="number" name="drug_items[{{ $loop->index }}][quantity_required]"
                                   value="{{ $drug->pivot->quantity_required }}" min="1" placeholder="0"
                                   class="bg-[#021829] border border-[#CCECEE]/20 text-[#f0f7f8] text-sm rounded-xl px-3 py-2 w-full outline-none focus:border-[#CCECEE]/50 transition">
                        </div>
                        <button type="button" onclick="removeRow('drug-row-{{ $loop->index }}', 'drug')"
                                class="flex items-center justify-center w-9 h-9 rounded-lg bg-red-400/8 border border-red-400/20 text-red-400/60 hover:bg-red-400/15 hover:text-red-400 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    @empty @endforelse
                </div>

                <div id="drugEmptyState" class="text-center py-8 border-2 border-dashed border-[#CCECEE]/10 rounded-xl text-[#CCECEE]/30 text-sm {{ $requisition->drugItems->isNotEmpty() ? 'hidden' : '' }}">
                    <svg class="w-8 h-8 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    No drug items added. Click <strong class="text-[#CCECEE]/50">Add Drug</strong> to begin.
                </div>
            </div>
        </div>

        {{-- ── Section 3: Supply Items ── --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl fade-in">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-violet-500/5">
                <div class="w-9 h-9 rounded-xl bg-violet-500/15 border border-violet-500/20 flex items-center justify-center text-violet-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Section 3 — Optional</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Supply Items</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                    <div class="flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-violet-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                        Surgical / None Items
                        <span id="supplyCount" class="bg-violet-500/15 border border-violet-500/20 text-violet-400 text-xs font-bold px-2 py-0.5 rounded">0</span>
                    </div>
                    <button type="button" onclick="addSupplyRow()"
                            class="inline-flex items-center gap-1.5 bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-400 text-xs font-bold px-3 py-1.5 rounded-lg transition hover:-translate-y-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Item
                    </button>
                </div>

                <div class="flex flex-col gap-3" id="supplyItemsList">
                    @forelse ($requisition->supplyItems as $item)
                    <div class="grid grid-cols-[1fr_140px_36px] gap-3 items-end bg-[#021829]/60 border border-[#CCECEE]/10 rounded-xl p-3" id="supply-row-{{ $loop->index }}" style="animation:rowSlideIn .2s ease both;">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#CCECEE]/40 mb-1.5">Item</p>
                            <select name="supply_items[{{ $loop->index }}][item_number]" class="wm-select bg-[#021829] border border-[#CCECEE]/20 text-[#f0f7f8] text-sm rounded-xl px-3 py-2 w-full outline-none focus:border-[#CCECEE]/50 transition">
                                <option value="" disabled>— Select item —</option>
                                @foreach ($supplyItems as $si)
                                    <option value="{{ $si->item_number }}" {{ $item->item_number == $si->item_number ? 'selected' : '' }}>
                                        #{{ $si->item_number }} — {{ $si->item_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#CCECEE]/40 mb-1.5">Qty Required</p>
                            <input type="number" name="supply_items[{{ $loop->index }}][quantity_required]"
                                   value="{{ $item->pivot->quantity_required }}" min="1" placeholder="0"
                                   class="bg-[#021829] border border-[#CCECEE]/20 text-[#f0f7f8] text-sm rounded-xl px-3 py-2 w-full outline-none focus:border-[#CCECEE]/50 transition">
                        </div>
                        <button type="button" onclick="removeRow('supply-row-{{ $loop->index }}', 'supply')"
                                class="flex items-center justify-center w-9 h-9 rounded-lg bg-red-400/8 border border-red-400/20 text-red-400/60 hover:bg-red-400/15 hover:text-red-400 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    @empty @endforelse
                </div>

                <div id="supplyEmptyState" class="text-center py-8 border-2 border-dashed border-[#CCECEE]/10 rounded-xl text-[#CCECEE]/30 text-sm {{ $requisition->supplyItems->isNotEmpty() ? 'hidden' : '' }}">
                    <svg class="w-8 h-8 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    No supply items added. Click <strong class="text-[#CCECEE]/50">Add Item</strong> to begin.
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-[#CCECEE]/10 bg-[#03416E]/10 flex-wrap">
                <p class="text-xs text-[#CCECEE]/40">
                    Last updated: {{ $requisition->updated_at->format('M d, Y h:i A') }}
                </p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('requisitions.index') }}"
                       class="inline-flex items-center gap-2 bg-transparent border border-[#CCECEE]/20 text-[#CCECEE]/50 hover:border-[#CCECEE]/40 hover:text-[#CCECEE]/80 text-sm font-semibold px-5 py-2.5 rounded-xl transition no-underline">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn"
                            class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
</div>

<script>
    const drugOptions = @json($pharmaceuticalItems->map(fn($p) => [
        'value' => $p->drug_number,
        'label' => '#' . $p->drug_number . ' — ' . $p->drug_name . ($p->dosage ? ' (' . $p->dosage . ')' : ''),
    ]));

    const supplyOptions = @json($supplyItems->map(fn($s) => [
        'value' => $s->item_number,
        'label' => '#' . $s->item_number . ' — ' . $s->item_name,
    ]));

    let drugIndex   = {{ $requisition->drugItems->count() }};
    let supplyIndex = {{ $requisition->supplyItems->count() }};

    const inputCls  = 'bg-[#021829] border border-[#CCECEE]/20 text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl px-3 py-2 w-full outline-none focus:border-[#CCECEE]/50 transition';
    const selectCls = 'wm-select bg-[#021829] border border-[#CCECEE]/20 text-[#f0f7f8] text-sm rounded-xl px-3 py-2 w-full outline-none focus:border-[#CCECEE]/50 transition';
    const rowCls    = 'grid grid-cols-[1fr_140px_36px] gap-3 items-end bg-[#021829]/60 border border-[#CCECEE]/10 rounded-xl p-3';
    const removeCls = 'flex items-center justify-center w-9 h-9 rounded-lg bg-red-400/8 border border-red-400/20 text-red-400/60 hover:bg-red-400/15 hover:text-red-400 transition';
    const labelCls  = 'text-xs font-semibold uppercase tracking-wider text-[#CCECEE]/40 mb-1.5';

    function buildSelect(name, options, placeholder) {
        let html = `<select name="${name}" class="${selectCls}"><option value="" disabled selected>— ${placeholder} —</option>`;
        options.forEach(o => { html += `<option value="${o.value}">${o.label}</option>`; });
        return html + '</select>';
    }

    function removeBtn(id, type) {
        return `<button type="button" onclick="removeRow('${id}','${type}')" class="${removeCls}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg></button>`;
    }

    function addDrugRow() {
        const list = document.getElementById('drugItemsList');
        const id   = 'drug-row-' + drugIndex;
        const div  = document.createElement('div');
        div.className = rowCls;
        div.id = id;
        div.style.animation = 'rowSlideIn .2s ease both';
        div.innerHTML = `
            <div><p class="${labelCls}">Drug</p>${buildSelect('drug_items['+drugIndex+'][drug_number]', drugOptions, 'Select drug')}</div>
            <div><p class="${labelCls}">Qty Required</p><input type="number" name="drug_items[${drugIndex}][quantity_required]" min="1" placeholder="0" class="${inputCls}"></div>
            ${removeBtn(id, 'drug')}`;
        list.appendChild(div);
        document.getElementById('drugEmptyState').classList.add('hidden');
        drugIndex++;
        updateCounts();
    }

    function addSupplyRow() {
        const list = document.getElementById('supplyItemsList');
        const id   = 'supply-row-' + supplyIndex;
        const div  = document.createElement('div');
        div.className = rowCls;
        div.id = id;
        div.style.animation = 'rowSlideIn .2s ease both';
        div.innerHTML = `
            <div><p class="${labelCls}">Item</p>${buildSelect('supply_items['+supplyIndex+'][item_number]', supplyOptions, 'Select item')}</div>
            <div><p class="${labelCls}">Qty Required</p><input type="number" name="supply_items[${supplyIndex}][quantity_required]" min="1" placeholder="0" class="${inputCls}"></div>
            ${removeBtn(id, 'supply')}`;
        list.appendChild(div);
        document.getElementById('supplyEmptyState').classList.add('hidden');
        supplyIndex++;
        updateCounts();
    }

    function removeRow(id, type) {
        document.getElementById(id)?.remove();
        const list  = document.getElementById(type === 'drug' ? 'drugItemsList' : 'supplyItemsList');
        const empty = document.getElementById(type === 'drug' ? 'drugEmptyState' : 'supplyEmptyState');
        if (list.children.length === 0) empty.classList.remove('hidden');
        updateCounts();
    }

    function updateCounts() {
        document.getElementById('drugCount').textContent   = document.getElementById('drugItemsList').children.length;
        document.getElementById('supplyCount').textContent = document.getElementById('supplyItemsList').children.length;
    }

    document.addEventListener('DOMContentLoaded', updateCounts);

    document.getElementById('reqForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
            viewBox="0 0 24 24" style="animation:spin .7s linear infinite;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
        </svg> Saving…`;
    });
</script>
@endsection