<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Staff - Wellmeadows Hospital</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

                <a href="{{ route('department') }}"
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

    <!-- MAIN -->
    <main class="p-6">

        <div class="max-w-6xl mx-auto">

            <div class="bg-white rounded-lg shadow-md p-6">

                <h2 class="text-2xl font-bold text-[#1f3b5c] mb-6">
                    Add New Staff Member
                </h2>

                <!-- SUCCESS MESSAGE -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ERROR MESSAGE -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">

                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                <!-- FORM -->
                <form action="{{ route('staff.store') }}" method="POST">

                    @csrf

                    <!-- BASIC INFORMATION -->
                    <div class="border-b border-gray-200 pb-4 mb-4">

                        <h3 class="text-lg font-semibold text-[#1f3b5c] mb-4">
                            Basic Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- FIRST NAME -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    First Name *
                                </label>

                                <input type="text"
                                    name="firstName"
                                    value="{{ old('firstName') }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded">
                            </div>

                            <!-- LAST NAME -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Last Name *
                                </label>

                                <input type="text"
                                    name="lastName"
                                    value="{{ old('lastName') }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded">
                            </div>

                            <!-- ADDRESS -->
                            <div class="md:col-span-2">

                                <label class="block text-gray-700 font-medium mb-2">
                                    Address *
                                </label>

                                <textarea name="address"
                                    rows="2"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded">{{ old('address') }}</textarea>

                            </div>

                        </div>
                    </div>

                    <!-- QUALIFICATIONS -->
                    <div class="border-b border-gray-200 pb-4 mb-4"
                        x-data="{ qualifications: [{ type: '', date: '', institution: '' }] }">

                        <div class="flex justify-between items-center mb-4">

                            <h3 class="text-lg font-semibold text-[#1f3b5c]">
                                Qualifications
                            </h3>

                            <button type="button"
                                @click="qualifications.push({ type: '', date: '', institution: '' })"
                                class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">

                                + Add Qualification

                            </button>

                        </div>

                        <template x-for="(qualification, index) in qualifications"
                            :key="index">

                            <div class="border border-gray-200 rounded p-4 mb-3">

                                <div class="flex justify-between items-center mb-3">

                                    <h4 class="font-medium"
                                        x-text="`Qualification ${index + 1}`">
                                    </h4>

                                    <button type="button"
                                        @click="qualifications.splice(index, 1)"
                                        class="text-red-600 hover:text-red-800 text-sm">

                                        Remove

                                    </button>

                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                                    <div>

                                        <label class="block text-gray-700 text-sm mb-1">
                                            Type
                                        </label>

                                        <input type="text"
                                            :name="`qualifications[${index}][type]`"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm">

                                    </div>

                                </div>

                            </div>

                        </template>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex justify-end gap-3 mt-6">

                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">

                            Cancel

                        </a>

                        <button type="submit"
                            class="px-4 py-2 bg-[#1f3b5c] text-white rounded hover:bg-[#2a4d7a]">

                            Add Staff Member

                        </button>

                    </div>

                </form>

            </div>
        </div>
    </main>

    <!-- DROPDOWN SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const avatarBtn = document.getElementById('avatar-btn');
            const dropdownMenu = document.getElementById('dropdown-menu');

            if (avatarBtn && dropdownMenu) {

                avatarBtn.addEventListener('click', function (event) {
                    event.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (event) {

                    if (
                        !avatarBtn.contains(event.target) &&
                        !dropdownMenu.contains(event.target)
                    ) {
                        dropdownMenu.classList.add('hidden');
                    }

                });
            }
        });
    </script>

</body>
</html>