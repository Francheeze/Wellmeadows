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
                <form action="{{ route('staff.store') }}" method="POST" x-data="{
                    qualifications: [{ type: '', date: '', institution: '' }],
                    workExperiences: [{ position: '', organization: '', startDate: '', finishDate: '' }]
                }">
                    @csrf

                    <!-- BASIC INFORMATION -->
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <h3 class="text-lg font-semibold text-[#1f3b5c] mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700">First Name *</label>
                                <input type="text" name="firstName" id="firstName" value="{{ old('firstName') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name *</label>
                                <input type="text" name="lastName" id="lastName" value="{{ old('lastName') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                                <textarea name="address" id="address" rows="2" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">{{ old('address') }}</textarea>
                            </div>
                            <div>
                                <label for="telephoneNumber" class="block text-sm font-medium text-gray-700">Telephone Number *</label>
                                <input type="text" name="telephoneNumber" id="telephoneNumber" value="{{ old('telephoneNumber') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="dateOfBirth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                                <input type="date" name="dateOfBirth" id="dateOfBirth" value="{{ old('dateOfBirth') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700">Sex *</label>
                                <select name="sex" id="sex" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                                    <option value="">Select Sex</option>
                                    <option value="M" @if(old('sex') == 'M') selected @endif>Male</option>
                                    <option value="F" @if(old('sex') == 'F') selected @endif>Female</option>
                                </select>
                            </div>
                            <div>
                                <label for="NIN" class="block text-sm font-medium text-gray-700">National Insurance Number *</label>
                                <input type="text" name="NIN" id="NIN" value="{{ old('NIN') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- EMPLOYMENT DETAILS -->
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <h3 class="text-lg font-semibold text-[#1f3b5c] mb-4">Employment Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700">Position *</label>
                                <input type="text" name="position" id="position" value="{{ old('position') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700">Department *</label>
                                <select name="department" id="department" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                                    <option value="">Select a Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department }}" @if(old('department') == $department) selected @endif>{{ $department }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="currentSalary" class="block text-sm font-medium text-gray-700">Current Salary (₱) *</label>
                                <input type="number" name="currentSalary" id="currentSalary" value="{{ old('currentSalary') }}" required step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="salaryScale" class="block text-sm font-medium text-gray-700">Salary Scale *</label>
                                <select name="salaryScale" id="salaryScale" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                                    <option value="">Select Salary Scale</option>
                                    @foreach($salaryScales as $scale)
                                        <option value="{{ $scale }}" @if(old('salaryScale') == $scale) selected @endif>{{ $scale }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="hoursPerWeek" class="block text-sm font-medium text-gray-700">Hours Per Week *</label>
                                <input type="number" name="hoursPerWeek" id="hoursPerWeek" value="{{ old('hoursPerWeek') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="contractType" class="block text-sm font-medium text-gray-700">Contract Type *</label>
                                <select name="contractType" id="contractType" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                                    <option value="">Select Contract Type</option>
                                    @foreach($contractTypes as $type)
                                        <option value="{{ $type }}" @if(old('contractType') == $type) selected @endif>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="paymentType" class="block text-sm font-medium text-gray-700">Payment Type *</label>
                                <select name="paymentType" id="paymentType" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                                    <option value="">Select Payment Type</option>
                                    @foreach($paymentTypes as $type)
                                        <option value="{{ $type }}" @if(old('paymentType') == $type) selected @endif>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- QUALIFICATIONS -->
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-[#1f3b5c]">Qualifications</h3>
                            <button type="button" @click="qualifications.push({ type: '', date: '', institution: '' })" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                + Add Qualification
                            </button>
                        </div>
                        <template x-for="(qualification, index) in qualifications" :key="index">
                            <div class="border border-gray-200 rounded p-4 mb-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="font-medium" x-text="`Qualification ${index + 1}`"></h4>
                                    <button type="button" @click="qualifications.splice(index, 1)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label :for="`qual_type_${index}`" class="block text-gray-700 text-sm mb-1">Type *</label>
                                        <input type="text" :name="`qualifications[${index}][type]`" :id="`qual_type_${index}`" x-model="qualification.type" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    </div>
                                    <div>
                                        <label :for="`qual_date_${index}`" class="block text-gray-700 text-sm mb-1">Date Awarded *</label>
                                        <input type="date" :name="`qualifications[${index}][date]`" :id="`qual_date_${index}`" x-model="qualification.date" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    </div>
                                    <div>
                                        <label :for="`qual_inst_${index}`" class="block text-gray-700 text-sm mb-1">Institution *</label>
                                        <input type="text" :name="`qualifications[${index}][institution]`" :id="`qual_inst_${index}`" x-model="qualification.institution" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- WORK EXPERIENCE -->
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-[#1f3b5c]">Work Experience</h3>
                            <button type="button" @click="workExperiences.push({ position: '', organization: '', startDate: '', finishDate: '' })" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                + Add Work Experience
                            </button>
                        </div>
                        <template x-for="(experience, index) in workExperiences" :key="index">
                            <div class="border border-gray-200 rounded p-4 mb-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="font-medium" x-text="`Experience ${index + 1}`"></h4>
                                    <button type="button" @click="workExperiences.splice(index, 1)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label :for="`work_pos_${index}`" class="block text-gray-700 text-sm mb-1">Position *</label>
                                        <input type="text" :name="`workExperiences[${index}][position]`" :id="`work_pos_${index}`" x-model="experience.position" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    </div>
                                    <div>
                                        <label :for="`work_org_${index}`" class="block text-gray-700 text-sm mb-1">Organization *</label>
                                        <input type="text" :name="`workExperiences[${index}][organization]`" :id="`work_org_${index}`" x-model="experience.organization" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    </div>
                                    <div>
                                        <label :for="`work_start_${index}`" class="block text-gray-700 text-sm mb-1">Start Date *</label>
                                        <input type="date" :name="`workExperiences[${index}][startDate]`" :id="`work_start_${index}`" x-model="experience.startDate" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    </div>
                                    <div>
                                        <label :for="`work_finish_${index}`" class="block text-gray-700 text-sm mb-1">Finish Date</label>
                                        <input type="date" :name="`workExperiences[${index}][finishDate]`" :id="`work_finish_${index}`" x-model="experience.finishDate" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex justify-end gap-4 mt-8">
                        <a href="{{ route('staff.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-[#1f3b5c] text-white rounded-md hover:bg-[#2a4d7a]">Add Staff Member</button>
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