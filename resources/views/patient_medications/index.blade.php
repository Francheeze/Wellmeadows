@extends('layouts.app')

@section('title', 'Patient Medications')

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
            <h1 class="text-4xl font-bold gradient-text leading-tight">Patient Medications</h1>
        </div>
        <a href="{{ route('patient_medications.create') }}"
           class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 whitespace-nowrap">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Medication
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
    <form method="GET" action="{{ route('patient_medications.index') }}"
          class="flex items-center gap-3 mb-5 flex-wrap">

        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#CCECEE]/50 pointer-events-none"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by patient number or drug name…"
                class="w-full bg-[#032d4f] border border-[#CCECEE]/20 text-[#f0f7f8] placeholder-[#CCECEE]/40 text-sm rounded-xl pl-9 pr-4 py-2.5 outline-none focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10 transition"
            >
        </div>

        {{-- Status filter --}}
        <select name="status" onchange="this.form.submit()"
                class="bg-[#032d4f] border border-[#CCECEE]/20 text-[#CCECEE]/70 text-sm rounded-xl px-4 py-2.5 outline-none focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10 transition cursor-pointer">
            <option value=""         {{ request('status') == ''         ? 'selected' : '' }}>All Statuses</option>
            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
            <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Finished</option>
        </select>

        {{-- Search button --}}
        <button type="submit"
                class="bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 whitespace-nowrap">
            Search
        </button>

        {{-- Clear --}}
        @if(request('search') || request('status'))
            <a href="{{ route('patient_medications.index') }}"
               class="text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium transition whitespace-nowrap">
                Clear
            </a>
        @endif

        {{-- Record count --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-[#CCECEE]/60 whitespace-nowrap ml-auto">
            Total: <span class="text-[#CCECEE]">{{ $medications->total() }}</span>
        </div>
    </form>

    {{-- Table Card --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl fade-in">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="medsTable">
                <thead>
                    <tr class="border-b border-[#CCECEE]/10 bg-[#03416E]/30">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Patient No.</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Drug</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Dosage</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Units / Day</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Finish Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#CCECEE]/5">
                    @forelse ($medications as $med)
                    @php
                        $active = $med->isActive();
                        $compositeParams = [
                            'patient_number' => $med->patient_number,
                            'drug_number'    => $med->drug_number,
                            'start_date'     => $med->start_date->toDateString(),
                        ];
                    @endphp
                    <tr class="hover:bg-[#03416E]/20 transition duration-100">

                        {{-- Patient Number --}}
                        <td class="px-6 py-4">
                            <span class="inline-block bg-[#CCECEE]/10 text-[#CCECEE] text-xs font-bold px-2.5 py-1 rounded-md font-mono border border-[#CCECEE]/20">
                                {{ $med->patient_number }}
                            </span>
                        </td>

                        {{-- Drug name + number --}}
                        <td class="px-6 py-4">
                            <div class="font-semibold text-[#f0f7f8]">
                                {{ $med->pharmaceuticalItem->drug_name ?? '—' }}
                            </div>
                            <div class="text-[#CCECEE]/40 text-xs font-mono mt-0.5">
                                {{ $med->drug_number }}
                            </div>
                        </td>

                        {{-- Dosage --}}
                        <td class="px-6 py-4">
                            @if($med->pharmaceuticalItem)
                                <span class="inline-block bg-sky-500/10 text-sky-300 border border-sky-500/20 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    {{ $med->pharmaceuticalItem->dosage }}
                                </span>
                            @else
                                <span class="text-[#CCECEE]/25 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Units per day --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-[#CCECEE]/70">
                                <svg class="w-3.5 h-3.5 shrink-0 text-[#CCECEE]/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span class="font-semibold text-[#f0f7f8]">{{ $med->units_per_day }}</span>
                                <span class="text-[#CCECEE]/40 text-xs">/ day</span>
                            </div>
                        </td>

                        {{-- Start date --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-[#CCECEE]/60 text-sm">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $med->start_date->format('d M Y') }}
                            </div>
                        </td>

                        {{-- Finish date --}}
                        <td class="px-6 py-4">
                            @if ($med->finish_date)
                                <div class="flex items-center gap-1.5 text-[#CCECEE]/60 text-sm">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $med->finish_date->format('d M Y') }}
                                </div>
                            @else
                                <span class="text-[#CCECEE]/25 text-sm">Ongoing</span>
                            @endif
                        </td>

                        {{-- Status badge --}}
                        <td class="px-6 py-4">
                            @if ($active)
                                <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-[#CCECEE]/5 border border-[#CCECEE]/15 text-[#CCECEE]/40 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#CCECEE]/30"></span>
                                    Finished
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">

                                {{-- View --}}
                                <a href="{{ route('patient_medications.show', $compositeParams) }}"
                                   title="View"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-[#CCECEE]/20 text-[#CCECEE]/60 hover:bg-[#CCECEE]/10 hover:border-[#CCECEE]/50 hover:text-[#CCECEE] transition hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('patient_medications.edit', $compositeParams) }}"
                                   title="Edit"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-amber-400/20 text-amber-400/60 hover:bg-amber-400/10 hover:border-amber-400/50 hover:text-amber-400 transition hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('patient_medications.destroy') }}"
                                      method="POST"
                                      onsubmit="return confirmDelete(event, '{{ $med->patient_number }}', '{{ addslashes($med->pharmaceuticalItem->drug_name ?? $med->drug_number) }}')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="patient_number" value="{{ $med->patient_number }}">
                                    <input type="hidden" name="drug_number"    value="{{ $med->drug_number }}">
                                    <input type="hidden" name="start_date"     value="{{ $med->start_date->toDateString() }}">
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
                        <td colspan="8" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 mx-auto text-[#CCECEE]/15 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-[#CCECEE]/40 text-sm mb-4">
                                @if(request('search') || request('status'))
                                    No records match your search.
                                    <a href="{{ route('patient_medications.index') }}"
                                       class="text-[#CCECEE]/70 underline hover:text-[#CCECEE] transition">Clear filters</a>
                                @else
                                    No medication records yet. Add the first one!
                                @endif
                            </p>
                            @if(!request('search') && !request('status'))
                                <a href="{{ route('patient_medications.create') }}"
                                   class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Medication
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($medications->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-[#CCECEE]/10 flex-wrap gap-3">
            <p class="text-xs text-[#CCECEE]/40">
                Showing <span class="font-semibold text-[#CCECEE]/70">{{ $medications->firstItem() }}</span>
                to <span class="font-semibold text-[#CCECEE]/70">{{ $medications->lastItem() }}</span>
                of <span class="font-semibold text-[#CCECEE]/70">{{ $medications->total() }}</span> records
            </p>
            {{ $medications->links() }}
        </div>
        @endif
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(e, patientNumber, drugName) {
        if (!confirm(`Remove ${drugName} from patient ${patientNumber}?\n\nThis action cannot be undone.`)) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush