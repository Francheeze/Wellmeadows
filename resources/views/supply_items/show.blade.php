@extends('layouts.app')

@section('title', 'Supply Item Details')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .row-hover:hover { background: rgba(204,236,238,.04); }
</style>
@endpush

@section('content')

@php
    $qty        = $supplyItem->quantity_in_stock;
    $reorder    = $supplyItem->reorder_level;
    $stockClass = $qty === 0 ? 'critical' : ($qty <= $reorder ? 'low' : 'ok');
    $stockLabel = $qty === 0 ? 'Out of Stock' : ($qty <= $reorder ? 'Low Stock' : 'In Stock');
    $pct        = $reorder > 0
                    ? min(100, round(($qty / ($reorder * 2)) * 100))
                    : ($qty > 0 ? 100 : 0);
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">

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

    {{-- ── Flash ── --}}
    @if (session('success'))
        <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30
                    text-emerald-400 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Reorder Warning Banner ── --}}
    @if ($supplyItem->needsReorder())
        <div class="flex items-center gap-3 bg-amber-500/8 border border-amber-500/25
                    text-amber-400 text-sm font-medium px-4 py-3.5 rounded-xl mb-6 anim-fade-up">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>
                <strong>Reorder Required —</strong>
                Current stock ({{ $supplyItem->quantity_in_stock }}) is at or below the reorder level ({{ $supplyItem->reorder_level }}).
            </span>
        </div>
    @endif

    {{-- ── Hero Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl p-6 mb-5
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up
                flex flex-wrap items-start justify-between gap-5">

        {{-- Left: icon + name + meta --}}
        <div class="flex items-start gap-4 flex-1 min-w-0">
            <div class="w-14 h-14 rounded-2xl bg-wm-cyan/10 border border-wm-cyan/20
                        flex items-center justify-center text-wm-cyan shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold tracking-widest uppercase text-wm-cyan-dim mb-1">Supply Item</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight mb-3 truncate">
                    {{ $supplyItem->item_name }}
                </h1>
                <div class="flex items-center gap-2.5 flex-wrap">
                    {{-- ID badge --}}
                    <span class="inline-flex items-center gap-1.5 bg-wm-cyan/10 border border-wm-cyan/20
                                 text-wm-cyan text-xs font-bold font-mono px-2.5 py-1 rounded-md">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                        {{ $supplyItem->item_number }}
                    </span>
                    {{-- Stock pill --}}
                    @if ($stockClass === 'ok')
                        <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/20
                                     text-emerald-400 text-xs font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ $stockLabel }}
                        </span>
                    @elseif ($stockClass === 'low')
                        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/20
                                     text-amber-400 text-xs font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ $stockLabel }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-red-500/10 border border-red-500/20
                                     text-red-400 text-xs font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ $stockLabel }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: action buttons --}}
        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <a href="{{ route('supply_items.edit', $supplyItem->item_number) }}"
               class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/30
                      text-amber-400 text-sm font-bold px-4 py-2.5 rounded-xl
                      hover:bg-amber-500/20 hover:border-amber-500/60 transition-all duration-200 no-underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('supply_items.destroy', $supplyItem->item_number) }}"
                  method="POST"
                  onsubmit="return confirmDelete(event, '{{ $supplyItem->item_name }}')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-red-500/8 border border-red-500/25
                               text-red-400 text-sm font-bold px-4 py-2.5 rounded-xl cursor-pointer
                               hover:bg-red-500/15 hover:border-red-500/50 transition-all duration-200 bg-transparent">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    {{-- ── 2-column grid ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- ── Item Details ── --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_4px_20px_rgba(0,0,0,.25)] anim-fade-up">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Item Details</h3>
            </div>
            <div class="divide-y divide-wm-navy/30">

                <div class="flex items-baseline justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[110px]">Item Number</span>
                    <span class="text-sm font-mono font-bold text-wm-cyan text-right">{{ $supplyItem->item_number }}</span>
                </div>

                <div class="flex items-baseline justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[110px]">Item Name</span>
                    <span class="text-sm font-medium text-white text-right">{{ $supplyItem->item_name }}</span>
                </div>

                <div class="flex items-baseline justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[110px]">Cost Per Unit</span>
                    <span class="text-base font-bold text-emerald-400 tabular-nums text-right">
                        ₱{{ number_format($supplyItem->cost_per_unit, 2) }}
                    </span>
                </div>

                <div class="flex items-baseline justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500 shrink-0 min-w-[110px]">Needs Reorder</span>
                    <span class="text-sm font-bold text-right {{ $supplyItem->needsReorder() ? 'text-amber-400' : 'text-emerald-400' }}">
                        {{ $supplyItem->needsReorder() ? 'Yes' : 'No' }}
                    </span>
                </div>

            </div>
        </div>

        {{-- ── Stock Overview ── --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_4px_20px_rgba(0,0,0,.25)] anim-fade-up" style="animation-delay:.05s">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Stock Overview</h3>
            </div>

            {{-- Progress section --}}
            <div class="px-5 py-5">
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <p class="text-2xl font-bold text-white leading-none tabular-nums">
                            {{ number_format($qty) }}
                            <span class="text-sm font-normal text-slate-400">units</span>
                        </p>
                        <p class="text-xs text-slate-500 mt-1">Current Stock</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-amber-400 leading-none tabular-nums">
                            {{ number_format($reorder) }}
                            <span class="text-sm font-normal text-slate-400">units</span>
                        </p>
                        <p class="text-xs text-slate-500 mt-1">Reorder Level</p>
                    </div>
                </div>

                {{-- Progress track --}}
                <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden mb-2">
                    <div class="h-full rounded-full transition-all duration-500
                                @if($stockClass === 'ok') bg-emerald-400
                                @elseif($stockClass === 'low') bg-amber-400
                                @else bg-red-400 @endif"
                         style="width: {{ $pct }}%;">
                    </div>
                </div>
                <div class="flex justify-between text-xs text-slate-600">
                    <span>0</span>
                    <span>Reorder at {{ number_format($reorder) }}</span>
                </div>
            </div>

            {{-- Status rows --}}
            <div class="border-t border-wm-navy/60 divide-y divide-wm-navy/30">
                <div class="flex items-center justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500">Status</span>
                    @if ($stockClass === 'ok')
                        <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-400
                                     text-xs font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ $stockLabel }}
                        </span>
                    @elseif ($stockClass === 'low')
                        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 text-amber-400
                                     text-xs font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ $stockLabel }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-red-500/10 text-red-400
                                     text-xs font-bold px-2.5 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ $stockLabel }}
                        </span>
                    @endif
                </div>
                <div class="flex items-baseline justify-between px-5 py-3.5 gap-4">
                    <span class="text-xs font-semibold tracking-wide uppercase text-slate-500">vs Reorder Level</span>
                    @php $diff = $qty - $reorder; @endphp
                    <span class="text-sm font-semibold tabular-nums {{ $diff >= 0 ? 'text-white' : 'text-slate-500 italic' }}">
                        {{ $diff >= 0 ? '+'.number_format($diff) : number_format($diff) }} units
                    </span>
                </div>
            </div>
        </div>

        {{-- ── Description (full width) ── --}}
        <div class="md:col-span-2 bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_4px_20px_rgba(0,0,0,.25)] anim-fade-up" style="animation-delay:.08s">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
                <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Description</h3>
            </div>
            <div class="px-5 py-5 text-sm leading-relaxed
                        {{ $supplyItem->description ? 'text-slate-300' : 'text-slate-500 italic' }}">
                {{ $supplyItem->description ?? 'No description provided.' }}
            </div>
        </div>

        {{-- ── Supplier (full width) ── --}}
        <div class="md:col-span-2 bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_4px_20px_rgba(0,0,0,.25)] anim-fade-up" style="animation-delay:.1s">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Supplier</h3>
            </div>

            @if ($supplyItem->supplier)
                <div class="flex items-center gap-4 px-5 py-5">
                    <div class="w-12 h-12 rounded-xl bg-wm-navy/60 border border-wm-navy
                                flex items-center justify-center text-wm-cyan-dim shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-white text-base mb-1.5">{{ $supplyItem->supplier->supplier_name }}</p>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 mb-1">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $supplyItem->supplier->address }}
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $supplyItem->supplier->telephone }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-10 px-4">
                    <p class="text-slate-500 text-sm italic">No supplier linked to this item.</p>
                </div>
            @endif
        </div>

        {{-- ── Requisitions (full width) ── --}}
        <div class="md:col-span-2 bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                    shadow-[0_4px_20px_rgba(0,0,0,.25)] anim-fade-up" style="animation-delay:.13s">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-wm-navy/60 bg-wm-navy/20">
                <svg class="w-4 h-4 text-wm-cyan shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Requisitions</h3>
                <span class="ml-1 bg-wm-cyan/10 text-wm-cyan text-xs font-bold px-2 py-0.5 rounded-md">
                    {{ $supplyItem->requisitions->count() }}
                </span>
            </div>

            @if ($supplyItem->requisitions->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-wm-navy/60 bg-wm-navy/10">
                                <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Requisition No.</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Date Ordered</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Ward No.</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Staff No.</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold tracking-widest uppercase text-slate-500">Qty Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-wm-navy/30">
                            @foreach ($supplyItem->requisitions as $req)
                            <tr class="row-hover transition-colors duration-150">
                                <td class="px-5 py-3.5">
                                    <span class="inline-block bg-wm-navy/60 border border-wm-navy
                                                 text-wm-cyan-dim text-xs font-bold font-mono
                                                 px-2.5 py-1 rounded-md">
                                        {{ $req->requisition_number }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-400 text-xs whitespace-nowrap">
                                    {{ $req->date_ordered ? \Carbon\Carbon::parse($req->date_ordered)->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $req->ward_number ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $req->staff_number ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 bg-wm-cyan/10 text-wm-cyan
                                                 text-xs font-bold px-2.5 py-1 rounded-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                        </svg>
                                        {{ $req->pivot->quantity_required }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 px-4">
                    <svg class="w-10 h-10 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-slate-500 text-sm">This item has not been included in any requisitions yet.</p>
                </div>
            @endif
        </div>

        {{-- ── Timestamps (full width) ── --}}
        <div class="md:col-span-2 bg-wm-card border border-wm-navy/60 rounded-2xl
                    shadow-[0_4px_20px_rgba(0,0,0,.25)] anim-fade-up" style="animation-delay:.16s">
            <div class="flex flex-wrap gap-6 px-5 py-4">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Created</p>
                    <p class="text-sm text-white font-medium">{{ $supplyItem->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Last Updated</p>
                    <p class="text-sm text-white font-medium">{{ $supplyItem->updated_at->format('F d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

    </div>{{-- end grid --}}

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