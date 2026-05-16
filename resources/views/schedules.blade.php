<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedules - Wellmeadows Hospital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#d9edf1]">

<!-- HEADER -->
<header class="bg-[#cfe6eb] shadow-md">

    <!-- TOP BAR -->
    <div class="flex items-center justify-between px-6 py-4">

        <div class="flex items-center gap-3">
            <div class="text-2xl font-bold text-[#1f3b5c]">
                🏥 WELLMEADOWS HOSPITAL
            </div>
        </div>

        <!-- PROFILE DROPDOWN -->
        <div class="relative" id="profile-dropdown">

            <button id="avatar-btn"
                class="text-2xl focus:outline-none cursor-pointer">
                👤
            </button>

            <div id="dropdown-menu"
                class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg z-50">

                <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- NAVIGATION -->
    <div class="bg-[#1f3b5c] text-white flex items-center justify-between px-6 py-3">

        <div class="flex gap-6 font-medium">

            <a href="{{ route('staff.index') }}"
                class="hover:underline">
                All Staff
            </a>

            <a href="{{ route('department.index') }}"
                class="hover:underline">
                Department
            </a>

            <a href="{{ route('schedules') }}"
                class="hover:underline font-bold bg-[#3c5a82] px-3 py-1 rounded">
                Schedules
            </a>

            <a href="{{ route('reports') }}"
                class="hover:underline">
                Reports
            </a>
            <a href="{{ route('dashboard') }}"
                class="hover:underline">
                Dashboard
            </a>

        </div>

        <div class="relative flex items-center gap-3">

            <input type="text" id="search-input"
                placeholder="Search staff or Department"
                class="px-3 py-1 rounded text-black text-sm w-64" />
            <div id="search-results" class="absolute top-full mt-2 w-64 bg-white rounded-lg shadow-lg z-50 hidden" style="right: 0;"></div>

        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-[#1f3b5c] mb-6">Staff Schedules</h1>
        
        <!-- Schedule content goes here -->
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📅</div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Schedule Management</h3>
            <p class="text-gray-500">Schedule feature coming soon. This page will display staff working hours, shifts, and rotations.</p>
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
                if (event.target.closest('#search-input') || event.target.closest('#search-results')) {
                    return;
                }
                searchResults.classList.add('hidden');
            });
        }
    });
</script>

</body>
</html>