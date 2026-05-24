@extends('layouts.app', ['module' => 'Staff and Department'])

@section('title', 'Edit Staff: ' . $staff->first_name . ' ' . $staff->last_name)

@section('content')
    <div class="bg-white rounded-lg shadow-md p-8 max-w-4xl mx-auto my-12">
        <h1 class="text-2xl font-bold text-[#1f3b5c] mb-8">Edit Staff Member: {{ $staff->first_name }} {{ $staff->last_name }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('staff.update', $staff->staff_number) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- PERSONAL DETAILS -->
            <div class="border-b border-gray-200 pb-8 mb-8">
                <h3 class="text-lg font-semibold text-[#1f3b5c] mb-6">Personal Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $staff->first_name) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $staff->last_name) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $staff->address) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="telephone_number" class="block text-sm font-medium text-gray-700">Telephone Number *</label>
                        <input type="tel" name="telephone_number" id="telephone_number" value="{{ old('telephone_number', $staff->telephone_number) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $staff->date_of_birth) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex *</label>
                        <select name="sex" id="sex" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            <option value="M" @if(old('sex', $staff->sex) == 'M') selected @endif>Male</option>
                            <option value="F" @if(old('sex', $staff->sex) == 'F') selected @endif>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="nin" class="block text-sm font-medium text-gray-700">National Insurance Number *</label>
                        <input type="text" name="nin" id="nin" value="{{ old('nin', $staff->nin) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>

            <!-- EMPLOYMENT DETAILS -->
            <div class="border-b border-gray-200 pb-8 mb-8">
                <h3 class="text-lg font-semibold text-[#1f3b5c] mb-6">Employment Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700">Position *</label>
                        <input type="text" name="position" id="position" value="{{ old('position', $staff->position) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700">Department *</label>
                        <select name="department_id" id="department_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @if(old('department_id', $staff->department_id) == $department->id) selected @endif>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="current_salary" class="block text-sm font-medium text-gray-700">Current Salary (₱) *</label>
                        <input type="number" name="current_salary" id="current_salary" value="{{ old('current_salary', $staff->current_salary) }}" required step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="salary_scale" class="block text-sm font-medium text-gray-700">Salary Scale *</label>
                        <select name="salary_scale" id="salary_scale" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            @foreach($salaryScales as $scale)
                                <option value="{{ $scale }}" @if(old('salary_scale', $staff->salary_scale) == $scale) selected @endif>{{ $scale }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="hours_per_week" class="block text-sm font-medium text-gray-700">Hours Per Week *</label>
                        <input type="number" name="hours_per_week" id="hours_per_week" value="{{ old('hours_per_week', $staff->hours_per_week) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="contract_type" class="block text-sm font-medium text-gray-700">Contract Type *</label>
                        <select name="contract_type" id="contract_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            @foreach($contractTypes as $type)
                                <option value="{{ $type }}" @if(old('contract_type', $staff->contract_type) == $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-700">Payment Type *</label>
                        <select name="payment_type" id="payment_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            @foreach($paymentTypes as $type)
                                <option value="{{ $type }}" @if(old('payment_type', $staff->payment_type) == $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- DYNAMIC FIELDS WRAPPER -->
            <div x-data="{
                qualifications: {{ json_encode(old('qualifications', $staff->qualifications->map(fn($q) => ['type' => $q->type, 'date' => $q->date, 'institution' => $q->institution]))) }},
                workExperiences: {{ json_encode(old('workExperiences', $staff->workExperiences->map(fn($w) => ['position' => $w->position, 'organization' => $w->organization, 'start_date' => $w->start_date, 'finish_date' => $w->finish_date]))) }}
            }">
                <!-- QUALIFICATIONS -->
                <div class="border-b border-gray-200 pb-8 mb-8">
                    <h3 class="text-lg font-semibold text-[#1f3b5c] mb-6">Qualifications</h3>
                    <!-- ... qualifications content ... -->
                </div>

                <!-- WORK EXPERIENCE -->
                <div class="border-b border-gray-200 pb-8 mb-8">
                    <h3 class="text-lg font-semibold text-[#1f3b5c] mb-6">Work Experience</h3>
                    <!-- ... work experience content ... -->
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-[#1f3b5c] text-white px-6 py-2 rounded-lg hover:bg-[#2a4d7a] transition-colors">
                    Update Staff Member
                </button>
            </div>
        </form>
    </div>
@endsection