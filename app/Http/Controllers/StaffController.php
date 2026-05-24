<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffQualification;
use App\Models\WorkExperience;
use App\Models\Department;
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
            $query->where('department_id', $department);
        }

        if ($search) {
            $searchTerms = explode(' ', $search);

            foreach ($searchTerms as $term) {
                $term = trim($term);
                if ($term) {
                    $query->where(function ($q) use ($term) {
                        $q->where('firstName', 'ilike', '%' . $term . '%')
                          ->orWhere('lastName', 'ilike', '%' . $term . '%');
                    });
                }
            }
        }

        $staff = $query->paginate(10);

        if ($search && $staff->total() === 1) {
            return redirect()->route('staff.show', $staff->items()[0]);
        }

        return view('staffs.index', compact('staff', 'department', 'search'));
    }

    // Show create form
    public function create()
    {
        $departments = Department::all();
        $salaryScales = ['Band 1', 'Band 2', 'Band 3', 'Band 4', 'Band 5'];
        $paymentTypes = ['Monthly', 'Weekly', 'Bi-Weekly'];
        $contractTypes = ['Full-time', 'Part-time'];

        return view('staffs.create', compact('departments', 'salaryScales', 'paymentTypes', 'contractTypes'));
    }

    // Store new staff
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'telephone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:M,F',
            'nin' => 'required|string|unique:staff,nin|max:20',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'current_salary' => 'required|numeric|min:0',
            'salary_scale' => 'required|string|in:Band 1,Band 2,Band 3,Band 4,Band 5',
            'hours_per_week' => 'required|integer|min:1|max:168',
            'contract_type' => 'required|string|in:Full-time,Part-time,Temporary,Contractor',
            'payment_type' => 'required|string|in:Monthly,Weekly,Bi-Weekly',

            'qualifications.*.type' => 'required|string|max:255',
            'qualifications.*.date' => 'required|date',
            'qualifications.*.institution' => 'required|string|max:255',

            'work_experiences.*.position' => 'required|string|max:255',
            'work_experiences.*.organization' => 'required|string|max:255',
            'work_experiences.*.start_date' => 'required|date',
            'work_experiences.*.finish_date' => 'nullable|date',
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

        $departments = Department::all();
        $salaryScales = ['Band 1', 'Band 2', 'Band 3', 'Band 4', 'Band 5'];
        $paymentTypes = ['Monthly', 'Weekly', 'Bi-Weekly'];
        $contractTypes = ['Full-time', 'Part-time', 'Temporary', 'Contractor'];

        return view('staffs.edit', compact('staff', 'departments', 'salaryScales', 'paymentTypes', 'contractTypes'));
    }

    // Update staff
    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'telephone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:M,F',
            'nin' => 'required|string|unique:staff,nin,' . $staff->staff_number . ',staff_number',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'current_salary' => 'required|numeric|min:0',
            'salary_scale' => 'required|string|max:50',
            'hours_per_week' => 'required|integer|min:1|max:168',
            'contract_type' => 'required|string|max:50',
            'payment_type' => 'required|string|max:50'
        ]);

        $staff->update($validated);

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
        $staff->load(['qualifications', 'workExperiences', 'department']);
        return view('staffs.show', compact('staff'));
    }

    // Autocomplete for staff search
    public function autocomplete(Request $request)
    {
        $search = $request->query('term');
        $staff = Staff::where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->limit(10)
                      ->get(['first_name', 'last_name']);

        return response()->json($staff);
    }
}