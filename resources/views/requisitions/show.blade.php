@extends('layouts.app')

@section('title', 'Requisition #' . $requisition->requisition_number)

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
    @keyframes fadeSlideIn   { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .fade-in   { animation: fadeSlideIn .4s ease both; }
    .fade-in-2 { animation: fadeSlideIn .4s .08s ease both; }
    .fade-in-3 { animation: fadeSlideIn .4s .16s ease both; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#021829] text-[#f0f7f8]">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back Link --}}
    <a href="{{ route('requisitions.index') }}"
       class="inline-flex items-center gap-1.5 text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium mb-8 transition-colors no-underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Requisitions
    </a>

    {{-- Flash --}}
    @if (session('success'))
        <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium px-4 py-3 rounded-xl mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex items-start justify-between gap-4 mb-8 flex-wrap">
        <div>
            <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">Wellmeadows Hospital</p>
            <h1 class="text-3xl font-bold gradient-text leading-tight mb-2">
                Requisition #{{ $requisition->requisition_number }}
            </h1>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 bg-[#CCECEE]/10 border border-[#CCECEE]/20 text-[#CCECEE] text-xs font-semibold px-3 py-1 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($requisition->date_ordered)->format('M d, Y') }}
                </span>
                <span class="text-[#CCECEE]/30 text-xs">
                    {{ \Carbon\Carbon::parse($requisition->date_ordered)->diffForHumans() }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-2 pt-1 shrink-0">
            <a href="{{ route('requisitions.edit', $requisition->requisition_number) }}"
               class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 no-underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('requisitions.destroy', $requisition->requisition_number) }}"
                  method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete()"
                        class="inline-flex items-center gap-2 bg-transparent border border-red-400/25 text-red-400/70 hover:bg-red-400/10 hover:border-red-400/50 hover:text-red-400 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    {{-- ── Requisition Info Card ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-[#CCECEE]/10 bg-[#03416E]/30">
            <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/10 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Record Details</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Requisition Information</h2>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4">

            <div class="px-6 py-4 border-b border-[#CCECEE]/10 sm:border-b-0 sm:border-r border-[#CCECEE]/10">
                <p class="flex items-center gap-1.5 text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50 mb-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    Req. No.
                </p>
                <p class="font-mono font-bold text-[#CCECEE]">{{ $requisition->requisition_number }}</p>
            </div>

            <div class="px-6 py-4 border-b border-[#CCECEE]/10 sm:border-b-0 sm:border-r border-[#CCECEE]/10">
                <p class="flex items-center gap-1.5 text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50 mb-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Date Ordered
                </p>
                <p class="font-semibold text-[#f0f7f8]">{{ \Carbon\Carbon::parse($requisition->date_ordered)->format('M d, Y') }}</p>
            </div>

            <div class="px-6 py-4 border-r border-[#CCECEE]/10">
                <p class="flex items-center gap-1.5 text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50 mb-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Staff No.
                </p>
                <p class="font-mono font-bold text-[#CCECEE]/80">{{ $requisition->staff_number }}</p>
            </div>

            <div class="px-6 py-4">
                <p class="flex items-center gap-1.5 text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50 mb-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                    Ward No.
                </p>
                <p class="font-mono font-bold text-[#CCECEE]/80">{{ $requisition->ward_number }}</p>
            </div>

        </div>
    </div>

    {{-- ── Drug Items Card ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in-2">

        <div class="flex items-center justify-between gap-3 px-6 py-4 border-b border-[#CCECEE]/10 bg-emerald-500/5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Line Items</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Drug Items</h2>
                </div>
            </div>
            <span class="bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-3 py-1 rounded-full">
                {{ $requisition->requisitionDrugItems->count() }} item(s)
            </span>
        </div>

        @if ($requisition->requisitionDrugItems->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[#CCECEE]/10 bg-[#03416E]/10">
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">Drug No.</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">Drug Name</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">Dosage</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">Method</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">In Stock</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">Qty Required</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#CCECEE]/5">
                        @foreach ($requisition->requisitionDrugItems as $drug)
                        <tr class="hover:bg-[#03416E]/20 transition">
                            <td class="px-5 py-3.5 font-mono text-[#CCECEE] text-xs font-bold">{{ $drug->drug_number }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-[#f0f7f8]">{{ $drug->drug_name }}</p>
                                @if ($drug->description)
                                    <p class="text-[#CCECEE]/40 text-xs mt-0.5">{{ Str::limit($drug->description, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-[#CCECEE]/50 text-xs">{{ $drug->dosage ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-[#CCECEE]/50 text-xs">{{ $drug->method_of_administration ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                @php $s = $drug->quantity_in_stock <= 0 ? 'out' : ($drug->quantity_in_stock <= $drug->reorder_level ? 'low' : 'ok'); @endphp
                                @if ($s === 'ok')
                                    <span class="inline-flex items-center gap-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>{{ $drug->quantity_in_stock }}
                                    </span>
                                @elseif ($s === 'low')
                                    <span class="inline-flex items-center gap-1 bg-amber-400/10 border border-amber-400/20 text-amber-400 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>{{ $drug->quantity_in_stock }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-400/10 border border-red-400/20 text-red-400 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Out of Stock
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 bg-[#CCECEE]/10 border border-[#CCECEE]/20 text-[#CCECEE] text-xs font-bold px-2.5 py-1 rounded-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                    </svg>
                                    {{ $drug->pivot->quantity_required }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center gap-2 py-12 text-[#CCECEE]/30">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                <p class="text-sm">No drug items in this requisition.</p>
            </div>
        @endif
    </div>

    {{-- ── Supply Items Card ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl mb-5 fade-in-3">

        <div class="flex items-center justify-between gap-3 px-6 py-4 border-b border-[#CCECEE]/10 bg-violet-500/5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-violet-500/15 border border-violet-500/20 flex items-center justify-center text-violet-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Line Items</p>
                    <h2 class="text-sm font-bold text-[#f0f7f8]">Supply Items</h2>
                </div>
            </div>
            <span class="bg-violet-500/15 border border-violet-500/20 text-violet-400 text-xs font-bold px-3 py-1 rounded-full">
                {{ $requisition->requisitionSupplyItems->count() }} item(s)
            </span>
        </div>

        @if ($requisition->requisitionSupplyItems->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[#CCECEE]/10 bg-[#03416E]/10">
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">Item No.</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">Item Name</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">In Stock</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">Cost / Unit</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/50">Qty Required</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#CCECEE]/5">
                        @foreach ($requisition->requisitionSupplyItems as $item)
                        <tr class="hover:bg-[#03416E]/20 transition">
                            <td class="px-5 py-3.5 font-mono text-violet-400 text-xs font-bold">{{ $item->item_number }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-[#f0f7f8]">{{ $item->item_name }}</p>
                                @if ($item->description)
                                    <p class="text-[#CCECEE]/40 text-xs mt-0.5">{{ Str::limit($item->description, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @php $s = $item->quantity_in_stock <= 0 ? 'out' : ($item->quantity_in_stock <= $item->reorder_level ? 'low' : 'ok'); @endphp
                                @if ($s === 'ok')
                                    <span class="inline-flex items-center gap-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>{{ $item->quantity_in_stock }}
                                    </span>
                                @elseif ($s === 'low')
                                    <span class="inline-flex items-center gap-1 bg-amber-400/10 border border-amber-400/20 text-amber-400 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>{{ $item->quantity_in_stock }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-400/10 border border-red-400/20 text-red-400 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Out of Stock
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-[#f0f7f8]">₱{{ number_format($item->cost_per_unit, 2) }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 bg-violet-500/10 border border-violet-500/20 text-violet-400 text-xs font-bold px-2.5 py-1 rounded-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                    </svg>
                                    {{ $item->pivot->quantity_required }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center gap-2 py-12 text-[#CCECEE]/30">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                <p class="text-sm">No supply items in this requisition.</p>
            </div>
        @endif
    </div>

    {{-- ── Timestamps ── --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl px-6 py-4 flex gap-8 flex-wrap fade-in-3">
        <div>
            <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/40 mb-1">Created</p>
            <p class="text-sm text-[#f0f7f8] font-medium">{{ $requisition->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/40 mb-1">Last Updated</p>
            <p class="text-sm text-[#f0f7f8] font-medium">{{ $requisition->updated_at->format('F d, Y h:i A') }}</p>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Delete Requisition #{{ $requisition->requisition_number }}?\n\nThis will also remove all linked drug and supply items.\nThis action cannot be undone.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endpush