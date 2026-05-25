<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Wellmeadows Hospital') — Wellmeadows</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Sidebar nav item active state — pill effect */
        .nav-pill-active {
            background: rgba(255,255,255,0.18);
            color: #ffffff;
        }
        .nav-pill-active:hover {
            background: rgba(255,255,255,0.22);
        }

        /* Tab underline active state */
        .tab-active {
            color: #03416E;
            border-bottom-color: #03416E;
        }
        .tab-inactive {
            color: #03416E;
            opacity: 0.45;
            border-bottom-color: transparent;
        }
        .tab-inactive:hover {
            opacity: 0.75;
            border-bottom-color: rgba(3,65,110,0.35);
        }

        /* Scrollbar styling for main content */
        .main-scroll::-webkit-scrollbar { width: 5px; }
        .main-scroll::-webkit-scrollbar-track { background: transparent; }
        .main-scroll::-webkit-scrollbar-thumb { background: rgba(3,65,110,0.2); border-radius: 999px; }
        .main-scroll::-webkit-scrollbar-thumb:hover { background: rgba(3,65,110,0.35); }
    </style>

    @yield('styles')
</head>

@php
    use Illuminate\Support\Facades\Route;

    $route = Route::currentRouteName() ?? '';

    /*
    |----------------------------------------------------------
    | MODULE DETECTION
    |
    | Each module claims its route prefixes here.
    |
    | Nested resources are automatically covered by their
    | parent prefix — e.g. patients.next_of_kins.* starts
    | with 'patients.' so no separate entry is needed.
    |
    | Custom named actions are also auto-covered — e.g.
    | 'appointments.record_result' starts with 'appointments.'
    | and 'in_patients.discharge' starts with 'in_patients.'
    |----------------------------------------------------------
    */
    $isApptTreatment = str_starts_with($route, 'suppliers.')
                    || str_starts_with($route, 'pharmaceutical_items.')
                    || str_starts_with($route, 'supply_items.')
                    || str_starts_with($route, 'patient_medications.')
                    || str_starts_with($route, 'requisitions.')
                    || str_starts_with($route, 'appointments.');   // also covers appointments.record_result

    $isPatientMgmt   = str_starts_with($route, 'patients.')      // also covers patients.next_of_kins.*
                    || str_starts_with($route, 'local_doctors.')
                    || str_starts_with($route, 'exam_results.')
                    || str_starts_with($route, 'in_patients.')    // also covers in_patients.discharge
                    || str_starts_with($route, 'out_patients.');

    $isStaffDept     = str_starts_with($route, 'staff.')
                    || str_starts_with($route, 'department.')
                    || str_starts_with($route, 'schedules.')
                    || str_starts_with($route, 'reports');

    $isSchedules     = str_starts_with($route, 'schedules.');

    // ── CHANGED: Ward and Bed detection ──
    $isWardBed = str_starts_with($route, 'wards.')
              || str_starts_with($route, 'beds.')
              || str_starts_with($route, 'staff-rota.');

    $isDashboard = $route === 'dashboard'
                || (!$isApptTreatment && !$isPatientMgmt && !$isStaffDept && !$isWardBed);

    /*
    |----------------------------------------------------------
    | MODULE TITLE
    |----------------------------------------------------------
    */
    $moduleTitle = $module ?? match(true) {
        $isApptTreatment => 'Appointment and Requisition',
        $isPatientMgmt   => 'Patient Management',
        $isStaffDept     => 'Staff and Department',
        $isSchedules     => 'Schedules',
        $isWardBed       => 'Ward and Bed',
        default          => 'Dashboard',
    };

    /*
    |----------------------------------------------------------
    | TAB DEFINITIONS
    |
    | label   — text shown on the tab
    | route   — named route for the link (standalone index only,
    |           never a nested resource index)
    | matches — routeIs() wildcard pattern; the tab stays
    |           highlighted for all CRUD pages of that entity
    |
    | IMPORTANT: Do NOT add nested resources as tabs.
    |   patients.next_of_kins has no standalone index — link
    |   to it from inside the patient show/detail page instead.
    |----------------------------------------------------------
    */

    // ── Appointment and Requisition ──────────────────────────
    $apptTabs = [
        ['label' => 'Supplier',            'route' => 'suppliers.index',            'matches' => 'suppliers.*'],
        ['label' => 'Pharmaceutical Item', 'route' => 'pharmaceutical_items.index', 'matches' => 'pharmaceutical_items.*'],
        ['label' => 'Supply Item',         'route' => 'supply_items.index',         'matches' => 'supply_items.*'],
        ['label' => 'Patient Medication',  'route' => 'patient_medications.index',  'matches' => 'patient_medications.*'],
        ['label' => 'Requisition',         'route' => 'requisitions.index',         'matches' => 'requisitions.*'],
        ['label' => 'Appointments',  'route' => 'appointments.index',  'matches' => 'appointments.*'],
    ];

    // ── Patient Management ────────────────────────────────
    // NOTE: next_of_kins is excluded — it is a nested resource
    // (patients.next_of_kins.*) with no standalone index.
    // Access next-of-kin from inside the patient show page.
    $patientTabs = [
        ['label' => 'Patients',      'route' => 'patients.index',      'matches' => 'patients.*'],
        ['label' => 'Local Doctors', 'route' => 'local_doctors.index', 'matches' => 'local_doctors.*'],
        ['label' => 'Exam Results',  'route' => 'exam_results.index',  'matches' => 'exam_results.*'],
        ['label' => 'In-Patients',   'route' => 'in_patients.index',   'matches' => 'in_patients.*'],
        ['label' => 'Out-Patients',  'route' => 'out_patients.index',  'matches' => 'out_patients.*'],
    ];
    $staffTabs = [
        ['label' => 'Departments', 'route' => 'department.index', 'matches' => 'department.*'],
        ['label' => 'All Staff',   'route' => 'staff.index',      'matches' => 'staff.*'],
        ['label' => 'Schedules',   'route' => 'schedules.index',  'matches' => 'schedules.*'],
        ['label' => 'Reports',     'route' => 'reports',          'matches' => 'reports'],
    ];

    // ── CHANGED: Ward tabs filled in ──
    $wardTabs = [
        ['label' => 'Ward',             'route' => 'wards.index',      'matches' => 'wards.*'],
        ['label' => 'Bed',              'route' => 'beds.index',       'matches' => 'beds.*'],
        ['label' => 'Staff Allocation', 'route' => 'staff-rota.index', 'matches' => 'staff-rota.*'],
    ];

    $activeTabs = match(true) {
        $isApptTreatment => $apptTabs,
        $isPatientMgmt   => $patientTabs,
        $isStaffDept     => $staffTabs,
        $isWardBed       => $wardTabs,
        default          => [],
    };
@endphp

<body class="bg-wm-cyan font-sans antialiased overflow-hidden h-screen">

<div class="flex h-screen w-screen overflow-hidden">

    {{-- ════════════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════════════ --}}
    <aside class="w-48 bg-wm-navy flex flex-col shrink-0 h-full">

        {{-- Logo / Seal --}}
        <div class="flex flex-col items-center pt-6 pb-5 px-4 border-b border-white/10">
            {{--
                Once you have the hospital seal asset, replace this div with:
                <img src="{{ asset('images/seal.png') }}" alt="Wellmeadows" class="w-14 h-14 rounded-full">
            --}}
            <div class="w-14 h-14 rounded-full border-2 border-white/25 bg-white/10
                        flex items-center justify-center relative overflow-hidden">
                <svg class="absolute inset-0 w-full h-full" viewBox="0 0 56 56" fill="none">
                    <circle cx="28" cy="28" r="27" stroke="rgba(255,255,255,0.3)" stroke-width="1" stroke-dasharray="3 2"/>
                    <circle cx="28" cy="28" r="22" stroke="rgba(255,255,255,0.15)" stroke-width="0.75"/>
                </svg>
                <svg class="w-7 h-7 text-white relative z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                </svg>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 flex flex-col gap-1 overflow-y-auto">

            <a href="{{ route('dashboard') }}"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isDashboard ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Dashboard
            </a>

            <a href="{{ route('patients.index') }}"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isPatientMgmt ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Patient Management
            </a>

            {{-- Staff and Department (placeholder) --}}
            <a href="{{ route('department.index') }}"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isStaffDept ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Staff and Department
            </a>

            {{-- CHANGED: href updated to wards.index --}}
            <a href="{{ route('wards.index') }}"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isWardBed ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Ward and Bed
            </a>

            <a href="{{ route('supply_items.index') }}"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isApptTreatment ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Appointment and Requisition
            </a>

        </nav>

        {{-- Sign out --}}
        <div class="border-t border-white/10 px-4 py-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-xs font-semibold text-white/50 hover:text-white/80
                               transition-colors duration-200 text-center py-1">
                    Sign out
                </button>
            </form>
        </div>

    </aside>

    {{-- ════════════════════════════════════════════════
         MAIN AREA
    ════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Module header: title + tab bar --}}
        <div class="bg-wm-cyan px-8 pt-7 pb-0 shrink-0">

            <div class="flex justify-between items-center">
                {{-- Module title --}}
                <h1 class="text-2xl font-bold text-wm-navy tracking-tight mb-5">
                    {{ $moduleTitle }}
                </h1>
            </div>

            @if (count($activeTabs) > 0)
                <nav class="flex items-end gap-0 border-b border-wm-navy/20" role="tablist">
                    @foreach ($activeTabs as $tab)
                        <a href="{{ route($tab['route']) }}"
                           role="tab"
                           class="px-5 py-2 text-sm font-semibold border-b-2 -mb-px
                                  whitespace-nowrap transition-all duration-150
                                  {{ request()->routeIs($tab['matches']) ? 'tab-active' : 'tab-inactive' }}">
                            {{ $tab['label'] }}
                        </a>
                    @endforeach
                </nav>
            @elseif (!$isDashboard)
                <div class="border-b border-wm-navy/20 pb-px">
                    <span class="px-5 py-2 text-sm font-semibold text-wm-navy/30 inline-block">
                        — tabs coming soon —
                    </span>
                </div>
            @endif
        </div>

        {{-- Page content — all blade files yield here --}}
        <div class="flex-1 overflow-y-auto main-scroll @yield('content-bg', 'bg-wm-dark')">
            @yield('content')
        </div>

    </div>

</div>

{{-- Global flash toast --}}
@if (session('success') || session('error'))
    <div id="globalToast"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3
                {{ session('success') ? 'bg-emerald-600' : 'bg-red-600' }}
                text-white text-sm font-semibold px-5 py-3.5 rounded-2xl
                shadow-[0_8px_30px_rgba(0,0,0,.3)]"
         style="animation: slideInToast .35s ease both;">
        @if (session('success'))
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        @else
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        @endif
        <button onclick="document.getElementById('globalToast').remove()"
                class="ml-2 text-white/70 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <style>
        @keyframes slideInToast {
            from { opacity:0; transform: translateY(12px); }
            to   { opacity:1; transform: translateY(0); }
        }
    </style>
    <script>
        setTimeout(() => {
            const t = document.getElementById('globalToast');
            if (t) {
                t.style.opacity = '0';
                t.style.transform = 'translateY(8px)';
                t.style.transition = 'opacity .3s, transform .3s';
                setTimeout(() => t?.remove(), 300);
            }
        }, 4000);
    </script>
@endif

@yield('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const searchResults = document.getElementById('search-results');

        searchInput.addEventListener('keyup', function() {
            const query = this.value;

            if (query.length > 2) {
                fetch(`{{ route('search') }}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        let hasResults = false;

                        // Process staff results
                        if (data.staff && data.staff.length > 0) {
                            hasResults = true;
                            data.staff.forEach(item => {
                                const a = document.createElement('a');
                                a.href = `{{ url('staff') }}/${item.staffNumber}`;
                                a.className = 'block px-4 py-2 text-gray-800 hover:bg-gray-200';
                                a.textContent = `${item.firstName} ${item.lastName}`;
                                searchResults.appendChild(a);
                            });
                        }

                        // Process department results
                        if (data.departments && data.departments.length > 0) {
                            hasResults = true;
                            data.departments.forEach(item => {
                                const a = document.createElement('a');
                                const departmentUrl = new URL('{{ route("staff.index") }}');
                                departmentUrl.searchParams.append('department', item.department);
                                a.href = departmentUrl.toString();
                                a.className = 'block px-4 py-2 text-gray-800 hover:bg-gray-200';
                                a.textContent = `${item.department} (Department)`;
                                searchResults.appendChild(a);
                            });
                        }

                        if (hasResults) {
                            searchResults.classList.remove('hidden');
                        } else {
                            searchResults.classList.add('hidden');
                        }
                    });
            } else {
                searchResults.classList.add('hidden');
            }
        });

        document.addEventListener('click', function(e) {
            if (!searchResults.contains(e.target) && e.target !== searchInput) {
                searchResults.classList.add('hidden');
            }
        });
    });
</script>
    @stack('scripts')
</body>
</html>