<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report New Incident - Wellmeadows Hospital</title>
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
                class="hover:underline">
                Schedules
            </a>

            <a href="{{ route('reports') }}"
                class="hover:underline">
                Reports
            </a>

        </div>

        <div class="flex items-center gap-3">

            <a href="{{ route('staff.create') }}"
                class="bg-white text-[#1f3b5c] px-3 py-1 rounded text-sm font-semibold">
                + Add Staff
            </a>

            <input type="text"
                placeholder="Search staff or Department"
                class="px-3 py-1 rounded text-black text-sm w-64" />

        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="p-6">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-[#1f3b5c] mb-6">Report a New Incident</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="staff_id" class="block text-sm font-medium text-gray-700">Staff Member Involved</label>
                    <select id="staff_id" name="staff_id" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1f3b5c] focus:border-[#1f3b5c] sm:text-sm" required>
                        <option value="">Select a staff member</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->staffNumber }}">{{ $member->firstName }} {{ $member->lastName }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="incident_date" class="block text-sm font-medium text-gray-700">Date of Incident</label>
                    <input type="date" id="incident_date" name="incident_date" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1f3b5c] focus:border-[#1f3b5c] sm:text-sm" required>
                </div>

                <div>
                    <label for="incident_type" class="block text-sm font-medium text-gray-700">Incident Type</label>
                    <input type="text" id="incident_type" name="incident_type" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1f3b5c] focus:border-[#1f3b5c] sm:text-sm" required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description of Incident</label>
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1f3b5c] focus:border-[#1f3b5c] sm:text-sm" required></textarea>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full px-4 py-2 bg-[#1f3b5c] text-white rounded hover:bg-[#2a4d7a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1f3b5c]">
                    Submit Report
                </button>
            </div>
        </form>
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