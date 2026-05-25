@extends('layouts.app')

@section('title', 'Dashboard')

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

    {{-- Header --}}
    <div class="mb-10 fade-in">
        <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE]/60 mb-1">Wellmeadows Hospital</p>
        <h1 class="text-4xl font-bold gradient-text leading-tight">Dashboard</h1>
    </div>

    {{-- Top Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl p-5 relative overflow-hidden fade-in">
            <div class="absolute top-0 right-0 w-20 h-20 rounded-full bg-[#CCECEE]/5 -translate-y-6 translate-x-6"></div>
            <div class="w-9 h-9 rounded-xl bg-blue-500/10 flex items-center justify-center mb-3">
                <i class="ti ti-users text-blue-400 text-lg"></i>
            </div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#CCECEE]/50 mb-1">Total patients</p>
            <p class="text-3xl font-bold text-[#f0f7f8]">{{ $totalPatients }}</p>
            <p class="text-xs text-[#CCECEE]/40 mt-1">Registered</p>
        </div>

        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl p-5 relative overflow-hidden fade-in">
            <div class="absolute top-0 right-0 w-20 h-20 rounded-full bg-[#CCECEE]/5 -translate-y-6 translate-x-6"></div>
            <div class="w-9 h-9 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-3">
                <i class="ti ti-bed text-emerald-400 text-lg"></i>
            </div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#CCECEE]/50 mb-1">Available beds</p>
            <p class="text-3xl font-bold text-[#f0f7f8]">{{ $availableBeds }}</p>
            <p class="text-xs text-[#CCECEE]/40 mt-1">Open now</p>
        </div>

        <div class="bg-[#032d4f] border border-red-500/20 rounded-2xl p-5 relative overflow-hidden fade-in">
            <div class="absolute top-0 right-0 w-20 h-20 rounded-full bg-red-500/5 -translate-y-6 translate-x-6"></div>
            <div class="w-9 h-9 rounded-xl bg-red-500/10 flex items-center justify-center mb-3">
                <i class="ti ti-bed-off text-red-400 text-lg"></i>
            </div>
            <p class="text-xs font-semibold uppercase tracking-widest text-red-400/60 mb-1">Occupied beds</p>
            <p class="text-3xl font-bold text-[#f0f7f8]">{{ $occupiedBeds }}</p>
            <p class="text-xs text-red-400/40 mt-1">In use</p>
        </div>

        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl p-5 relative overflow-hidden fade-in">
            <div class="absolute top-0 right-0 w-20 h-20 rounded-full bg-[#CCECEE]/5 -translate-y-6 translate-x-6"></div>
            <div class="w-9 h-9 rounded-xl bg-purple-500/10 flex items-center justify-center mb-3">
                <i class="ti ti-stethoscope text-purple-400 text-lg"></i>
            </div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#CCECEE]/50 mb-1">Total staff</p>
            <p class="text-3xl font-bold text-[#f0f7f8]">{{ $totalStaff }}</p>
            <p class="text-xs text-[#CCECEE]/40 mt-1">Active</p>
        </div>

        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl p-5 relative overflow-hidden fade-in">
            <div class="absolute top-0 right-0 w-20 h-20 rounded-full bg-[#CCECEE]/5 -translate-y-6 translate-x-6"></div>
            <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center mb-3">
                <i class="ti ti-building-hospital text-amber-400 text-lg"></i>
            </div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#CCECEE]/50 mb-1">Total wards</p>
            <p class="text-3xl font-bold text-[#f0f7f8]">{{ $totalWards }}</p>
            <p class="text-xs text-[#CCECEE]/40 mt-1">All active</p>
        </div>

    </div>

    {{-- Module Summary Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">

        {{-- Patient Management --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden fade-in">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#CCECEE]/10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                        <i class="ti ti-users text-blue-400 text-base"></i>
                    </div>
                    <span class="text-sm font-semibold text-[#f0f7f8]">Patient management</span>
                </div>
                <span class="text-xs bg-[#021829] text-[#CCECEE]/60 border border-[#CCECEE]/15 rounded-full px-3 py-1">
                    {{ $totalPatients }} patients
                </span>
            </div>
            <div class="divide-y divide-[#CCECEE]/5">
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-user-plus text-[#CCECEE]/40 text-base"></i> In-patients
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $inPatients }}</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-user-check text-[#CCECEE]/40 text-base"></i> Out-patients
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $outPatients }}</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-gender-male text-[#CCECEE]/40 text-base"></i> Male
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $malePatients }}</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-gender-female text-[#CCECEE]/40 text-base"></i> Female
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $femalePatients }}</span>
                </div>
            </div>
        </div>

        {{-- Ward and Bed --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden fade-in">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#CCECEE]/10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center">
                        <i class="ti ti-building-hospital text-amber-400 text-base"></i>
                    </div>
                    <span class="text-sm font-semibold text-[#f0f7f8]">Ward and bed</span>
                </div>
                <span class="text-xs bg-[#021829] text-[#CCECEE]/60 border border-[#CCECEE]/15 rounded-full px-3 py-1">
                    {{ $totalWards }} wards
                </span>
            </div>
            <div class="divide-y divide-[#CCECEE]/5">
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-bed text-[#CCECEE]/40 text-base"></i> Total beds
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $totalBeds }}</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-circle-check text-[#CCECEE]/40 text-base"></i> Available
                    </span>
                    <span class="inline-flex items-center text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full px-2.5 py-0.5">
                        {{ $availableBeds }} open
                    </span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-circle-x text-[#CCECEE]/40 text-base"></i> Occupied
                    </span>
                    <span class="inline-flex items-center text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20 rounded-full px-2.5 py-0.5">
                        {{ $occupiedBeds }} taken
                    </span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-users text-[#CCECEE]/40 text-base"></i> Staff on rota
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $staffOnRota }}</span>
                </div>
            </div>
        </div>

        {{-- Staff and Department --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden fade-in">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#CCECEE]/10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center">
                        <i class="ti ti-stethoscope text-purple-400 text-base"></i>
                    </div>
                    <span class="text-sm font-semibold text-[#f0f7f8]">Staff and department</span>
                </div>
                <span class="text-xs bg-[#021829] text-[#CCECEE]/60 border border-[#CCECEE]/15 rounded-full px-3 py-1">
                    {{ $totalStaff }} staff
                </span>
            </div>
            <div class="divide-y divide-[#CCECEE]/5">
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-building text-[#CCECEE]/40 text-base"></i> Departments
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $totalDepartments }}</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-nurse text-[#CCECEE]/40 text-base"></i> Charge nurses
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $chargeNurses }}</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-sun text-[#CCECEE]/40 text-base"></i> Early shift
                    </span>
                    <span class="inline-flex items-center text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full px-2.5 py-0.5">
                        {{ $earlyShift }}
                    </span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-moon text-[#CCECEE]/40 text-base"></i> Night shift
                    </span>
                    <span class="inline-flex items-center text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-full px-2.5 py-0.5">
                        {{ $nightShift }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Appointment and Requisition --}}
        <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden fade-in">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#CCECEE]/10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-teal-500/10 flex items-center justify-center">
                        <i class="ti ti-pill text-teal-400 text-base"></i>
                    </div>
                    <span class="text-sm font-semibold text-[#f0f7f8]">Appointment and requisition</span>
                </div>
                <span class="text-xs bg-[#021829] text-[#CCECEE]/60 border border-[#CCECEE]/15 rounded-full px-3 py-1">
                    {{ $totalSupplies }} supply items
                </span>
            </div>
            <div class="divide-y divide-[#CCECEE]/5">
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-calendar text-[#CCECEE]/40 text-base"></i> Total appointments
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $totalAppointments }}</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-calendar-due text-[#CCECEE]/40 text-base"></i> Upcoming
                    </span>
                    <span class="inline-flex items-center text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-full px-2.5 py-0.5">
                        {{ $upcomingAppointments }}
                    </span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-clipboard-list text-[#CCECEE]/40 text-base"></i> Requisitions
                    </span>
                    <span class="text-sm font-semibold text-[#f0f7f8]">{{ $totalRequisitions }}</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-alert-triangle text-[#CCECEE]/40 text-base"></i> Low stock
                    </span>
                    <span class="inline-flex items-center text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full px-2.5 py-0.5">
                        {{ $lowStock }} items
                    </span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-sm text-[#CCECEE]/60">
                        <i class="ti ti-x text-[#CCECEE]/40 text-base"></i> Out of stock
                    </span>
                    <span class="inline-flex items-center text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20 rounded-full px-2.5 py-0.5">
                        {{ $outOfStock }} items
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- Quick Links --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden fade-in">
        <div class="px-5 py-4 border-b border-[#CCECEE]/10">
            <span class="text-sm font-semibold text-[#f0f7f8]">Quick links</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 p-4">
            <a href="{{ route('patients.create') }}"
               class="flex flex-col items-center gap-2 bg-[#021829] hover:bg-[#03416E]/40 border border-[#CCECEE]/10 hover:border-[#CCECEE]/30 rounded-xl p-4 transition-all duration-200 hover:-translate-y-0.5 group">
                <i class="ti ti-user-plus text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-xl transition-colors"></i>
                <span class="text-xs text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-center transition-colors">Add patient</span>
            </a>
            <a href="{{ route('wards.create') }}"
               class="flex flex-col items-center gap-2 bg-[#021829] hover:bg-[#03416E]/40 border border-[#CCECEE]/10 hover:border-[#CCECEE]/30 rounded-xl p-4 transition-all duration-200 hover:-translate-y-0.5 group">
                <i class="ti ti-building-hospital text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-xl transition-colors"></i>
                <span class="text-xs text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-center transition-colors">Add ward</span>
            </a>
            <a href="{{ route('beds.create') }}"
               class="flex flex-col items-center gap-2 bg-[#021829] hover:bg-[#03416E]/40 border border-[#CCECEE]/10 hover:border-[#CCECEE]/30 rounded-xl p-4 transition-all duration-200 hover:-translate-y-0.5 group">
                <i class="ti ti-bed text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-xl transition-colors"></i>
                <span class="text-xs text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-center transition-colors">Add bed</span>
            </a>
            <a href="{{ route('staff.create') }}"
               class="flex flex-col items-center gap-2 bg-[#021829] hover:bg-[#03416E]/40 border border-[#CCECEE]/10 hover:border-[#CCECEE]/30 rounded-xl p-4 transition-all duration-200 hover:-translate-y-0.5 group">
                <i class="ti ti-user-check text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-xl transition-colors"></i>
                <span class="text-xs text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-center transition-colors">Add staff</span>
            </a>
            <a href="{{ route('staff-rota.create') }}"
               class="flex flex-col items-center gap-2 bg-[#021829] hover:bg-[#03416E]/40 border border-[#CCECEE]/10 hover:border-[#CCECEE]/30 rounded-xl p-4 transition-all duration-200 hover:-translate-y-0.5 group">
                <i class="ti ti-calendar-plus text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-xl transition-colors"></i>
                <span class="text-xs text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-center transition-colors">Add to rota</span>
            </a>
            <a href="{{ route('supply_items.create') }}"
               class="flex flex-col items-center gap-2 bg-[#021829] hover:bg-[#03416E]/40 border border-[#CCECEE]/10 hover:border-[#CCECEE]/30 rounded-xl p-4 transition-all duration-200 hover:-translate-y-0.5 group">
                <i class="ti ti-package text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-xl transition-colors"></i>
                <span class="text-xs text-[#CCECEE]/50 group-hover:text-[#CCECEE] text-center transition-colors">Add supply</span>
            </a>
        </div>
    </div>

</div>
</div>
@endsection