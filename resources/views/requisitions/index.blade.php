@extends('layouts.app')

@section('title', 'Requisitions')

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
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeSlideIn .35s ease both; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#021829] text-[#f0f7f8]">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page Header --}}
    <div class="flex items-end justify-between mb-10 gap-4 flex-wrap">
        <div>
            <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">Wellmeadows Hospital</p>
            <h1 class="text-4xl font-bold gradient-text leading-tight">Requisitions</h1>
        </div>
        <a href="{{ route('requisitions.create') }}"
           class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 whitespace-nowrap no-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Requisition
        </a>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="fade-in flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="fade-in flex items-center gap-3 bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#CCECEE]/10 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#f0f7f8]">{{ $requisitions->total() }}</p>
                <p class="text-xs text-[#CCECEE]/50 font-medium mt-0.5">Total Requisitions</p>
            </div>
        </div>

        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#03416E]/60 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE]/80 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#f0f7f8]">
                    {{ $requisitions->getCollection()->filter(fn($r) => \Carbon\Carbon::parse($r->date_ordered)->isToday())->count() }}
                </p>
                <p class="text-xs text-[#CCECEE]/50 font-medium mt-0.5">Today</p>
            </div>
        </div>

        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#f0f7f8]">
                    {{ $requisitions->getCollection()->sum(fn($r) => $r->requisitionDrugItems->count()) }}
                </p>
                <p class="text-xs text-[#CCECEE]/50 font-medium mt-0.5">Drug Line Items</p>
            </div>
        </div>

        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-violet-500/15 border border-violet-500/20 flex items-center justify-center text-violet-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#f0f7f8]">
                    {{ $requisitions->getCollection()->sum(fn($r) => $r->requisitionSupplyItems->count()) }}
                </p>
                <p class="text-xs text-[#CCECEE]/50 font-medium mt-0.5">Supply Line Items</p>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="flex items-center gap-3 mb-5 flex-wrap">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#CCECEE]/40 pointer-events-none"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input
                type="text"
                id="reqSearch"
                onkeyup="filterTable()"
                placeholder="Search by requisition no., staff, or ward…"
                class="w-full bg-[#032d4f] border border-[#CCECEE]/20 text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl pl-9 pr-4 py-2.5 outline-none focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10 transition"
            >
        </div>
        <div class="bg-[#032d4f] border border-[#CCECEE]/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-[#CCECEE]/60 whitespace-nowrap">
            Total: <span class="text-[#CCECEE]">{{ $requisitions->total() }}</span>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl fade-in">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="reqTable">
                <thead>
                    <tr class="border-b border-[#CCECEE]/10 bg-[#03416E]/30">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider whitespace-nowrap">Req. No.</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider whitespace-nowrap">Date Ordered</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider whitespace-nowrap">Staff No.</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider whitespace-nowrap">Ward No.</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider whitespace-nowrap">Drug Items</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider whitespace-nowrap">Supply Items</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#CCECEE]/5">
                    @forelse ($requisitions as $requisition)
                    <tr class="hover:bg-[#03416E]/20 transition duration-100">

                        <td class="px-5 py-4">
                            <span class="inline-block bg-[#CCECEE]/10 border border-[#CCECEE]/20 text-[#CCECEE] text-xs font-bold px-2.5 py-1 rounded-md font-mono">
                                {{ $requisition->requisition_number }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-semibold text-[#f0f7f8] text-sm leading-tight">
                                {{ \Carbon\Carbon::parse($requisition->date_ordered)->format('M d, Y') }}
                            </p>
                            <p class="text-[#CCECEE]/40 text-xs mt-0.5">
                                {{ \Carbon\Carbon::parse($requisition->date_ordered)->diffForHumans() }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <span class="inline-block bg-[#03416E]/60 border border-[#CCECEE]/15 text-[#CCECEE]/80 text-xs font-bold px-2.5 py-1 rounded-md font-mono">
                                {{ $requisition->staff_number }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            <span class="inline-block bg-[#03416E]/60 border border-[#CCECEE]/15 text-[#CCECEE]/80 text-xs font-bold px-2.5 py-1 rounded-md font-mono">
                                {{ $requisition->ward_number }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            @if ($requisition->requisitionDrugItems->count() > 0)
                                <span class="inline-flex items-center gap-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                    {{ $requisition->requisitionDrugItems->count() }} drug{{ $requisition->requisitionDrugItems->count() > 1 ? 's' : '' }}
                                </span>
                            @else
                                <span class="text-[#CCECEE]/25 text-xs">None</span>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            @if ($requisition->requisitionSupplyItems->count() > 0)
                                <span class="inline-flex items-center gap-1 bg-violet-500/10 border border-violet-500/20 text-violet-400 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                    </svg>
                                    {{ $requisition->requisitionSupplyItems->count() }} item{{ $requisition->requisitionSupplyItems->count() > 1 ? 's' : '' }}
                                </span>
                            @else
                                <span class="text-[#CCECEE]/25 text-xs">None</span>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('requisitions.show', $requisition->requisition_number) }}" title="View"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-[#CCECEE]/20 text-[#CCECEE]/50 hover:bg-[#CCECEE]/10 hover:border-[#CCECEE]/50 hover:text-[#CCECEE] transition hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('requisitions.edit', $requisition->requisition_number) }}" title="Edit"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-amber-400/20 text-amber-400/60 hover:bg-amber-400/10 hover:border-amber-400/50 hover:text-amber-400 transition hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('requisitions.destroy', $requisition->requisition_number) }}"
                                      method="POST"
                                      onsubmit="return confirmDelete(event, '{{ $requisition->requisition_number }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-red-400/20 text-red-400/60 hover:bg-red-400/10 hover:border-red-400/50 hover:text-red-400 transition hover:-translate-y-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 mx-auto text-[#CCECEE]/15 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-[#CCECEE]/40 text-sm mb-4">No requisitions found. Create your first one!</p>
                            <a href="{{ route('requisitions.create') }}"
                               class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 no-underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                New Requisition
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($requisitions->hasPages())
        <div class="flex items-center justify-between px-5 py-4 border-t border-[#CCECEE]/10 flex-wrap gap-3">
            <p class="text-xs text-[#CCECEE]/40">
                Showing <span class="font-semibold text-[#CCECEE]/70">{{ $requisitions->firstItem() }}</span>
                to <span class="font-semibold text-[#CCECEE]/70">{{ $requisitions->lastItem() }}</span>
                of <span class="font-semibold text-[#CCECEE]/70">{{ $requisitions->total() }}</span> requisitions
            </p>
            {{ $requisitions->links() }}
        </div>
        @endif
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    function filterTable() {
        const query = document.getElementById('reqSearch').value.toLowerCase();
        document.querySelectorAll('#reqTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
        });
    }
    function confirmDelete(e, reqNumber) {
        if (!confirm(`Delete Requisition #${reqNumber}?\n\nThis will also remove all linked drug and supply items.\nThis action cannot be undone.`)) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush