@extends('layouts.app')

@section('title', 'Pharmaceutical Items')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="min-h-screen bg-wm-dark font-sans">
<div class="max-w-[1300px] mx-auto px-6 py-10">

    {{-- Page Header --}}
    <div class="flex items-end justify-between mb-8 gap-4 flex-wrap">
        <div>
            <p class="text-[11px] font-semibold tracking-[0.2em] uppercase text-wm-cyan mb-1.5">
                Wellmeadows Hospital
            </p>
            <h1 class="text-3xl font-bold text-white tracking-tight">Pharmaceutical Items</h1>
        </div>
        <a href="{{ route('pharmaceutical_items.create') }}"
           class="inline-flex items-center gap-2 bg-wm-cyan hover:bg-wm-cyan-dim text-wm-dark text-sm font-bold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-black/30 whitespace-nowrap">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Drug
        </a>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flex items-center gap-3 bg-teal-400/10 border border-teal-400/25 text-teal-300 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="shrink-0" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center gap-3 bg-red-400/10 border border-red-400/25 text-red-400 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="shrink-0" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Total Drugs --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-wm-navy/70 flex items-center justify-center text-wm-cyan shrink-0">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">{{ $pharmaceuticalItems->total() }}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-1">Total Drugs</p>
            </div>
        </div>

        {{-- In Stock --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-teal-500/10 flex items-center justify-center text-teal-400 shrink-0">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">
                    {{ $pharmaceuticalItems->getCollection()->filter(fn($i) => $i->quantity_in_stock > $i->reorder_level)->count() }}
                </p>
                <p class="text-[11px] text-slate-400 font-medium mt-1">In Stock</p>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400 shrink-0">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">
                    {{ $pharmaceuticalItems->getCollection()->filter(fn($i) => $i->quantity_in_stock <= $i->reorder_level && $i->quantity_in_stock > 0)->count() }}
                </p>
                <p class="text-[11px] text-slate-400 font-medium mt-1">Low Stock</p>
            </div>
        </div>

        {{-- Out of Stock --}}
        <div class="bg-wm-card border border-wm-navy/50 rounded-2xl p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-400 shrink-0">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">
                    {{ $pharmaceuticalItems->getCollection()->filter(fn($i) => $i->quantity_in_stock === 0)->count() }}
                </p>
                <p class="text-[11px] text-slate-400 font-medium mt-1">Out of Stock</p>
            </div>
        </div>

    </div>

    {{-- Toolbar --}}
    <div class="flex items-center gap-3 mb-5 flex-wrap">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"
                 width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input
                type="text"
                id="pharmaSearch"
                placeholder="Search by drug name, dosage, or method…"
                onkeyup="filterTable()"
                class="w-full bg-wm-card border border-wm-navy/50 rounded-xl text-slate-200 text-sm placeholder-slate-500 pl-9 pr-4 py-2.5
                       focus:outline-none focus:border-wm-cyan/50 focus:ring-2 focus:ring-wm-cyan/10 transition-all duration-200"
            >
        </div>
        <div class="bg-wm-card border border-wm-navy/50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-400 whitespace-nowrap">
            Total: <span class="text-wm-cyan">{{ $pharmaceuticalItems->total() }}</span>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-wm-card border border-wm-navy/50 rounded-2xl overflow-hidden shadow-2xl shadow-black/40">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="pharmaTable">
                <thead>
                    <tr class="border-b border-wm-navy/40 bg-wm-navy/25">
                        <th class="px-4 py-3.5 text-left text-[11px] font-semibold tracking-widest uppercase text-slate-400">#</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-semibold tracking-widest uppercase text-slate-400">Drug Name</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-semibold tracking-widest uppercase text-slate-400">Dosage</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-semibold tracking-widest uppercase text-slate-400">Method</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-semibold tracking-widest uppercase text-slate-400">Stock</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-semibold tracking-widest uppercase text-slate-400">Reorder Lvl</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-semibold tracking-widest uppercase text-slate-400">Cost / Unit</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-semibold tracking-widest uppercase text-slate-400">Supplier</th>
                        <th class="px-4 py-3.5 text-right text-[11px] font-semibold tracking-widest uppercase text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse ($pharmaceuticalItems as $item)
                    <tr class="hover:bg-wm-navy/20 transition-colors duration-150">

                        {{-- Drug Number --}}
                        <td class="px-4 py-3.5">
                            <span class="inline-block bg-wm-cyan/15 text-wm-cyan text-[11px] font-bold px-2 py-0.5 rounded-md font-mono">
                                {{ $item->drug_number }}
                            </span>
                        </td>

                        {{-- Drug Name + Description --}}
                        <td class="px-4 py-3.5">
                            <p class="font-semibold text-white">{{ $item->drug_name }}</p>
                            @if ($item->description)
                                <p class="text-[12px] text-slate-400 mt-0.5 max-w-[220px] truncate"
                                   title="{{ $item->description }}">
                                    {{ $item->description }}
                                </p>
                            @endif
                        </td>

                        {{-- Dosage --}}
                        <td class="px-4 py-3.5 text-slate-400">{{ $item->dosage ?? '—' }}</td>

                        {{-- Method of Administration --}}
                        <td class="px-4 py-3.5 text-slate-400">{{ $item->method_of_administration ?? '—' }}</td>

                        {{-- Stock Badge --}}
                        <td class="px-4 py-3.5">
                            @if ($item->quantity_in_stock === 0)
                                <span class="inline-flex items-center gap-1.5 bg-red-500/10 text-red-400 text-[12px] font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    Out of Stock
                                </span>
                            @elseif ($item->quantity_in_stock <= $item->reorder_level)
                                <span class="inline-flex items-center gap-1.5 bg-amber-500/10 text-amber-400 text-[12px] font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $item->quantity_in_stock }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-teal-500/10 text-teal-400 text-[12px] font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $item->quantity_in_stock }}
                                </span>
                            @endif
                        </td>

                        {{-- Reorder Level --}}
                        <td class="px-4 py-3.5 text-slate-400 tabular-nums">{{ $item->reorder_level }}</td>

                        {{-- Cost Per Unit --}}
                        <td class="px-4 py-3.5">
                            <span class="font-semibold text-white tabular-nums">₱{{ number_format($item->cost_per_unit, 2) }}</span>
                        </td>

                        {{-- Supplier --}}
                        <td class="px-4 py-3.5">
                            @if ($item->supplier)
                                <span class="inline-flex items-center gap-1.5 bg-wm-navy/60 text-wm-cyan text-[12px] font-semibold px-2.5 py-1 rounded-md border border-wm-cyan/15">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                    </svg>
                                    {{ $item->supplier->supplier_name }}
                                </span>
                            @else
                                <span class="text-slate-500 text-[12px]">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3.5">
                            <div class="flex items-center justify-end gap-1.5">

                                <a href="{{ route('pharmaceutical_items.show', $item->drug_number) }}"
                                   title="View"
                                   class="w-8 h-8 inline-flex items-center justify-center rounded-lg border border-wm-cyan/25 text-wm-cyan
                                          hover:bg-wm-cyan/10 hover:border-wm-cyan/50 hover:-translate-y-0.5 transition-all duration-150">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <a href="{{ route('pharmaceutical_items.edit', $item->drug_number) }}"
                                   title="Edit"
                                   class="w-8 h-8 inline-flex items-center justify-center rounded-lg border border-amber-400/25 text-amber-400
                                          hover:bg-amber-400/10 hover:border-amber-400/50 hover:-translate-y-0.5 transition-all duration-150">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <form action="{{ route('pharmaceutical_items.destroy', $item->drug_number) }}"
                                      method="POST"
                                      onsubmit="return confirmDelete(event, '{{ $item->drug_name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete"
                                       class="w-8 h-8 inline-flex items-center justify-center rounded-lg border border-red-400/25 text-red-400 bg-transparent cursor-pointer
                                              hover:bg-red-400/10 hover:border-red-400/50 hover:-translate-y-0.5 transition-all duration-150">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="text-center py-16 px-4">
                                <svg class="mx-auto mb-4 text-slate-600" width="52" height="52" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                                <p class="text-slate-400 mb-4 text-[15px]">No pharmaceutical items found. Add your first drug!</p>
                                <a href="{{ route('pharmaceutical_items.create') }}"
                                   class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-all duration-200">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Drug
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($pharmaceuticalItems->hasPages())
        <div class="flex items-center justify-between px-5 py-4 border-t border-wm-navy/40 flex-wrap gap-3">
            <span class="text-[12px] text-slate-400">
                Showing {{ $pharmaceuticalItems->firstItem() }}–{{ $pharmaceuticalItems->lastItem() }}
                of {{ $pharmaceuticalItems->total() }} items
            </span>
            {{ $pharmaceuticalItems->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    function filterTable() {
        const query = document.getElementById('pharmaSearch').value.toLowerCase();
        document.querySelectorAll('#pharmaTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
        });
    }

    function confirmDelete(e, name) {
        if (!confirm(`Delete "${name}" from pharmaceutical items?\n\nThis action cannot be undone.`)) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush