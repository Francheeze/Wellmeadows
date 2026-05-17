@extends('layouts.app', ['module' => 'Staff and Department'])

@section('title', 'Edit Staff: ' . $staff->firstName . ' ' . $staff->lastName)

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto" x-data="{
        qualifications: {{ json_encode(old('qualifications', $staff->qualifications->map(fn($q) => ['type' => $q->type, 'date' => $q->date, 'institution' => $q->institution]))) }},
        workExperiences: {{ json_encode(old('workExperiences', $staff->workExperiences->map(fn($w) => ['position' => $w->position, 'organization' => $w->organization, 'startDate' => $w->startDate, 'finishDate' => $w->finishDate]))) }}
    }">
        <h1 class="text-2xl font-bold text-[#1f3b5c] mb-6">Edit Staff Member: {{ $staff->firstName }} {{ $staff->lastName }}</h1>

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

        <form action="{{ route('staff.update', $staff->staffNumber) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- PERSONAL DETAILS -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-[#1f3b5c] mb-4">Personal Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name *</label>
                        <input type="text" name="firstName" id="firstName" value="{{ old('firstName', $staff->firstName) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name *</label>
                        <input type="text" name="lastName" id="lastName" value="{{ old('lastName', $staff->lastName) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $staff->address) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="telephoneNumber" class="block text-sm font-medium text-gray-700">Telephone Number *</label>
                        <input type="tel" name="telephoneNumber" id="telephoneNumber" value="{{ old('telephoneNumber', $staff->telephoneNumber) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="dateOfBirth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                        <input type="date" name="dateOfBirth" id="dateOfBirth" value="{{ old('dateOfBirth', $staff->dateOfBirth) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex *</label>
                        <select name="sex" id="sex" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            <option value="M" @if(old('sex', $staff->sex) == 'M') selected @endif>Male</option>
                            <option value="F" @if(old('sex', $staff->sex) == 'F') selected @endif>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="NIN" class="block text-sm font-medium text-gray-700">National Insurance Number *</label>
                        <input type="text" name="NIN" id="NIN" value="{{ old('NIN', $staff->NIN) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>

            <!-- EMPLOYMENT DETAILS -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-[#1f3b5c] mb-4">Employment Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700">Position *</label>
                        <input type="text" name="position" id="position" value="{{ old('position', $staff->position) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700">Department *</label>
                        <select name="department" id="department" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            @foreach($departments as $department)
                                <option value="{{ $department }}" @if(old('department', $staff->department) == $department) selected @endif>{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="currentSalary" class="block text-sm font-medium text-gray-700">Current Salary (₱) *</label>
                        <input type="number" name="currentSalary" id="currentSalary" value="{{ old('currentSalary', $staff->currentSalary) }}" required step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="salaryScale" class="block text-sm font-medium text-gray-700">Salary Scale *</label>
                        <select name="salaryScale" id="salaryScale" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            @foreach($salaryScales as $scale)
                                <option value="{{ $scale }}" @if(old('salaryScale', $staff->salaryScale) == $scale) selected @endif>{{ $scale }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="hoursPerWeek" class="block text-sm font-medium text-gray-700">Hours Per Week *</label>
                        <input type="number" name="hoursPerWeek" id="hoursPerWeek" value="{{ old('hoursPerWeek', $staff->hoursPerWeek) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="contractType" class="block text-sm font-medium text-gray-700">Contract Type *</label>
                        <select name="contractType" id="contractType" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            @foreach($contractTypes as $type)
                                <option value="{{ $type }}" @if(old('contractType', $staff->contractType) == $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="paymentType" class="block text-sm font-medium text-gray-700">Payment Type *</label>
                        <select name="paymentType" id="paymentType" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm">
                            @foreach($paymentTypes as $type)
                                <option value="{{ $type }}" @if(old('paymentType', $staff->paymentType) == $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
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