@extends('layouts.app')

@section('title', 'Suppliers')

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
            <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">
                Wellmeadows Hospital
            </p>
            <h1 class="text-4xl font-bold gradient-text leading-tight">Suppliers</h1>
        </div>
        <a href="{{ route('suppliers.create') }}"
           class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 whitespace-nowrap">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Supplier
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

    {{-- Toolbar --}}
    <div class="flex items-center gap-3 mb-5 flex-wrap">
        <form method="GET" action="{{ route('suppliers.index') }}"
            class="relative flex-1 min-w-[200px] flex items-center gap-2">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#CCECEE]/50 pointer-events-none"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, address, or phone…"
                    class="w-full bg-[#032d4f] border border-[#CCECEE]/20 text-[#f0f7f8] placeholder-[#CCECEE]/40 text-sm rounded-xl pl-9 pr-4 py-2.5 outline-none focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10 transition"
                >
            </div>
            @if (request('search'))
                <a href="{{ route('suppliers.index') }}"
                class="text-xs text-[#CCECEE]/50 hover:text-[#CCECEE] transition whitespace-nowrap">
                    Clear
                </a>
            @endif
        </form>
        <div class="bg-[#032d4f] border border-[#CCECEE]/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-[#CCECEE]/60 whitespace-nowrap">
            Total: <span class="text-[#CCECEE]">{{ $suppliers->total() }}</span>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl fade-in">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="suppliersTable">
                <thead>
                    <tr class="border-b border-[#CCECEE]/10 bg-[#03416E]/30">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Supplier Name</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Telephone</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Fax Number</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#CCECEE]/5">
                    @forelse ($suppliers as $supplier)
                    <tr class="hover:bg-[#03416E]/20 transition duration-100">

                        {{-- ID --}}
                        <td class="px-6 py-4">
                            <span class="inline-block bg-[#CCECEE]/10 text-[#CCECEE] text-xs font-bold px-2.5 py-1 rounded-md font-mono border border-[#CCECEE]/20">
                                {{ $supplier->supplier_number }}
                            </span>
                        </td>

                        {{-- Name --}}
                        <td class="px-6 py-4 font-semibold text-[#f0f7f8]">
                            {{ $supplier->supplier_name }}
                        </td>

                        {{-- Address --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-[#CCECEE]/60 text-sm">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $supplier->address }}
                            </div>
                        </td>

                        {{-- Telephone --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-[#CCECEE]/60 text-sm">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $supplier->telephone }}
                            </div>
                        </td>

                        {{-- Fax --}}
                        <td class="px-6 py-4">
                            @if ($supplier->fax_number)
                                <div class="flex items-center gap-1.5 text-[#CCECEE]/60 text-sm">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    {{ $supplier->fax_number }}
                                </div>
                            @else
                                <span class="text-[#CCECEE]/25 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">

                                {{-- View --}}
                                <a href="{{ route('suppliers.show', $supplier->supplier_number) }}"
                                   title="View"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-[#CCECEE]/20 text-[#CCECEE]/60 hover:bg-[#CCECEE]/10 hover:border-[#CCECEE]/50 hover:text-[#CCECEE] transition -translate-y-0 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('suppliers.edit', $supplier->supplier_number) }}"
                                   title="Edit"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-amber-400/20 text-amber-400/60 hover:bg-amber-400/10 hover:border-amber-400/50 hover:text-amber-400 transition hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('suppliers.destroy', $supplier->supplier_number) }}"
                                      method="POST"
                                      onsubmit="return confirmDelete(event, '{{ $supplier->supplier_name }}')">
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
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 mx-auto text-[#CCECEE]/15 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-[#CCECEE]/40 text-sm mb-4">No suppliers found. Add your first one!</p>
                            <a href="{{ route('suppliers.create') }}"
                               class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Supplier
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($suppliers->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-[#CCECEE]/10 flex-wrap gap-3">
            <p class="text-xs text-[#CCECEE]/40">
                Showing <span class="font-semibold text-[#CCECEE]/70">{{ $suppliers->firstItem() }}</span>
                to <span class="font-semibold text-[#CCECEE]/70">{{ $suppliers->lastItem() }}</span>
                of <span class="font-semibold text-[#CCECEE]/70">{{ $suppliers->total() }}</span> suppliers
            </p>
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    function filterTable() {
        const query = document.getElementById('supplierSearch').value.toLowerCase();
        document.querySelectorAll('#suppliersTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
        });
    }

    function confirmDelete(e, name) {
        if (!confirm(`Delete supplier "${name}"?\n\nThis action cannot be undone.`)) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush