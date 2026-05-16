<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Wellmeadows Hospital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
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

        <!-- Profile Dropdown -->
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
            <a href="{{ route('staff.index') }}" class="hover:underline">All Staff</a>
            <a href="{{ route('department.index') }}" class="hover:underline">Department</a>
            <a href="{{ route('schedules') }}" class="hover:underline">Schedules</a>
            <a href="{{ route('reports') }}" class="hover:underline">Reports</a>
<a href="{{ route('dashboard') }}" class="hover:underline font-bold bg-[#2a4d7a] px-3 py-1 rounded">Dashboard</a>
        </div>
        <div class="relative flex items-center gap-3">
            <input type="text" id="search-input" placeholder="Search staff or Department" class="px-3 py-1 rounded text-black text-sm w-64" />
            <div id="search-results" class="absolute top-full mt-2 w-64 bg-white rounded-lg shadow-lg z-50 hidden" style="right: 0;"></div>
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="p-6">
    
    <!-- Welcome Message -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-2xl font-bold text-[#1f3b5c]">Welcome to Wellmeadows Hospital</h1>
        <p class="text-gray-600 mt-2">Manage your staff, departments, schedules, and reports from this dashboard.</p>
    </div>

    <!-- Departments Section -->
    <h2 class="text-[#1f3b5c] font-semibold mb-4 text-xl">Hospital Departments</h2>
    
    <!-- Department Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Cardiology</h3>
                    <p class="text-sm mt-1">Heart and cardiovascular care</p>
                    <p class="text-2xl font-bold mt-3">{{ $departments['Cardiology'] }} Staff</p>
                </div>
                <div class="text-4xl">❤️</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Neurology</h3>
                    <p class="text-sm mt-1">Brain and nervous system</p>
                    <p class="text-2xl font-bold mt-3">{{ $departments['Neurology'] }} Staff</p>
                </div>
                <div class="text-4xl">🧠</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Pediatrics</h3>
                    <p class="text-sm mt-1">Child healthcare</p>
                    <p class="text-2xl font-bold mt-3">{{ $departments['Pediatrics'] }} Staff</p>
                </div>
                <div class="text-4xl">👶</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Orthopedics</h3>
                    <p class="text-sm mt-1">Bone and joint care</p>
                    <p class="text-2xl font-bold mt-3">{{ $departments['Orthopedics'] }} Staff</p>
                </div>
                <div class="text-4xl">🦴</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Emergency</h3>
                    <p class="text-sm mt-1">24/7 emergency care</p>
                    <p class="text-2xl font-bold mt-3">{{ $departments['Emergency'] }} Staff</p>
                </div>
                <div class="text-4xl">🚑</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Radiology</h3>
                    <p class="text-sm mt-1">Medical imaging</p>
                    <p class="text-2xl font-bold mt-3">{{ $departments['Radiology'] }} Staff</p>
                </div>
                <div class="text-4xl">📊</div>
            </div>
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
                <a href="{{ route('schedules') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">View Schedule →</a>
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

        // Search functionality
        const searchInput = document.getElementById('search-input');
        const searchResults = document.getElementById('search-results');

        if (searchInput && searchResults) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length < 2) {
                    searchResults.innerHTML = '';
                    searchResults.classList.add('hidden');
                    return;
                }

                fetch(`/search?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        searchResults.classList.remove('hidden');

                        if (data.staff.length === 0 && data.departments.length === 0) {
                            const noResults = document.createElement('div');
                            noResults.className = 'p-2 text-gray-500';
                            noResults.textContent = 'No results found';
                            searchResults.appendChild(noResults);
                            return;
                        }

                        if (data.staff.length > 0) {
                            const staffHeader = document.createElement('div');
                            staffHeader.className = 'p-2 font-bold text-gray-600';
                            staffHeader.textContent = 'Staff';
                            searchResults.appendChild(staffHeader);

                            data.staff.forEach(staff => {
                                const staffLink = document.createElement('a');
                                staffLink.href = `/staff/${staff.staffNumber}`;
                                staffLink.className = 'block p-2 text-gray-700 hover:bg-gray-100';
                                staffLink.textContent = `${staff.firstName} ${staff.lastName}`;
                                searchResults.appendChild(staffLink);
                            });
                        }

                        if (data.departments.length > 0) {
                            const departmentsHeader = document.createElement('div');
                            departmentsHeader.className = 'p-2 font-bold text-gray-600';
                            departmentsHeader.textContent = 'Departments';
                            searchResults.appendChild(departmentsHeader);

                            data.departments.forEach(department => {
                                const departmentLink = document.createElement('a');
                                departmentLink.href = `/department/${department.department}`;
                                departmentLink.className = 'block p-2 text-gray-700 hover:bg-gray-100';
                                departmentLink.textContent = department.department;
                                searchResults.appendChild(departmentLink);
                            });
                        }
                    });
            });

            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    const firstResult = searchResults.querySelector('a');
                    if (firstResult) {
                        window.location.href = firstResult.href;
                    }
                }
            });

            document.addEventListener('click', function(event) {
                if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                    searchResults.classList.add('hidden');
                }
            });
        }
    });
</script>

</body>
</html>