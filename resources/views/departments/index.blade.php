@extends('layouts.app')

@section('title', 'Departments')

@push('styles')
<style>
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .anim-fade-up { animation: fadeUp .35s ease both; }
</style>
@endpush

@section('content')
<div class="p-6 anim-fade-up">
    
    <!-- Welcome Message -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-2xl font-bold text-[#1f3b5c]">Welcome to Wellmeadows Hospital</h1>
        <p class="text-gray-600 mt-2">Manage your staff, departments, schedules, and reports from this dashboard.</p>
    </div>

    <!-- Departments Section -->
    <h2 class="text-white font-semibold mb-4 text-xl">Hospital Departments</h2>
    
    <!-- Department Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <a href="{{ route('staff.index', ['department' => 'Cardiology']) }}" class="block hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Cardiology</h3>
                        <p class="text-sm mt-1">Heart and cardiovascular care</p>
                        <p class="text-2xl font-bold mt-3">{{ $departments['Cardiology'] }} Staff</p>
                    </div>
                    <div class="text-4xl">❤️</div>
                </div>
            </a>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <a href="{{ route('staff.index', ['department' => 'Neurology']) }}" class="block hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Neurology</h3>
                        <p class="text-sm mt-1">Brain and nervous system</p>
                        <p class="text-2xl font-bold mt-3">{{ $departments['Neurology'] }} Staff</p>
                    </div>
                    <div class="text-4xl">🧠</div>
                </div>
            </a>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <a href="{{ route('staff.index', ['department' => 'Pediatrics']) }}" class="block hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Pediatrics</h3>
                        <p class="text-sm mt-1">Child healthcare</p>
                        <p class="text-2xl font-bold mt-3">{{ $departments['Pediatrics'] }} Staff</p>
                    </div>
                    <div class="text-4xl">👶</div>
                </div>
            </a>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-md p-6 text-white">
            <a href="{{ route('staff.index', ['department' => 'Orthopedics']) }}" class="block hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Orthopedics</h3>
                        <p class="text-sm mt-1">Bone and joint care</p>
                        <p class="text-2xl font-bold mt-3">{{ $departments['Orthopedics'] }} Staff</p>
                    </div>
                    <div class="text-4xl">🦴</div>
                </div>
            </a>
        </div>
        
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md p-6 text-white">
            <a href="{{ route('staff.index', ['department' => 'Emergency']) }}" class="block hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Emergency</h3>
                        <p class="text-sm mt-1">24/7 emergency care</p>
                        <p class="text-2xl font-bold mt-3">{{ $departments['Emergency'] }} Staff</p>
                    </div>
                    <div class="text-4xl">🚑</div>
                </div>
            </a>
        </div>
        
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow-md p-6 text-white">
            <a href="{{ route('staff.index', ['department' => 'Radiology']) }}" class="block hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Radiology</h3>
                        <p class="text-sm mt-1">Medical imaging</p>
                        <p class="text-2xl font-bold mt-3">{{ $departments['Radiology'] }} Staff</p>
                    </div>
                    <div class="text-4xl">📊</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-4xl mb-3">👥</div>
                <h3 class="font-semibold text-lg text-[#1f3b5c]">Total Staff</h3>
                <p class="text-3xl font-bold text-gray-700 mt-2">{{ $totalStaff }}</p>
                <a href="{{ route('staff.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">View All Staff →</a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-4xl mb-3">📅</div>
                <h3 class="font-semibold text-lg text-[#1f3b5c]">Today's Schedule</h3>
                <p class="text-gray-600 mt-2">View and manage schedules</p>
                <a href="{{ route('schedules.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">View Schedule →</a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-4xl mb-3">📊</div>
                <h3 class="font-semibold text-lg text-[#1f3b5c]">Reports</h3>
                <p class="text-gray-600 mt-2">Generate and view reports</p>
                <a href="{{ route('reports') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">View Reports →</a>
            </div>
        </div>
    </div>
</div>
@endsection