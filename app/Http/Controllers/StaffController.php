<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffQualification;
use App\Models\WorkExperience;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // Display all staff
    public function index(Request $request)
    {
        $department = $request->query('department');
        $search = $request->query('search');

        $query = Staff::with(['qualifications', 'workExperiences']);

        if ($department) {
            $query->where('department', $department);
        }

        if ($search) {
            // Use ILIKE for case-insensitive search, which is crucial for PostgreSQL on production.
            $query->where(function($q) use ($search) {
                $q->where('firstName', 'ilike', "%{$search}%")
                  ->orWhere('lastName', 'ilike', "%{$search}%");
            });
        }

        // Paginate the results first for efficiency.
        $staff = $query->paginate(10);

        // If the search found exactly one result, redirect to that staff member's page.
        if ($search && $staff->total() === 1) {
            // Use the first item from the paginator's collection for the redirect.
            return redirect()->route('staff.show', $staff->items()[0]);
        }

        return view('staffs.index', compact('staff', 'department', 'search'));
    }

    // Show create form
    public function create()
    {
        $departments = [
            'Cardiology',
            'Neurology',
            'Pediatrics',
            'Orthopedics',
            'Emergency',
            'Radiology'
        ];

        $salaryScales = ['Band 1', 'Band 2', 'Band 3', 'Band 4', 'Band 5'];
        $paymentTypes = ['Monthly', 'Weekly', 'Bi-Weekly'];
        $contractTypes = ['Full-time', 'Part-time'];

        return view('staffs.create', compact('departments', 'salaryScales', 'paymentTypes', 'contractTypes'));
    }

    // Store new staff
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'address' => 'required|string',
            'telephoneNumber' => 'required|string|max:20',
            'dateOfBirth' => 'required|date',
            'sex' => 'required|in:M,F',
            'NIN' => 'required|string|unique:staff,NIN|max:20',
            'department' => 'required|string|in:Cardiology,Neurology,Pediatrics,Orthopedics,Emergency,Radiology',
            'position' => 'required|string|max:255',
            'currentSalary' => 'required|numeric|min:0',
            'salaryScale' => 'required|string|in:Band 1,Band 2,Band 3,Band 4,Band 5',
            'hoursPerWeek' => 'required|integer|min:1|max:168',
            'contractType' => 'required|string|in:Full-time,Part-time,Temporary,Contractor',
            'paymentType' => 'required|string|in:Monthly,Weekly,Bi-Weekly',

            'qualifications.*.type' => 'required|string|max:255',
            'qualifications.*.date' => 'required|date',
            'qualifications.*.institution' => 'required|string|max:255',

            'workExperiences.*.position' => 'required|string|max:255',
            'workExperiences.*.organization' => 'required|string|max:255',
            'workExperiences.*.startDate' => 'required|date',
            'workExperiences.*.finishDate' => 'nullable|date',
        ]);

        $staff = Staff::create($validated);

        // Save qualifications
        if ($request->has('qualifications')) {
            foreach ($request->qualifications as $qualification) {
                $staff->qualifications()->create([
                    'type' => $qualification['type'],
                    'date' => $qualification['date'],
                    'institution' => $qualification['institution']
                ]);
            }
        }

        // Save work experiences
        if ($request->has('workExperiences')) {
            foreach ($request->workExperiences as $experience) {
                $staff->workExperiences()->create([
                    'position' => $experience['position'],
                    'organization' => $experience['organization'],
                    'startDate' => $experience['startDate'],
                    'finishDate' => $experience['finishDate'] ?? null
                ]);
            }
        }

        return redirect()->route('staff.index')
            ->with('success', 'Staff member added successfully!');
    }

    // Show edit form
    public function edit(Staff $staff)
    {
        $staff->load(['qualifications', 'workExperiences']);

        $departments = ['Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics', 'Emergency', 'Radiology'];
        $salaryScales = ['Band 1', 'Band 2', 'Band 3', 'Band 4', 'Band 5'];
        $paymentTypes = ['Monthly', 'Weekly', 'Bi-Weekly'];
        $contractTypes = ['Full-time', 'Part-time', 'Temporary', 'Contractor'];

        return view('staffs.edit', compact('staff', 'departments', 'salaryScales', 'paymentTypes', 'contractTypes'));
    }

    // Update staff
    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'address' => 'required|string',
            'telephoneNumber' => 'required|string|max:20',
            'dateOfBirth' => 'required|date',
            'sex' => 'required|in:M,F',
            'NIN' => 'required|string|unique:staff,NIN,' . $staff->staffNumber . ',staffNumber|max:20',
            'department' => 'required|string|in:Cardiology,Neurology,Pediatrics,Orthopedics,Emergency,Radiology',
            'position' => 'required|string|max:255',
            'currentSalary' => 'required|numeric|min:0',
            'salaryScale' => 'required|string|max:50',
            'hoursPerWeek' => 'required|integer|min:1|max:168',
            'contractType' => 'required|string|max:50',
            'paymentType' => 'required|string|max:50'
        ]);

        $staff->update($validated);
        $staff->department = $request->input('department');
        $staff->save();

        // Sync qualifications
        $staff->qualifications()->delete();
        if ($request->has('qualifications')) {
            foreach ($request->qualifications as $qualification) {
                $staff->qualifications()->create($qualification);
            }
        }

        // Sync work experiences
        $staff->workExperiences()->delete();
        if ($request->has('workExperiences')) {
            foreach ($request->workExperiences as $experience) {
                $staff->workExperiences()->create($experience);
            }
        }

        return redirect()->route('staff.index')
            ->with('success', 'Staff member updated successfully!');
    }

    // Delete staff
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Staff member deleted successfully!');
    }

    // Show a single staff member
    public function show(Staff $staff)
    {
        $staff->load(['qualifications', 'workExperiences']);
        return view('staffs.show', compact('staff'));
    }

    // Autocomplete for staff search
    public function autocomplete(Request $request)
    {
        $search = $request->query('term');
        $staff = Staff::where('firstName', 'like', "%{$search}%")
                      ->orWhere('lastName', 'like', "%{$search}%")
                      ->limit(10)
                      ->get(['firstName', 'lastName']);

        return response()->json($staff);
    }
}