@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-wm-card border border-wm-navy/60 rounded-2xl overflow-hidden shadow-[0_8px_40px_rgba(0,0,0,.4)] p-8">
        <h2 class="text-2xl font-bold text-white mb-6">Add New Staff Member</h2>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded relative mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- ERROR MESSAGE -->
        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded relative mb-6" role="alert">
                <p class="font-bold mb-2">Please correct the errors below:</p>
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
            workExperiences: []
        }">
            @csrf

            <!-- BASIC INFORMATION -->
            <div class="border-b border-wm-navy/60 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-white mb-4">Basic Information</h3>
                <div class="space-y-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-300">First Name *</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-300">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-300">Address *</label>
                        <textarea name="address" id="address" rows="2" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">{{ old('address') }}</textarea>
                    </div>
                    <div>
                        <label for="telephone_number" class="block text-sm font-medium text-gray-300">Telephone Number *</label>
                        <input type="text" name="telephone_number" id="telephone_number" value="{{ old('telephone_number') }}" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-300">Date of Birth *</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3" style="color-scheme: dark;">
                    </div>
                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-300">Sex *</label>
                        <select name="sex" id="sex" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                            <option value="">Select Sex</option>
                            <option value="M" @if(old('sex') == 'M') selected @endif>Male</option>
                            <option value="F" @if(old('sex') == 'F') selected @endif>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="nin" class="block text-sm font-medium text-gray-300">National Insurance Number *</label>
                        <input type="text" name="nin" id="nin" value="{{ old('nin') }}" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                    </div>
                </div>
            </div>

            <!-- EMPLOYMENT DETAILS -->
            <div class="border-b border-wm-navy/60 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-white mb-4">Employment Details</h3>
                <div class="space-y-4">
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-300">Position *</label>
                        <input type="text" name="position" id="position" value="{{ old('position') }}" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                    </div>
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-300">Department *</label>
                        <select name="department_id" id="department_id" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                            <option value="">Select a Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @if(old('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="current_salary" class="block text-sm font-medium text-gray-300">Current Salary (₱) *</label>
                        <input type="number" name="current_salary" id="current_salary" value="{{ old('current_salary') }}" required step="0.01" class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                    </div>
                    <div>
                        <label for="salary_scale" class="block text-sm font-medium text-gray-300">Salary Scale *</label>
                        <select name="salary_scale" id="salary_scale" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                            <option value="">Select Salary Scale</option>
                            @foreach($salaryScales as $scale)
                                <option value="{{ $scale }}" @if(old('salary_scale') == $scale) selected @endif>{{ $scale }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="hours_per_week" class="block text-sm font-medium text-gray-300">Hours Per Week *</label>
                        <input type="number" name="hours_per_week" id="hours_per_week" value="{{ old('hours_per_week') }}" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                    </div>
                    <div>
                        <label for="contract_type" class="block text-sm font-medium text-gray-300">Contract Type *</label>
                        <select name="contract_type" id="contract_type" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                            <option value="">Select Contract Type</option>
                            @foreach($contractTypes as $type)
                                <option value="{{ $type }}" @if(old('contract_type') == $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-300">Payment Type *</label>
                        <select name="payment_type" id="payment_type" required class="mt-1 block w-full bg-gray-800 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm px-4 py-3">
                            <option value="">Select Payment Type</option>
                            @foreach($paymentTypes as $type)
                                <option value="{{ $type }}" @if(old('payment_type') == $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- QUALIFICATIONS -->
            <div class="border-b border-wm-navy/60 pb-4 mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-white">Qualifications</h3>
                    <button type="button" @click="qualifications.push({ type: '', date: '', institution: '' })" class="bg-cyan-300/10 text-cyan-300 px-3 py-1 rounded text-sm hover:bg-cyan-300/20">
                        + Add Qualification
                    </button>
                </div>
                <template x-for="(qualification, index) in qualifications" :key="index">
                    <div class="border border-wm-navy/60 rounded p-4 mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-medium text-gray-300" x-text="`Qualification ${index + 1}`"></h4>
                            <button type="button" @click="qualifications.splice(index, 1)" class="text-red-500 hover:text-red-400 text-sm">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label :for="`qual_type_${index}`" class="block text-gray-400 text-sm mb-1">Type *</label>
                                <input type="text" :name="`qualifications[${index}][type]`" :id="`qual_type_${index}`" x-model="qualification.type" required class="w-full bg-gray-800 border border-gray-600 text-white rounded text-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 px-4 py-3">
                            </div>
                            <div>
                                <label :for="`qual_date_${index}`" class="block text-gray-400 text-sm mb-1">Date Awarded *</label>
                                <input type="date" :name="`qualifications[${index}][date]`" :id="`qual_date_${index}`" x-model="qualification.date" required class="w-full bg-gray-800 border border-gray-600 text-white rounded text-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 px-4 py-3" style="color-scheme: dark;">
                            </div>
                            <div>
                                <label :for="`qual_inst_${index}`" class="block text-gray-400 text-sm mb-1">Institution *</label>
                                <input type="text" :name="`qualifications[${index}][institution]`" :id="`qual_inst_${index}`" x-model="qualification.institution" required class="w-full bg-gray-800 border border-gray-600 text-white rounded text-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 px-4 py-3">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- WORK EXPERIENCE (OPTIONAL) -->
            <div class="pb-4 mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-white">Work Experience <span class="text-sm text-gray-400 font-normal">(Optional)</span></h3>
                    <button type="button" @click="workExperiences.push({ position: '', organization: '', startDate: '', finishDate: '' })" class="bg-cyan-300/10 text-cyan-300 px-3 py-1 rounded text-sm hover:bg-cyan-300/20">
                        + Add Work Experience
                    </button>
                </div>
                <div x-show="workExperiences.length === 0" class="text-gray-400 text-sm py-4 text-center border border-dashed border-wm-navy/60 rounded-lg">
                    No work experience added. Click the button above to add previous employment history if applicable.
                </div>
                <template x-for="(experience, index) in workExperiences" :key="index">
                    <div class="border border-wm-navy/60 rounded p-4 mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-medium text-gray-300" x-text="`Experience ${index + 1}`"></h4>
                            <button type="button" @click="workExperiences.splice(index, 1)" class="text-red-500 hover:text-red-400 text-sm">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label :for="`work_pos_${index}`" class="block text-gray-400 text-sm mb-1">Position *</label>
                                <input type="text" :name="`workExperiences[${index}][position]`" :id="`work_pos_${index}`" x-model="experience.position" required class="w-full bg-gray-800 border border-gray-600 text-white rounded text-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 px-4 py-3">
                            </div>
                            <div>
                                <label :for="`work_org_${index}`" class="block text-gray-400 text-sm mb-1">Organization *</label>
                                <input type="text" :name="`workExperiences[${index}][organization]`" :id="`work_org_${index}`" x-model="experience.organization" required class="w-full bg-gray-800 border border-gray-600 text-white rounded text-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 px-4 py-3">
                            </div>
                            <div>
                                <label :for="`work_start_${index}`" class="block text-gray-400 text-sm mb-1">Start Date *</label>
                                <input type="date" :name="`workExperiences[${index}][startDate]`" :id="`work_start_${index}`" x-model="experience.startDate" required class="w-full bg-gray-800 border border-gray-600 text-white rounded text-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 px-4 py-3" style="color-scheme: dark;">
                            </div>
                            <div>
                                <label :for="`work_finish_${index}`" class="block text-gray-400 text-sm mb-1">Finish Date</label>
                                <input type="date" :name="`workExperiences[${index}][finishDate]`" :id="`work_finish_${index}`" x-model="experience.finishDate" class="w-full bg-gray-800 border border-gray-600 text-white rounded text-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 px-4 py-3" style="color-scheme: dark;">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-4 mt-8">
                <a href="{{ route('staff.index') }}" class="text-gray-400 hover:text-white font-bold py-2 px-4">Cancel</a>
                <button type="submit" class="bg-cyan-300 hover:bg-cyan-400 text-gray-900 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">Add Staff Member</button>
            </div>
        </form>
    </div>
</div>
@endsection