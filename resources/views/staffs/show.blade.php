<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $staff->firstName }} {{ $staff->lastName }} - Wellmeadows Hospital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#d9edf1]">

<!-- HEADER -->
<header class="bg-[#cfe6eb] shadow-md">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="text-2xl font-bold text-[#1f3b5c]">
                🏥 WELLMEADOWS HOSPITAL
            </div>
        </div>
        <div class="relative" id="profile-dropdown">
            <button id="avatar-btn" class="text-2xl focus:outline-none cursor-pointer">
                👤
            </button>
            <div id="dropdown-menu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg z-50">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- NAVBAR -->
    <div class="bg-[#1f3b5c] text-white flex items-center justify-between px-6 py-3">
        <div class="flex gap-6 font-medium">
            <a href="{{ route('staff.index') }}" class="hover:underline font-bold bg-[#3c5a82] px-3 py-1 rounded">All Staff</a>
            <a href="{{ route('department.index') }}" class="hover:underline">Department</a>
            <a href="{{ route('schedules') }}" class="hover:underline">Schedules</a>
            <a href="{{ route('reports') }}" class="hover:underline">Reports</a>
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
        </div>
        <div class="flex items-center gap-3">
            <input type="text" placeholder="Search staff or Department" class="px-3 py-1 rounded text-black text-sm w-64" />
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#1f3b5c]">{{ $staff->firstName }} {{ $staff->lastName }}</h1>
            <a href="{{ route('staff.index') }}" class="text-blue-600 hover:text-blue-800">← Back to All Staff</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-bold text-[#1f3b5c] mb-4">Personal Information</h2>
                <div class="space-y-2">
                    <p><strong>Staff Number:</strong> {{ $staff->staffNumber }}</p>
                    <p><strong>Address:</strong> {{ $staff->address }}</p>
                    <p><strong>Telephone:</strong> {{ $staff->telephoneNumber }}</p>
                    <p><strong>Date of Birth:</strong> {{ $staff->dateOfBirth->format('F j, Y') }}</p>
                    <p><strong>Sex:</strong> {{ $staff->sex }}</p>
                    <p><strong>National Insurance Number:</strong> {{ $staff->NIN }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-bold text-[#1f3b5c] mb-4">Employment Details</h2>
                <div class="space-y-2">
                    <p><strong>Department:</strong> {{ $staff->department }}</p>
                    <p><strong>Position:</strong> {{ $staff->position }}</p>
                    <p><strong>Current Salary:</strong> ₱ {{ number_format($staff->currentSalary, 2) }}</p>
                    <p><strong>Salary Scale:</strong> {{ $staff->salaryScale }}</p>
                    <p><strong>Hours Per Week:</strong> {{ $staff->hoursPerWeek }}</p>
                    <p><strong>Contract Type:</strong> {{ $staff->contractType }}</p>
                    <p><strong>Payment Type:</strong> {{ $staff->paymentType }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-bold text-[#1f3b5c] mb-4">Qualifications</h2>
            @if ($staff->qualifications->count() > 0)
                <ul class="list-disc list-inside">
                    @foreach ($staff->qualifications as $qualification)
                        <li>{{ $qualification->type }} from {{ $qualification->institution }} ({{ $qualification->date->format('Y') }})</li>
                    @endforeach
                </ul>
            @else
                <p>No qualifications listed.</p>
            @endif
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-bold text-[#1f3b5c] mb-4">Work Experience</h2>
            @if ($staff->workExperiences->count() > 0)
                <ul class="list-disc list-inside">
                    @foreach ($staff->workExperiences as $experience)
                        <li>{{ $experience->position }} at {{ $experience->organization }} ({{ $experience->startDate->format('Y') }} - {{ $experience->finishDate ? $experience->finishDate->format('Y') : 'Present' }})</li>
                    @endforeach
                </ul>
            @else
                <p>No work experience listed.</p>
            @endif
        </div>
    </div>
</main>

<script>
    // Dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        const avatarBtn = document.getElementById('avatar-btn');
        const dropdownMenu = document.getElementById('dropdown-menu');
        
        if (avatarBtn && dropdownMenu) {
            avatarBtn.addEventListener('click', function(event) {
                event.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function(event) {
                if (!avatarBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
    });
</script>

</body>
</html>