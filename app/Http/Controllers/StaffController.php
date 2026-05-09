<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Qualification;
use App\Models\WorkExperience;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // Display all staff
    public function index()
    {
        $staff = Staff::with(['qualifications', 'workExperiences'])->get();

        return view('staffs.index', compact('staff'));
    }

    // Show create form
    public function create()
    {
        return view('staffs.create');
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
            'position' => 'required|string|max:255',
            'currentSalary' => 'required|numeric|min:0',
            'salaryScale' => 'required|string|max:50',
            'hoursPerWeek' => 'required|integer|min:1|max:168',
            'contractType' => 'required|string|max:50',
            'paymentType' => 'required|string|max:50',

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

        return view('staffs.edit', compact('staff'));
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
            'position' => 'required|string|max:255',
            'currentSalary' => 'required|numeric|min:0',
            'salaryScale' => 'required|string|max:50',
            'hoursPerWeek' => 'required|integer|min:1|max:168',
            'contractType' => 'required|string|max:50',
            'paymentType' => 'required|string|max:50'
        ]);

        $staff->update($validated);

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
}