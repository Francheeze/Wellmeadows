@extends('layouts.app')

@section('title', 'Supply Items')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .row-hover:hover { background: rgba(204,236,238,.04); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-wm-dark font-sans text-white">
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

    {{-- ── Page Header ── --}}
    <div class="flex flex-wrap items-end justify-between gap-4 mb-8 anim-fade-up">
        <div>
            <p class="text-xs font-semibold tracking-widest uppercase text-wm-cyan-dim mb-1">
                Wellmeadows Hospital
            </p>
            <h1 class="text-3xl font-bold text-white tracking-tight">Supply Items</h1>
        </div>

        <a href="{{ route('supply_items.create') }}"
           class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                  px-5 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors duration-200 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Item
        </a>
    </div>

    {{-- ── Flash Messages ── --}}
    @if (session('success'))
        <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30
                    text-emerald-400 text-sm font-medium px-4 py-3 rounded-xl mb-6 anim-fade-up">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center gap-3 bg-red-500/10 border border-red-500/30
                    text-red-400 text-sm font-medium px-4 py-3 rounded-xl mb-6 anim-fade-up">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Stats Row ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 anim-fade-up">

        {{-- Total Items --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">{{ $supplyItems->total() }}</p>
                <p class="text-xs text-slate-400 font-medium mt-1">Total Items</p>
            </div>
        </div>

        {{-- In Stock --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">
                    {{ $inStockCount }}
                </p>
                <p class="text-xs text-slate-400 font-medium mt-1">In Stock</p>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">{{ $lowStockCount }}</p>
                <p class="text-xs text-slate-400 font-medium mt-1">Low Stock</p>
            </div>
        </div>

        {{-- Out of Stock --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">
                    {{ $outStockCount }}
                </p>
                <p class="text-xs text-slate-400 font-medium mt-1">Out of Stock</p>
            </div>
        </div>

    </div>

    {{-- ── Toolbar ── --}}
    <div class="flex flex-wrap items-center gap-3 mb-4 anim-fade-up">
        <div class="relative flex-1 min-w-48">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 pointer-events-none"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input
                type="text"
                id="supplySearch"
                onkeyup="filterTable()"
                placeholder="Search by item name or number…"
                class="w-full bg-wm-card border border-wm-navy/60 rounded-xl text-white text-sm
                       placeholder-slate-500 pl-9 pr-4 py-2.5
                       focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                       transition-all duration-200"
            >
        </div>
        <div class="bg-wm-card border border-wm-navy/60 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-400 shrink-0">
            Total: <span class="text-wm-cyan">{{ $supplyItems->total() }}</span>
        </div>
    </div>

    {{-- ── Table Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up">
        <div class="overflow-x-auto">
            <table id="supplyTable" class="w-full text-sm">
                <thead>
                    <tr class="border-b border-wm-navy/60 bg-wm-navy/20">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">#</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Item Name</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Stock</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Reorder Lvl</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Cost / Unit</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Supplier</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold tracking-widest uppercase text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-wm-navy/30">
                    @forelse ($supplyItems as $item)
                    <tr class="row-hover transition-colors duration-150">

                        {{-- Item Number --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold
                                         font-mono px-2.5 py-1 rounded-md tracking-wide">
                                {{ $item->item_number }}
                            </span>
                        </td>

                        {{-- Item Name + Description --}}
                        <td class="px-5 py-4">
                            <p class="font-semibold text-white leading-snug">{{ $item->item_name }}</p>
                            @if ($item->description)
                                <p class="text-xs text-slate-400 mt-0.5 max-w-xs truncate"
                                   title="{{ $item->description }}">
                                    {{ $item->description }}
                                </p>
                            @endif
                        </td>

                        {{-- Stock Badge --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($item->quantity_in_stock === 0)
                                <span class="inline-flex items-center gap-1.5 bg-red-500/10 text-red-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    Out of Stock
                                </span>
                            @elseif ($item->quantity_in_stock <= $item->reorder_level)
                                <span class="inline-flex items-center gap-1.5 bg-amber-500/10 text-amber-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $item->quantity_in_stock }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $item->quantity_in_stock }}
                                </span>
                            @endif
                        </td>

                        {{-- Reorder Level --}}
                        <td class="px-5 py-4 text-slate-400 tabular-nums whitespace-nowrap">
                            {{ $item->reorder_level }}
                        </td>

                        {{-- Cost Per Unit --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="font-semibold text-white tabular-nums">
                                ₱{{ number_format($item->cost_per_unit, 2) }}
                            </span>
                        </td>

                        {{-- Supplier --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($item->supplier)
                                <span class="inline-flex items-center gap-1.5 bg-wm-navy/60 text-wm-cyan-dim
                                             text-xs font-semibold px-2.5 py-1 rounded-md border border-wm-navy">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                    </svg>
                                    {{ $item->supplier->supplier_name }}
                                </span>
                            @else
                                <span class="text-slate-600 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1.5">

                                <a href="{{ route('supply_items.show', $item->item_number) }}"
                                   title="View"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                          border-wm-cyan/25 text-wm-cyan hover:bg-wm-cyan/10
                                          hover:border-wm-cyan transition-all duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <a href="{{ route('supply_items.edit', $item->item_number) }}"
                                   title="Edit"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                          border-amber-500/25 text-amber-400 hover:bg-amber-500/10
                                          hover:border-amber-500/60 transition-all duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <form action="{{ route('supply_items.destroy', $item->item_number) }}"
                                      method="POST"
                                      onsubmit="return confirmDelete(event, '{{ $item->item_name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                                   border-red-500/25 text-red-400 hover:bg-red-500/10
                                                   hover:border-red-500/60 transition-all duration-150 cursor-pointer bg-transparent">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="text-center py-16 px-4">
                                <svg class="w-14 h-14 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                                <p class="text-slate-500 text-sm mb-4">No supply items found. Add your first one!</p>
                                <a href="{{ route('supply_items.create') }}"
                                   class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm
                                          font-bold px-5 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Item
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        @if ($supplyItems->hasPages())
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-t border-wm-navy/60">
            <span class="text-xs text-slate-500">
                Showing {{ $supplyItems->firstItem() }}–{{ $supplyItems->lastItem() }}
                of {{ $supplyItems->total() }} items
            </span>
            <div class="[&_.pagination]:flex [&_.pagination]:gap-1
                        [&_.page-link]:flex [&_.page-link]:items-center [&_.page-link]:justify-center
                        [&_.page-link]:min-w-[2rem] [&_.page-link]:h-8 [&_.page-link]:px-2.5
                        [&_.page-link]:rounded-lg [&_.page-link]:text-xs [&_.page-link]:font-medium
                        [&_.page-link]:border [&_.page-link]:border-wm-navy/60
                        [&_.page-link]:text-slate-400 [&_.page-link]:bg-transparent
                        [&_.page-link]:no-underline [&_.page-link]:transition-all
                        [&_.page-link:hover]:bg-wm-cyan/10 [&_.page-link:hover]:border-wm-cyan/40 [&_.page-link:hover]:text-wm-cyan
                        [&_.active_.page-link]:bg-wm-cyan [&_.active_.page-link]:border-wm-cyan [&_.active_.page-link]:text-wm-dark [&_.active_.page-link]:font-bold
                        [&_.disabled_.page-link]:opacity-30 [&_.disabled_.page-link]:pointer-events-none">
                {{ $supplyItems->links() }}
            </div>
        </div>
        @endif
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    function filterTable() {
        const q = document.getElementById('supplySearch').value.toLowerCase();
        document.querySelectorAll('#supplyTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    function confirmDelete(e, name) {
        if (!confirm(`Delete "${name}" from supply items?\n\nThis action cannot be undone.`)) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush