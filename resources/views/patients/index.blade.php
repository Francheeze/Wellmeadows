@extends('layouts.app')

@section('title', 'Patients')

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

        {{-- Total Registered --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-wm-cyan/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-wm-cyan" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">{{ number_format($totalCount) }}</p>
                <p class="text-xs text-slate-400 font-medium mt-1">Total Registered</p>
            </div>
        </div>

        {{-- Male --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">
                    {{ $patients->getCollection()->where('sex', 'Male')->count() }}
                </p>
                <p class="text-xs text-slate-400 font-medium mt-1">Male <span class="text-slate-600">(this page)</span></p>
            </div>
        </div>

        {{-- Female --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">
                    {{ $patients->getCollection()->where('sex', 'Female')->count() }}
                </p>
                <p class="text-xs text-slate-400 font-medium mt-1">Female <span class="text-slate-600">(this page)</span></p>
            </div>
        </div>

        {{-- Filter results count --}}
        <div class="bg-wm-card border border-wm-navy/60 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none">{{ number_format($patients->total()) }}</p>
                <p class="text-xs text-slate-400 font-medium mt-1">
                    {{ request()->hasAny(['search','sex','marital_status']) ? 'Matching Filters' : 'All Patients' }}
                </p>
            </div>
        </div>

    </div>

    {{-- ── Search & Filter Toolbar ── --}}
    <form method="GET" action="{{ route('patients.index') }}"
          class="flex flex-wrap items-center gap-3 mb-4 anim-fade-up"
          style="animation-delay:.05s">

        {{-- Search input --}}
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
                placeholder="Search by name, patient #, or phone…"
                class="w-full bg-wm-card border border-wm-navy/60 rounded-xl text-white text-sm
                       placeholder-slate-500 pl-9 pr-4 py-2.5
                       focus:outline-none focus:border-wm-cyan/60 focus:ring-2 focus:ring-wm-cyan/15
                       transition-all duration-200"
            >
        </div>

        {{-- Sex filter --}}
        <select name="sex"
                onchange="this.form.submit()"
                class="bg-wm-card border border-wm-navy/60 rounded-xl text-sm text-white
                       px-3 py-2.5 focus:outline-none focus:border-wm-cyan/60
                       focus:ring-2 focus:ring-wm-cyan/15 transition-all duration-200
                       appearance-none cursor-pointer pr-8"
                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right .75rem center;">
            <option value="" {{ !request('sex') ? 'selected' : '' }}>All Sexes</option>
            <option value="Male"   {{ request('sex') === 'Male'   ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other"  {{ request('sex') === 'Other'  ? 'selected' : '' }}>Other</option>
        </select>

        {{-- Marital status filter --}}
        <select name="marital_status"
                onchange="this.form.submit()"
                class="bg-wm-card border border-wm-navy/60 rounded-xl text-sm text-white
                       px-3 py-2.5 focus:outline-none focus:border-wm-cyan/60
                       focus:ring-2 focus:ring-wm-cyan/15 transition-all duration-200
                       appearance-none cursor-pointer pr-8"
                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right .75rem center;">
            <option value="" {{ !request('marital_status') ? 'selected' : '' }}>All Statuses</option>
            <option value="Single"    {{ request('marital_status') === 'Single'    ? 'selected' : '' }}>Single</option>
            <option value="Married"   {{ request('marital_status') === 'Married'   ? 'selected' : '' }}>Married</option>
            <option value="Divorced"  {{ request('marital_status') === 'Divorced'  ? 'selected' : '' }}>Divorced</option>
            <option value="Widowed"   {{ request('marital_status') === 'Widowed'   ? 'selected' : '' }}>Widowed</option>
            <option value="Separated" {{ request('marital_status') === 'Separated' ? 'selected' : '' }}>Separated</option>
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

        {{-- Clear filters --}}
        @if (request()->hasAny(['search', 'sex', 'marital_status']))
            <a href="{{ route('patients.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400
                      hover:text-white transition-colors duration-200 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear
            </a>
        @endif

        {{-- Results badge + Add button --}}
        <div class="ml-auto flex items-center gap-3 shrink-0">
            <span class="bg-wm-card border border-wm-navy/60 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-400">
                Total: <span class="text-wm-cyan">{{ number_format($totalCount) }}</span>
            </span>
            <a href="{{ route('patients.create') }}"
               class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm font-bold
                      px-5 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Patient
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
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400 whitespace-nowrap">Patient #</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Full Name</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Age</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Sex</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Status</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Telephone</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400 whitespace-nowrap">Registered</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold tracking-widest uppercase text-slate-400">Referred By</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold tracking-widest uppercase text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-wm-navy/30">
                    @forelse ($patients as $patient)
                    <tr class="row-hover transition-colors duration-150">

                        {{-- Patient Number --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-block bg-wm-cyan/10 text-wm-cyan text-xs font-bold
                                         font-mono px-2.5 py-1 rounded-md tracking-wide">
                                {{ $patient->patient_number }}
                            </span>
                        </td>

                        {{-- Full Name --}}
                        <td class="px-5 py-4">
                            <p class="font-semibold text-white leading-snug">
                                {{ $patient->last_name }}, {{ $patient->first_name }}
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5 truncate max-w-[200px]"
                               title="{{ $patient->address }}">
                                {{ $patient->address }}
                            </p>
                        </td>

                        {{-- Age --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-white font-medium tabular-nums">{{ $patient->age }}</span>
                            <span class="text-slate-500 text-xs ml-0.5">yrs</span>
                        </td>

                        {{-- Sex badge --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($patient->sex === 'Male')
                                <span class="inline-flex items-center gap-1.5 bg-sky-500/10 text-sky-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    Male
                                </span>
                            @elseif ($patient->sex === 'Female')
                                <span class="inline-flex items-center gap-1.5 bg-rose-500/10 text-rose-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    Female
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-slate-500/10 text-slate-400
                                             text-xs font-semibold px-2.5 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    Other
                                </span>
                            @endif
                        </td>

                        {{-- Marital Status --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = match($patient->marital_status) {
                                    'Married'   => 'bg-emerald-500/10 text-emerald-400',
                                    'Single'    => 'bg-slate-500/10 text-slate-400',
                                    'Divorced'  => 'bg-amber-500/10 text-amber-400',
                                    'Widowed'   => 'bg-purple-500/10 text-purple-400',
                                    'Separated' => 'bg-orange-500/10 text-orange-400',
                                    default     => 'bg-slate-500/10 text-slate-400',
                                };
                            @endphp
                            <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-md {{ $statusClasses }}">
                                {{ $patient->marital_status }}
                            </span>
                        </td>

                        {{-- Telephone --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-slate-300 text-sm tabular-nums">
                                {{ $patient->telephone_number }}
                            </span>
                        </td>

                        {{-- Date Registered --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-slate-400 text-xs">
                                {{ $patient->date_registered->format('M d, Y') }}
                            </span>
                        </td>

                        {{-- Referred By --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($patient->localDoctor)
                                <span class="inline-flex items-center gap-1.5 bg-wm-navy/60 text-wm-cyan-dim
                                             text-xs font-semibold px-2.5 py-1 rounded-md border border-wm-navy">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $patient->localDoctor->full_name }}
                                </span>
                            @else
                                <span class="text-slate-600 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1.5">

                                {{-- View --}}
                                <a href="{{ route('patients.show', $patient->patient_number) }}"
                                   title="View patient profile"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                          border-wm-cyan/25 text-wm-cyan hover:bg-wm-cyan/10
                                          hover:border-wm-cyan transition-all duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('patients.edit', $patient->patient_number) }}"
                                   title="Edit patient"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border
                                          border-amber-500/25 text-amber-400 hover:bg-amber-500/10
                                          hover:border-amber-500/60 transition-all duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('patients.destroy', $patient->patient_number) }}"
                                      method="POST"
                                      onsubmit="return confirmDelete(event, '{{ addslashes($patient->full_name) }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            title="Delete patient"
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
                                <svg class="w-14 h-14 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                @if (request()->hasAny(['search', 'sex', 'marital_status']))
                                    <p class="text-slate-500 text-sm mb-1">No patients match your search criteria.</p>
                                    <a href="{{ route('patients.index') }}"
                                       class="text-wm-cyan text-sm hover:text-wm-cyan-dim transition-colors">
                                        Clear filters
                                    </a>
                                @else
                                    <p class="text-slate-500 text-sm mb-4">No patients registered yet.</p>
                                    <a href="{{ route('patients.create') }}"
                                       class="inline-flex items-center gap-2 bg-wm-cyan text-wm-dark text-sm
                                              font-bold px-5 py-2.5 rounded-xl hover:bg-wm-cyan-dim transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Register First Patient
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
        @if ($patients->hasPages())
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-t border-wm-navy/60">
            <span class="text-xs text-slate-500">
                Showing {{ $patients->firstItem() }}–{{ $patients->lastItem() }}
                of {{ number_format($patients->total()) }} patients
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
                {{ $patients->appends(request()->query())->links() }}
            </div>
        </div>
        @endif

    </div>{{-- end table card --}}

</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(e, name) {
        if (!confirm(`Delete patient "${name}"?\n\nThis will also remove their next-of-kin records.\nAppointments, admissions, and visit records will block deletion.\n\nThis action cannot be undone.`)) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush