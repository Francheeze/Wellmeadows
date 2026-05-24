@extends('layouts.app')

@section('title', 'Exam Results')

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
            <h1 class="text-4xl font-bold gradient-text leading-tight">Exam Results</h1>
        </div>
        <a href="{{ route('exam_results.create') }}"
           class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 whitespace-nowrap">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Record Result
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

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-7">

        {{-- Total --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#CCECEE]/10 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#f0f7f8] leading-none">{{ $results->total() }}</p>
                <p class="text-xs text-[#CCECEE]/50 font-medium mt-1">Total Results</p>
            </div>
        </div>

        {{-- Out-patient --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-sky-500/15 border border-sky-500/20 flex items-center justify-center text-sky-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#f0f7f8] leading-none">{{ $outPatientCount }}</p>
                <p class="text-xs text-[#CCECEE]/50 font-medium mt-1">Out-patient</p>
            </div>
        </div>

        {{-- Waiting List --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-violet-500/15 border border-violet-500/20 flex items-center justify-center text-violet-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#f0f7f8] leading-none">{{ $waitingListCount }}</p>
                <p class="text-xs text-[#CCECEE]/50 font-medium mt-1">Waiting List</p>
            </div>
        </div>

    </div>

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('exam_results.index') }}"
          class="flex items-center gap-3 mb-5 flex-wrap">

        {{-- Search --}}
        <div class="relative flex-1 min-w-[220px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#CCECEE]/50 pointer-events-none"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by patient or appointment…"
                class="w-full bg-[#032d4f] border border-[#CCECEE]/20 text-[#f0f7f8] placeholder-[#CCECEE]/40 text-sm rounded-xl pl-9 pr-4 py-2.5 outline-none focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10 transition"
            >
        </div>

        {{-- Result filter --}}
        <select name="result" onchange="this.form.submit()"
                class="bg-[#032d4f] border border-[#CCECEE]/20 text-[#CCECEE]/70 text-sm rounded-xl px-4 py-2.5 outline-none focus:border-[#CCECEE]/60 transition cursor-pointer">
            <option value=""            {{ request('result') == ''            ? 'selected' : '' }}>All Results</option>
            <option value="Out-patient" {{ request('result') == 'Out-patient' ? 'selected' : '' }}>Out-patient</option>
            <option value="WaitingList" {{ request('result') == 'WaitingList' ? 'selected' : '' }}>Waiting List</option>
        </select>

        {{-- Search button --}}
        <button type="submit"
                class="bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 whitespace-nowrap">
            Search
        </button>

        {{-- Clear --}}
        @if(request('search') || request('result'))
            <a href="{{ route('exam_results.index') }}"
               class="text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium transition whitespace-nowrap">
                Clear
            </a>
        @endif

        {{-- Count --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-[#CCECEE]/60 whitespace-nowrap ml-auto">
            Total: <span class="text-[#CCECEE]">{{ $results->total() }}</span>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl fade-in">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#CCECEE]/10 bg-[#03416E]/30">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Appt #</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Examined Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Result</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Downstream Record</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-[#CCECEE]/60 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#CCECEE]/5">
                    @forelse ($results as $examResult)
                    <tr class="hover:bg-[#03416E]/20 transition duration-100">

                        {{-- Appointment Number --}}
                        <td class="px-6 py-4">
                            <span class="inline-block bg-[#CCECEE]/10 text-[#CCECEE] text-xs font-bold px-2.5 py-1 rounded-md font-mono border border-[#CCECEE]/20">
                                {{ $examResult->appointment_number }}
                            </span>
                        </td>

                        {{-- Patient --}}
                        <td class="px-6 py-4">
                            <div class="font-semibold text-[#f0f7f8]">
                                {{ $examResult->appointment->patient->first_name ?? '—' }}
                                {{ $examResult->appointment->patient->last_name  ?? '' }}
                            </div>
                            <div class="text-[#CCECEE]/40 text-xs font-mono mt-0.5">
                                {{ $examResult->appointment->patient->patient_number ?? '—' }}
                            </div>
                        </td>

                        {{-- Examined Date --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-[#CCECEE]/70 text-sm">
                                <svg class="w-3.5 h-3.5 shrink-0 text-[#CCECEE]/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $examResult->examined_date->format('d M Y') }}
                            </div>
                        </td>

                        {{-- Result badge --}}
                        <td class="px-6 py-4">
                            @if ($examResult->result === 'Out-patient')
                                <span class="inline-flex items-center gap-1.5 bg-sky-500/10 border border-sky-500/20 text-sky-300 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                    Out-patient
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-violet-500/10 border border-violet-500/20 text-violet-300 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                                    Waiting List
                                </span>
                            @endif
                        </td>

                        {{-- Downstream record link --}}
                        <td class="px-6 py-4">
                            @if ($examResult->result === 'Out-patient' && $examResult->appointment?->outPatient)
                                <a href="{{ route('out_patients.show', $examResult->appointment_number) }}"
                                   class="inline-flex items-center gap-1.5 text-sky-400/80 hover:text-sky-300 text-xs font-medium transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    View Out-patient
                                </a>
                            @elseif ($examResult->result === 'WaitingList' && $examResult->appointment?->inPatient)
                                <a href="{{ route('in_patients.show', $examResult->appointment_number) }}"
                                   class="inline-flex items-center gap-1.5 text-violet-400/80 hover:text-violet-300 text-xs font-medium transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    View In-patient
                                </a>
                            @elseif ($examResult->result === 'WaitingList')
                                <a href="{{ route('in_patients.create', ['appointment_number' => $examResult->appointment_number]) }}"
                                   class="inline-flex items-center gap-1.5 text-amber-400/80 hover:text-amber-300 text-xs font-medium transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                    </svg>
                                    Pending Admission
                                </a>
                            @else
                                <span class="text-[#CCECEE]/25 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">

                                {{-- View --}}
                                <a href="{{ route('exam_results.show', $examResult->appointment_number) }}"
                                   title="View"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-[#CCECEE]/20 text-[#CCECEE]/60 hover:bg-[#CCECEE]/10 hover:border-[#CCECEE]/50 hover:text-[#CCECEE] transition hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('exam_results.edit', $examResult->appointment_number) }}"
                                   title="Edit"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-amber-400/20 text-amber-400/60 hover:bg-amber-400/10 hover:border-amber-400/50 hover:text-amber-400 transition hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('exam_results.destroy', $examResult->appointment_number) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete exam result for appointment #{{ $examResult->appointment_number }}?\n\nThis cannot be undone.')">
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <p class="text-[#CCECEE]/40 text-sm mb-4">
                                @if(request('search') || request('result'))
                                    No exam results match your search.
                                    <a href="{{ route('exam_results.index') }}"
                                       class="text-[#CCECEE]/70 underline hover:text-[#CCECEE] transition">Clear filters</a>
                                @else
                                    No exam results recorded yet.
                                @endif
                            </p>
                            @if(!request('search') && !request('result'))
                                <a href="{{ route('exam_results.create') }}"
                                   class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Record First Result
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($results->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-[#CCECEE]/10 flex-wrap gap-3">
            <p class="text-xs text-[#CCECEE]/40">
                Showing <span class="font-semibold text-[#CCECEE]/70">{{ $results->firstItem() }}</span>
                to <span class="font-semibold text-[#CCECEE]/70">{{ $results->lastItem() }}</span>
                of <span class="font-semibold text-[#CCECEE]/70">{{ $results->total() }}</span> results
            </p>
            {{ $results->links() }}
        </div>
        @endif
    </div>

</div>
</div>
@endsection