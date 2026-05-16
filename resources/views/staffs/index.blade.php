<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Staff - Wellmeadows Hospital</title>

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
            <a href="{{ route('staff.index') }}" class="hover:underline font-bold bg-[#2a4d7a] px-3 py-1 rounded">All Staff</a>
            <a href="{{ route('department.index') }}" class="hover:underline">Department</a>
            <a href="{{ route('schedules') }}" class="hover:underline">Schedules</a>
            <a href="{{ route('reports') }}" class="hover:underline">Reports</a>
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
        </div>
        <div class="flex items-center gap-3 relative">
            <input type="text" id="search-input" placeholder="Search staff or Department" class="px-3 py-1 rounded text-black text-sm w-64" />
            <div id="search-results" class="absolute top-full mt-2 w-64 bg-white rounded-lg shadow-lg z-50 hidden"></div>
        </div>
    </div>
</header>

    <!-- MAIN CONTENT -->
    <main class="p-6">

    @if(isset($department))
    <h1 class="text-2xl font-bold text-[#1f3b5c] mb-4">Staff in {{ $department }}</h1>
@else
    <h1 class="text-2xl font-bold text-[#1f3b5c] mb-4">All Staff Members</h1>
@endif

        <div class="max-w-7xl mx-auto">

            <div class="bg-white rounded-lg shadow-md p-6">

                <!-- PAGE TITLE -->
                <div class="flex justify-between items-center mb-6">

                    <h2 class="text-2xl font-bold text-[#1f3b5c]">
                        All Staff Members
                    </h2>

                    <!-- SINGLE ADD BUTTON -->
                    <a href="{{ route('staff.create') }}"
                        class="bg-[#1f3b5c] text-white px-4 py-2 rounded hover:bg-[#2a4d7a]">

                        + Add New Staff

                    </a>

                </div>

                <!-- SUCCESS MESSAGE -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- STAFF TABLE -->
                <div class="overflow-x-auto">

                    <table class="min-w-full border border-gray-300">

                        <thead class="bg-[#1f3b5c] text-white">

                            <tr>

                                <th class="px-4 py-3 border">
                                    Staff No
                                </th>

                                <th class="px-4 py-3 border">
                                    Full Name
                                </th>

                                <th class="px-4 py-3 border">
                                    Position
                                </th>

                                <th class="px-4 py-3 border">
                                    Telephone
                                </th>

                                <th class="px-4 py-3 border">
                                    Salary
                                </th>

                                <th class="px-4 py-3 border">
                                    Contract
                                </th>

                                <th class="px-4 py-3 border">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($staff as $member)

                                <tr class="hover:bg-gray-100">

                                    <!-- STAFF NUMBER -->
                                    <td class="px-4 py-3 border text-center">
                                        {{ $member->staffNumber }}
                                    </td>

                                    <!-- NAME -->
                                    <td class="px-4 py-3 border">
                                        {{ $member->firstName }}
                                        {{ $member->lastName }}
                                    </td>

                                    <!-- POSITION -->
                                    <td class="px-4 py-3 border">
                                        {{ $member->position }}
                                    </td>

                                    <!-- TELEPHONE -->
                                    <td class="px-4 py-3 border">
                                        {{ $member->telephoneNumber }}
                                    </td>

                                    <!-- SALARY -->
                                    <td class="px-4 py-3 border">
                                        ₱{{ number_format($member->currentSalary, 2) }}
                                    </td>

                                    <!-- CONTRACT -->
                                    <td class="px-4 py-3 border">
                                        {{ $member->contractType }}
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="px-4 py-3 border">

                                        <div class="flex gap-2 justify-center">

                                            <!-- EDIT -->
                                            <a href="{{ route('staff.edit', $member->staffNumber) }}"
                                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">

                                                Edit

                                            </a>

                                            <!-- DELETE -->
                                            <form action="{{ route('staff.destroy', $member->staffNumber) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this staff member?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">

                                                    Delete

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <!-- EMPTY STATE -->
                                <tr>

                                    <td colspan="7"
                                        class="text-center py-6 text-gray-500">

                                        No staff members found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

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