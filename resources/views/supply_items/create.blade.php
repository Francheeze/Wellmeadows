@extends('layouts.app')

@section('title', 'Add Supply Item')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin   { to { transform: rotate(360deg); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .anim-spin    { animation: spin .7s linear infinite; }

    /* Custom select arrow */
    select.wm-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }
    select.wm-select option {
        background-color: #032d4f;
        color: #ffffff;
    }
</style>
@endpush

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

    {{-- ── Back Link ── --}}
    <a href="{{ route('supply_items.index') }}"
       class="inline-flex items-center gap-2 text-slate-400 text-sm font-medium
              hover:text-wm-cyan transition-colors duration-200 mb-8 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Supply Items
    </a>

    {{-- ── Page Header ── --}}
    <div class="mb-7 anim-fade-up">
        <p class="text-xs font-semibold tracking-widest uppercase text-wm-cyan-dim mb-1">
            Inventory Management
        </p>
        <h1 class="text-3xl font-bold text-white tracking-tight">Add New Supply Item</h1>
    </div>

    {{-- ── Validation Error Summary ── --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-red-500/8 border border-red-500/30
                    text-red-400 text-sm px-4 py-4 rounded-2xl mb-6 anim-fade-up">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

    <form action="{{ route('supply_items.store') }}" method="POST" id="supplyForm" class="space-y-5">
        @csrf

        {{-- ══════════════════════════════════════
             Section 1 · Basic Information
        ══════════════════════════════════════ --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up">

            {{-- Card Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4.5 h-4.5 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Step 1</p>
                    <h2 class="text-sm font-bold text-white leading-none">Basic Information</h2>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Item Number --}}
                <div class="flex flex-col gap-1.5">
                    <label for="item_number" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Item Number <span class="text-wm-cyan">*</span>
                    </label>
                    <input
                        type="number"
                        id="item_number"
                        name="item_number"
                        value="{{ old('item_number') }}"
                        placeholder="e.g. 1001"
                        min="1"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               {{ $errors->has('item_number')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                    <p class="text-xs text-slate-500">Unique ID for this supply item.</p>
                    @error('item_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Item Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="item_name" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Item Name <span class="text-wm-cyan">*</span>
                    </label>
                    <input
                        type="text"
                        id="item_name"
                        name="item_name"
                        value="{{ old('item_name') }}"
                        placeholder="e.g. Surgical Gloves"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none transition-all duration-200
                               {{ $errors->has('item_name')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                    @error('item_name')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="description" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Brief description of the item and its intended use…"
                        class="w-full bg-wm-dark border rounded-xl text-white text-sm placeholder-slate-600
                               px-4 py-2.5 focus:outline-none resize-y transition-all duration-200
                               {{ $errors->has('description')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════
             Section 2 · Stock & Pricing
        ══════════════════════════════════════ --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up"
             style="animation-delay:.05s">

            {{-- Card Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4.5 h-4.5 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Step 2</p>
                    <h2 class="text-sm font-bold text-white leading-none">Stock & Pricing</h2>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Quantity In Stock --}}
                <div class="flex flex-col gap-1.5">
                    <label for="quantity_in_stock" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Quantity In Stock <span class="text-wm-cyan">*</span>
                    </label>
                    <div class="flex rounded-xl overflow-hidden border transition-all duration-200
                                {{ $errors->has('quantity_in_stock')
                                    ? 'border-red-500/60 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/15'
                                    : 'border-wm-navy/70 focus-within:border-wm-cyan/60 focus-within:ring-2 focus-within:ring-wm-cyan/15' }}">
                        <span class="flex items-center px-3 bg-wm-navy/40 border-r border-wm-navy/70">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </span>
                        <input
                            type="number"
                            id="quantity_in_stock"
                            name="quantity_in_stock"
                            value="{{ old('quantity_in_stock', 0) }}"
                            min="0"
                            placeholder="0"
                            class="flex-1 min-w-0 bg-wm-dark text-white text-sm placeholder-slate-600
                                   px-3 py-2.5 focus:outline-none"
                        >
                        <span class="flex items-center px-3 bg-wm-navy/40 border-l border-wm-navy/70
                                     text-xs font-semibold text-slate-500">
                            units
                        </span>
                    </div>
                    @error('quantity_in_stock')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Reorder Level --}}
                <div class="flex flex-col gap-1.5">
                    <label for="reorder_level" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Reorder Level <span class="text-wm-cyan">*</span>
                    </label>
                    <div class="flex rounded-xl overflow-hidden border transition-all duration-200
                                {{ $errors->has('reorder_level')
                                    ? 'border-red-500/60 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/15'
                                    : 'border-wm-navy/70 focus-within:border-wm-cyan/60 focus-within:ring-2 focus-within:ring-wm-cyan/15' }}">
                        <span class="flex items-center px-3 bg-wm-navy/40 border-r border-wm-navy/70">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </span>
                        <input
                            type="number"
                            id="reorder_level"
                            name="reorder_level"
                            value="{{ old('reorder_level', 0) }}"
                            min="0"
                            placeholder="0"
                            class="flex-1 min-w-0 bg-wm-dark text-white text-sm placeholder-slate-600
                                   px-3 py-2.5 focus:outline-none"
                        >
                        <span class="flex items-center px-3 bg-wm-navy/40 border-l border-wm-navy/70
                                     text-xs font-semibold text-slate-500">
                            units
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Alert triggers when stock drops to this level.</p>
                    @error('reorder_level')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Cost Per Unit --}}
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="cost_per_unit" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Cost Per Unit <span class="text-wm-cyan">*</span>
                    </label>
                    <div class="flex rounded-xl overflow-hidden border transition-all duration-200
                                {{ $errors->has('cost_per_unit')
                                    ? 'border-red-500/60 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/15'
                                    : 'border-wm-navy/70 focus-within:border-wm-cyan/60 focus-within:ring-2 focus-within:ring-wm-cyan/15' }}">
                        <span class="flex items-center px-4 bg-wm-navy/40 border-r border-wm-navy/70
                                     text-sm font-bold text-slate-300">
                            ₱
                        </span>
                        <input
                            type="number"
                            id="cost_per_unit"
                            name="cost_per_unit"
                            value="{{ old('cost_per_unit') }}"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            class="flex-1 min-w-0 bg-wm-dark text-white text-sm placeholder-slate-600
                                   px-3 py-2.5 focus:outline-none"
                        >
                        <span class="flex items-center px-3 bg-wm-navy/40 border-l border-wm-navy/70
                                     text-xs font-semibold text-slate-500">
                            PHP
                        </span>
                    </div>
                    @error('cost_per_unit')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════
             Section 3 · Supplier
        ══════════════════════════════════════ --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,.35)] anim-fade-up"
             style="animation-delay:.1s">

            {{-- Card Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <div class="w-9 h-9 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                    <svg class="w-4.5 h-4.5 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 leading-none mb-0.5">Step 3</p>
                    <h2 class="text-sm font-bold text-white leading-none">Supplier</h2>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6">
                <div class="flex flex-col gap-1.5">
                    <label for="supplier_number" class="text-xs font-semibold tracking-wide uppercase text-slate-400">
                        Supplier <span class="text-wm-cyan">*</span>
                    </label>
                    <select
                        id="supplier_number"
                        name="supplier_number"
                        class="wm-select w-full bg-wm-dark border rounded-xl text-white text-sm
                               px-4 py-2.5 focus:outline-none cursor-pointer transition-all duration-200
                               {{ $errors->has('supplier_number')
                                    ? 'border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/15'
                                    : 'border-wm-navy/70 focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15' }}"
                    >
                        <option value="" disabled {{ old('supplier_number') ? '' : 'selected' }}
                                class="text-slate-500">
                            — Select a supplier —
                        </option>
                        @foreach ($suppliers as $supplier)
                            <option
                                value="{{ $supplier->supplier_number }}"
                                {{ old('supplier_number') == $supplier->supplier_number ? 'selected' : '' }}
                            >
                                #{{ $supplier->supplier_number }} — {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_number')
                        <p class="flex items-center gap-1.5 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- ── Card Footer / Submit ── --}}
            <div class="flex flex-wrap items-center justify-end gap-3 px-6 py-4
                        border-t border-wm-navy/60 bg-wm-navy/10">
                <a href="{{ route('supply_items.index') }}"
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
                    Save Item
                </button>
            </div>

        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('supplyForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="w-4 h-4 anim-spin" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
            </svg>
            Saving…`;
    });
</script>
@endpush