<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Departments - Wellmeadows Hospital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#d9edf1]">

<!-- HEADER (same as dashboard) -->
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

    <div class="bg-[#1f3b5c] text-white flex items-center justify-between px-6 py-3">
        <div class="flex gap-6 font-medium">
            <a href="{{ route('staff.index') }}" class="hover:underline">All Staff</a>
            <a href="{{ route('department') }}" class="hover:underline font-bold">Department</a>
            <a href="{{ route('schedules') }}" class="hover:underline">Schedules</a>
            <a href="{{ route('reports') }}" class="hover:underline">Reports</a>
               <a href="{{ route('dashboard') }}"
                    class="hover:underline">
                    Dashboard
                </a>
        </div>
        <div class="flex items-center gap-3">
            <input type="text" placeholder="Search staff or Department" class="px-3 py-1 rounded text-black text-sm w-64" />
        </div>
    </div>
</header>

<main class="p-6">
    <h2 class="text-[#1f3b5c] font-semibold mb-4 text-2xl"> Departments</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Department Cards -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="bg-blue-500 h-2"></div>
            <div class="p-6">
                <div class="text-4xl mb-3">❤️</div>
                <h3 class="text-xl font-bold text-[#1f3b5c] mb-2">Cardiology</h3>
                <p class="text-gray-600 mb-4">Comprehensive heart care including diagnosis, treatment, and prevention of cardiovascular diseases.</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">👥 12 Staff Members</span>
                    <a href="#" class="text-blue-600 hover:text-blue-800">View Details →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="bg-green-500 h-2"></div>
            <div class="p-6">
                <div class="text-4xl mb-3">🧠</div>
                <h3 class="text-xl font-bold text-[#1f3b5c] mb-2">Neurology</h3>
                <p class="text-gray-600 mb-4">Specialized care for disorders of the nervous system including brain, spinal cord, and nerves.</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">👥 8 Staff Members</span>
                    <a href="#" class="text-blue-600 hover:text-blue-800">View Details →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="bg-purple-500 h-2"></div>
            <div class="p-6">
                <div class="text-4xl mb-3">👶</div>
                <h3 class="text-xl font-bold text-[#1f3b5c] mb-2">Pediatrics</h3>
                <p class="text-gray-600 mb-4">Medical care for infants, children, and adolescents from birth to young adulthood.</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">👥 10 Staff Members</span>
                    <a href="#" class="text-blue-600 hover:text-blue-800">View Details →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="bg-yellow-500 h-2"></div>
            <div class="p-6">
                <div class="text-4xl mb-3">🦴</div>
                <h3 class="text-xl font-bold text-[#1f3b5c] mb-2">Orthopedics</h3>
                <p class="text-gray-600 mb-4">Treatment of musculoskeletal system including bones, joints, ligaments, tendons, and muscles.</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">👥 9 Staff Members</span>
                    <a href="#" class="text-blue-600 hover:text-blue-800">View Details →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="bg-red-500 h-2"></div>
            <div class="p-6">
                <div class="text-4xl mb-3">🚑</div>
                <h3 class="text-xl font-bold text-[#1f3b5c] mb-2">Emergency</h3>
                <p class="text-gray-600 mb-4">24/7 emergency care for acute illnesses and injuries requiring immediate medical attention.</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">👥 15 Staff Members</span>
                    <a href="#" class="text-blue-600 hover:text-blue-800">View Details →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="bg-indigo-500 h-2"></div>
            <div class="p-6">
                <div class="text-4xl mb-3">📊</div>
                <h3 class="text-xl font-bold text-[#1f3b5c] mb-2">Radiology</h3>
                <p class="text-gray-600 mb-4">Medical imaging techniques including X-rays, CT scans, MRI, and ultrasound for diagnosis.</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">👥 7 Staff Members</span>
                    <a href="#" class="text-blue-600 hover:text-blue-800">View Details →</a>
                </div>
            </div>
        </div>
    </div>
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