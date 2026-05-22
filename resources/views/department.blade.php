<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Departments - Wellmeadows Hospital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#0d253f]">

<!-- HEADER -->
<header class="bg-[#1a3a5d] shadow-md">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
        <div class="flex items-center gap-3">
            <div class="text-2xl font-bold text-white">
                🏥 WELLMEADOWS HOSPITAL
            </div>
        </div>

        <div class="relative" id="profile-dropdown">
            <button id="avatar-btn" class="text-2xl focus:outline-none cursor-pointer text-white">
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

    <div class="bg-[#1a3a5d] text-white flex items-center justify-between px-6 py-3">
        <div class="flex gap-6 font-medium">
            <a href="{{ route('staff.index') }}" class="text-gray-300 hover:text-white hover:underline">All Staff</a>
            <a href="{{ route('department.index') }}" class="font-bold text-white underline bg-gray-700/50 px-3 py-1 rounded">Department</a>
            <a href="{{ route('schedules') }}" class="text-gray-300 hover:text-white hover:underline">Schedules</a>
            <a href="{{ route('reports') }}" class="text-gray-300 hover:text-white hover:underline">Reports</a>
            <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white hover:underline">Dashboard</a>
        </div>
        <div class="relative flex items-center gap-3">
            {{-- Search bar removed as it is now in the main navigation --}}
        </div>
    </div>
</header>

<main class="p-6">
    <h2 class="text-white font-semibold mb-4 text-2xl"> Departments</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($departments as $name => $details)
        <a href="{{ route('department.show', ['name' => $name]) }}" class="block bg-[#1a3a5d] rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow flex flex-col">
            <div class="p-6 flex flex-col flex-grow">
                <div class="text-4xl mb-3">{{ $details['emoji'] }}</div>
                <h3 class="text-xl font-bold text-white mb-2">{{ $name }}</h3>
                <p class="text-[#8caabe] mb-4">{{ $details['description'] }}</p>
                <div class="flex justify-between items-center mt-auto">
                    <span class="text-sm text-[#8caabe]">👥 {{ $departmentCounts[strtolower($name)] ?? 0 }} Staff Members</span>
                    <span class="text-[#36a9e1] hover:underline">View Details →</span>
                </div>
            </div>
        </a>
        @endforeach
</main>

<script>
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