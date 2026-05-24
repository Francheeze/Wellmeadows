@extends('layouts.app')

@section('title', 'In-Patients')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
    .row-hover:hover { background: rgba(204,236,238,.04); }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- ── Stats Row ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 anim-fade-up">

        {{-- Total Records --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">{{ number_format($inPatients->total()) }}</p>
                <p class="text-xs text-slate-400 font-medium mt-1">Total Records</p>
            </div>
        </div>

        {{-- Currently Admitted --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">{{ number_format($currentlyAdmitted) }}</p>
                <p class="text-xs text-slate-400 font-medium mt-1">Currently Admitted</p>
            </div>
        </div>

        {{-- Discharged (page) --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">
                    {{ $inPatients->getCollection()->filter(fn($r) => $r->actual_leave !== null)->count() }}
                </p>
                <p class="text-xs text-slate-400 font-medium mt-1">Discharged <span class="text-slate-600">(this page)</span></p>
            </div>
        </div>

        {{-- Avg Expected Stay (page) --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                @php
                    $avgStay = $inPatients->getCollection()->avg('expected_stay');
                @endphp
                <p class="text-2xl font-bold text-white leading-none">
                    {{ $avgStay ? number_format($avgStay, 1) : '—' }}
                </p>
                <p class="text-xs text-slate-400 font-medium mt-1">Avg. Expected Stay <span class="text-slate-600">(days)</span></p>
            </div>
        </div>

    </div>

    {{-- ── Search & Filter Toolbar ── --}}
    <form method="GET" action="{{ route('in_patients.index') }}"
          class="flex flex-wrap items-center gap-3 mb-4 anim-fade-up"
          style="animation-delay:.05s">

        {{-- Search --}}
        <div class="relative flex-1 min-w-52">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 pointer-events-none"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by patient name, ward, or bed…"
                class="w-full bg-wm-card border border-wm-navy/60 rounded-xl text-white text-sm
                       placeholder-slate-500 pl-9 pr-4 py-2.5
                       focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                       transition-all duration-200"
            >
        </div>

        {{-- Status filter --}}
        <select name="status"
                onchange="this.form.submit()"
                class="bg-wm-card border border-wm-navy/60 rounded-xl text-sm text-white
                       px-3 py-2.5 focus:outline-none focus:border-wm-cyan/60
                       focus:ring-2 focus:ring-wm-cyan/15 transition-all duration-200
                       cursor-pointer appearance-none pr-8"
                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right .75rem center;">
            <option value=""         {{ !request('status') ? 'selected' : '' }}>All Records</option>
            <option value="admitted" {{ request('status') === 'admitted'  ? 'selected' : '' }}>Currently Admitted</option>
            <option value="discharged" {{ request('status') === 'discharged' ? 'selected' : '' }}>Discharged</option>
        </select>

        {{-- Search button --}}
        <button type="submit"
                class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                       px-4 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors duration-200 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            Search
        </button>

        {{-- Clear --}}
        @if (request()->hasAny(['search', 'status']))
            <a href="{{ route('in_patients.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400
                      hover:text-white transition-colors duration-200 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear
            </a>
        @endif

        {{-- Total badge + Add button --}}
        <div class="ml-auto flex items-center gap-3 shrink-0">
            <span class="bg-wm-card border border-wm-navy/60 rounded-xl px-4 py-2.5
                         text-xs font-semibold text-slate-400">
                Total: <span class="text-wm-cyan">{{ number_format($inPatients->total()) }}</span>
            </span>
            <a href="{{ route('in_patients.create') }}"
               class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                      px-5 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Admit Patient
            </a>
        </div>

    </form>

    {{-- ── Table Card ── --}}
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden
                shadow-[0_8px_40px_rgba(0,0,0,.4)] anim-fade-up" style="animation-delay:.08s">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-wm-navy/60 bg-wm-navy/20">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400 whitespace-nowrap">Appt #</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Patient</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Ward</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Bed</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400 whitespace-nowrap">Date Placed</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400 whitespace-nowrap">Expected Stay</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400 whitespace-nowrap">Expected Leave</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Status</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold tracking-widest uppercase text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-wm-navy/30">
                    @forelse ($inPatients as $record)
                    <tr class="row-hover transition-colors duration-150">

                        {{-- Appointment Number --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold
                                         font-mono px-2.5 py-1 rounded-md">
                                {{ $record->appointment_number }}
                            </span>
                        </td>

                        {{-- Patient --}}
                        <td class="px-5 py-4">
                            @if ($record->patient)
                                <p class="font-semibold text-white leading-snug">
                                    {{ $record->patient->last_name }}, {{ $record->patient->first_name }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5 font-mono">
                                    {{ $record->patient_number }}
                                </p>
                            @else
                                <span class="text-slate-600 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Ward --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 bg-wm-navy/60 border border-wm-navy
                                         text-wm-cyan-dim text-xs font-semibold px-2.5 py-1 rounded-md">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Ward {{ $record->ward_number }}
                            </span>
                        </td>

                        {{-- Bed --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-block bg-wm-navy/40 text-slate-300
                                         text-xs font-semibold px-2.5 py-1 rounded-md">
                                Bed {{ $record->bed_number }}
                            </span>
                        </td>

                        {{-- Date Placed --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-sm text-white">
                                {{ $record->date_placed->format('M d, Y') }}
                            </span>
                        </td>

                        {{-- Expected Stay --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-sm text-slate-300 tabular-nums">
                                {{ $record->expected_stay }}
                                <span class="text-slate-500 text-xs">day{{ $record->expected_stay !== 1 ? 's' : '' }}</span>
                            </span>
                        </td>

                        {{-- Expected Leave --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($record->date_leave)
                                <span class="text-sm text-slate-300">
                                    {{ $record->date_leave->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-slate-600 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($record->isCurrentlyAdmitted())
                                <span class="inline-flex items-center gap-1.5 bg-sky-500/10 text-sky-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                                    Admitted
                                </span>
                            @else
                                <div>
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-400
                                                 text-xs font-semibold px-2.5 py-1 rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        Discharged
                                    </span>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ $record->actual_leave->format('M d, Y') }}
                                    </p>
                                </div>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1.5">

                                {{-- View --}}
                                <a href="{{ route('in_patients.show', $record->appointment_number) }}"
                                   title="View record"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                          border-wm-cyan/25 text-wm-cyan hover:bg-wm-cyan/10
                                          hover:border-wm-cyan transition-all duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('in_patients.edit', $record->appointment_number) }}"
                                   title="Edit record"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                          border-amber-500/25 text-amber-400 hover:bg-amber-500/10
                                          hover:border-amber-500/60 transition-all duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Quick Discharge (only shown when still admitted) --}}
                                @if ($record->isCurrentlyAdmitted())
                                    <form action="{{ route('in_patients.discharge', $record->appointment_number) }}"
                                          method="POST"
                                          onsubmit="return confirm('Discharge this patient today?\n\nThis sets the actual leave date to today and cannot be undone.')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                title="Discharge patient"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                                       border-emerald-500/25 text-emerald-400 hover:bg-emerald-500/10
                                                       hover:border-emerald-500/60 transition-all duration-150
                                                       cursor-pointer bg-transparent">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    {{-- Spacer so actions column stays aligned --}}
                                    <div class="w-8"></div>
                                @endif

                                {{-- Delete --}}
                                <form action="{{ route('in_patients.destroy', $record->appointment_number) }}"
                                      method="POST"
                                      onsubmit="return confirmDelete(event, '{{ addslashes($record->patient->full_name ?? $record->patient_number) }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            title="Delete record"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                                   border-red-500/25 text-red-400 hover:bg-red-500/10
                                                   hover:border-red-500/60 transition-all duration-150
                                                   cursor-pointer bg-transparent">
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
                        <td colspan="9">
                            <div class="text-center py-16 px-4">
                                <svg class="w-14 h-14 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                @if (request()->hasAny(['search', 'status']))
                                    <p class="text-slate-500 text-sm mb-2">No records match your search criteria.</p>
                                    <a href="{{ route('in_patients.index') }}"
                                       class="text-wm-cyan text-sm hover:text-wm-cyan-dim transition-colors">
                                        Clear filters
                                    </a>
                                @else
                                    <p class="text-slate-500 text-sm mb-4">No in-patient records yet.</p>
                                    <a href="{{ route('in_patients.create') }}"
                                       class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm
                                              font-bold px-5 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Admit First Patient
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        @if ($inPatients->hasPages())
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-t border-wm-navy/60">
            <span class="text-xs text-slate-500">
                Showing {{ $inPatients->firstItem() }}–{{ $inPatients->lastItem() }}
                of {{ number_format($inPatients->total()) }} records
            </span>
            <div class="[&_.pagination]:flex [&_.pagination]:gap-1
                        [&_.page-link]:flex [&_.page-link]:items-center [&_.page-link]:justify-center
                        [&_.page-link]:min-w-[2rem] [&_.page-link]:h-8 [&_.page-link]:px-2.5
                        [&_.page-link]:rounded-lg [&_.page-link]:text-xs [&_.page-link]:font-medium
                        [&_.page-link]:border [&_.page-link]:border-wm-navy/60
                        [&_.page-link]:text-slate-400 [&_.page-link]:bg-transparent [&_.page-link]:no-underline
                        [&_.page-link]:transition-all
                        [&_.page-link:hover]:bg-wm-cyan/10 [&_.page-link:hover]:border-wm-cyan/40 [&_.page-link:hover]:text-wm-cyan
                        [&_.active_.page-link]:bg-wm-cyan [&_.active_.page-link]:border-wm-cyan [&_.active_.page-link]:text-wm-dark [&_.active_.page-link]:font-bold
                        [&_.disabled_.page-link]:opacity-30 [&_.disabled_.page-link]:pointer-events-none">
                {{ $inPatients->appends(request()->query())->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(e, name) {
        if (!confirm(`Delete in-patient record for "${name}"?\n\nThis action cannot be undone.`)) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush