@extends('layouts.app')

@section('title', 'Edit Pharmaceutical Item')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .wm-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%234b6a82' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }
    .wm-select option { background: #032d4f; color: #e2e8f0; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-wm-dark font-sans">
<div class="max-w-[860px] mx-auto px-6 py-10">

    {{-- Back Link --}}
    <a href="{{ route('pharmaceutical_items.index') }}"
       class="inline-flex items-center gap-2 text-slate-400 hover:text-wm-cyan text-sm font-medium mb-8 transition-colors duration-200">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Pharmaceutical Items
    </a>

    {{-- Page Header --}}
    <div class="mb-8">
        <p class="text-[11px] font-semibold tracking-[0.2em] uppercase text-wm-cyan mb-1.5">Wellmeadows Hospital</p>
        <h1 class="text-3xl font-bold text-white tracking-tight">Edit Drug</h1>
        <div class="inline-flex items-center gap-2 bg-wm-cyan/10 border border-wm-cyan/25 text-wm-cyan text-[13px] font-semibold px-3 py-1.5 rounded-full mt-3">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            Drug ID: <span class="font-mono tracking-wide">{{ $pharmaceuticalItem->drug_number }}</span>
        </div>
    </div>

    {{-- Validation Error Summary --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-red-500/8 border border-red-400/30 text-red-400 text-sm font-medium px-4 py-4 rounded-xl mb-6">
            <svg class="shrink-0 mt-0.5" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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

    <form action="{{ route('pharmaceutical_items.update', $pharmaceuticalItem->drug_number) }}"
          method="POST"
          id="pharmaForm">
        @csrf
        @method('PUT')

        {{-- ── Section 1: Basic Information ── --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-xl shadow-black/30 mb-5">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/40 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-navy flex items-center justify-center text-wm-cyan shrink-0">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-slate-500">Section 1</p>
                    <h2 class="text-[15px] font-bold text-white leading-tight">Basic Information</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Drug Number (read-only) --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold tracking-wider uppercase text-slate-400">
                            Drug Number
                        </label>
                        <input type="number"
                               value="{{ $pharmaceuticalItem->drug_number }}"
                               disabled
                               class="w-full bg-wm-dark/60 border border-wm-navy/40 rounded-xl text-slate-500 text-sm px-4 py-2.5 cursor-not-allowed opacity-60">
                        <span class="text-[11px] text-slate-500">Drug ID cannot be changed after creation.</span>
                    </div>

                    {{-- Drug Name --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="drug_name" class="text-[11px] font-semibold tracking-wider uppercase text-slate-400">
                            Drug Name <span class="text-wm-cyan">*</span>
                        </label>
                        <input type="text" id="drug_name" name="drug_name"
                               value="{{ old('drug_name', $pharmaceuticalItem->drug_name) }}"
                               placeholder="e.g. Amoxicillin"
                               class="w-full bg-wm-dark border rounded-xl text-slate-200 text-sm px-4 py-2.5 placeholder-slate-600
                                      focus:outline-none focus:ring-2 transition-all duration-200
                                      {{ $errors->has('drug_name')
                                          ? 'border-red-400/50 focus:border-red-400/50 focus:ring-red-400/10'
                                          : 'border-wm-navy/60 focus:border-wm-cyan/50 focus:ring-wm-cyan/10' }}">
                        @error('drug_name')
                            <span class="flex items-center gap-1.5 text-[12px] text-red-400">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="flex flex-col gap-1.5 sm:col-span-2">
                        <label for="description" class="text-[11px] font-semibold tracking-wider uppercase text-slate-400">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  placeholder="Brief description of the drug…"
                                  class="w-full bg-wm-dark border rounded-xl text-slate-200 text-sm px-4 py-2.5 placeholder-slate-600 resize-y
                                         focus:outline-none focus:ring-2 transition-all duration-200
                                         {{ $errors->has('description')
                                             ? 'border-red-400/50 focus:border-red-400/50 focus:ring-red-400/10'
                                             : 'border-wm-navy/60 focus:border-wm-cyan/50 focus:ring-wm-cyan/10' }}"
                        >{{ old('description', $pharmaceuticalItem->description) }}</textarea>
                        @error('description')
                            <span class="flex items-center gap-1.5 text-[12px] text-red-400">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Section 2: Clinical Details ── --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-xl shadow-black/30 mb-5">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/40 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-navy flex items-center justify-center text-wm-cyan shrink-0">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-slate-500">Section 2</p>
                    <h2 class="text-[15px] font-bold text-white leading-tight">Clinical Details</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Dosage --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="dosage" class="text-[11px] font-semibold tracking-wider uppercase text-slate-400">
                            Dosage
                        </label>
                        <input type="text" id="dosage" name="dosage"
                               value="{{ old('dosage', $pharmaceuticalItem->dosage) }}"
                               placeholder="e.g. 500mg"
                               class="w-full bg-wm-dark border rounded-xl text-slate-200 text-sm px-4 py-2.5 placeholder-slate-600
                                      focus:outline-none focus:ring-2 transition-all duration-200
                                      {{ $errors->has('dosage')
                                          ? 'border-red-400/50 focus:border-red-400/50 focus:ring-red-400/10'
                                          : 'border-wm-navy/60 focus:border-wm-cyan/50 focus:ring-wm-cyan/10' }}">
                        @error('dosage')
                            <span class="flex items-center gap-1.5 text-[12px] text-red-400">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Method of Administration --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="method_of_administration" class="text-[11px] font-semibold tracking-wider uppercase text-slate-400">
                            Method of Administration
                        </label>
                        <select id="method_of_administration" name="method_of_administration"
                                class="wm-select w-full bg-wm-dark border rounded-xl text-slate-200 text-sm px-4 py-2.5 cursor-pointer
                                       focus:outline-none focus:ring-2 transition-all duration-200
                                       {{ $errors->has('method_of_administration')
                                           ? 'border-red-400/50 focus:border-red-400/50 focus:ring-red-400/10'
                                           : 'border-wm-navy/60 focus:border-wm-cyan/50 focus:ring-wm-cyan/10' }}">
                            <option value="" disabled {{ old('method_of_administration', $pharmaceuticalItem->method_of_administration) ? '' : 'selected' }}>
                                — Select method —
                            </option>
                            @foreach ([
                                'Oral', 'Intravenous (IV)', 'Intramuscular (IM)',
                                'Subcutaneous', 'Topical', 'Inhalation',
                                'Sublingual', 'Rectal', 'Ophthalmic', 'Otic', 'Other'
                            ] as $method)
                                <option value="{{ $method }}"
                                        {{ old('method_of_administration', $pharmaceuticalItem->method_of_administration) === $method ? 'selected' : '' }}>
                                    {{ $method }}
                                </option>
                            @endforeach
                        </select>
                        @error('method_of_administration')
                            <span class="flex items-center gap-1.5 text-[12px] text-red-400">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Section 3: Stock & Pricing ── --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-xl shadow-black/30 mb-5">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/40 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-navy flex items-center justify-center text-wm-cyan shrink-0">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-slate-500">Section 3</p>
                    <h2 class="text-[15px] font-bold text-white leading-tight">Stock & Pricing</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Quantity In Stock --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="quantity_in_stock" class="text-[11px] font-semibold tracking-wider uppercase text-slate-400">
                            Quantity In Stock <span class="text-wm-cyan">*</span>
                        </label>
                        <div class="flex items-stretch border rounded-xl overflow-hidden transition-all duration-200
                                    {{ $errors->has('quantity_in_stock')
                                        ? 'border-red-400/50 focus-within:ring-2 focus-within:ring-red-400/10'
                                        : 'border-wm-navy/60 focus-within:border-wm-cyan/50 focus-within:ring-2 focus-within:ring-wm-cyan/10' }}">
                            <span class="flex items-center px-3 bg-wm-navy/40 border-r border-wm-navy/60 text-slate-400 shrink-0">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                            </span>
                            <input type="number" id="quantity_in_stock" name="quantity_in_stock"
                                   value="{{ old('quantity_in_stock', $pharmaceuticalItem->quantity_in_stock) }}"
                                   min="0"
                                   oninput="updateStockStatus()"
                                   class="flex-1 min-w-0 bg-wm-dark text-slate-200 text-sm px-3 py-2.5 placeholder-slate-600 focus:outline-none">
                            <span class="flex items-center px-3 bg-wm-navy/40 border-l border-wm-navy/60 text-slate-400 text-[12px] font-medium shrink-0">
                                units
                            </span>
                        </div>
                        {{-- Live stock status indicator --}}
                        <div id="stockStatus"></div>
                        @error('quantity_in_stock')
                            <span class="flex items-center gap-1.5 text-[12px] text-red-400">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Reorder Level --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="reorder_level" class="text-[11px] font-semibold tracking-wider uppercase text-slate-400">
                            Reorder Level <span class="text-wm-cyan">*</span>
                        </label>
                        <div class="flex items-stretch border rounded-xl overflow-hidden transition-all duration-200
                                    {{ $errors->has('reorder_level')
                                        ? 'border-red-400/50 focus-within:ring-2 focus-within:ring-red-400/10'
                                        : 'border-wm-navy/60 focus-within:border-wm-cyan/50 focus-within:ring-2 focus-within:ring-wm-cyan/10' }}">
                            <span class="flex items-center px-3 bg-wm-navy/40 border-r border-wm-navy/60 text-slate-400 shrink-0">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </span>
                            <input type="number" id="reorder_level" name="reorder_level"
                                   value="{{ old('reorder_level', $pharmaceuticalItem->reorder_level) }}"
                                   min="0"
                                   oninput="updateStockStatus()"
                                   class="flex-1 min-w-0 bg-wm-dark text-slate-200 text-sm px-3 py-2.5 placeholder-slate-600 focus:outline-none">
                            <span class="flex items-center px-3 bg-wm-navy/40 border-l border-wm-navy/60 text-slate-400 text-[12px] font-medium shrink-0">
                                units
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-500">Alert triggers when stock drops to this level.</span>
                        @error('reorder_level')
                            <span class="flex items-center gap-1.5 text-[12px] text-red-400">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Cost Per Unit --}}
                    <div class="flex flex-col gap-1.5 sm:col-span-2">
                        <label for="cost_per_unit" class="text-[11px] font-semibold tracking-wider uppercase text-slate-400">
                            Cost Per Unit <span class="text-wm-cyan">*</span>
                        </label>
                        <div class="flex items-stretch border rounded-xl overflow-hidden transition-all duration-200
                                    {{ $errors->has('cost_per_unit')
                                        ? 'border-red-400/50 focus-within:ring-2 focus-within:ring-red-400/10'
                                        : 'border-wm-navy/60 focus-within:border-wm-cyan/50 focus-within:ring-2 focus-within:ring-wm-cyan/10' }}">
                            <span class="flex items-center px-4 bg-wm-navy/40 border-r border-wm-navy/60 text-slate-300 text-sm font-semibold shrink-0">
                                ₱
                            </span>
                            <input type="number" id="cost_per_unit" name="cost_per_unit"
                                   value="{{ old('cost_per_unit', $pharmaceuticalItem->cost_per_unit) }}"
                                   min="0" step="0.01" placeholder="0.00"
                                   class="flex-1 min-w-0 bg-wm-dark text-slate-200 text-sm px-3 py-2.5 placeholder-slate-600 focus:outline-none">
                            <span class="flex items-center px-3 bg-wm-navy/40 border-l border-wm-navy/60 text-slate-400 text-[12px] font-medium shrink-0">
                                PHP
                            </span>
                        </div>
                        @error('cost_per_unit')
                            <span class="flex items-center gap-1.5 text-[12px] text-red-400">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Section 4: Supplier ── --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-xl shadow-black/30">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/40 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-navy flex items-center justify-center text-wm-cyan shrink-0">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-slate-500">Section 4</p>
                    <h2 class="text-[15px] font-bold text-white leading-tight">Supplier</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="flex flex-col gap-1.5">
                    <label for="supplier_number" class="text-[11px] font-semibold tracking-wider uppercase text-slate-400">
                        Supplier <span class="text-wm-cyan">*</span>
                    </label>
                    <select id="supplier_number" name="supplier_number"
                            class="wm-select w-full bg-wm-dark border rounded-xl text-slate-200 text-sm px-4 py-2.5 cursor-pointer
                                   focus:outline-none focus:ring-2 transition-all duration-200
                                   {{ $errors->has('supplier_number')
                                       ? 'border-red-400/50 focus:border-red-400/50 focus:ring-red-400/10'
                                       : 'border-wm-navy/60 focus:border-wm-cyan/50 focus:ring-wm-cyan/10' }}">
                        <option value="" disabled>— Select a supplier —</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_number }}"
                                    {{ old('supplier_number', $pharmaceuticalItem->supplier_number) == $supplier->supplier_number ? 'selected' : '' }}>
                                #{{ $supplier->supplier_number }} — {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_number')
                        <span class="flex items-center gap-1.5 text-[12px] text-red-400">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Card Footer --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-wm-navy/40 bg-wm-navy/10 flex-wrap gap-3">
                <span class="hidden sm:flex items-center gap-1.5 text-[12px] text-slate-500">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Last updated: {{ $pharmaceuticalItem->updated_at->format('M d, Y h:i A') }}
                </span>
                <div class="flex items-center gap-3 ml-auto">
                    <a href="{{ route('pharmaceutical_items.index') }}"
                       class="inline-flex items-center gap-2 bg-transparent border border-wm-navy/60 text-slate-400 hover:border-slate-500 hover:text-slate-200 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn"
                            class="inline-flex items-center gap-2 bg-wm-cyan hover:bg-wm-cyan-dim text-wm-dark text-sm font-bold px-6 py-2.5 rounded-xl
                                   transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-black/30 disabled:opacity-60 disabled:cursor-not-allowed disabled:transform-none">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
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
@endsection

@push('scripts')
<script>
    function updateStockStatus() {
        const qty     = parseInt(document.getElementById('quantity_in_stock').value) || 0;
        const reorder = parseInt(document.getElementById('reorder_level').value)     || 0;
        const el      = document.getElementById('stockStatus');

        if (qty === 0) {
            el.innerHTML = `
                <span class="inline-flex items-center gap-1.5 bg-red-500/10 text-red-400 text-[12px] font-semibold px-2.5 py-1 rounded-md mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    Out of Stock
                </span>`;
        } else if (qty <= reorder) {
            el.innerHTML = `
                <span class="inline-flex items-center gap-1.5 bg-amber-500/10 text-amber-400 text-[12px] font-semibold px-2.5 py-1 rounded-md mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    Low Stock — reorder soon
                </span>`;
        } else {
            el.innerHTML = `
                <span class="inline-flex items-center gap-1.5 bg-teal-500/10 text-teal-400 text-[12px] font-semibold px-2.5 py-1 rounded-md mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    In Stock
                </span>`;
        }
    }

    updateStockStatus();

    document.getElementById('pharmaForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"
                 viewBox="0 0 24 24" style="animation:spin .7s linear infinite;">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
            </svg>
            Saving…`;
    });
</script>
@endpush