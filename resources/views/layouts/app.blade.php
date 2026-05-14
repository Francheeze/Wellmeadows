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

    @stack('styles')
</head>

<body class="bg-wm-cyan font-sans antialiased overflow-hidden h-screen">

@php
    use Illuminate\Support\Facades\Route;

    $route = Route::currentRouteName() ?? '';

    /*
    |----------------------------------------------------------
    | Determine which sidebar module is active
    |----------------------------------------------------------
    */
    $isApptTreatment = str_starts_with($route, 'suppliers.')
                    || str_starts_with($route, 'pharmaceutical_items.')
                    || str_starts_with($route, 'supply_items.')
                    || str_starts_with($route, 'patient_medications.')
                    || str_starts_with($route, 'requisitions.');

    $isPatientMgmt   = str_starts_with($route, 'patients.')
                    || str_starts_with($route, 'next_of_kin.')
                    || str_starts_with($route, 'doctor.');          // adjust to your teammates' routes

    $isStaffDept     = str_starts_with($route, 'staff.')
                    || str_starts_with($route, 'departments.')
                    || str_starts_with($route, 'qualifications.');   // adjust to your teammates' routes

    $isWardBed       = str_starts_with($route, 'wards.')
                    || str_starts_with($route, 'beds.');             // adjust to your teammates' routes

    $isDashboard     = $route === 'dashboard' || (!$isApptTreatment && !$isPatientMgmt && !$isStaffDept && !$isWardBed);

    /*
    |----------------------------------------------------------
    | Module title shown in the main header
    |----------------------------------------------------------
    */
    $moduleTitle = match(true) {
        $isApptTreatment => 'Appointment and Treatment',
        $isPatientMgmt   => 'Patient Management',
        $isStaffDept     => 'Staff and Department',
        $isWardBed       => 'Ward and Bed',
        default          => 'Dashboard',
    };

    /*
    |----------------------------------------------------------
    | Tab definitions per module
    | Each tab: ['label' => '...', 'route' => '...', 'matches' => '...']
    | 'matches' is a wildcard pattern for routeIs()
    |----------------------------------------------------------
    */
    $apptTabs = [
        ['label' => 'Supplier',            'route' => 'suppliers.index',           'matches' => 'suppliers.*'],
        ['label' => 'Pharmaceutical Item', 'route' => 'pharmaceutical_items.index', 'matches' => 'pharmaceutical_items.*'],
        ['label' => 'Supply Item',         'route' => 'supply_items.index',         'matches' => 'supply_items.*'],
        ['label' => 'Patient Medication',  'route' => 'patient_medications.index',  'matches' => 'patient_medications.*'],
        ['label' => 'Requisition',         'route' => 'requisitions.index',         'matches' => 'requisitions.*'],
    ];

    // Teammates fill in their own tab definitions when they integrate their modules
    $patientTabs = [
        // e.g. ['label' => 'Patients', 'route' => 'patients.index', 'matches' => 'patients.*'],
    ];
    $staffTabs = [];
    $wardTabs  = [];

    // Pick the correct tabs for the active module
    $activeTabs = match(true) {
        $isApptTreatment => $apptTabs,
        $isPatientMgmt   => $patientTabs,
        $isStaffDept     => $staffTabs,
        $isWardBed       => $wardTabs,
        default          => [],
    };
@endphp

<div class="flex h-screen w-screen overflow-hidden">

    {{-- ════════════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════════════ --}}
    <aside class="w-48 bg-wm-navy flex flex-col shrink-0 h-full">

        {{-- Logo / Seal --}}
        <div class="flex flex-col items-center pt-6 pb-5 px-4 border-b border-white/10">
            {{--
                Replace the SVG below with an <img> tag pointing to your actual
                hospital seal once you have the asset:
                <img src="{{ asset('images/seal.png') }}" alt="Wellmeadows" class="w-14 h-14">
            --}}
            <div class="w-14 h-14 rounded-full border-2 border-white/25 bg-white/10
                        flex items-center justify-center relative overflow-hidden">
                {{-- Outer ring decoration --}}
                <svg class="absolute inset-0 w-full h-full" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="28" cy="28" r="27" stroke="rgba(255,255,255,0.3)" stroke-width="1" stroke-dasharray="3 2"/>
                    <circle cx="28" cy="28" r="22" stroke="rgba(255,255,255,0.15)" stroke-width="0.75"/>
                </svg>
                {{-- Cross / plus symbol --}}
                <svg class="w-7 h-7 text-white relative z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                </svg>
            </div>
        </div>

        {{-- Navigation items --}}
        <nav class="flex-1 px-3 py-4 flex flex-col gap-1 overflow-y-auto">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isDashboard ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Dashboard
            </a>

            {{-- ── Module Nav Items ──
                 Your teammates: replace # with your module's landing route.
                 e.g. route('patients.index')
            --}}

            {{-- Patient Management (placeholder) --}}
            <a href="#"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isPatientMgmt ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Patient Management
            </a>

            {{-- Staff and Department (placeholder) --}}
            <a href="#"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isStaffDept ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Staff and Department
            </a>

            {{-- Ward and Bed (placeholder) --}}
            <a href="#"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isWardBed ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Ward and Bed
            </a>

            {{-- Appointment and Treatment (your module) --}}
            <a href="{{ route('supply_items.index') }}"
               class="block text-center text-xs font-semibold px-3 py-2.5 rounded-2xl
                      transition-all duration-200 leading-tight
                      {{ $isApptTreatment ? 'nav-pill-active' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                Appointment and treatment
            </a>

        </nav>

        {{-- Sidebar footer (optional: user info / logout) --}}
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

        {{-- ── Module Header: Title + Tab Bar ── --}}
        <div class="bg-wm-cyan px-8 pt-7 pb-0 shrink-0">

            {{-- Module title --}}
            <h1 class="text-2xl font-bold text-wm-navy tracking-tight mb-5">
                {{ $moduleTitle }}
            </h1>

            {{-- Tab navigation --}}
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
            @elseif ($isDashboard)
                {{-- No tabs on dashboard --}}
            @else
                {{-- Placeholder tab bar for modules not yet integrated --}}
                <div class="border-b border-wm-navy/20 pb-px">
                    <span class="px-5 py-2 text-sm font-semibold text-wm-navy/30 inline-block">
                        — tabs coming soon —
                    </span>
                </div>
            @endif
        </div>

        {{-- ── Page Content ── --}}
        <div class="flex-1 overflow-y-auto main-scroll bg-wm-dark">
            @yield('content')
        </div>

    </div>

</div>

{{-- Flash / toast messages (global) --}}
@if (session('success') || session('error'))
    <div id="globalToast"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3
                {{ session('success') ? 'bg-emerald-600' : 'bg-red-600' }}
                text-white text-sm font-semibold px-5 py-3.5 rounded-2xl
                shadow-[0_8px_30px_rgba(0,0,0,.3)]
                transition-all duration-300"
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
        // Auto-dismiss after 4 seconds
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

@stack('scripts')
</body>
</html>