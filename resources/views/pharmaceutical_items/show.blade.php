@extends('layouts.app')

@section('title', 'Drug Details')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')

@php
    $qty        = $pharmaceuticalItem->quantity_in_stock;
    $reorder    = $pharmaceuticalItem->reorder_level;
    $stockClass = $qty === 0 ? 'critical' : ($qty <= $reorder ? 'low' : 'ok');
    $stockLabel = $qty === 0 ? 'Out of Stock' : ($qty <= $reorder ? 'Low Stock' : 'In Stock');
    $pct        = $reorder > 0 ? min(100, round(($qty / ($reorder * 2)) * 100)) : ($qty > 0 ? 100 : 0);
@endphp

<div class="min-h-screen bg-wm-dark font-sans">
<div class="max-w-[1000px] mx-auto px-6 py-10">

    {{-- Back Link --}}
    <a href="{{ route('pharmaceutical_items.index') }}"
       class="inline-flex items-center gap-2 text-slate-400 hover:text-wm-cyan text-sm font-medium mb-8 transition-colors duration-200">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Pharmaceutical Items
    </a>

    {{-- Flash --}}
    @if (session('success'))
        <div class="flex items-center gap-3 bg-teal-400/10 border border-teal-400/25 text-teal-300 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="shrink-0" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Hero Card --}}
    <div class="bg-wm-card border border-wm-navy/50 rounded-2xl p-6 mb-6 flex items-start justify-between gap-6 flex-wrap shadow-2xl shadow-black/40">

        <div class="flex items-start gap-4 flex-1 min-w-0">
            {{-- Icon --}}
            <div class="w-14 h-14 bg-wm-cyan/10 border border-wm-cyan/20 rounded-2xl flex items-center justify-center text-wm-cyan shrink-0">
                <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            {{-- Info --}}
            <div class="min-w-0">
                <p class="text-[11px] font-semibold tracking-[0.15em] uppercase text-wm-cyan mb-1">Pharmaceutical Item</p>
                <h1 class="text-2xl font-bold text-white tracking-tight leading-tight mb-3 truncate">
                    {{ $pharmaceuticalItem->drug_name }}
                </h1>
                <div class="flex items-center gap-2.5 flex-wrap">
                    {{-- ID Badge --}}
                    <span class="inline-flex items-center gap-1.5 bg-wm-cyan/10 border border-wm-cyan/20 text-wm-cyan text-[12px] font-bold px-2.5 py-1 rounded-md font-mono">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                        {{ $pharmaceuticalItem->drug_number }}
                    </span>
                    {{-- Stock Pill --}}
                    @if ($stockClass === 'critical')
                        <span class="inline-flex items-center gap-1.5 bg-red-500/10 border border-red-500/20 text-red-400 text-[12px] font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $stockLabel }}
                        </span>
                    @elseif ($stockClass === 'low')
                        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[12px] font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $stockLabel }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-teal-500/10 border border-teal-500/20 text-teal-400 text-[12px] font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $stockLabel }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2.5 shrink-0 w-full sm:w-auto">
            <a href="{{ route('pharmaceutical_items.edit', $pharmaceuticalItem->drug_number) }}"
               class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-wm-navy/60 border border-wm-cyan/25 text-wm-cyan text-sm font-bold px-4 py-2.5 rounded-xl
                      hover:bg-wm-cyan/10 hover:border-wm-cyan/50 hover:-translate-y-0.5 transition-all duration-200">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('pharmaceutical_items.destroy', $pharmaceuticalItem->drug_number) }}"
                  method="POST"
                  onsubmit="return confirmDelete(event, '{{ $pharmaceuticalItem->drug_name }}')"
                  class="flex-1 sm:flex-none">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-red-500/8 border border-red-400/25 text-red-400 text-sm font-bold px-4 py-2.5 rounded-xl cursor-pointer
                               hover:bg-red-400/15 hover:border-red-400/50 hover:-translate-y-0.5 transition-all duration-200">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>

    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Drug Details --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
            <div class="flex items-center gap-2.5 px-5 py-3.5 border-b border-wm-navy/40 bg-wm-navy/15">
                <svg class="text-wm-cyan shrink-0" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Drug Details</h3>
            </div>
            <div class="divide-y divide-white/[0.04]">

                <div class="flex items-baseline justify-between px-5 py-3 gap-4">
                    <span class="text-[11px] font-semibold tracking-wider uppercase text-slate-500 shrink-0 min-w-[110px]">Drug Number</span>
                    <span class="text-[13px] font-bold text-wm-cyan font-mono text-right">{{ $pharmaceuticalItem->drug_number }}</span>
                </div>

                <div class="flex items-baseline justify-between px-5 py-3 gap-4">
                    <span class="text-[11px] font-semibold tracking-wider uppercase text-slate-500 shrink-0 min-w-[110px]">Drug Name</span>
                    <span class="text-[13px] font-medium text-slate-200 text-right">{{ $pharmaceuticalItem->drug_name }}</span>
                </div>

                <div class="flex items-baseline justify-between px-5 py-3 gap-4">
                    <span class="text-[11px] font-semibold tracking-wider uppercase text-slate-500 shrink-0 min-w-[110px]">Dosage</span>
                    @if ($pharmaceuticalItem->dosage)
                        <span class="text-[13px] font-medium text-slate-200 text-right">{{ $pharmaceuticalItem->dosage }}</span>
                    @else
                        <span class="text-[13px] italic text-slate-500 text-right">Not specified</span>
                    @endif
                </div>

                <div class="flex items-baseline justify-between px-5 py-3 gap-4">
                    <span class="text-[11px] font-semibold tracking-wider uppercase text-slate-500 shrink-0 min-w-[110px]">Method</span>
                    @if ($pharmaceuticalItem->method_of_administration)
                        <span class="text-[13px] font-medium text-slate-200 text-right">{{ $pharmaceuticalItem->method_of_administration }}</span>
                    @else
                        <span class="text-[13px] italic text-slate-500 text-right">Not specified</span>
                    @endif
                </div>

                <div class="flex items-baseline justify-between px-5 py-3 gap-4">
                    <span class="text-[11px] font-semibold tracking-wider uppercase text-slate-500 shrink-0 min-w-[110px]">Cost Per Unit</span>
                    <span class="text-[17px] font-bold text-wm-cyan tabular-nums text-right">
                        ₱{{ number_format($pharmaceuticalItem->cost_per_unit, 2) }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Stock Overview --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
            <div class="flex items-center gap-2.5 px-5 py-3.5 border-b border-wm-navy/40 bg-wm-navy/15">
                <svg class="text-wm-cyan shrink-0" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                <h3 class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Stock Overview</h3>
            </div>

            <div class="px-5 pt-5 pb-4">
                {{-- Numbers --}}
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <p class="text-[26px] font-bold text-white leading-none tabular-nums">
                            {{ number_format($qty) }}
                            <span class="text-sm font-normal text-slate-400 ml-0.5">units</span>
                        </p>
                        <p class="text-[11px] text-slate-500 mt-1">Current Stock</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[16px] font-bold text-amber-400 leading-none tabular-nums">
                            {{ number_format($reorder) }}
                            <span class="text-xs font-normal text-slate-400 ml-0.5">units</span>
                        </p>
                        <p class="text-[11px] text-slate-500 mt-1">Reorder Level</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden mb-2">
                    <div class="h-full rounded-full transition-all duration-500
                                @if($stockClass === 'critical') bg-red-400
                                @elseif($stockClass === 'low') bg-amber-400
                                @else bg-teal-400 @endif"
                         style="width: {{ $pct }}%;">
                    </div>
                </div>

                <div class="flex justify-between text-[11px] text-slate-500 mb-4">
                    <span>0</span>
                    <span>Reorder at {{ number_format($reorder) }}</span>
                </div>
            </div>

            <div class="divide-y divide-white/[0.04] border-t border-wm-navy/40">
                <div class="flex items-center justify-between px-5 py-3 gap-4">
                    <span class="text-[11px] font-semibold tracking-wider uppercase text-slate-500 shrink-0">Status</span>
                    @if ($stockClass === 'critical')
                        <span class="inline-flex items-center gap-1.5 bg-red-500/10 border border-red-500/20 text-red-400 text-[11px] font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $stockLabel }}
                        </span>
                    @elseif ($stockClass === 'low')
                        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[11px] font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $stockLabel }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-teal-500/10 border border-teal-500/20 text-teal-400 text-[11px] font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $stockLabel }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center justify-between px-5 py-3 gap-4">
                    <span class="text-[11px] font-semibold tracking-wider uppercase text-slate-500 shrink-0">vs Reorder Level</span>
                    <span class="text-[13px] font-semibold tabular-nums {{ ($qty - $reorder) >= 0 ? 'text-teal-400' : 'text-amber-400' }}">
                        {{ $qty >= $reorder ? '+' : '' }}{{ number_format($qty - $reorder) }} units
                    </span>
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-lg shadow-black/20 md:col-span-2">
            <div class="flex items-center gap-2.5 px-5 py-3.5 border-b border-wm-navy/40 bg-wm-navy/15">
                <svg class="text-wm-cyan shrink-0" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
                <h3 class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Description</h3>
            </div>
            <div class="px-5 py-4 text-[14px] leading-relaxed
                        {{ $pharmaceuticalItem->description ? 'text-slate-300' : 'text-slate-500 italic' }}">
                {{ $pharmaceuticalItem->description ?? 'No description provided.' }}
            </div>
        </div>

        {{-- Supplier --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-lg shadow-black/20 md:col-span-2">
            <div class="flex items-center gap-2.5 px-5 py-3.5 border-b border-wm-navy/40 bg-wm-navy/15">
                <svg class="text-wm-cyan shrink-0" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Supplier</h3>
            </div>

            @if ($pharmaceuticalItem->supplier)
                <div class="flex items-center gap-4 px-5 py-4">
                    <div class="w-11 h-11 bg-wm-navy/60 border border-wm-cyan/15 rounded-xl flex items-center justify-center text-wm-cyan shrink-0">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-[15px] text-white mb-1.5">{{ $pharmaceuticalItem->supplier->supplier_name }}</p>
                        <div class="flex items-center gap-1.5 text-[12px] text-slate-400 mb-1">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $pharmaceuticalItem->supplier->address }}
                        </div>
                        <div class="flex items-center gap-1.5 text-[12px] text-slate-400">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $pharmaceuticalItem->supplier->telephone }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-10 px-4">
                    <svg class="mx-auto mb-3 text-slate-600" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                    <p class="text-slate-500 text-sm italic">No supplier linked to this drug.</p>
                </div>
            @endif
        </div>

        {{-- Patient Medications --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-lg shadow-black/20 md:col-span-2">
            <div class="flex items-center gap-2.5 px-5 py-3.5 border-b border-wm-navy/40 bg-wm-navy/15">
                <svg class="text-wm-cyan shrink-0" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3 class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Patient Medications</h3>
                <span class="ml-1 bg-wm-navy/60 border border-wm-cyan/20 text-wm-cyan text-[11px] font-bold px-2 py-0.5 rounded-md">
                    {{ $pharmaceuticalItem->patientMedications->count() }}
                </span>
            </div>

            @if ($pharmaceuticalItem->patientMedications->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-wm-navy/40 bg-wm-navy/15">
                                <th class="px-5 py-3 text-left text-[10px] font-semibold tracking-widest uppercase text-slate-500">Patient No.</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold tracking-widest uppercase text-slate-500">Units / Day</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold tracking-widest uppercase text-slate-500">Start Date</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold tracking-widest uppercase text-slate-500">Finish Date</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold tracking-widest uppercase text-slate-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.04]">
                            @foreach ($pharmaceuticalItem->patientMedications as $med)
                            <tr class="hover:bg-wm-navy/15 transition-colors duration-150">
                                <td class="px-5 py-3">
                                    <span class="inline-block bg-wm-navy/60 border border-wm-cyan/15 text-wm-cyan text-[11px] font-bold px-2 py-0.5 rounded-md font-mono">
                                        {{ $med->patient_number }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-slate-300">
                                    {{ $med->units_per_day }}
                                    <span class="text-slate-500 text-[12px]">units/day</span>
                                </td>
                                <td class="px-5 py-3 text-slate-400 text-[13px]">
                                    {{ \Carbon\Carbon::parse($med->start_date)->format('M d, Y') }}
                                </td>
                                <td class="px-5 py-3 text-slate-400 text-[13px]">
                                    {{ $med->finish_date ? \Carbon\Carbon::parse($med->finish_date)->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-5 py-3">
                                    @if (!$med->finish_date || \Carbon\Carbon::parse($med->finish_date)->isFuture())
                                        <span class="inline-flex items-center gap-1.5 bg-teal-500/10 border border-teal-500/20 text-teal-400 text-[11px] font-bold px-2.5 py-1 rounded-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-white/5 border border-white/10 text-slate-500 text-[11px] font-bold px-2.5 py-1 rounded-md">
                                            Completed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10 px-4">
                    <svg class="mx-auto mb-3 text-slate-600" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-slate-500 text-sm italic">No patient medications recorded for this drug.</p>
                </div>
            @endif
        </div>

        {{-- Timestamps --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-lg shadow-black/20 md:col-span-2">
            <div class="flex items-center gap-6 px-5 py-4 flex-wrap">
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-slate-500 mb-1">Created</p>
                    <p class="text-[13px] font-medium text-slate-300">
                        {{ $pharmaceuticalItem->created_at->format('F d, Y h:i A') }}
                    </p>
                </div>
                <div class="w-px h-8 bg-wm-navy/60 hidden sm:block"></div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-slate-500 mb-1">Last Updated</p>
                    <p class="text-[13px] font-medium text-slate-300">
                        {{ $pharmaceuticalItem->updated_at->format('F d, Y h:i A') }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(e, name) {
        if (!confirm(`Delete "${name}"?\n\nThis action cannot be undone.`)) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush